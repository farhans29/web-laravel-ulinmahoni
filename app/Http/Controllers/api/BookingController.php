<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\ApiController;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
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
            $grandtotalPrice = 0;
            $checkIn = \Carbon\Carbon::parse($request->check_in);
            $checkOut = \Carbon\Carbon::parse($request->check_out);

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
                $adminFees = 0;
                $grandtotalPrice = $roomPrice + $adminFees;
            } else {
                // MONTHLY BOOKING
                $monthlyPrice = $request->monthly_price;
                $bookingMonths = $request->booking_months;

                $roomPrice = $monthlyPrice * $bookingMonths;
                // $adminFees = $roomPrice * 0.10;
                $adminFees = 0;
                $grandtotalPrice = $roomPrice + $adminFees;
            }
            
            // Generate order_id in format INV-UM-APP-yymmddXXXPP
            $property = $request->property_id ? Property::find($request->property_id) : null;
            $propertyInitial = $property && $property->initial ? $property->initial : 'XX';
            $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $order_id = 'INV-UM-APP-' . now()->format('ymd') . $randomNumber . $propertyInitial;

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
                'grandtotal_price' => $grandtotalPrice,
                // CODE AND STATUS
                'transaction_code' => 'TRX-' . Str::random(8),
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

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully',
                'data' => $transaction
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
            $updated = DB::table('t_transactions')
                ->where('idrec', $id)
                ->update(['attachment' => $base64Image]);

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
    
}