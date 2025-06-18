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
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-lg font-medium text-gray-700 mb-4">Payment Method</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="relative">
                                            <button type="button" id="bankTransferBtn" class="w-full text-left border rounded-lg p-4 hover:border-teal-500 transition-colors flex items-center justify-between">
                                                <span class="text-lg font-semibold text-gray-800">Transfer Bank</span>
                                                <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="bankArrow">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                            <div id="bankOptions" class="hidden mt-2 border rounded-lg p-4 bg-white shadow-lg absolute z-10 w-full">
                                                <p class="text-sm text-gray-500 mb-3">Pilih Bank Tujuan</p>
                                                <div class="space-y-3">
                                                    <label class="flex items-center p-3 rounded-lg border hover:bg-gray-50 cursor-pointer">
                                                        <input type="radio" name="bank_selection" value="bca" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                                        <div class="ml-3 flex items-center">
                                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-6 mr-2">
                                                            <span class="font-bold text-gray-700">BCA</span>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center p-3 rounded-lg border hover:bg-gray-50 cursor-pointer">
                                                        <input type="radio" name="bank_selection" value="mandiri" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                                        <div class="ml-3 flex items-center">
                                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1200px-Bank_Mandiri_logo_2016.svg.png" alt="Mandiri" class="h-6 mr-2">
                                                            <span class="font-bold text-gray-700">Mandiri</span>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center p-3 rounded-lg border hover:bg-gray-50 cursor-pointer">
                                                        <input type="radio" name="bank_selection" value="bni" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                                        <div class="ml-3 flex items-center">
                                                            <img src="https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg" alt="BNI" class="h-6 mr-2">
                                                            <span class="font-bold text-gray-700">BNI</span>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center p-3 rounded-lg border hover:bg-gray-50 cursor-pointer">
                                                        <input type="radio" name="bank_selection" value="bri" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                                        <div class="ml-3 flex items-center">
                                                            <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg" alt="BRI" class="h-6 mr-2">
                                                            <span class="font-bold text-gray-700">BRI</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="payment-option relative border rounded-lg p-4 w-full cursor-pointer hover:border-teal-500 transition-colors flex items-center justify-between" data-method="cash">
                                            <span class="text-lg font-semibold text-gray-800">Bayar di Tempat</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <button type="submit" id="submitPayment" 
                                    class="w-full bg-teal-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                    <span id="submitText">Lanjutkan Pembayaran</span>
                                    <span id="loadingSpinner" class="hidden">
                                        <i class="fas fa-spinner fa-spin ml-2"></i>
                                    </span>
                                </button>
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
                                <p class="text-sm text-gray-600">Duration: {{ $booking->booking_months }} months</p>
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
        
        // Toggle bank options
        const bankTransferBtn = document.getElementById('bankTransferBtn');
        const bankOptions = document.getElementById('bankOptions');
        const bankArrow = document.getElementById('bankArrow');
        const paymentForm = document.getElementById('paymentForm');
        const submitBtn = document.getElementById('submitPayment');
        const submitText = document.getElementById('submitText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        let selectedPaymentMethod = '';
        
        // Toggle bank options dropdown
        bankTransferBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            bankOptions.classList.toggle('hidden');
            bankArrow.classList.toggle('transform');
            bankArrow.classList.toggle('rotate-180');
        });
        
        // Close bank options when clicking outside
        document.addEventListener('click', function(e) {
            if (!bankOptions.contains(e.target) && e.target !== bankTransferBtn) {
                bankOptions.classList.add('hidden');
                bankArrow.classList.remove('transform', 'rotate-180');
            }
        });
        
        // Handle bank selection
        document.querySelectorAll('input[name="bank_selection"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedBank = this.value;
                selectedPaymentMethod = 'bank';
                document.getElementById('payment_method').value = selectedBank; // 'bca', 'mandiri', etc.
                bankOptions.classList.add('hidden');
                bankArrow.classList.remove('transform', 'rotate-180');
                submitBtn.disabled = false;
                
                // Update submit button text
                submitText.textContent = `Bayar dengan ${this.nextElementSibling.querySelector('span').textContent}`;
                
                // Highlight selected bank
                document.querySelectorAll('input[name="bank_selection"]').forEach(r => {
                    r.closest('label').classList.remove('border-teal-500', 'bg-teal-50');
                });
                this.closest('label').classList.add('border-teal-500', 'bg-teal-50');
            });
        });
        
        // Handle pay on location option
        document.querySelector('.payment-option[data-method="cash"]').addEventListener('click', function() {
            selectedPaymentMethod = 'cash';
            document.getElementById('payment_method').value = 'cash';
            submitBtn.disabled = false;
            submitText.textContent = 'Bayar di Tempat';
            
            // Remove highlight from bank options
            document.querySelectorAll('input[name="bank_selection"]').forEach(r => {
                r.closest('label').classList.remove('border-teal-500', 'bg-teal-50');
            });
            
            // Highlight pay on location
            this.classList.add('border-teal-500', 'bg-teal-50');
        });
        
        // Handle form submission
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!selectedPaymentMethod) {
                alert('Silakan pilih metode pembayaran');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Memproses...';
            loadingSpinner.classList.remove('hidden');
            
            // Submit form
            fetch(paymentForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(new FormData(paymentForm))
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses pembayaran');
                }
                return data;
            })
            .then(data => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.href = '{{ route("bookings.index") }}';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
                submitBtn.disabled = false;
                submitText.textContent = selectedPaymentMethod === 'cash' ? 'Bayar di Tempat' : `Bayar dengan ${document.querySelector('input[name="bank_selection"]:checked')?.nextElementSibling?.querySelector('span')?.textContent || 'Bank'}`;
                loadingSpinner.classList.add('hidden');
            });
        });
    });
</script>
</body>
</html>
