<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Upload attachment for a booking
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadAttachment(Request $request, $id)
    {
        try {
            $request->validate([
                'attachment' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
            ]);
    
            $booking = DB::table('t_transactions')
                ->where('idrec', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$booking) {
                return back()->with('error', 'Booking not found.');
            }
    
            // Delete old attachment if exists
            if ($booking->attachment) {
                Storage::disk('public')->delete($booking->attachment);
            }
    
            // Store the new file
            $path = $request->file('attachment')->store('booking-attachments/' . $id, 'public');
    
            // Update the booking record
            DB::table('t_transactions')
                ->where('order_id', $id)
                ->update(['attachment' => $path]);
    
            return back()->with('success', 'Attachment uploaded successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Error uploading attachment: ' . $e->getMessage());
        }
    }

    /**
     * Update payment method for a booking
     * 
     * @param Request $request
     * @param string $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePaymentMethod(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_method' => 'required|in:bca,cash'
            ]);

            $booking = DB::table('t_transactions')
                ->where('idrec', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$booking) {
                return back()->with('error', 'Booking not found.');
            }

            // Update the booking record with payment method
            DB::table('t_transactions')
                ->where('idrec', $id)
                ->update([
                    'transaction_type' => $request->payment_method,
                    'status' => $request->payment_method === 'bca' ? 'paid' : 'pending',
                    'payment_date' => $request->payment_method === 'bca' ? now() : null
                ]);

            return redirect()->route('bookings.index')
                ->with('success', 'Payment method updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Error updating payment method: ' . $e->getMessage());
        }
    }

    // public function uploadAttachment(Request $request, $orderId)
    // {
    //     try {
    //         $request->validate([
    //             'attachment' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
    //         ]);
    
    //         $booking = DB::table('t_transactions')
    //             ->where('order_id', $orderId)
    //             ->where('user_id', Auth::id())
    //             ->first();

    //         if (!$booking) {
    //             return back()->with('error', 'Booking not found.');
    //         }
    
    //         // Delete old attachment if exists
    //         if ($booking->attachment) {
    //             Storage::disk('public')->delete($booking->attachment);
    //         }
    
    //         // Store the new file
    //         $path = $request->file('attachment')->store('booking-attachments/' . $orderId, 'public');
    
    //         // Update the booking record
    //         DB::table('t_transactions')
    //             ->where('order_id', $orderId)
    //             ->update(['attachment' => $path]);
    
    //         return back()->with('success', 'Attachment uploaded successfully.');
    //     } catch (Exception $e) {
    //         return back()->with('error', 'Error uploading attachment: ' . $e->getMessage());
    //     }
    // }
    public function index()
    {
        $bookings = DB::table('t_transactions')
            ->join('users', 't_transactions.user_id', '=', 'users.id')
            ->select('t_transactions.*', 'users.username', 'users.email')
            ->where('t_transactions.status','1')
            ->where('users.id', Auth::user()->id)
            ->orderBy('t_transactions.created_at', 'desc')
            ->paginate(10);

        // Convert the paginated results to Booking model instances
        $bookings->getCollection()->transform(function ($booking) {
            return (new Booking)->forceFill((array)$booking);
        });

        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rent_type' => 'required|in:daily,monthly',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'property_name' => 'required|string',
            'room_name' => 'required|string',
            'months' => 'required_if:rent_type,monthly|in:1,3,6,12'
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            if (!$user) {
                throw new Exception('User not authenticated');
            }
            
            // Generate unique transaction code
            $transactionCode = 'TRX-' . strtoupper(Str::random(8));

            $room = Room::where('name', $request->room_name)->first();
            if (!$room) {
                throw new Exception('Room not found');
            }

            // Get prices from request instead of room model
            $prices = json_decode($request->prices, true);
            $price = $prices[$request->rent_type] ?? 0;

            if ($price <= 0) {
                throw new Exception('Invalid room price');
            }

            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);

            if ($request->rent_type === 'monthly') {
                $months = (int) $request->months;
                $checkOut = $checkIn->copy()->addMonths($months);
                $duration = $months;
                $totalPrice = $price * $months;
            } else {
                $duration = $checkIn->diffInDays($checkOut);
                $totalPrice = $price * $duration;
            }

            $adminFee = $totalPrice * 0.1; // 10% admin fee

            $booking = Booking::create([
                'order_id' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'user_name' => $user->name,
                // 'user_phone_number' => $user->phone,
                'property_name' => $request->property_name,
                'transaction_date' => now(),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'room_name' => $request->room_name,
                'user_email' => $user->email,
                'booking_days' => $duration,
                'daily_price' => $price,
                'room_price' => $totalPrice,
                'admin_fees' => $adminFee,
                'grandtotal_price' => $totalPrice + $adminFee,
                'property_type' => $request->property_type ?? 'room',
                'transaction_type' => $request->transaction_type ?? '',
                'transaction_code' => $transactionCode,
                'transaction_status' => 'pending',
                'status' => '1'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('payment.show', ['booking' => $booking->order_id])
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => ['general' => [$e->getMessage()]]
            ], 422);
        }
    }
}
