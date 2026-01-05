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
            {{-- <img src="{{ asset('images/assets/pics/WhatsApp Image 2025-02-20 at 14.30.45.jpeg') }}" 
                alt="Payment Hero" 
                class="w-full h-full object-cover"> --}}
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
                                    <p class="text-sm text-gray-600 mb-4">Pilih bank untuk generate Virtual Account</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @php
                                            $banks = config('services.doku.banks');
                                            $bankLogos = [
                                                'BRI' => 'https://images.seeklogo.com/logo-png/45/1/bank-bri-logo-png_seeklogo-459990.png',
                                                'BNI' => 'https://www.pinclipart.com/picdir/big/105-1051729_bank-negara-indonesia-logo-bank-bni-transparan-clipart.png',
                                                'MANDIRI' => 'https://freepngdesign.com/content/uploads/images/p-2813-2-bank-mandiri-logo-png-transparent-logo-699390155888.png',
                                                'BCA' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg',
                                                'CIMB' => 'https://www.buysolar.my/images/banklogo/cimb.png',
                                                'BTN' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/BTN_2024.svg/960px-BTN_2024.svg.png',
                                                'DANAMON' => 'https://www.clipartmax.com/png/middle/86-863868_danamon-bank-logo-bank-danamon.png',
                                                'BNC' => 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEiKM1U7jUvSqIDsJYruR_kCYRBzyjAojO62aFqFt2fd4n0Eg1z3pwgRD6jgQkBP7lqtNincIx7UCOuNC7fNhBx3iuJKRClkNADRTTHCoIUvZ8ff3k0nhwWBJaz3QhDOYY1VORkGehl_-_Zy0XO2N2IpqloOCXOZnyJk97WpRnRm9U0nFssUtO9mXRec/s400/GKL24_Bank%20Neo%20Commerce%20-%20Koleksilogo.com.jpg',
                                                'BSI' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Bank_Syariah_Indonesia.svg/960px-Bank_Syariah_Indonesia.svg.png',
                                                'MAYBANK' => 'https://upload.wikimedia.org/wikipedia/en/thumb/d/d7/Maybank_logo.svg/960px-Maybank_logo.svg.png',
                                                'PERMATA' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Permata_Bank_%282024%29.svg/960px-Permata_Bank_%282024%29.svg.png',
                                            ];
                                        @endphp

                                        @foreach($banks as $bankCode => $bankConfig)
                                        <label class="bank-card relative p-6 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-teal-500 hover:shadow-md has-[:checked]:border-teal-500 has-[:checked]:ring-2 has-[:checked]:ring-teal-200">
                                            <input type="radio" name="bank_selection" value="{{ strtolower($bankCode) }}" class="sr-only peer"
                                                data-bank="{{ strtolower($bankCode) }}"
                                                data-bank-name="{{ $bankCode }}"
                                                data-bank-logo="{{ $bankLogos[$bankCode] ?? '' }}"
                                                data-dgpc="{{ $bankConfig['dgpc'] }}"
                                                data-channel="{{ $bankConfig['channel'] }}">
                                            <div class="flex items-center">
                                                <div class="bg-white p-3 rounded-lg border mr-4">
                                                    @if(isset($bankLogos[$bankCode]))
                                                        <img src="{{ $bankLogos[$bankCode] }}" alt="{{ $bankCode }}" class="h-10 w-auto">
                                                    @else
                                                        <div class="h-10 w-20 flex items-center justify-center bg-gray-100 rounded">
                                                            <span class="text-xs font-bold text-gray-600">{{ $bankCode }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-bold text-black text-lg mb-1">{{ $bankCode }}</div>
                                                    <div class="text-sm text-gray-600">Virtual Account</div>
                                                </div>
                                            </div>
                                        </label>
                                        @endforeach
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
                                <p class="text-sm text-gray-600">Check-in: {{ $booking->check_in->format('d F Y') }}</p>
                                <p class="text-sm text-gray-600">Check-out: {{ $booking->check_out->format('d F Y') }}</p>

                                @if($booking->booking_months > 0)
                                    <p class="text-sm text-gray-600">Durasi: {{ $booking->booking_months }} bulan</p>
                                @elseif($booking->booking_days > 0)
                                    <p class="text-sm text-gray-600">Durasi: {{ $booking->booking_days }} hari</p>
                                @endif

                            </div>

                            <div class="pt-4 border-t">
                                <h4 class="font-medium">Pricing</h4>
                                <!-- <p class="text-sm text-gray-600">Harga Harian: {{ number_format($booking->daily_price, 0) }}</p> -->
                                <p class="text-sm text-gray-600">Harga Kamar: {{ number_format($booking->room_price, 0) }}</p>
                                {{-- <p class="text-sm text-gray-600">Biaya Admin: {{ number_format($booking->admin_fees, 0) }}</p> --}}
                                <p class="text-sm text-gray-600">Biaya Layanan: {{ number_format($booking->service_fees, 0) }}</p>
                                <p class="text-sm font-medium text-gray-900 mt-2">Total: {{ number_format($booking->grandtotal_price, 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Success Modal -->
        <div id="successModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Virtual Account Berhasil Dibuat!</h3>
                    <div class="mt-4 px-4 py-3 bg-gray-50 rounded-lg">
                        <div class="text-left space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Bank:</span>
                                <span class="text-sm font-medium" id="modalBank"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Nomor VA:</span>
                                <span class="text-sm font-bold" id="modalVANumber"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Jumlah:</span>
                                <span class="text-sm font-medium" id="modalAmount"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2">
                        <a id="howToPayLink" href="#" target="_blank" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            Lihat Cara Pembayaran
                        </a>
                        <button id="closeModalBtn" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div id="errorModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Terjadi Kesalahan</h3>
                    <div class="mt-2 px-4 py-3">
                        <p class="text-sm text-gray-600" id="errorMessage"></p>
                    </div>
                    <div class="mt-4">
                        <button id="closeErrorModalBtn" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!selectedPaymentMethod) {
                alert('Silakan pilih metode pembayaran');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitText.textContent = 'Memproses pembayaran...';
            loadingSpinner.classList.remove('hidden');

            try {
                // Get selected bank
                const selectedBank = document.querySelector('input[name="bank_selection"]:checked');
                if (!selectedBank) {
                    throw new Error('Silakan pilih bank');
                }

                // Prepare request payload
                const requestPayload = {
                    order_id: '{{ $booking->order_id }}',
                    user_name: `{{ $booking->user_name }}`,
                    user_email: '{{ $booking->user_email }}',
                    user_phone: '{{ $booking->user_phone_number }}',
                    amount: parseFloat({{ $booking->grandtotal_price }}),
                    bank: selectedBank.dataset.bankName
                };

                console.log('Sending VA generation request:', requestPayload);

                // Call DOKU VA generation API
                const vaResponse = await fetch('/api/v1/doku/test-generate-va', {
                    method: 'POST',
                    headers: {
                        'X-API-KEY': '{{ env("API_KEY") }}',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestPayload)
                });

                const vaData = await vaResponse.json();

                if (!vaResponse.ok) {
                    // Show detailed validation errors
                    console.error('VA Generation Error:', vaData);
                    let errorMsg = vaData.message || 'Gagal membuat Virtual Account';
                    if (vaData.errors) {
                        errorMsg += '\n\nDetail:\n';
                        for (const [field, messages] of Object.entries(vaData.errors)) {
                            errorMsg += `- ${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}\n`;
                        }
                    }
                    throw new Error(errorMsg);
                }

                if (!vaData.data || !vaData.data.success) {
                    throw new Error(vaData.error || vaData.message || 'Gagal membuat Virtual Account');
                }

                // Update payment method in booking
                const updateResponse = await fetch(paymentForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        payment_method: selectedBank.value,
                        bank: selectedBank.dataset.bankName,
                        virtual_account_no: vaData.data.virtual_account_no,
                        va_data: JSON.stringify(vaData.data)
                    })
                });

                const updateData = await updateResponse.json();

                if (!updateResponse.ok) {
                    throw new Error(updateData.message || 'Gagal memperbarui pembayaran');
                }

                // Show VA information in modal
                showSuccessModal(vaData.data);

            } catch (error) {
                console.error('Error:', error);
                showErrorModal(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
                submitBtn.disabled = false;
                const selectedBank = document.querySelector('input[name="bank_selection"]:checked');
                submitText.textContent = selectedBank ?
                    `Bayar dengan ${selectedBank.dataset.bankName}` : 'Lanjutkan Pembayaran';
                loadingSpinner.classList.add('hidden');
            }
        });

        // Modal functions
        function showSuccessModal(vaData) {
            // Populate modal with VA data
            document.getElementById('modalBank').textContent = vaData.bank;
            document.getElementById('modalVANumber').textContent = vaData.virtual_account_no;
            document.getElementById('modalAmount').textContent = `Rp ${vaData.total_amount.toLocaleString('id-ID')}`;

            // Set how to pay link
            const howToPayLink = document.getElementById('howToPayLink');
            if (vaData.how_to_pay_page) {
                howToPayLink.href = vaData.how_to_pay_page;
                howToPayLink.classList.remove('hidden');
            } else if (vaData.how_to_pay_api) {
                howToPayLink.href = vaData.how_to_pay_api;
                howToPayLink.classList.remove('hidden');
            } else {
                howToPayLink.classList.add('hidden');
            }

            // Show modal
            document.getElementById('successModal').classList.remove('hidden');
        }

        function showErrorModal(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorModal').classList.remove('hidden');
        }

        // Close modal handlers
        document.getElementById('closeModalBtn').addEventListener('click', function() {
            document.getElementById('successModal').classList.add('hidden');
            window.location.href = '{{ route("bookings.index") }}';
        });

        document.getElementById('closeErrorModalBtn').addEventListener('click', function() {
            document.getElementById('errorModal').classList.add('hidden');
        });

        // Close modal when clicking outside
        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                window.location.href = '{{ route("bookings.index") }}';
            }
        });

        document.getElementById('errorModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
</script>
</body>
</html>
