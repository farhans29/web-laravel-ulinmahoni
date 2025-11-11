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
        <div class="hero-section-booking h-72 relative">
            <img src="{{ asset('images/assets/pics/WhatsApp Image 2025-02-20 at 14.30.45.jpeg') }}" 
                alt="Payment Hero" 
                class="w-full h-full object-cover">
            <div class="absolute inset-0 gradient-overlay flex items-center justify-center">
                <h1 class="text-4xl text-white font-medium">Payment Details</h1>
            </div>
        </div>

        <!-- Payment Section -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Selesaikan Pembayaran</h2>
                    <p class="text-gray-600 mt-1">Pilih metode pembayaran yang Anda inginkan</p>
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
                                    <label class="block text-lg font-medium text-gray-700 mb-4">Metode Pembayaran</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Bank Transfer -->
                                        <div class="space-y-3">
                                            <p class="text-sm font-medium text-gray-700">Pilih Bank Tujuan</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <label class="bank-card relative p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-teal-500 hover:shadow-md has-[:checked]:border-teal-500 has-[:checked]:ring-2 has-[:checked]:ring-teal-200">
                                                    <input type="radio" name="bank_selection" value="bca" class="sr-only peer" data-bank="bca" data-bank-name="BCA" data-bank-logo="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" data-va-number="888811112222">
                                                    <div class="flex items-center">
                                                        <div class="bg-white p-2 rounded-lg border mr-3">
                                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-8 w-auto">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="font-semibold text-gray-800">BCA Virtual Account</div>
                                                            <div class="text-xs text-gray-500 mt-0.5">8888 1111 2222</div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="absolute top-2 right-2 h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:bg-teal-500 peer-checked:border-teal-500 flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div> -->
                                                </label>

                                                <label class="bank-card relative p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-teal-500 hover:shadow-md has-[:checked]:border-teal-500 has-[:checked]:ring-2 has-[:checked]:ring-teal-200">
                                                    <input type="radio" name="bank_selection" value="mandiri" class="sr-only peer" data-bank="mandiri" data-bank-name="Mandiri" data-bank-logo="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1200px-Bank_Mandiri_logo_2016.svg.png" data-va-number="888811112222">
                                                    <div class="flex items-center">
                                                        <div class="bg-white p-2 rounded-lg border mr-3">
                                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1200px-Bank_Mandiri_logo_2016.svg.png" alt="Mandiri" class="h-8 w-auto">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="font-semibold text-gray-800">Mandiri Virtual Account</div>
                                                            <div class="text-xs text-gray-500 mt-0.5">8888 1111 2222</div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="absolute top-2 right-2 h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:bg-teal-500 peer-checked:border-teal-500 flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div> -->
                                                </label>

                                                <label class="bank-card relative p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-teal-500 hover:shadow-md has-[:checked]:border-teal-500 has-[:checked]:ring-2 has-[:checked]:ring-teal-200">
                                                    <input type="radio" name="bank_selection" value="bni" class="sr-only peer" data-bank="bni" data-bank-name="BNI" data-bank-logo="https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg" data-va-number="888811112222">
                                                    <div class="flex items-center">
                                                        <div class="bg-white p-2 rounded-lg border mr-3">
                                                            <img src="https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg" alt="BNI" class="h-8 w-auto">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="font-semibold text-gray-800">BNI Virtual Account</div>
                                                            <div class="text-xs text-gray-500 mt-0.5">8888 1111 2222</div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="absolute top-2 right-2 h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:bg-teal-500 peer-checked:border-teal-500 flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div> -->
                                                </label>

                                                <label class="bank-card relative p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-teal-500 hover:shadow-md has-[:checked]:border-teal-500 has-[:checked]:ring-2 has-[:checked]:ring-teal-200">
                                                    <input type="radio" name="bank_selection" value="bri" class="sr-only peer" data-bank="bri" data-bank-name="BRI" data-bank-logo="https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg" data-va-number="888811112222">
                                                    <div class="flex items-center">
                                                        <div class="bg-white p-2 rounded-lg border mr-3">
                                                            <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg" alt="BRI" class="h-8 w-auto">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="font-semibold text-gray-800">BRI Virtual Account</div>
                                                            <div class="text-xs text-gray-500 mt-0.5">8888 1111 2222</div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="absolute top-2 right-2 h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:bg-teal-500 peer-checked:border-teal-500 flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div> -->
                                                </label>
                                            </div>
                                        </div>

                                        <!-- <button type="button" class="payment-option relative border rounded-lg p-4 w-full cursor-pointer hover:border-teal-500 transition-colors flex items-center justify-between" data-method="cash">
                                            <span class="text-lg font-semibold text-gray-800">Bayar di Tempat</span>
                                        </button> -->
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
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Pemesanan</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium">Detail Transaksi</h4>
                                <p class="text-sm text-gray-600 font-bold mb-6">Order ID: {{ $booking->order_id }}</p>
                                <p class="text-sm text-gray-600">Kode: {{ $booking->transaction_code }}</p>
                                <p class="text-sm text-gray-600">Tanggal: {{ $booking->created_at->format('d M Y H:i') }}</p>
                                <p class="text-sm text-gray-600">Status: {{ ucfirst($booking->transaction_status) }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium">Detail Tamu</h4>
                                <h4 class="font-medium text-gray-600">Nama</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $booking->user_name }}</p>
                                <h4 class="font-medium text-gray-600">Email</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $booking->user_email }}</p>
                                <h4 class="font-medium text-gray-600">Nomor Telepon</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $booking->user_phone_number }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium">Detail Properti</h4>
                                <p class="text-sm text-gray-600">{{ $booking->property_name }} ({{ $booking->room_name }})</p>
                                <p class="text-sm text-gray-600">Type: {{ ucfirst($booking->property_type) }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium">Jangka Waktu</h4>
                                <p class="text-sm text-gray-600">Check-in: {{ $booking->check_in->format('d M Y') }}</p>
                                <p class="text-sm text-gray-600">Check-out: {{ $booking->check_out->format('d M Y') }}</p>
                                <p class="text-sm text-gray-600">Durasi: {{ $booking->booking_months }} bulan</p>
                                <p class="text-sm text-gray-600">Durasi: {{ $booking->booking_days }} hari</p>
                            </div>

                            <div class="pt-4 border-t">
                                <h4 class="font-medium">Pricing</h4>
                                <!-- <p class="text-sm text-gray-600">Harga Harian: {{ number_format($booking->daily_price, 0) }}</p> -->
                                <p class="text-sm text-gray-600">Harga Kamar: {{ number_format($booking->room_price, 0) }}</p>
                                <p class="text-sm text-gray-600">Biaya Admin: {{ number_format($booking->admin_fees, 0) }}</p>
                                <p class="text-sm text-gray-600">Biaya Layanan: {{ number_format($booking->service_fees, 0) }}</p>
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
        
        const paymentForm = document.getElementById('paymentForm');
        const submitBtn = document.getElementById('submitPayment');
        const submitText = document.getElementById('submitText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        let selectedPaymentMethod = '';
        
        // Handle bank card selection
        document.querySelectorAll('.bank-card').forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            const bankName = radio.dataset.bankName;
            const bankLogo = radio.dataset.bankLogo;
            
            card.addEventListener('click', () => {
                // Update the hidden payment method input
                selectedPaymentMethod = 'bank_transfer';
                document.getElementById('payment_method').value = radio.value;
                
                // Update form data
                let bankInput = paymentForm.querySelector('input[name="bank"]');
                if (!bankInput) {
                    bankInput = document.createElement('input');
                    bankInput.type = 'hidden';
                    bankInput.name = 'bank';
                    paymentForm.appendChild(bankInput);
                }
                bankInput.value = radio.value;
                
                // Update submit button
                submitBtn.disabled = false;
                submitText.textContent = `Bayar dengan ${bankName}`;
                
                // Update visual selection
                document.querySelectorAll('.bank-card').forEach(c => {
                    c.classList.remove('border-teal-500', 'ring-2', 'ring-teal-200');
                    c.classList.add('border-gray-200');
                });
                card.classList.add('border-teal-500', 'ring-2', 'ring-teal-200');
                card.classList.remove('border-gray-200');
                
                // Ensure the radio is checked for form submission
                radio.checked = true;
            });
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
                const selectedBank = document.querySelector('input[name="bank_selection"]:checked');
                submitText.textContent = selectedBank ? 
                    `Bayar dengan ${selectedBank.dataset.bankName}` : 'Pilih Bank';
                loadingSpinner.classList.add('hidden');
            });
        });
    });
</script>
</body>
</html>
