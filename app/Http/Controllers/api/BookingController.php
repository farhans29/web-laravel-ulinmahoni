<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Controllers\ApiController;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Property;

class BookingController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $query = Transaction::query();
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('transaction_status')) {
                $query->where('transaction_status', $request->transaction_status);
            }

            $query->orderBy('created_at', 'desc');
            
            if ($request->has('limit') && $request->has('page')) {
                $transactions = $query->paginate($request->limit);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bookings retrieved successfully',
                    'data' => $transactions->items(),
                    'meta' => [
                        'current_page' => $transactions->currentPage(),
                        'last_page' => $transactions->lastPage(),
                        'per_page' => $transactions->perPage(),
                        'total' => $transactions->total()
                    ]
                ]);
            }
            
            $transactions = $query->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Bookings retrieved successfully',
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkInByOrderId(Request $request, $order_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'doc_type' => 'required|string',
                'document' => 'required|string', // base64 encoded image
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $orderId = $order_id;

            // Find the booking by order_id
            $booking = Booking::where('order_id', $orderId)
                ->where('status', '1')
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found or already inactive'
                ], 404);
            }

            // Check if already checked in (validate if check_in_at and doc_path are already filled)
            if (!is_null($booking->check_in_at) && !is_null($booking->doc_path)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking has already been checked in. Cannot perform check-in again.',
                    'data' => [
                        'order_id' => $orderId,
                        'check_in_at' => $booking->check_in_at,
                        'doc_type' => $booking->doc_type,
                        'doc_path' => $booking->doc_path
                    ]
                ], 400);
            }

            // Process base64 document upload
            $base64Image = $request->input('document');

            // Remove data URL prefix if present
            if (strpos($base64Image, ';base64,') !== false) {
                [$_, $base64Image] = explode(';', $base64Image);
                [$_, $base64Image] = explode(',', $base64Image);
            }

            // Validate base64 image
            $imageData = base64_decode($base64Image, true);
            if ($imageData === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid base64 image'
                ], 422);
            }

            // Validate image type
            $f = finfo_open();
            $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
            finfo_close($f);

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!in_array($mimeType, $allowedTypes)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid image type. Only JPEG, PNG, and GIF are allowed.'
                ], 422);
            }

            // Determine file extension from mime type
            $extensionMap = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/jpg' => 'jpg',
                'image/gif' => 'gif'
            ];
            $extension = $extensionMap[$mimeType] ?? 'jpg';

            // Generate filename and store the image
            $filename = 'doc_' . $orderId . '_' . time() . '.' . $extension;
            $path = 'documents/' . $filename;

            // Save file to storage/app/public/documents
            Storage::disk('public')->put($path, $imageData);

            // Update booking with check-in data
            $booking->check_in_at = Carbon::now();
            $booking->doc_type = $request->doc_type;
            $booking->doc_path = $path;
            $booking->save();

            // Also update transaction status if needed
            // Transaction::where('order_id', $orderId)
            //     ->where('status', '1')
            //     ->update([
            //         'transaction_status' => 'checked_in',
            //         'updated_at' => Carbon::now()
            //     ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-in successful',
                'data' => [
                    'order_id' => $orderId,
                    'check_in_at' => $booking->check_in_at,
                    'doc_type' => $booking->doc_type,
                    'doc_path' => $path,
                    'doc_url' => Storage::disk('public')->url($path)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error during check-in',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function checkAvailability(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'property_id' => 'required|integer|exists:m_properties,idrec',
            'room_id' => 'required|integer|exists:m_rooms,idrec',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $propertyId = $request->property_id;
        $roomId = $request->room_id;
        $checkIn = Carbon::parse($request->check_in)->startOfDay();
        $checkOut = Carbon::parse($request->check_out)->endOfDay();

        // Check for conflicting bookings using simplified query
        $conflictingBookings = DB::table('t_transactions')
            ->where('property_id', $propertyId) //property_id from the m_properties
            ->where('room_id', $roomId) //room_id from the m_rooms
            ->where('status', '1')  // if the status = 1
            ->whereNotIn('transaction_status', ['cancelled',]) // if the transaction_status is not cancelled, finished, completed, paid
            ->where('check_in', '<', $checkOut) // if the check_in is less than the check_out
            ->where('check_out', '>', $checkIn) // if the check_out is greater than the check_in
            ->limit(5) // limit the result to 5
            ->get();

        $isAvailable = $conflictingBookings->isEmpty();

        // Get the first booking if available
        $firstBooking = $conflictingBookings->first();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'is_available' => $isAvailable,
                'conflicting_bookings' => $isAvailable ? [] : $conflictingBookings,
                'check_in' => $checkIn->format('Y-m-d H:i:s'),
                'check_out' => $checkOut->format('Y-m-d H:i:s'),
                'property_id' => $propertyId,
                'room_id' => $roomId,
                'user_info' => $firstBooking ? [
                    'user_name' => $firstBooking->user_name ?? null,
                    'user_phone_number' => $firstBooking->user_phone_number ?? null,
                    'user_email' => $firstBooking->user_email ?? null,
                ] : null,
            ]
        ]);
    }

    public function show($id)
    {
        try {
            $transaction = Transaction::find($id);
            
            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Booking retrieved successfully',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'user_name' => 'required|string|max:255',
            'user_phone_number' => 'nullable|string|max:20',
            'user_email' => 'required|email|max:255',
            'property_id' => 'nullable|integer',
            'property_name' => 'required|string|max:255',
            'property_type' => 'required|string',
            'room_name' => 'required|string|max:255',
            'room_id' => 'nullable|integer',
            'booking_type' => 'nullable',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'daily_price' => 'nullable|numeric|min:0',
            'monthly_price' => 'nullable|numeric|min:0',
            'booking_days' => 'nullable|integer|required_without:booking_months',
            'booking_months' => 'nullable|integer|required_without:booking_days'
        ], [
            'booking_days.required_without' => 'Either booking_days or booking_months is required',
            'booking_months.required_without' => 'Either booking_days or booking_months is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $bookingDays = null;
            $bookingMonths = null;
            $roomPrice = 0;
            $adminFees = 0;
            $serviceFees = 30000;
            $grandtotalPrice = 0;
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);

            if ($request->has('booking_days') && $request->booking_days > 0) {
                // DAILY BOOKING
                $calculatedDays = $checkOut->diffInDays($checkIn);
                
                // Verify the calculated days match the provided booking_days
                if ($calculatedDays != $request->booking_days) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The check-in/check-out dates do not match the provided booking days',
                        'calculated_days' => $calculatedDays
                    ], 422);
                }
                
                $bookingDays = $calculatedDays;
                $roomPrice = $request->daily_price * $bookingDays;
                // $adminFees = $roomPrice * 0.10;
                $adminFees = $request->admin_fees;
                $serviceFees = $request->service_fees;
                $tax = $request->tax;
                $grandtotalPrice = $roomPrice + $adminFees + $serviceFees + $tax;
            } else {
                // MONTHLY BOOKING
                $monthlyPrice = $request->monthly_price;
                $bookingMonths = $request->booking_months;

                $roomPrice = $monthlyPrice * $bookingMonths;
                // $adminFees = $roomPrice * 0.10;
                $adminFees = $request->admin_fees;
                $serviceFees = $request->service_fees;
                $tax = $request->tax;
                $grandtotalPrice = $roomPrice + $adminFees + $serviceFees + $tax;
            }
            
            // Generate order_id in format INV-UM-APP-yymmddXXXPP
            $property = $request->property_id ? Property::find($request->property_id) : null;
            $propertyInitial = $property && $property->initial ? $property->initial : 'XX';
            $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $order_id = 'UMA-' . now()->format('ymd') . $randomNumber . $propertyInitial;

            // Prepare transaction data
            $transactionData = [
                // USER DATAS
                'user_id' => $request->user_id,
                'user_name' => $request->user_name,
                'user_phone_number' => $request->user_phone_number,
                'user_email' => $request->user_email,
                // PROPERTY DATAS
                'property_id' => $request->property_id,
                'property_name' => $request->property_name,
                'property_type' => $request->property_type,
                // ROOM DATAS
                'room_id' => $request->room_id,
                'room_name' => $request->room_name,
                // ORDER DETAILS
                'order_id' => $order_id,
                'transaction_date' => now(),
                'booking_type' => $request->has('booking_days') && $request->booking_days > 0 ? 'daily' : 'monthly',
                'booking_days' => $bookingDays,
                'booking_months' => $bookingMonths,
                // PRICES
                'room_price' => $roomPrice,
                'daily_price' => $request->daily_price,
                'monthly_price' => $request->monthly_price,
                'admin_fees' => $adminFees,
                'service_fees' => $request->service_fees,
                'grandtotal_price' => $grandtotalPrice,
                // CODE AND STATUS
                'transaction_type' => $request->transaction_type,
                'transaction_code' => 'TRX-' . Str::random(16),
                'transaction_status' => 'pending',
                'status' => '1',
                // DATES
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
            ];

            // Create transaction
            $transaction = Transaction::create($transactionData);

            // Create booking if room_id is provided
            if ($request->has('room_id') && $request->room_id) {
                $bookingData = [
                    'property_id' => $request->property_id,
                    'order_id' => $order_id,
                    'room_id' => $request->room_id,
                    // 'check_in_at' => $request->check_in,
                    // 'check_out_at' => $request->check_out,
                    'status' => '1',
                    'booking_type' => $request->has('booking_days') && $request->booking_days > 0 ? 'daily' : 'monthly'
                ];
                Booking::create($bookingData);
            }

            // Create payment record
            $paymentData = [
                'property_id' => $request->property_id,
                'room_id' => $request->room_id,
                'order_id' => $order_id,
                'user_id' => $request->user_id,
                'grandtotal_price' => $grandtotalPrice,
                'payment_status' => 'unpaid',
                'created_by' => $request->user_id,
                'updated_by' => $request->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            Payment::create($paymentData);

            // Process payment with DOKU
            // $dokuPaymentResponse = $this->processDokuPayment([
            //     'order_id' => $order_id,
            //     'transaction_code' => $transaction->transaction_code,
            //     'amount' => $grandtotalPrice,
            //     'property_name' => $request->property_name,
            //     'room_name' => $request->room_name,
            //     'user_name' => $request->user_name,
            //     'user_email' => $request->user_email,
            //     'user_phone' => $request->user_phone_number
            // ]);

            if (isset($dokuPaymentResponse['error'])) {
                $errorMessage = 'Doku API Error: ' . json_encode($dokuPaymentResponse['error'] ?? [], JSON_PRETTY_PRINT) . ' - ' . json_encode($dokuPaymentResponse['error'] ?? [], JSON_PRETTY_PRINT);
                \Log::error($errorMessage);
                throw new \Exception($errorMessage);
            }

            // Update transaction with payment URL and expiration
            $transaction->update([
                'payment_url' => $dokuPaymentResponse['payment_url'] ?? null,
                'expired_at' => $dokuPaymentResponse['expired_at'] ?? null
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully',
                'data' => array_merge($transaction->toArray(), [
                    'payment_url' => $dokuPaymentResponse['payment_url'] ?? null,
                    'expired_at' => $dokuPaymentResponse['expired_at'] ?? null
                ])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating booking',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
    

    public function update(Request $request, $id)
    {
        try {
            $transaction = Transaction::find($id);
            
            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'transaction_status' => 'sometimes|required',
                'status' => 'sometimes|required',
                'paid_at' => 'sometimes|required|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $transaction->update($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Booking updated successfully',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified booking by order_id
     *
     * @param  string  $order_id
     * @return \Illuminate\Http\Response
     */
    public function showByOrderId($order_id)
    {
        try {
            $booking = Transaction::where('order_id', $order_id)->first();
            
            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Booking retrieved successfully',
                'data' => $booking
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }    /**
         * Display bookings for a specific user
         *
         * @param  int  $user_id
         * @return \Illuminate\Http\Response
         */
        public function showByUserId($user_id)
        {
            try {
                $bookings = Transaction::where('user_id', $user_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
    
                if ($bookings->isEmpty()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No bookings found for this user'
                    ], 404);
                }
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bookings retrieved successfully',
                    'data' => $bookings
                ]);
    
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error retrieving bookings',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

    /**
     * Upload attachment for a booking
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAttachment(Request $request, $id)
    {
        try {
            // Validate the ID first
            $booking = DB::table('t_transactions')
                ->where('idrec', $id)
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }


            $validator = Validator::make($request->all(), [
                'attachment_file' => 'required|string', // Changed from file to string
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }


            $base64Image = $request->input('attachment_file');
            
            // Remove data URL prefix if present
            if (strpos($base64Image, ';base64,') !== false) {
                [$_, $base64Image] = explode(';', $base64Image);
                [$_, $base64Image] = explode(',', $base64Image);
            }


            // Validate base64 image
            $imageData = base64_decode($base64Image, true);
            if ($imageData === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid base64 image'
                ], 422);
            }


            // Validate image type
            $f = finfo_open();
            $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
            if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid image type. Only JPEG and PNG are allowed.'
                ], 422);
            }


            // Update the booking with the base64 image
            $updateData = ['attachment' => $base64Image];

            // Only update status to 'waiting' if it's not already 'waiting'
            if ($booking->transaction_status !== 'waiting') {
                $updateData['transaction_status'] = 'waiting';
            }

            $updated = DB::table('t_transactions')
                ->where('idrec', $id)
                ->update($updateData);

            if (!$updated) {
                throw new \Exception('Failed to update booking with attachment');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Attachment uploaded successfully',
                'data' => [
                    'booking_id' => $id,
                    'attachment_uploaded' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error uploading attachment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update attachment for a booking
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAttachment(Request $request, $id)
    {
        try {
            // Validate the ID first
            $booking = DB::table('t_transactions')
                ->where('idrec', $id)
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'attachment_file' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $base64Image = $request->input('attachment_file');
            
            // Remove data URL prefix if present
            if (strpos($base64Image, ';base64,') !== false) {
                [$_, $base64Image] = explode(';', $base64Image);
                [$_, $base64Image] = explode(',', $base64Image);
            }

            // Validate base64 image
            $imageData = base64_decode($base64Image, true);
            if ($imageData === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid base64 image'
                ], 422);
            }

            // Validate image type
            $f = finfo_open();
            $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
            if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid image type. Only JPEG and PNG are allowed.'
                ], 422);
            }

            // Update the booking with the new base64 image
            $updateData = [
                'attachment' => $base64Image,
                'updated_at' => now()
            ];

            // Only update status to 'waiting' if it's not already 'waiting'
            if ($booking->transaction_status !== 'waiting') {
                $updateData['transaction_status'] = 'waiting';
            }

            $updated = DB::table('t_transactions')
                ->where('idrec', $id)
                ->update($updateData);

            if (!$updated) {
                throw new \Exception('Failed to update booking attachment');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Attachment updated successfully',
                'data' => [
                    'booking_id' => $id,
                    'attachment_updated' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating attachment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment method for a booking
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePaymentMethod(Request $request, $id)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'payment_method' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find the booking
            $booking = DB::table('t_transactions')
                ->where('idrec', $id)
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }

            // Check if transaction_type is already set
            if (!is_null($booking->transaction_type)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment method has already been set and cannot be changed'
                ], 400);
            }

            // Update payment method
            $updated = DB::table('t_transactions')
                ->where('idrec', $id)
                ->whereNull('transaction_type') // Additional safety check
                ->update([
                    'transaction_type' => $request->payment_method,
                    'transaction_status' => 'waiting',
                    'paid_at' => null,
                    'updated_at' => now()
                ]);

            if (!$updated) {
                throw new \Exception('Failed to update payment method. It may have already been set.');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Payment method updated successfully',
                'data' => [
                    'booking_id' => $id,
                    'payment_method' => $request->payment_method,
                    'transaction_status' => 'waiting'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating payment method',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate a secure signature for Doku API authentication
     * 
     * This method creates an HMAC-SHA256 signature using the provided credentials and request data.
     * The signature is used to authenticate requests to the Doku payment gateway.
     *
     * @param string $clientId     The Doku API client ID
     * @param string $requestId    Unique request identifier (UUID)
     * @param string $timestamp    Request timestamp in ISO 8601 format
     * @param string $requestTarget API endpoint path (e.g., '/checkout/v1/payment')
     * @param string $requestBody  JSON-encoded request payload
     * @param string $secretKey    Doku API secret key for signature generation
     * 
     * @return string HMAC-SHA256 signature prefixed with 'HMACSHA256='
     * 
     * @throws \InvalidArgumentException If any required parameter is empty or invalid
     */
    private function generateDokuSignature(
        string $clientId,
        string $requestId,
        string $timestamp,
        string $requestTarget,
        string $requestBody,
        string $secretKey
    ): string {
        // Input validation
        if (empty($clientId) || empty($requestId) || empty($timestamp) || 
            empty($requestTarget) || empty($secretKey)) {
            throw new \InvalidArgumentException('All parameters are required for signature generation');
        }

        // Generate digest from request body
        $digest = base64_encode(hash('sha256', $requestBody, true));
        
        // Prepare signature components in exact order required by Doku
        $signatureComponents = "Client-Id:{$clientId}\n" .
                             "Request-Id:{$requestId}\n" .
                             "Request-Timestamp:{$timestamp}\n" .
                             "Request-Target:{$requestTarget}\n" .
                             "Digest:{$digest}";
        
        // Generate HMAC-SHA256 signature
        $signature = base64_encode(hash_hmac('sha256', $signatureComponents, $secretKey, true));
        
        return 'HMACSHA256=' . $signature;
    }

    /**
     * Process payment through Doku payment gateway
     * 
     * This method handles the entire payment flow with Doku, including:
     * - Request preparation
     * - Signature generation
     * - API communication
     * - Response handling
     * - Error management
     *
     * @param array $data {
     *     Required payment data
     *     
     *     @type string $order_id          Unique order identifier
     *     @type string $transaction_code  Internal transaction reference
     *     @type float  $amount            Payment amount
     *     @type string $property_name     Name of the property being booked
     *     @type string $room_name         Name of the room being booked
     *     @type string $user_name         Full name of the customer
     *     @type string $user_email        Customer's email address
     *     @type string $user_phone        Customer's phone number
     * }
     * 
     * @return array{
     *     payment_url: string|null,
     *     expired_at: string|null,
     *     response: array
     * }
     * 
     * @throws \RuntimeException If payment processing fails
     * @throws \InvalidArgumentException If required parameters are missing
     */
    private function processDokuPayment(array $data): array
    {
        try {
            // Input validation
            $requiredFields = ['order_id', 'transaction_code', 'amount', 'user_email', 'user_phone'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new \InvalidArgumentException("Missing required field: {$field}");
                }
            }

            // Get API configuration
            $dokuUrl = config('services.doku.api_url', 'https://api-sandbox.doku.com/checkout/v1/payment');
            $clientId = config('services.doku.client_id');
            $secretKey = config('services.doku.secret_key');
            
            if (empty($clientId) || empty($secretKey)) {
                throw new \RuntimeException('Payment gateway configuration is incomplete');
            }

            // Prepare request data with type safety
            $requestData = [
                'order' => [
                    'amount' => (float) $data['amount'],
                    'invoice_number' => (string) $data['order_id'],
                    'currency' => 'IDR',
                    'session_id' => (string) $data['transaction_code'],
                    'callback_url' => config('app.url') . '/api/payment/callback',
                    'line_items' => [
                        [
                            'name' => sprintf('%s - %s', $data['property_name'] ?? 'Property', $data['room_name'] ?? 'Room'),
                            'price' => (float) $data['amount'],
                            'quantity' => 1
                        ]
                    ]
                ],
                'payment' => [
                    'payment_due_date' => 60 // 60 minutes
                ],
                'customer' => [
                    'name' => $data['user_name'] ?? 'Customer',
                    'email' => $data['user_email'],
                    'phone' => $data['user_phone'],
                    'address' => $data['user_address'] ?? 'Indonesia',
                    'country' => 'ID'
                ]
            ];

            // Generate request metadata
            $requestId = (string) Str::uuid();
            $timestamp = gmdate('Y-m-d\TH:i:s\Z');
            $requestTarget = '/checkout/v1/payment';
            $requestBody = json_encode($requestData);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Failed to encode request data: ' . json_last_error_msg());
            }

            // Generate and validate signature
            $signature = $this->generateDokuSignature(
                $clientId,
                $requestId,
                $timestamp,
                $requestTarget,
                $requestBody,
                $secretKey
            );

            // Execute API request with timeout and retry
            $response = Http::timeout(30)
                ->retry(3, 100)
                ->withHeaders([
                    'Client-Id' => $clientId,
                    'Request-Id' => $requestId,
                    'Request-Timestamp' => $timestamp,
                    'Signature' => $signature,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($dokuUrl, $requestData);

            $responseData = $response->json() ?? [];

            // Handle API errors
            if (!$response->successful()) {
                $errorDetails = [
                    'status' => $response->status(),
                    'error' => $responseData['error'] ?? null,
                    'code' => $responseData['error_code'] ?? null,
                    'message' => $responseData['message'] ?? 'Unknown error',
                    'request_id' => $requestId
                ];
                
                \Log::error('Doku API Request Failed', $errorDetails);
                throw new \RuntimeException($errorDetails['message'], $errorDetails['code'] ?? 0);
            }

            return [
                'payment_url' => $responseData['response']['payment']['url'] ?? null,
                'expired_at' => $responseData['response']['payment']['expired_datetime'] ?? null,
                'response' => $responseData
            ];

        } catch (\Exception $e) {
            \Log::error('Payment Processing Error', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \RuntimeException(
                'Payment processing failed: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}