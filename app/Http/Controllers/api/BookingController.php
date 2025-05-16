<?php

namespace aApp\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;

use aApp\Http\Controllers\ApiController;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
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
            'user_id' => 'required',
            'user_name' => 'required',
            'user_phone_number' => 'required',
            'property_id'=>'nullable',
            'property_name' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'room_name' => 'required',
            'user_email' => 'required|email',
            'daily_price' => 'required|numeric',
            'property_type' => 'required'
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

            $checkIn = \Carbon\Carbon::parse($request->check_in);
            $checkOut = \Carbon\Carbon::parse($request->check_out);
            $bookingDays = $checkOut->diffInDays($checkIn);

            $roomPrice = $request->daily_price * $bookingDays;
            $adminFees = $roomPrice * 0.10; // 10% admin fee
            $grandtotalPrice = $roomPrice + $adminFees;

            // Generate order_id in format INV/UM0/APP/2505003JH using property_id
            $property = null;
            if ($request->property_id) {
                $property = Property::find($request->property_id);
            }
            $propertyInitial = $property && $property->initial ? $property->initial : 'XX';
            $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $order_id = 'INV-UM-APP-' . now()->format('ymd') . $randomNumber . $propertyInitial;

            // Insert into transactions table
            $transaction = Transaction::create([
                ...$validator->validated(),
                'order_id' => $order_id,
                'transaction_date' => now(),
                'booking_days' => $bookingDays,
                'room_price' => $roomPrice,
                'admin_fees' => $adminFees,
                'grandtotal_price' => $grandtotalPrice,
                'transaction_type' => 'booking',
                'transaction_code' => 'TRX-' . Str::random(8),
                'transaction_status' => 'pending',
                'status' => '1'
            ]);

            // Prepare booking data for t_booking
            $bookingData = [
                'property_id' => $request->property_id,
                'order_id' => $order_id,
                'room_id' => $request->room_id ?? null,
                'check_in_at' => $request->check_in,
                'check_out_at' => $request->check_out,
                'status' => '1'
            ];
            Booking::create($bookingData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully',
                'data' => $transaction
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating booking',
                'errors' => ['general' => [$e->getMessage()]]
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
}