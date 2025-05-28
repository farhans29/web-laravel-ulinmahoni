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
                'payment_method' => 'required'
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
        $tab = request()->get('tab', 'all');
        
        // Get all bookings with relationships
        $query = Transaction::with(['user', 'room', 'property'])
            ->where('user_id', Auth::id())
            ->when($tab === 'all', function($query) {
                return $query->whereIn('transaction_status', ['pending', 'waiting']);
            })
            ->when($tab === 'completed', function($query) {
                return $query->where('transaction_status', ['completed','paid']);
            })
            ->orderBy('created_at', 'desc');
            
        $bookings = $query->get();
        
        // Get counts for tabs using the model
        $completedCount = Transaction::where('user_id', Auth::id())
            ->where('status', '1')
            ->where('transaction_status', 'paid')
            ->count();

        $activeCount = Transaction::where('user_id', Auth::id())
            ->where('status', '1')
            ->where('transaction_status', '!=', 'paid')
            ->count();

        return view('bookings.index', [
            'bookings' => $bookings,
            'completedCount' => $completedCount,
            'activeCount' => $activeCount,
            'activeTab' => $tab
        ]);
    }

    public function store(Request $request)
{
    $response = ['success' => false, 'message' => '', 'redirect_url' => null];

    $validator = \Validator::make($request->all(), [
        'rent_type' => 'required|in:daily,monthly',
        'check_in' => 'nullable|date|after_or_equal:today',
        'check_out' => 'nullable|date|after:check_in',
        'property_name' => 'required|string',
        'room_name' => 'nullable|string',
        'room_id' => 'required|integer|exists:m_rooms,idrec',
        'booking_type' => 'nullable|string|max:100',
        'months' => 'nullable|integer'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to make a booking.',
                'redirect_url' => route('login')
            ], 401);
        }

        // Get room with property relationship
        $room = Room::with('property')->findOrFail($request->room_id);

        // Verify room name matches (prevent tampering)
        if ($room->name !== $request->room_name) {
            return response()->json([
                'success' => false,
                'message' => 'Room information mismatch',
                'errors' => ['room_name' => ['The room information does not match.']]
            ], 422);
        }

        // Get the price based on rent type
        $priceField = 'price_original_' . $request->rent_type;
        $price = $room->$priceField;
        
        // Log the price being used
        \Log::info('Room price selected:', [
            'room_id' => $room->id,
            'rent_type' => $request->rent_type,
            'price_field' => $priceField,
            'price' => $price
        ]);

        if (!$price || $price <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This room is not available for booking at the moment. No valid price found.',
                'errors' => [
                    'price' => ["No valid {$request->rent_type} rate found for this room."]
                ]
            ], 400);
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

        // $adminFee = $totalPrice * 0.1; // 10% admin fee
        $adminFee = 0;

        // Generate order_id in format INV-UM-WEB-yymmddXXXPP
        $propertyInitial = $room->property->initial ?? 'HX';
        $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        $order_id = 'INV-UM-WEB-' . now()->format('ymd') . $randomNumber . $propertyInitial;

        // Prepare transaction data
        $transactionData = [
            'property_id' => $room->property_id,
            'room_id' => $room->idrec,
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
            'booking_months' => $request->rent_type === 'monthly' ? $request->months : null,
            'daily_price' => $price,
            'monthly_price'=> $request->rent_type === 'monthly' ? $price : null,
            'room_price' => $totalPrice,
            'admin_fees' => $adminFee,
            'grandtotal_price' => $totalPrice + $adminFee,
            'property_type' => $room->type ?? $request->property_type ?? 'room',
            'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
            'transaction_status' => 'pending',
            'status' => '1',
        ];

        // Create transaction
        $transaction = Transaction::create($transactionData);

        // Create booking
        $bookingData = [
            'property_id' => $room->property_id,
            'order_id' => $order_id,
            'room_id' => $room->id,
            // 'check_in_at' => $checkIn,
            // 'check_out_at' => $checkOut,
            'status' => '1',
            'booking_type' => $request->booking_type
        ];
        $booking = Booking::create($bookingData);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'redirect_url' => route('payment.show', ['booking' => $transaction->order_id])
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'The requested room is not available.',
            'errors' => ['room_id' => ['The requested room is not available.']]
        ], 404);
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Booking creation failed: ' . $e->getMessage(), [
            'exception' => $e,
            'user_id' => Auth::id(),
            'room_id' => $request->room_id ?? null
        ]);
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage() ?: 'Failed to create booking',
            'errors' => ['server' => [$e->getMessage() ?: 'An unexpected error occurred']]
        ], 500);
    }
}
}
