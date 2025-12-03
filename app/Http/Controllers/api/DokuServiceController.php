<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class DokuServiceController extends ApiController
{
    /**
     * Handle DOKU Virtual Account SNAP payment notification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dokuPaymentNotification(Request $request)
    {
        try {
            // Headers have been validated by DokuHeaderMiddleware
            // Get headers that were validated by middleware
            $headers = [
                'x-timestamp' => $request->header('X-TIMESTAMP'),
                'x-signature' => $request->header('X-SIGNATURE'),
                'x-partner-id' => $request->header('X-PARTNER-ID'),
                'x-external-id' => $request->header('X-EXTERNAL-ID'),
                'channel-id' => $request->header('CHANNEL-ID'),
            ];

            // Log DOKU notification for debugging (consider implementing proper signature verification)
            \Log::info('DOKU Payment Notification Received', [
                'headers' => $headers,
                'body' => $request->all(),
                'timestamp' => now()->toISOString()
            ]);

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'partnerServiceId' => 'required|string|max:255',
                'customerNo' => 'required|string|max:255',
                'virtualAccountNo' => 'required|string|max:255',
                'virtualAccountName' => 'required|string|max:255',
                'trxId' => 'required|string|max:255',
                'paymentRequestId' => 'required|string|max:255',
                'paidAmount' => 'required|array',
                'paidAmount.value' => 'required|string',
                'paidAmount.currency' => 'required|string|max:3',
                'virtualAccountEmail' => 'nullable|email|max:255',
                'virtualAccountPhone' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                \Log::warning('DOKU Payment Notification Validation Failed', [
                    'errors' => $validator->errors()->toArray(),
                    'body' => $request->all()
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Extract payment data from DOKU format
            $partnerId = $request->partnerServiceId;
            $customerNo = $request->customerNo;
            $virtualAccountNo = $request->virtualAccountNo;
            $virtualAccountName = $request->virtualAccountName;
            $trxId = $request->trxId;
            $paymentRequestId = $request->paymentRequestId;
            $paidAmount = $request->paidAmount;
            $paidValue = $paidAmount['value'] ?? '0';
            $currency = $paidAmount['currency'] ?? 'IDR';
            $email = $request->virtualAccountEmail;
            $phone = $request->virtualAccountPhone;

            // Find associated user (this logic should be customized based on your user mapping)
            // Default to a test user or implement your own user lookup logic
            $user = User::first(); // Or use: User::where('customer_id', $customerNo)->first()

            // Check if transaction exists with order_id
            $billExists = \App\Models\Transaction::where('order_id', $trxId)->exists();

            if (!$billExists) {
                // \Log::warning('DOKU Payment Notification: Bill not found', [
                //     'customerNo' => $customerNo,
                //     'virtualAccountNo' => $virtualAccountNo,
                //     'orderId' => $virtualAccountNo,
                //     'trxId' => $trxId
                // ]);

                return response()->json([
                    'responseCode' => '4042512',
                    'responseMessage' => "Bill {$trxId} not found",
                    // 'message' => "Bill {$trxId} not found",
                    // 'status' => 'error',
                ], 404);
            }

            if (!$user) {
                $user = auth()->user() ?? $this->user;
            }

            if (!$user) {
                \Log::error('DOKU Payment Notification: User not found', [
                    'customerNo' => $customerNo,
                    'virtualAccountNo' => $virtualAccountNo
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Associated user not found',
                    'customer_error' => 'Customer mapping required'
                ], 404);
            }

            $notificationData = [
                'title' => 'DOKU Transfer VA Payment',
                'message' => $this->getVirtualAccountPaymentMessage($paidValue, $currency, $virtualAccountName, $trxId),
                'type' => 'payment',
                'category' => 'virtual_account',
                'doku_reference' => [
                    'transaction_id' => $trxId,
                    'payment_request_id' => $paymentRequestId,
                    'virtual_account_number' => $virtualAccountNo,
                    'virtual_account_name' => $virtualAccountName,
                    'customer_no' => $customerNo,
                    'partner_service_id' => $partnerId,
                ],
                'payment_detail' => [
                    'amount' => (float) $paidValue,
                    'currency' => strtoupper($currency),
                    'email' => $email,
                    'phone' => $phone,
                ],
            ];

            // Create notification in database
            $user->notify(new \App\Notifications\PaymentNotification($notificationData));

            // Verify notification was created in notifications table
            // $notificationSaved = \DB::table('notifications')
            //     ->where('notifiable_id', $user->id)
            //     ->where('notifiable_id', Str::random(10))
            //     ->where('notifiable_type', 'App\\Models\\User')
            //     ->where('type', 'App\\Notifications\\PaymentNotification')
            //     ->latest()
            //     ->first();

            // if (!$notificationSaved) {
            //     \Log::error('DOKU notification failed to save to database', [
            //         'user_id' => $user->id,
            //         'notification_data' => $notificationData
            //     ]);

            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Failed to save notification to database',
            //     ], 500);
            // }

            // Log successful processing
            \Log::info('DOKU Virtual Account Payment Notification Processed Successfully', [
                'user_id' => $user->id,
                'doku_reference' => $notificationData['doku_reference'],
                'amount' => $paidValue,
                'currency' => $currency,
                'transaction_id' => $trxId
            ]);

            return response()->json([
                'responseCode' => '2002500',
                'responseMessage' => 'Success',
                'virtualAccountData' => [
                    'partnerServiceId' => $partnerId,
                    'customerNo' => $customerNo,
                    'virtualAccountNo' => $virtualAccountNo,
                    'virtualAccountName' => $virtualAccountName,
                    'trxId' => $trxId,
                    'paymentRequestId' => $paymentRequestId,
                ]
            ], 200);
        

        } catch (\Exception $e) {
            \Log::error('DOKU Payment Notification Processing Error', [
                'error' => $e->getMessage(),
                'body' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error processing DOKU Virtual Account payment notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function dokuQRPaymentNotification(Request $request)
    {
        try {
            // New expected body format
            // {
            //   "originalReferenceNo": "INV-20251119112706",
            //   "originalPartnerReferenceNo": "INV-20251119112706",
            //   "merchantId": "12345",
            //   "serviceCode": "12"
            // }

            // Headers have been validated by DokuHeaderMiddleware
            // Get headers that were validated by middleware
            $headers = [
                'x-timestamp' => $request->header('X-TIMESTAMP'),
                'x-signature' => $request->header('X-SIGNATURE'),
                'x-partner-id' => $request->header('X-PARTNER-ID'),
                'x-external-id' => $request->header('X-EXTERNAL-ID'),
                'channel-id' => $request->header('CHANNEL-ID'),
            ];

            // Log DOKU notification for debugging
            // \Log::info('DOKU QR Payment Notification Received', [
            //     'headers' => $headers,
            //     'body' => $request->all(),
            //     'timestamp' => now()->toISOString()
            // ]);

            // Validate new request format
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'originalReferenceNo' => 'required|string|max:255',
                'originalPartnerReferenceNo' => 'required|string|max:255',
                'merchantId' => 'required|string|max:255',
                'serviceCode' => 'required|string|max:10',
            ]);

            if ($validator->fails()) {
                \Log::warning('DOKU QR Payment Notification Validation Failed', [
                    'errors' => $validator->errors()->toArray(),
                    'body' => $request->all()
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Extract data from new format
            $originalReferenceNo = $request->originalReferenceNo;
            $originalPartnerReferenceNo = $request->originalPartnerReferenceNo;
            $merchantId = $request->merchantId;
            $serviceCode = $request->serviceCode;

            // Log processing details
            // \Log::info('DOKU QR Payment Notification Processed Successfully', [
            //     'originalReferenceNo' => $originalReferenceNo,
            //     'originalPartnerReferenceNo' => $originalPartnerReferenceNo,
            //     'merchantId' => $merchantId,
            //     'serviceCode' => $serviceCode,
            //     'processed_at' => now()->toISOString()
            // ]);
            
            // if (!$billExists) {
            //     // \Log::warning('DOKU Payment Notification: Bill not found', [
            //     //     'customerNo' => $customerNo,
            //     //     'virtualAccountNo' => $virtualAccountNo,
            //     //     'orderId' => $virtualAccountNo,
            //     //     'trxId' => $trxId
            //     // ]);

            //     return response()->json([
            //         'responseCode' => '4042512',
            //         'responseMessage' => "Bill {$trxId} not found",
            //         // 'message' => "Bill {$trxId} not found",
            //         // 'status' => 'error',
            //     ], 404);
            // }

            // Return the expected response format
            return response()->json([
                'responseCode' => '2005100',
                'responseMessage' => 'Request has been processed successfully',
                'originalReferenceNo' => $originalReferenceNo,
                'originalPartnerReferenceNo' => $originalPartnerReferenceNo,
                'serviceCode' => $serviceCode,
                'latestTransactionStatus' => '03',
                'transactionStatusDesc' => 'Pending',
                'amount' => [
                    'value' => 1.00,
                    'currency' => 'IDR'
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('DOKU QR Payment Notification Processing Error', [
                'error' => $e->getMessage(),
                'body' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error processing DOKU QR payment notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle DOKU B2B access token request
     * Endpoint: POST /authorization/v1/access-token/b2b
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dokuGetTokenB2B(Request $request)
    {
        try {
            // All validation (headers, body, credentials, signature, timestamp)
            // has been performed by DokuB2BHeaderMiddleware
            $clientKey = $request->header('X-CLIENT-KEY');

            // Generate JWT access token (B2B token) - approximately 128 characters
            $timestamp = now()->timestamp;
            $expiresIn = 900; // 15 minutes in seconds
            $expiresAt = now()->addSeconds($expiresIn);
            $secretKey = config('services.doku.secret_key');

            // JWT Header
            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT'
            ];

            // JWT Payload
            $payload = [
                'exp' => $expiresAt->timestamp,
                'nbf' => $timestamp,
                'iss' => 'DOKU',
                'iat' => $timestamp,
                'client_id' => $clientKey,
                'grant_type' => 'client_credentials'
            ];

            // Base64URL encode function
            $base64UrlEncode = function($data) {
                return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
            };

            // Encode header and payload
            $headerEncoded = $base64UrlEncode(json_encode($header));
            $payloadEncoded = $base64UrlEncode(json_encode($payload));

            // Create signature
            $signatureInput = $headerEncoded . '.' . $payloadEncoded;
            $signature = hash_hmac('sha256', $signatureInput, $secretKey, true);
            $signatureEncoded = $base64UrlEncode($signature);

            // Combine to create JWT
            $accessToken = $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;

            // Log successful token generation
            \Log::info('DOKU B2B Token Generated Successfully', [
                'client_key' => $clientKey,
                'expires_in' => $expiresIn,
                'expires_at' => $expiresAt->toISOString(),
                'timestamp' => now()->toISOString()
            ]);

            // Return successful response with token (matching DOKU expected format)
            return response()->json([
                'responseCode' => '2007300',
                'responseMessage' => 'Successful',
                'accessToken' => $accessToken,
                'tokenType' => 'Bearer',
                'expiresIn' => $expiresIn,
                'additionalInfo' => ''
            ], 200);

        } catch (\Exception $e) {
            \Log::error('DOKU B2B Token Request Processing Error', [
                'error' => $e->getMessage(),
                'body' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'responseCode' => '5007300',
                'responseMessage' => 'Internal Server Error'
            ], 500);
        }
    }
    /**
     * Get virtual account payment message
     *
     * @param string $amount
     * @param string $currency
     * @param string $accountName
     * @param string $trxId
     * @return string
     */
    private function getVirtualAccountPaymentMessage($amount, $currency, $accountName, $trxId)
    {
        $formattedAmount = number_format((float)$amount, 2) . ' ' . strtoupper($currency);
        
        return "Virtual Account payment received for {$formattedAmount} from {$accountName} (Transaction ID: {$trxId}). Payment has been processed successfully.";
    }

    /**
     * Get payment message based on status
     *
     * @param string $status
     * @param float $amount
     * @param string $currency
     * @return string
     */
    private function getPaymentMessage($status, $amount, $currency)
    {
        $formattedAmount = number_format($amount, 2) . ' ' . strtoupper($currency);
        
        switch ($status) {
            case 'completed':
                return "Your payment of {$formattedAmount} has been successfully processed.";
            case 'pending':
                return "Your payment of {$formattedAmount} is pending confirmation.";
            case 'failed':
                return "Your payment of {$formattedAmount} has failed. Please try again.";
            case 'cancelled':
                return "Your payment of {$formattedAmount} has been cancelled.";
            default:
                return "Payment status updated for {$formattedAmount}.";
        }
    }
}