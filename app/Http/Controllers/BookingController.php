<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\Property;

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
                'attachment_file' => 'required|file|mimes:jpg,jpeg,png|max:10240', // 10MB max
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

            if ($request->hasFile('attachment_file') && $request->file('attachment_file')->isValid()) {
                $file = $request->file('attachment_file');
                $fileContents = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileContents);

                // Store the base64 string directly in the attachment column
                DB::table('t_transactions')
                    ->where('idrec', $id)
                    ->update(['attachment' => $base64]);

                return back()->with('success', 'Attachment uploaded successfully.');
            } else {
                return back()->with('error', 'Invalid file upload.');
            }
        } catch (Exception $e) {
            return back()->with('error', 'Error uploading attachment: ' . $e->getMessage());
        }
    }


    /**
     * View booking attachment
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function viewAttachment($id)
    {
        $booking = DB::table('t_transactions')
            ->where('idrec', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$booking || !$booking->attachment) {
            abort(404);
        }

        // Detect MIME type
        $mime = 'image/png'; // default
        $decoded = base64_decode($booking->attachment, true);
        if ($decoded !== false && strlen($decoded) > 2) {
            if (substr($decoded, 0, 2) === "\xFF\xD8") $mime = 'image/jpeg';
            elseif (substr($decoded, 0, 8) === "\x89PNG\x0D\x0A\x1A\x0A") $mime = 'image/png';
        }

        return response($decoded)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="booking_attachment_' . $id . '"');
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
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
                }
                return back()->with('error', 'Booking not found.');
            }

            DB::table('t_transactions')
                ->where('idrec', $id)
                ->update([
                    'transaction_type' => $request->payment_method,
                    'transaction_status' => 'waiting',
                    'paid_at' => null
                ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment method updated successfully.',
                    'redirect_url' => route('bookings.index')
                ]);
            }
            return redirect()->route('bookings.index')
                ->with('success', 'Payment method updated successfully.');
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating payment method: ' . $e->getMessage()
                ], 500);
            }
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

            // Generate order_id in format INV/UM/APP/2505003JH using property_id
            $propertyInitial = 'HX';
            if (!empty($request->property_id)) {
                $property = Property::find($request->property_id);
                if ($property && $property->initial) {
                    $propertyInitial = $property->initial;
                }
            }
            $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $order_id = 'INV-UM-WEB-' . now()->format('ymd') . $randomNumber . $propertyInitial;

            // Get property_id from the room
            $propertyId = null;
            if (!empty($request->room_id)) {
                $room = Room::find($request->room_id);
                if ($room && $room->property_id) {
                    $propertyId = $room->property_id;
                }
            } elseif (!empty($request->room_name)) {
                $room = Room::where('name', $request->room_name)->first();
                if ($room && $room->property_id) {
                    $propertyId = $room->property_id;
                }
            }

            // Prepare transaction data array for transactions table
            $transactionData = [
                'property_id' => $propertyId,
                'room_id' => $room ? $room->id : null,
                'order_id' => $order_id,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_phone_number' => $user->phone,
                'property_name' => $request->property_name,
                'transaction_date' => now(),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'room_name' => $request->room_name,
                'booking_days' => $duration,
                'daily_price' => $price,
                'room_price' => $totalPrice,
                'admin_fees' => $adminFee,
                'grandtotal_price' => $totalPrice + $adminFee,
                'property_type' => $request->property_type ?? 'room',
                'transaction_type' => $request->transaction_type ?? '',
                'transaction_code' => $transactionCode,
                'transaction_status' => 'pending',
                'status' => '1',
                'booking_months' => $request->rent_type === 'monthly' ? ($request->booking_months ?? null) : null
            ];

            // Prepare booking data array for t_booking table
            $bookingData = [
                'property_id' => $propertyId,
                'order_id' => $order_id,
                'room_id' => $room ? $room->id : null,
                'check_in_at' => $checkIn,
                'check_out_at' => $checkOut,
                'status' => '1'
            ];

            // Insert into transactions table
            $transaction = Transaction::create($transactionData);

            // Insert into bookings table
            $booking = Booking::create($bookingData);


            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('payment.show', ['booking' => $transaction->order_id])
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
