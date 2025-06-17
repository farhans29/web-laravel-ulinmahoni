<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
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
    /**
     * Check room availability
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            ->where('property_id', $propertyId)
            ->where('room_id', $roomId)
            ->where('status', '1')  // Active/confirmed booking
            ->whereNotIn('transaction_status', ['cancelled', 'finished'])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->limit(5)
            ->get();

        $isAvailable = $conflictingBookings->isEmpty();

        return response()->json([
            'status' => 'success',
            'data' => [
                'is_available' => $isAvailable,
                'conflicting_bookings' => $isAvailable ? [] : $conflictingBookings,
                'check_in' => $checkIn->format('Y-m-d H:i:s'),
                'check_out' => $checkOut->format('Y-m-d H:i:s'),
                'property_id' => $propertyId,
                'room_id' => $roomId
            ]
        ]);
    }
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Upload attachment for a booking
     * 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function uploadAttachment(Request $request, $id)
    {
        try {
            $request->validate([
                'attachment_file' => 'required|file|mimes:jpg,jpeg,png|max:10240', // 10MB max
            ]);

            // Find the booking
            $booking = DB::table('t_transactions')
                ->where('idrec', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$booking) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Booking not found.'
                    ], 404);
                }
                return back()->with('error', 'Booking not found.');
            }

            // Handle file upload
            if ($request->hasFile('attachment_file') && $request->file('attachment_file')->isValid()) {
                $file = $request->file('attachment_file');
                
                // Validate file type
                $validTypes = ['image/jpeg', 'image/png'];
                if (!in_array($file->getMimeType(), $validTypes)) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Invalid file type. Please upload a JPEG or PNG image.'
                        ], 400);
                    }
                    return back()->with('error', 'Invalid file type. Please upload a JPEG or PNG image.');
                }

                // Validate file size (10MB)
                if ($file->getSize() > 10 * 1024 * 1024) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'File is too large. Maximum size is 10MB.'
                        ], 400);
                    }
                    return back()->with('error', 'File is too large. Maximum size is 10MB.');
                }

                // Read file contents and convert to base64
                $fileContents = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileContents);

                // Update the booking with the new attachment
                $updated = DB::table('t_transactions')
                    ->where('idrec', $id)
                    ->update([
                        'attachment' => $base64,
                        'updated_at' => now(),
                    ]);

                if ($updated) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Payment proof uploaded successfully.',
                            'data' => [
                                'booking_id' => $id,
                                'has_attachment' => true
                            ]
                        ]);
                    }
                    return back()->with('success', 'Payment proof uploaded successfully.');
                }


                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to update booking with attachment.'
                    ], 500);
                }
                return back()->with('error', 'Failed to update booking with attachment.');

            } else {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid file upload.'
                    ], 400);
                }
                return back()->with('error', 'Invalid file upload.');
            }
        } catch (\Exception $e) {
            \Log::error('Error uploading attachment: ' . $e->getMessage(), [
                'booking_id' => $id,
                'user_id' => Auth::id(),
                'exception' => $e
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while uploading the file.',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            return back()->with('error', 'An error occurred while uploading the file. Please try again.');
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
                    'transaction_status' => 'pending',
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
        $userId = Auth::id();
        
        // Debug: Log the tab and user ID
        \Log::info('Bookings index', [
            'tab' => $tab,
            'user_id' => $userId,
            'url' => request()->fullUrl()
        ]);
        
        // Get all bookings for the user with relationships
        $bookings = Transaction::with(['user', 'room', 'property'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Debug: Log the query results
        \Log::info('All user bookings', [
            'count' => $bookings->count(),
            'statuses' => $bookings->pluck('transaction_status')->toArray()
        ]);
        
        // Get counts for tabs
        $completedCount = $bookings->whereIn('transaction_status', ['paid', 'completed'])->count();
        $activeCount = $bookings->whereNotIn('transaction_status', ['paid', 'completed', 'cancelled'])->count();
        $cancelledCount = $bookings->where('transaction_status', 'cancelled')->count();

        return view('bookings.index', [
            'allBookings' => $bookings,  // Pass all bookings to the view
            'completedCount' => $completedCount,
            'activeCount' => $activeCount,
            'cancelledCount' => $cancelledCount,
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
            $bookingMonths = 0;
            $bookingDays = 0;

            if ($request->rent_type === 'monthly') {
                $bookingMonths = (int) $request->months;
                $checkOut = $checkIn->copy()->addMonths($bookingMonths);
                $bookingDays = $checkIn->diffInDays($checkOut);
                $totalPrice = $price * $bookingMonths;
            } else {
                $bookingDays = $checkIn->diffInDays($checkOut);
                $totalPrice = $price * $bookingDays;
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
                // 'room_id' => $request->room_id,
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
                'booking_days' => $bookingDays,
                'booking_months' => $request->rent_type === 'monthly' ? $bookingMonths : null,
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
                'room_id' => $room->idrec,
                // 'check_in_at' => $checkIn,
                // 'check_out_at' => $checkOut,
                'status' => '1',
                'booking_type' => $request->booking_type
            ];
            $booking = Booking::create($bookingData);

            // Create payment record
            $paymentData = [
                'property_id' => $room->property_id,
                'room_id' => $room->idrec,
                'order_id' => $order_id,
                'user_id' => $user->id,
                'grandtotal_price' => $totalPrice + $adminFee,
                'payment_status' => 'unpaid',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];

            Payment::create($paymentData);

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
