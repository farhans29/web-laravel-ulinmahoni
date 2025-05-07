<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id' => 'required',
            'user_name' => 'required',
            'user_phone_number' => 'required',
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
            $checkIn = \Carbon\Carbon::parse($request->check_in);
            $checkOut = \Carbon\Carbon::parse($request->check_out);
            $bookingDays = $checkOut->diffInDays($checkIn);

            $roomPrice = $request->daily_price * $bookingDays;
            $adminFees = $roomPrice * 0.10; // 10% admin fee
            $grandtotalPrice = $roomPrice + $adminFees;

            $transaction = Transaction::create([
                ...$validator->validated(),
                'order_id' => 'ORD-' . Str::random(10),
                'transaction_date' => now(),
                'booking_days' => $bookingDays,
                'room_price' => $roomPrice,
                'admin_fees' => $adminFees,
                'grandtotal_price' => $grandtotalPrice,
                'transaction_type' => 'booking',
                'transaction_code' => 'TRX-' . Str::random(8),
                'transaction_status' => 'pending',
                'status' => 'active'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully',
                'data' => $transaction
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating booking',
                'error' => $e->getMessage()
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

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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
}