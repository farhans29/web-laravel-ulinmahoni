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
                            <input type="hidden" name="voucher_id" id="voucher_id">
                            <input type="hidden" name="voucher_code" id="voucher_code_hidden">
                            <input type="hidden" name="discount_amount" id="discount_amount" value="0">

                            <!-- Voucher Section -->
                            <div class="mb-6">
                                <label class="block text-lg font-medium text-gray-700 mb-2">Kode Voucher (Opsional)</label>
                                <div class="flex gap-2">
                                    <input type="text" id="voucherCodeInput"
                                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        placeholder="Masukkan kode voucher">
                                    <button type="button" id="applyVoucherBtn"
                                        class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 disabled:opacity-50"
                                        disabled>
                                        <span id="voucherBtnText">Terapkan</span>
                                        <span id="voucherBtnLoading" class="hidden">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                    </button>
                                </div>
                                <div id="voucherMessage" class="mt-2 text-sm hidden"></div>
                                <div id="voucherDetails" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg hidden">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-green-800" id="voucherName"></p>
                                            <p class="text-sm text-green-600" id="voucherDescription"></p>
                                            <p class="text-sm font-bold text-green-700 mt-1">Diskon: <span id="voucherDiscount"></span></p>
                                        </div>
                                        <button type="button" id="removeVoucherBtn" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-lg font-medium text-gray-700 mb-4">Metode Pembayaran</label>

                                    <!-- Virtual Account Payment -->
                                    <div class="mb-6">
                                        <h3 class="text-md font-medium text-gray-700 mb-3">Transfer Bank (Virtual Account)</h3>
                                        <p class="text-sm text-gray-600 mb-3">Pilih bank untuk generate Virtual Account</p>
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
                                        <label class="bank-card relative p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-200 hover:border-teal-500 hover:shadow-md bg-white">
                                            <input type="radio" name="payment_selection" value="{{ strtolower($bankCode) }}" class="sr-only"
                                                data-payment-type="bank_transfer"
                                                data-bank="{{ strtolower($bankCode) }}"
                                                data-bank-name="{{ $bankCode }}"
                                                data-bank-logo="{{ $bankLogos[$bankCode] ?? '' }}"
                                                data-dgpc="{{ $bankConfig['dgpc'] }}"
                                                data-channel="{{ $bankConfig['channel'] }}">
                                            <div class="flex items-center">
                                                <div class="w-16 h-16 flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200 mr-4 flex-shrink-0">
                                                    @if(isset($bankLogos[$bankCode]))
                                                        <img src="{{ $bankLogos[$bankCode] }}" alt="{{ $bankCode }}" class="w-12 h-12 object-contain">
                                                    @else
                                                        <div class="flex items-center justify-center">
                                                            <span class="text-xs font-bold text-gray-600">{{ $bankCode }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-bold text-gray-900 text-lg mb-1">{{ $bankCode }}</div>
                                                    <div class="text-sm text-gray-600">Virtual Account</div>
                                                </div>
                                            </div>
                                        </label>
                                        @endforeach
                                        </div>
                                    </div>

                                    <!-- QRIS Payment -->
                                    <div class="mb-6">
                                        <h3 class="text-md font-medium text-gray-700 mb-3">E-Wallet / QRIS</h3>
                                        <div>
                                            <label class="payment-card relative p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-200 hover:border-teal-500 hover:shadow-md bg-white block">
                                                <input type="radio" name="payment_selection" value="qris" class="sr-only" data-payment-type="qris">
                                                <div class="flex items-center">
                                                    <div class="w-16 h-16 flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200 mr-4 flex-shrink-0">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/1200px-QRIS_logo.svg.png" alt="QRIS" class="w-12 h-12 object-contain">
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="font-bold text-gray-900 text-lg mb-1">QRIS</div>
                                                        <div class="text-sm text-gray-600">Scan QR Code</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Credit Card Payment -->
                                    <div class="mb-6">
                                        <h3 class="text-md font-medium text-gray-700 mb-3">Kartu Kredit / Debit</h3>
                                        <div>
                                            <label class="payment-card relative p-6 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-200 hover:border-teal-500 hover:shadow-md bg-white block">
                                                <input type="radio" name="payment_selection" value="credit_card" class="sr-only" data-payment-type="credit_card">
                                                <div class="flex items-center">
                                                    <div class="w-20 h-16 flex items-center justify-center gap-1 bg-gray-50 rounded-lg border border-gray-200 mr-4 flex-shrink-0 px-2">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/1200px-Visa_Inc._logo.svg.png" alt="Visa" class="h-8 w-auto object-contain">
                                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1200px-Mastercard-logo.svg.png" alt="Mastercard" class="h-8 w-auto object-contain">
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="font-bold text-gray-900 text-lg mb-1">Kartu Kredit/Debit</div>
                                                        <div class="text-sm text-gray-600">Visa, Mastercard, JCB, dan kartu lainnya</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
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
                                <h4 class="font-medium mb-2">Pricing</h4>
                                <!-- <p class="text-sm text-gray-600">Harga Harian: {{ number_format($booking->daily_price, 0) }}</p> -->
                                <p class="text-sm text-gray-600">Harga Kamar: Rp <span id="summaryRoomPrice">{{ number_format($booking->room_price, 0) }}</span></p>
                                {{-- <p class="text-sm text-gray-600">Biaya Admin: {{ number_format($booking->admin_fees, 0) }}</p> --}}
                                <p class="text-sm text-gray-600 mt-2 pt-2 border-t">Subtotal: Rp <span id="summarySubtotal">{{ number_format($booking->room_price + ($booking->admin_fees ?? 0), 0) }}</span></p>
                                <p id="summaryDiscountRow" class="text-sm text-green-600 hidden">Diskon Voucher: -Rp <span id="summaryDiscountAmount">0</span></p>
                                <p class="text-sm text-gray-600">Biaya Layanan: +Rp <span id="summaryServiceFee">{{ number_format($booking->service_fees, 0) }}</span></p>
                                <p class="text-sm font-bold text-gray-900 mt-2 pt-2 border-t">Grand Total: Rp <span id="summaryTotal">{{ number_format($booking->grandtotal_price, 0) }}</span></p>
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

        <!-- QRIS Modal -->
        <div id="qrisModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">QRIS Berhasil Dibuat!</h3>
                    <div class="mt-4 px-4 py-3 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-3">Scan QR Code</p>
                            <div id="qrisCodeContainer" class="flex justify-center mb-3">
                                <img id="qrisCodeImage" src="" alt="QRIS Code" class="w-64 h-64 border-2 border-gray-300 rounded">
                            </div>
                            <div class="text-left space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Jumlah:</span>
                                    <span class="text-sm font-medium" id="modalQrisAmount"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Berlaku hingga:</span>
                                    <span class="text-sm font-medium" id="modalQrisExpiry"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button id="closeQrisModalBtn" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credit Card Modal -->
        <div id="ccModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Halaman Pembayaran Siap!</h3>
                    <div class="mt-4 px-4 py-3 bg-gray-50 rounded-lg">
                        <div class="text-left space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Invoice:</span>
                                <span class="text-sm font-medium" id="modalCCInvoice"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Jumlah:</span>
                                <span class="text-sm font-medium" id="modalCCAmount"></span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Klik tombol di bawah untuk melanjutkan ke halaman pembayaran kartu kredit/debit</p>
                    </div>
                    <div class="mt-4 space-y-2">
                        <a id="ccPaymentLink" href="#" target="_blank" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            Lanjut ke Pembayaran
                        </a>
                        <button id="closeCCModalBtn" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
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

        // Voucher state
        let appliedVoucher = null;
        const roomPrice = {{ $booking->room_price }};
        const serviceFee = {{ $booking->service_fees }};
        const adminFees = {{ $booking->admin_fees ?? 0 }};

        // Calculate subtotal before service fee (for voucher calculation)
        // Formula: Subtotal = Room Price + Admin Fees (without service fee)
        const subtotalBeforeServiceFee = roomPrice + adminFees;
        const originalTotal = {{ $booking->grandtotal_price }};

        // Voucher UI elements
        const voucherCodeInput = document.getElementById('voucherCodeInput');
        const applyVoucherBtn = document.getElementById('applyVoucherBtn');
        const voucherMessage = document.getElementById('voucherMessage');
        const voucherDetails = document.getElementById('voucherDetails');
        const removeVoucherBtn = document.getElementById('removeVoucherBtn');

        // Enable voucher button when input has value
        voucherCodeInput.addEventListener('input', function() {
            applyVoucherBtn.disabled = this.value.trim().length === 0;
        });

        // Apply voucher
        applyVoucherBtn.addEventListener('click', async function() {
            const voucherCode = voucherCodeInput.value.trim();
            if (!voucherCode) return;

            // Show loading
            document.getElementById('voucherBtnText').classList.add('hidden');
            document.getElementById('voucherBtnLoading').classList.remove('hidden');
            applyVoucherBtn.disabled = true;

            try {
                const response = await fetch('/api/v1/voucher/validate', {
                    method: 'POST',
                    headers: {
                        'X-API-KEY': '{{ env("API_KEY") }}',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        voucher_code: voucherCode,
                        user_id: {{ auth()->id() }},
                        transaction_amount: subtotalBeforeServiceFee,
                        property_id: {{ $booking->property_id }},
                        room_id: {{ $booking->room_id }}
                    })
                });

                const data = await response.json();

                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Voucher tidak valid');
                }

                // Apply voucher
                appliedVoucher = {
                    code: voucherCode,
                    ...data.data.voucher,
                    calculation: data.data.calculation
                };

                // Update UI
                document.getElementById('voucherName').textContent = appliedVoucher.name;
                document.getElementById('voucherDescription').textContent = appliedVoucher.description || '';
                document.getElementById('voucherDiscount').textContent = `Rp ${appliedVoucher.calculation.discount_amount.toLocaleString('id-ID')}`;

                voucherDetails.classList.remove('hidden');
                voucherCodeInput.disabled = true;
                applyVoucherBtn.classList.add('hidden');

                // Update hidden fields
                document.getElementById('voucher_code_hidden').value = voucherCode;
                document.getElementById('discount_amount').value = appliedVoucher.calculation.discount_amount;

                // Update summary
                updatePriceSummary();

                showVoucherMessage('Voucher berhasil diterapkan!', 'success');

            } catch (error) {
                showVoucherMessage(error.message, 'error');
            } finally {
                document.getElementById('voucherBtnText').classList.remove('hidden');
                document.getElementById('voucherBtnLoading').classList.add('hidden');
                applyVoucherBtn.disabled = false;
            }
        });

        // Remove voucher
        removeVoucherBtn.addEventListener('click', function() {
            appliedVoucher = null;
            voucherCodeInput.value = '';
            voucherCodeInput.disabled = false;
            voucherDetails.classList.add('hidden');
            applyVoucherBtn.classList.remove('hidden');
            voucherMessage.classList.add('hidden');

            // Clear hidden fields
            document.getElementById('voucher_code_hidden').value = '';
            document.getElementById('discount_amount').value = '0';

            // Reset summary
            updatePriceSummary();
        });

        // Update price summary
        // Formula: Grandtotal = Subtotal - Voucher + Service Fee
        function updatePriceSummary() {
            const discountAmount = appliedVoucher ? appliedVoucher.calculation.discount_amount : 0;

            // Calculate final total: Subtotal - Discount + Service Fee
            const subtotalAfterDiscount = subtotalBeforeServiceFee - discountAmount;
            const finalTotal = subtotalAfterDiscount + serviceFee;

            // Calculate final total: Subtotal - Discount + Service Fee
            const subtotalAfterDiscount = subtotalBeforeServiceFee - discountAmount;
            const finalTotal = subtotalAfterDiscount + serviceFee;

            // Update all price elements
            document.getElementById('summarySubtotal').textContent = subtotalBeforeServiceFee.toLocaleString('id-ID');
            document.getElementById('summaryDiscountAmount').textContent = discountAmount.toLocaleString('id-ID');
            document.getElementById('summaryTotal').textContent = finalTotal.toLocaleString('id-ID');

            if (discountAmount > 0) {
                document.getElementById('summaryDiscountRow').classList.remove('hidden');
            } else {
                document.getElementById('summaryDiscountRow').classList.add('hidden');
            }
        }

        // Show voucher message
        function showVoucherMessage(message, type) {
            voucherMessage.textContent = message;
            voucherMessage.classList.remove('hidden', 'text-red-600', 'text-green-600');
            voucherMessage.classList.add(type === 'error' ? 'text-red-600' : 'text-green-600');
        }

        // Handle payment method selection
        document.querySelectorAll('.payment-card, .bank-card').forEach(card => {
            const radio = card.querySelector('input[type="radio"]');

            card.addEventListener('click', () => {
                const paymentType = radio.dataset.paymentType;
                const bankName = radio.dataset.bankName;

                // Update the hidden payment method input
                selectedPaymentMethod = paymentType;
                document.getElementById('payment_method').value = radio.value;

                // Update submit button text
                submitBtn.disabled = false;
                if (paymentType === 'qris') {
                    submitText.textContent = 'Bayar dengan QRIS';
                } else if (paymentType === 'credit_card') {
                    submitText.textContent = 'Bayar dengan Kartu Kredit/Debit';
                } else if (paymentType === 'bank_transfer') {
                    submitText.textContent = `Bayar dengan ${bankName}`;
                    // Store bank info for VA generation
                    let bankInput = paymentForm.querySelector('input[name="bank"]');
                    if (!bankInput) {
                        bankInput = document.createElement('input');
                        bankInput.type = 'hidden';
                        bankInput.name = 'bank';
                        paymentForm.appendChild(bankInput);
                    }
                    bankInput.value = radio.value;
                }

                // Update visual selection
                document.querySelectorAll('.payment-card, .bank-card').forEach(c => {
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
                // Calculate final amount using formula: Subtotal - Voucher + Service Fee
                const discountAmount = appliedVoucher ? appliedVoucher.calculation.discount_amount : 0;
                const finalAmount = subtotalBeforeServiceFee - discountAmount + serviceFee;

                // Base request payload
                const basePayload = {
                    order_id: '{{ $booking->order_id }}',
                    customer_name: `{{ $booking->user_name }}`,
                    customer_email: '{{ $booking->user_email }}',
                    customer_phone: '{{ $booking->user_phone_number }}',
                    amount: parseFloat(finalAmount)
                };

                let paymentData, endpoint, updateParams;

                // Handle different payment types
                if (selectedPaymentMethod === 'qris') {
                    // QRIS Payment
                    console.log('Generating QRIS:', basePayload);
                    endpoint = '/api/v1/doku/test-generate-qris';

                    const qrisResponse = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'X-API-KEY': '{{ env("API_KEY") }}',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(basePayload)
                    });

                    paymentData = await qrisResponse.json();

                    if (!qrisResponse.ok || !paymentData.data?.success) {
                        throw new Error(paymentData.error || paymentData.message || 'Gagal membuat QRIS');
                    }

                    updateParams = {
                        payment_method: 'qris',
                        qr_content: paymentData.data.qr_content,
                        qris_data: JSON.stringify(paymentData.data)
                    };

                    // Add voucher data if applied
                    if (appliedVoucher) {
                        updateParams.voucher_code = appliedVoucher.code;
                        updateParams.discount_amount = appliedVoucher.calculation.discount_amount;
                    }

                    // Update booking
                    await updateBookingPayment(updateParams);

                    // Show QRIS modal
                    showQrisModal(paymentData.data);

                } else if (selectedPaymentMethod === 'credit_card') {
                    // Credit Card Payment
                    console.log('Generating Credit Card Payment Page:', basePayload);
                    endpoint = '/api/v1/doku/test-generate-cc';

                    const ccResponse = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'X-API-KEY': '{{ env("API_KEY") }}',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(basePayload)
                    });

                    paymentData = await ccResponse.json();

                    if (!ccResponse.ok || !paymentData.success) {
                        throw new Error(paymentData.error || paymentData.message || 'Gagal membuat halaman pembayaran');
                    }

                    updateParams = {
                        payment_method: 'credit_card',
                        payment_url: paymentData.payment_url,
                        invoice_number: paymentData.invoice_number,
                        cc_data: JSON.stringify(paymentData)
                    };

                    // Add voucher data if applied
                    if (appliedVoucher) {
                        updateParams.voucher_code = appliedVoucher.code;
                        updateParams.discount_amount = appliedVoucher.calculation.discount_amount;
                    }

                    // Update booking
                    await updateBookingPayment(updateParams);

                    // Show CC modal
                    showCCModal(paymentData);

                } else if (selectedPaymentMethod === 'bank_transfer') {
                    // Virtual Account Payment
                    const selectedBank = document.querySelector('input[name="payment_selection"]:checked');
                    if (!selectedBank || !selectedBank.dataset.bankName) {
                        throw new Error('Silakan pilih bank');
                    }

                    const vaPayload = {
                        ...basePayload,
                        user_name: basePayload.customer_name,
                        user_email: basePayload.customer_email,
                        user_phone: basePayload.customer_phone,
                        bank: selectedBank.dataset.bankName
                    };

                    console.log('Generating VA:', vaPayload);
                    endpoint = '/api/v1/doku/test-generate-va';

                    const vaResponse = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'X-API-KEY': '{{ env("API_KEY") }}',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(vaPayload)
                    });

                    paymentData = await vaResponse.json();

                    if (!vaResponse.ok || !paymentData.data?.success) {
                        throw new Error(paymentData.error || paymentData.message || 'Gagal membuat Virtual Account');
                    }

                    updateParams = {
                        payment_method: selectedBank.value,
                        bank: selectedBank.dataset.bankName,
                        virtual_account_no: paymentData.data.virtual_account_no,
                        va_data: JSON.stringify(paymentData.data)
                    };

                    // Add voucher data if applied
                    if (appliedVoucher) {
                        updateParams.voucher_code = appliedVoucher.code;
                        updateParams.discount_amount = appliedVoucher.calculation.discount_amount;
                    }

                    // Update booking
                    await updateBookingPayment(updateParams);

                    // Show VA modal
                    showSuccessModal(paymentData.data);
                }

            } catch (error) {
                console.error('Error:', error);
                showErrorModal(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
                submitBtn.disabled = false;
                submitText.textContent = 'Lanjutkan Pembayaran';
                loadingSpinner.classList.add('hidden');
            }
        });

        // Helper function to update booking payment
        async function updateBookingPayment(params) {
            const updateResponse = await fetch(paymentForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(params)
            });

            const updateData = await updateResponse.json();

            if (!updateResponse.ok) {
                throw new Error(updateData.message || 'Gagal memperbarui pembayaran');
            }

            return updateData;
        }

        // Modal functions
        function showQrisModal(qrisData) {
            // Generate QR code image URL (assuming qr_content is base64 or URL)
            const qrImage = document.getElementById('qrisCodeImage');

            // If qr_content is a base64 string, use it directly
            if (qrisData.qr_content && qrisData.qr_content.startsWith('data:image')) {
                qrImage.src = qrisData.qr_content;
            } else if (qrisData.qr_content) {
                // Generate QR code using quickchart.io API (reliable and free alternative)
                qrImage.src = `https://quickchart.io/qr?text=${encodeURIComponent(qrisData.qr_content)}&size=300`;
            } else {
                console.error('No QR content available');
            }

            document.getElementById('modalQrisAmount').textContent = `Rp ${parseFloat(qrisData.amount || 0).toLocaleString('id-ID')}`;

            // Calculate expiry time
            const now = new Date();
            const expiryMinutes = qrisData.validity_period || '60M';
            const minutes = parseInt(expiryMinutes.replace('M', ''));
            const expiryTime = new Date(now.getTime() + minutes * 60000);
            document.getElementById('modalQrisExpiry').textContent = expiryTime.toLocaleString('id-ID');

            // Show modal
            document.getElementById('qrisModal').classList.remove('hidden');
        }

        function showCCModal(ccData) {
            document.getElementById('modalCCInvoice').textContent = ccData.invoice_number || ccData.order_id;
            document.getElementById('modalCCAmount').textContent = `Rp ${parseFloat(ccData.amount || 0).toLocaleString('id-ID')}`;
            document.getElementById('ccPaymentLink').href = ccData.payment_url;

            // Show modal
            document.getElementById('ccModal').classList.remove('hidden');
        }

        // Modal functions
        function showSuccessModal(vaData) {
            // Populate modal with VA data
            document.getElementById('modalBank').textContent = vaData.bank;
            document.getElementById('modalVANumber').textContent = vaData.virtual_account_no;

            // Format the amount properly
            const amount = parseFloat(vaData.total_amount || vaData.amount || 0);
            document.getElementById('modalAmount').textContent = `Rp ${amount.toLocaleString('id-ID')}`;

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

        document.getElementById('closeQrisModalBtn').addEventListener('click', function() {
            document.getElementById('qrisModal').classList.add('hidden');
            window.location.href = '{{ route("bookings.index") }}';
        });

        document.getElementById('closeCCModalBtn').addEventListener('click', function() {
            document.getElementById('ccModal').classList.add('hidden');
            window.location.href = '{{ route("bookings.index") }}';
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

        document.getElementById('qrisModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                window.location.href = '{{ route("bookings.index") }}';
            }
        });

        document.getElementById('ccModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                window.location.href = '{{ route("bookings.index") }}';
            }
        });
    });
</script>
</body>
</html>
