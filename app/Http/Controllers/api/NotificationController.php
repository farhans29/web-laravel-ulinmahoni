<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class NotificationController extends ApiController
{
    /**
     * Get user notifications
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        try {
            $user = $request->user() ?? auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->get(['id', 'type', 'data', 'read_at', 'created_at']);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifications retrieved successfully',
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create payment notification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentNotifications(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'transaction_id' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'status' => 'required|string|in:pending,completed,failed,cancelled',
            'payment_method' => 'required|string|max:50',
            'timestamp' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user() ?? auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $notificationData = [
                'title' => 'Payment Update',
                'message' => $this->getPaymentMessage($request->status, $request->amount, $request->currency),
                'type' => 'payment',
                'transaction_id' => $request->transaction_id,
                'amount' => $request->amount,
                'currency' => strtoupper($request->currency),
                'status' => $request->status,
                'payment_method' => $request->payment_method,
                'timestamp' => $request->timestamp,
            ];

            // Create notification in database
            $user->notify(new \App\Notifications\PaymentNotification($notificationData));

            return response()->json([
                'status' => 'success',
                'message' => 'Payment notification created successfully',
                'data' => [
                    'notification' => $notificationData,
                    'created_at' => now()->toISOString()
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating payment notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notifications as read
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markNotificationsAsRead(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'required|string|exists:notifications,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user() ?? auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Mark specified notifications as read
            $updatedCount = $user->notifications()
                ->whereIn('id', $request->notification_ids)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifications marked as read',
                'data' => [
                    'updated_count' => $updatedCount,
                    'notification_ids' => $request->notification_ids
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error marking notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
                'title' => 'DOKU Virtual Account Payment',
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
                'timestamp' => $headers['x-timestamp'],
                'external_id' => $headers['x-external-id'],
                'channel_id' => $headers['channel-id'],
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
                'status' => 'success',
                'message' => 'DOKU Virtual Account payment notification processed successfully. Payment confirmation has been recorded and notification sent to user.',
                'data' => [
                    'notification_data' => $notificationData,
                    'processed_at' => now()->toISOString(),
                    'response_code' => '200',
                    'response_message' => 'Success'
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