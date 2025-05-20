<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\Property;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
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
    /**
     * Generate a signed URL for viewing the attachment
     * 
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateAttachmentUrl($id)
    {
        // Verify the booking exists and belongs to the user
        $booking = DB::table('t_transactions')
            ->where('idrec', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$booking || !$booking->attachment) {
            abort(404, 'Attachment not found');
        }

        // Create a signed URL that's valid for 30 minutes
        $signedUrl = URL::temporarySignedRoute(
            'attachments.view',
            now()->addMinutes(10),
            ['id' => $id]
        );

        return redirect($signedUrl);
    }

    /**
     * View booking attachment (protected by signed URL)
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function viewAttachment($id)
    {
        // The signed middleware already verified the request
        $booking = DB::table('t_transactions')
            ->where('idrec', $id)
            ->first();

        if (!$booking || !$booking->attachment) {
            abort(404, 'Attachment not found');
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
            ->header('Content-Disposition', 'inline; filename="booking_attachment_' . $id . '"')
            ->header('Cache-Control', 'private, max-age=1800'); // Cache for 30 minutes (matching URL expiration)
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
        'room_id' => 'required|integer',
        'booking_type' => 'nullable|string|max:100',
        'months' => 'nullable'
    ]);

    try {
        DB::beginTransaction();

        $user = Auth::user();
        if (!$user) {
            return back()->with('error', 'Please login to make a booking.');
        }

        // Get room with property relationship
        $room = Room::with('property')->findOrFail($request->room_id);

        // Verify room name matches (prevent tampering)
        if ($room->name !== $request->room_name) {
            throw new Exception('Room information mismatch');
        }

        // Get prices from room data
        $roomPrices = is_string($room->price) 
            ? json_decode($room->price, true) 
            : ($room->price ?? []);
            
        $price = $roomPrices[$request->rent_type]['discounted'] 
            ?? $roomPrices[$request->rent_type]['original'] 
            ?? null;

        if (!$price || $price <= 0) {
            throw new \Exception('This room is not available for booking at the moment');
        }

        // Calculate dates and prices
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

        // Generate order_id in format INV-UM-WEB-yymmddXXXPP
        $propertyInitial = $room->property->initial ?? 'HX';
        $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        $order_id = 'INV-UM-WEB-' . now()->format('ymd') . $randomNumber . $propertyInitial;

        // Prepare transaction data
        $transactionData = [
            'property_id' => $room->property_id,
            'room_id' => $room->id,
            'order_id' => $order_id,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_phone_number' => $user->phone,
            'property_name' => $room->property->name ?? $request->property_name,
            'transaction_date' => now(),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'room_name' => $room->name,
            'booking_days' => $duration,
            'daily_price' => $price,
            'room_price' => $totalPrice,
            'admin_fees' => $adminFee,
            'grandtotal_price' => $totalPrice + $adminFee,
            'property_type' => $room->type ?? $request->property_type ?? 'room',
            'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
            'transaction_status' => 'pending',
            'status' => '1',
            'booking_months' => $request->rent_type === 'monthly' ? $request->months : null
        ];

        // Create transaction
        $transaction = Transaction::create($transactionData);

        // Create booking
        $bookingData = [
            'property_id' => $room->property_id,
            'order_id' => $order_id,
            'room_id' => $room->id,
            'check_in_at' => $checkIn,
            'check_out_at' => $checkOut,
            'status' => '1',
            'booking_type' => $request->booking_type
        ];
        $booking = Booking::create($bookingData);

        DB::commit();

        // Redirect to payment page
        return redirect()->route('payment.show', ['booking' => $transaction->order_id])
            ->with('success', 'Booking created successfully!');

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        DB::rollBack();
        return back()->with('error', 'The requested room is not available.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Booking creation failed: ' . $e->getMessage(), [
            'exception' => $e,
            'user_id' => Auth::id(),
            'room_id' => $request->room_id ?? null
        ]);
        
        return back()->with('error', $e->getMessage() ?: 'Failed to create booking');
    }
}
}
