<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Details - Ulin Mahoni</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @include('components.homepage.styles')
</head>

<body>
    @include('components.homepage.header')

    <main>
        <!-- Hero Section -->
        <div class="hero-section h-64 relative">
            <img src="{{ asset('images/assets/pics/WhatsApp Image 2025-02-20 at 14.30.45.jpeg') }}" 
                alt="Payment Hero" 
                class="w-full h-full object-cover">
            <div class="absolute inset-0 gradient-overlay flex items-center justify-center">
                <h1 class="text-4xl text-white font-light">Payment Details</h1>
            </div>
        </div>

        <!-- Payment Section -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Complete Your Payment</h2>
                    <p class="text-gray-600 mt-1">Choose your preferred payment method to proceed</p>
                </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <form id="paymentForm" action="{{ route('bookings.update-payment', $booking->idrec) }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="payment_method" id="payment_method">
                            <!-- Payment Method -->
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Payment Method</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <button type="button" class="relative border rounded-lg p-4 w-full cursor-pointer hover:border-teal-500 transition-colors payment-option" data-method="bca">
                                        <span class="flex items-center justify-center">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-8">
                                        </span>
                                    </button>
                                    <button type="button" class="relative border rounded-lg p-4 w-full cursor-pointer hover:border-teal-500 transition-colors payment-option" data-method="cash">
                                        <span class="flex items-center justify-center text-lg font-semibold">
                                            Cash
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Summary</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium">Transaction Details</h4>
                                <p class="text-sm text-gray-600 font-bold mb-6">Order ID: {{ $booking->order_id }}</p>
                                <p class="text-sm text-gray-600">Code: {{ $booking->transaction_code }}</p>
                                <p class="text-sm text-gray-600">Date: {{ $booking->created_at->format('d M Y H:i') }}</p>
                                <p class="text-sm text-gray-600">Status: {{ ucfirst($booking->transaction_status) }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium">Guest Details</h4>
                                <h4 class="font-medium text-gray-600">Name</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $booking->user_name }}</p>
                                <h4 class="font-medium text-gray-600">Email</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $booking->user_email }}</p>
                                <h4 class="font-medium text-gray-600">Phone Number</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $booking->user_phone_number }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium">Property Details</h4>
                                <p class="text-sm text-gray-600">{{ $booking->property_name }} ({{ $booking->room_name }})</p>
                                <p class="text-sm text-gray-600">Type: {{ ucfirst($booking->property_type) }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium">Stay Period</h4>
                                <p class="text-sm text-gray-600">Check-in: {{ $booking->check_in->format('d M Y') }}</p>
                                <p class="text-sm text-gray-600">Check-out: {{ $booking->check_out->format('d M Y') }}</p>
                                <p class="text-sm text-gray-600">Duration: {{ $booking->booking_days }} days</p>
                            </div>

                            <div class="pt-4 border-t">
                                <h4 class="font-medium">Pricing</h4>
                                <p class="text-sm text-gray-600">Daily Price: {{ number_format($booking->daily_price, 0) }}</p>
                                <p class="text-sm text-gray-600">Room Price: {{ number_format($booking->room_price, 0) }}</p>
                                <p class="text-sm text-gray-600">Admin Fees: {{ number_format($booking->admin_fees, 0) }}</p>
                                <p class="text-sm font-medium text-gray-900 mt-2">Total: {{ number_format($booking->grandtotal_price, 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('components.homepage.footer')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        @include('components.homepage.scripts')
        document.querySelectorAll('.payment-option').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('payment_method').value = btn.getAttribute('data-method');
                document.getElementById('paymentForm').submit();
            });
        });
    });
</script>
</body>
</html>
