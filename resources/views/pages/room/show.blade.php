<!DOCTYPE html>
<html lang="en">
<head>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Room Details - {{ $room['name'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Styles -->
    @include('components.property.styles')
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .fa-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="font-inter antialiased bg-white text-gray-900 tracking-tight">
    <!-- Header -->
    @include('components.homepage.header')

    <main>
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('homepage') }}" class="hover:text-gray-700">Home</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ url()->previous() }}" class="hover:text-gray-700">Property</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900">{{ $room['name'] }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Room Details (left) -->
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Room Image Gallery -->
                        <div class="relative h-96" x-data="{ showModal: false, modalImg: '', modalAlt: '' }">
                            <!-- Modal Popup -->
                            <div x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70" style="display: none;" @click.away="showModal=false" @click.self="showModal=false" @keydown.escape.window="showModal=false">
                                <img :src="modalImg" :alt="modalAlt" class="max-h-[80vh] max-w-[90vw] rounded shadow-lg border-4 border-white object-contain" @click.stop>
                                <button @click="showModal=false" class="absolute top-4 right-6 text-white text-3xl font-bold focus:outline-none">&times;</button>
                            </div>
                            @php
                                $roomImages = $room['images'] ?? [];
                                $mainImage = $room['image'] ?? null;
                                $totalImages = count($roomImages) > 0 ? count($roomImages) : ($mainImage ? 1 : 0);
                            @endphp
                            
                            <!-- Main Image -->
                            <div class="absolute inset-0">
                                @if($mainImage)
                                    <img src="data:image/jpeg;base64,{{ $mainImage }}" 
                                        alt="{{ $room['name'] }}" 
                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105 cursor-pointer"
                                        @click.prevent="showModal=true; modalImg='data:image/jpeg;base64,{{ $mainImage }}'; modalAlt='{{ $room['name'] }}'">
                                @else
                                    <div class="bg-gray-100 w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-gray-400"></i>
                                        <span class="ml-2 font-medium text-gray-500">No Image</span>
                                    </div>
                                @endif
                                
                                <!-- Image Counter -->
                                @if($totalImages > 1)
                                    <div class="absolute bottom-4 right-4">
                                        <span class="bg-black bg-opacity-60 text-white px-3 py-1 rounded-full text-sm">
                                            <i class="fas fa-camera mr-1"></i> {{ $totalImages }}
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent h-16">
                                    <span class="absolute top-4 left-4 bg-teal-600 text-white px-3 py-1 rounded-full text-sm">
                                        {{ ucfirst($room['type']) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Thumbnails (if multiple images) -->
                            @if(count($roomImages) > 1)
                                <div class="absolute bottom-4 left-4 right-4 flex space-x-2 overflow-x-auto pb-2">
                                    @foreach($roomImages as $index => $image)
                                        <div class="flex-shrink-0 w-16 h-16 rounded overflow-hidden border-2 border-white shadow-md">
                                            <img src="data:image/jpeg;base64,{{ $image['image'] }}" 
                                                alt="{{ $room['name'] }} - Image {{ $index + 1 }}" 
                                                class="w-full h-full object-cover cursor-pointer hover:opacity-80"
                                                @click.prevent="showModal=true; modalImg='data:image/jpeg;base64,{{ $image['image'] }}'; modalAlt='{{ $room['name'] }} - Image {{ $index + 1 }}'">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="p-8">
                            <!-- Room Title and Price -->
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $room['name'] }}</h1>
                                    <p class="text-gray-500 uppercase tracking-wide">{{ $room['type'] }}</p>
                                </div>
                                <div class="space-y-4 text-right">
                                @if(!empty($room['price_original_daily']) && $room['price_original_daily'] > 0)
                                <div class="mt-4">
                                    <p class="text-3xl font-bold text-teal-600">Rp {{ number_format($room['price_original_daily'], 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500">per malam</p>
                                </div>
                                @else
                                <div class="mt-4">
                                    <p class="text-3xl font-bold text-teal-600">Hubungi Kami</p>
                                    <p class="text-sm text-gray-500">untuk harga per malam</p>
                                </div>
                                @endif
                                @if(!empty($room['price_original_monthly']) && $room['price_original_monthly'] > 0)
                                <div class="mt-4">
                                    <p class="text-3xl font-bold text-teal-600">Rp {{ number_format($room['price_original_monthly'], 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500">per bulan</p>
                                </div>
                                @else
                                <div class="mt-4">
                                    <p class="text-3xl font-bold text-teal-600">Hubungi Kami</p>
                                    <p class="text-sm text-gray-500">untuk harga per bulan</p>
                                </div>
                                @endif
                            </div>
                            </div>

                            <!-- Room Facilities -->
                            @if(!empty($room['facility']) && is_array($room['facility']) && count($room['facility']) > 0)
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Fasilitas Kamar</h2>
                                <div class="grid grid-cols-2 gap-6">
                                    @foreach($room['facility'] as $facility)
                                        <div class="flex items-center space-x-3 py-2">
                                            <i class="fas fa-check text-teal-600"></i>
                                            <span class="text-gray-600">{{ strtoupper($facility) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Fasilitas Kamar</h2>
                                <p class="text-gray-500">Tidak ada fasilitas yang tercantum</p>
                            </div>
                            @endif

                            <!-- Room Description -->
                            <div class="space-y-6">
                                <div class="prose prose-lg max-w-none">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi Kamar</h3>
                                    <p class="text-gray-600 leading-relaxed">{{ $room['descriptions'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form (right) -->
                <div class="lg:col-span-5 lg:pl-4">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 sticky top-8">
                        <!-- Status and Price Summary -->
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Pesan Kamar</h2>
                                <p class="text-gray-500">Isi detail dibawah ini</p>
                            </div>
                            <span class="px-4 py-2 rounded-full text-sm font-medium
                                {{ $room['status'] == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $room['status'] == 1 ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>
                        
                        <form id="bookingForm" class="space-y-6" method="POST" action="{{ route('bookings.store') }}" novalidate>
                            @csrf
                            <input type="hidden" name="property_name" value="{{ $room['property_name'] ?? ($property['name'] ?? $room['name']) }}">
                            <input type="hidden" name="room_name" value="{{ $room['name'] }}">
                            <input type="hidden" name="room_id" value="{{ $room['id'] }}">
                            <input type="hidden" name="price_daily" id="priceDaily" value="{{ $room['price_original_daily'] }}">
                            <input type="hidden" name="price_monthly" id="priceMonthly" value="{{ $room['price_original_monthly'] }}">
                            <!-- Rental Type -->
                            <div class="mb-6">
                                <label for="rent_type" class="block text-sm font-medium text-gray-700 mb-2">Rental Type</label>
                                <select id="rent_type" name="rent_type" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                    onchange="updateRentalType()">
                                    <option value="daily" selected>Harian</option>
                                    <option value="monthly">Bulanan</option>
                                </select>
                            </div>

                            <!-- Months Selection (Hidden by default) -->
                            <div id="monthInput" class="hidden">
                                <label for="months" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bulan</label>
                                <select id="months" name="months" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                    onchange="updatePriceSummary()">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Bulan' : 'Bulan' }}</option>
                                    @endfor
                                </select>
                                <input type="hidden" name="booking_months" id="bookingMonths" value="1">
                            </div>

                            <!-- Dates (Visible by default) -->
                            <div id="dateInputs">
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Check-in Date -->
                                    <div>
                                        <label for="check_in" class="block text-sm font-medium text-gray-700 mb-2">Check-in</label>
                                        <input type="date" id="check_in" name="check_in" 
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                            min="{{ date('Y-m-d') }}" data-required="true">
                                        <div id="check_inError" class="text-red-500 text-xs mt-1 hidden error-message"></div>
                                    </div>

                                    <!-- Check-out Date -->
                                    <div id="checkOutGroup">
                                        <label for="check_out" class="block text-sm font-medium text-gray-700 mb-2">Check-out</label>
                                        <input type="date" id="check_out" name="check_out" 
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                            min="{{ date('Y-m-d', strtotime('+1 day')) }}" data-required="true">
                                        <div id="check_outError" class="text-red-500 text-xs mt-1 hidden error-message"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Summary -->
                            <!-- Base Rates Info -->
                             {{--  
                             <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                 <h4 class="font-medium text-gray-900 mb-3">Rate Information</h4>
                                 <div class="space-y-4">
                                     
                                    <div id="dailyRateInfo" class="hidden">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Daily Rate</span>
                                            @if($room['price']['discounted']['daily'] < $room['price']['original']['daily'])
                                                <div class="text-right">
                                                    <span class="line-through text-gray-400 text-sm">Rp {{ number_format($room['price']['original']['daily'], 0, ',', '.') }}</span>
                                                    <span class="font-semibold text-teal-600 ml-2">Rp {{ number_format($room['price']['discounted']['daily'], 0, ',', '.') }}</span>
                                                </div>
                                            @else
                                                <span class="font-semibold">Rp {{ number_format($room['price']['original']['daily'], 0, ',', '.') }}</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Rate per night stay</p>
                                        </div>
                                        
                                    
                                    <div id="monthlyRateInfo" class="hidden">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Monthly Rate</span>
                                            @if($room['price']['discounted']['monthly'] < $room['price']['original']['monthly'])
                                                <div class="text-right">
                                                    <span class="line-through text-gray-400 text-sm">Rp {{ number_format($room['price']['original']['monthly'], 0, ',', '.') }}</span>
                                                    <span class="font-semibold text-teal-600 ml-2">Rp {{ number_format($room['price']['discounted']['monthly'], 0, ',', '.') }}</span>
                                                </div>
                                            @else
                                                <span class="font-semibold">Rp {{ number_format($room['price']['original']['monthly'], 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Rate per month stay</p>
                                    </div>
                                </div>
                            </div> 
                            --}}
                            
                            <!-- Price Summary -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Harga Keseluruhan</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600" id="rateTypeDisplay">Daily Rate: </span>
                                        <div class="text-right">
                                            <div class="text-gray-900" id="rateDisplay">
                                                <!-- Daily Rate Display -->
                                                <div id="dailyRateDisplay" class="hidden">
                                                    <span class="text-black-600">Rp {{ number_format($room['price_original_daily'], 0, ',', '.') }}</span>
                                                    <div class="text-xs text-gray-500 mt-1">per malam</div>
                                                </div>
                                                <!-- Monthly Rate Display -->
                                                <div id="monthlyRateDisplay" class="hidden">
                                                    <span class="text-black-600">Rp {{ number_format($room['price_original_monthly'], 0, ',', '.') }}</span>
                                                    <div class="text-xs text-gray-500 mt-1">per bulan</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Durasi: </span>
                                        <span class="text-gray-900" id="durationDisplay">-</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Harga Kamar:</span>
                                        <span class="text-gray-900" id="roomTotal">-</span>
                                    </div>
                                    
                                    {{-- <div class="flex justify-between">
                                        <span class="text-gray-600">Admin Fee:</span>
                                        <span class="text-gray-900" id="adminFee">{{ number_format($room['admin_fees'], 0, ',', '.') }}</span>
                                    </div> --}}
                                    
                                    <div class="flex justify-between font-medium text-lg pt-3 border-t mt-3">
                                        <span>Total:</span>
                                        <span class="text-teal-600" id="grandTotal">-</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Error Alert -->
                            <div id="errorAlert" class="hidden bg-red-50 text-red-800 p-4 rounded-lg text-sm mb-6"></div>

                            <!-- Submit Button -->
                            <div>
                                @guest
                                    <button type="button" 
                                        class="w-full bg-teal-600 text-white py-4 px-6 rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors duration-200 text-lg font-medium flex items-center justify-center gap-2"
                                        onclick="window.location.href = '/login'">
                                        <i class="fas fa-lock"></i>
                                        Masuk untuk memesan
                                    </button>
                                    <p class="text-sm text-gray-500 text-center mt-2">Mohon login atau register untuk membuat pemesanan</p>
                                @else
                                    <button type="button" id="bookNowButton"
                                        class="w-full {{ $room['status'] == 0 ? 'bg-gray-400' : 'bg-teal-600' }} text-white py-4 px-6 rounded-lg {{ $room['status'] == 0 ? '' : 'hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors duration-200' }} text-lg font-medium"
                                        {{ $room['status'] == 0 ? 'disabled' : '' }} data-room-id="{{ $room['id'] }}" data-property-id="{{ $room['property_id'] }}">
                                        {{ $room['status'] == 1 ? 'Pesan Sekarang' : 'Kamar Tidak Tersedia' }}
                                    </button>
                                @endguest
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-teal-500"></div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.homepage.footer')

    <script>
        // --- Element references ---
        let bookingForm, errorAlert, loadingOverlay, submitButton, monthInput, dateInputs, monthsSelect, rentTypeSelect, rulesModal, closeRulesModal, showRulesButton, confirmBooking, cancelBooking, agreeTerms, bookNowButton;
        let checkInInput, checkOutInput;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize element references
            bookingForm = document.getElementById('bookingForm');
            errorAlert = document.getElementById('errorAlert');
            loadingOverlay = document.getElementById('loadingOverlay');
            bookNowButton = document.getElementById('bookNowButton');
            
            // Add click event to bookNowButton
            if (bookNowButton) {
                bookNowButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleFormSubmit(e);
                });
            }
            
            // Initialize other element references
            submitButton = bookingForm?.querySelector('button[type="submit"]');
            checkInInput = document.getElementById('check_in');
            checkOutInput = document.getElementById('check_out');
            monthInput = document.getElementById('monthInput');
            dateInputs = document.getElementById('dateInputs');
            monthsSelect = document.getElementById('months');
            rentTypeSelect = document.querySelector('select[name="rent_type"]');
            
            // Remove duplicate element references that were declared later
            const priceDailyInput = document.getElementById('priceDaily');
            const priceMonthlyInput = document.getElementById('priceMonthly');

            // --- Utility functions ---
            function getDaysBetweenDates(start, end) {
                const d1 = new Date(start); d1.setHours(0,0,0,0);
                const d2 = new Date(end); d2.setHours(0,0,0,0);
                return Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));
            }
            function formatRupiah(num) {
                return `Rp ${num.toLocaleString('id-ID')}`;
            }
            function resetSummary() {
                document.getElementById('durationDisplay').textContent = '-';
                document.getElementById('roomTotal').textContent = '-';
                document.getElementById('adminFee').textContent = '-';
                document.getElementById('grandTotal').textContent = '-';
            }

            // --- Price summary logic ---
            function updatePriceSummary() {
                // Get all required elements
                const rentTypeSelect = document.getElementById('rent_type');
                const checkInInput = document.getElementById('check_in');
                const checkOutInput = document.getElementById('check_out');
                const monthsSelect = document.getElementById('months');
                const priceDailyInput = document.getElementById('priceDaily');
                const priceMonthlyInput = document.getElementById('priceMonthly');
                
                // Check if all required elements exist
                if (!rentTypeSelect || !checkInInput || !checkOutInput || !monthsSelect || !priceDailyInput || !priceMonthlyInput) {
                    console.error('Required form elements not found');
                    return;
                }
                
                const rentType = rentTypeSelect.value;
                const rateTypeDisplay = document.getElementById('rateTypeDisplay');
                const dailyRateDisplay = document.getElementById('dailyRateDisplay');
                const monthlyRateDisplay = document.getElementById('monthlyRateDisplay');
                const durationDisplay = document.getElementById('durationDisplay');
                
                // Toggle rate display in summary
                if (rateTypeDisplay) rateTypeDisplay.textContent = rentType === 'monthly' ? 'Harga Bulanan' : 'Harga Harian';
                if (dailyRateDisplay) dailyRateDisplay.classList.toggle('hidden', rentType !== 'daily');
                if (monthlyRateDisplay) monthlyRateDisplay.classList.toggle('hidden', rentType !== 'monthly');

                let duration = 0, rate = 0, roomTotal = 0;
                
                try {
                    if (rentType === 'daily') {
                        if (!checkInInput.value || !checkOutInput.value) return resetSummary();
                        const nights = getDaysBetweenDates(checkInInput.value, checkOutInput.value);
                        if (nights <= 0) return resetSummary();
                        duration = nights;
                        rate = parseFloat(priceDailyInput.value) || 0;
                        roomTotal = duration * rate;
                        if (durationDisplay) durationDisplay.textContent = `${duration} malam`;
                    } else {
                        duration = parseInt(monthsSelect.value || '1', 10);
                        rate = parseFloat(priceMonthlyInput.value) || 0;
                        roomTotal = duration * rate;
                        if (durationDisplay) durationDisplay.textContent = `${duration} bulan`;
                    }
                } catch (error) {
                    console.error('Error updating price summary:', error);
                    return resetSummary();
                }
                // Get admin fee value from the hidden input or use the default
                const adminFee = 0;
                const grandTotal = roomTotal ;
                document.getElementById('roomTotal').textContent = formatRupiah(roomTotal);
                // document.getElementById('adminFee').textContent = formatRupiah(adminFee);
                document.getElementById('grandTotal').textContent = formatRupiah(grandTotal);
            }

            // --- Rental type toggle logic ---
            function updateRentalType() {
                const rentTypeSelect = document.getElementById('rent_type');
                const monthInput = document.getElementById('monthInput');
                const dateInputs = document.getElementById('dateInputs');
                const checkInInput = document.getElementById('check_in');
                const checkOutInput = document.getElementById('check_out');
                const monthsSelect = document.getElementById('months');
                const dailyRateDisplay = document.getElementById('dailyRateDisplay');
                const monthlyRateDisplay = document.getElementById('monthlyRateDisplay');
                const rateTypeDisplay = document.getElementById('rateTypeDisplay');
                const savedSearch = localStorage.getItem('propertySearch');
                const searchState = savedSearch ? JSON.parse(savedSearch) : null;

                if (rentTypeSelect.value === 'monthly') {
                    monthInput.classList.remove('hidden');
                    // Show only check-in date for monthly rentals
                    dateInputs.classList.remove('hidden');
                    const checkOutGroup = document.getElementById('checkOutGroup');
                    if (checkOutGroup) checkOutGroup.classList.add('hidden');
                    dailyRateDisplay.classList.add('hidden');
                    monthlyRateDisplay.classList.remove('hidden');
                    rateTypeDisplay.textContent = 'Harga Bulanan';
                    
                    // Set months from saved search or default to 1
                    if (monthsSelect) {
                        monthsSelect.value = searchState?.period === 'monthly' && searchState?.months 
                            ? searchState.months 
                            : 1;
                        document.getElementById('bookingMonths').value = monthsSelect.value;
                    }
                } else {
                    monthInput.classList.add('hidden');
                    dateInputs.classList.remove('hidden');
                    dailyRateDisplay.classList.remove('hidden');
                    monthlyRateDisplay.classList.add('hidden');
                    rateTypeDisplay.textContent = 'Harga Harian';
                    
                    // Set dates from saved search or use defaults
                    const today = new Date();
                    const tomorrow = new Date(today);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    
                    if (checkInInput) {
                        checkInInput.value = searchState?.check_in || today.toISOString().split('T')[0];
                        checkInInput.dispatchEvent(new Event('change'));
                    }
                    
                    if (checkOutInput) {
                        checkOutInput.value = searchState?.check_out || tomorrow.toISOString().split('T')[0];
                        checkOutInput.dispatchEvent(new Event('change'));
                    }
                }
                updatePriceSummary();
            }

            // --- Date input logic ---
            function handleCheckInChange() {
                const rentTypeSelect = document.getElementById('rent_type');
                const monthsSelect = document.getElementById('months');
                if (!checkInInput.value) return;
                if (rentTypeSelect.value === 'monthly') {
                    // For monthly, update check-out based on months
                    const checkInDate = new Date(checkInInput.value);
                    const months = parseInt(monthsSelect.value, 10) || 1;
                    const checkOutDate = new Date(checkInDate);
                    checkOutDate.setMonth(checkOutDate.getMonth() + months);
                    checkOutInput.value = checkOutDate.toISOString().split('T')[0];
                } else {
                    // For daily, normal logic
                    const minCheckout = new Date(checkInInput.value);
                    minCheckout.setDate(minCheckout.getDate() + 1);
                    checkOutInput.min = minCheckout.toISOString().split('T')[0];
                    if (!checkOutInput.value || new Date(checkOutInput.value) <= new Date(checkInInput.value)) {
                        checkOutInput.value = minCheckout.toISOString().split('T')[0];
                    }
                }
                updatePriceSummary();
            }
            function handleCheckOutChange() {
                updatePriceSummary();
            }

            // --- Initialization ---
            function initializeForm() {
                // Get saved search state
                const savedSearch = localStorage.getItem('propertySearch');
                const searchState = savedSearch ? JSON.parse(savedSearch) : null;
                
                // Get current date for default values
                const today = new Date();
                const defaultCheckIn = searchState?.check_in || today.toISOString().split('T')[0];
                const defaultCheckOut = searchState?.check_out || new Date(today);
                
                // Set rent type from saved search if available
                if (searchState?.period) {
                    const rentTypeSelect = document.getElementById('rent_type');
                    if (rentTypeSelect) {
                        rentTypeSelect.value = searchState.period;
                        updateRentalType(); // Update UI based on rent type
                    }
                }
                defaultCheckOut.setDate(today.getDate() + 1);
                const defaultCheckOutStr = defaultCheckOut.toISOString().split('T')[0];
                
                // Set initial values
                const checkInInput = document.getElementById('check_in');
                const checkOutInput = document.getElementById('check_out');
                const rentTypeSelect = document.getElementById('rent_type');
                const monthsSelect = document.getElementById('months');
                
                if (checkInInput) {
                    checkInInput.value = defaultCheckIn;
                    checkInInput.min = defaultCheckIn;
                    
                    // Handle check-in date changes
                    checkInInput.addEventListener('change', function() {
                        if (checkInInput.value) {
                            const minCheckout = new Date(checkInInput.value);
                            minCheckout.setDate(minCheckout.getDate() + 1);
                            
                            if (checkOutInput) {
                                checkOutInput.min = formatDate(minCheckout);
                                // If current check-out is before new min date, update it
                                if (!checkOutInput.value || new Date(checkOutInput.value) <= new Date(checkInInput.value)) {
                                    checkOutInput.value = formatDate(minCheckout);
                                }
                            }
                        }
                        updatePriceSummary();
                    });
                }
                
                if (checkOutInput) {
                    checkOutInput.value = defaultCheckOutStr;
                    checkOutInput.min = defaultCheckOutStr;
                    
                    // Handle check-out date changes
                    checkOutInput.addEventListener('change', updatePriceSummary);
                }
                
                // Initialize other form elements
                if (rentTypeSelect) {
                    rentTypeSelect.addEventListener('change', updateRentalType);
                }
                
                if (monthsSelect) {
                    monthsSelect.addEventListener('change', updatePriceSummary);
                }
                
                // Initialize form state
                updateRentalType();
                updatePriceSummary();
                
                // Add form submission handler
                if (bookingForm) {
                    bookingForm.addEventListener('submit', handleFormSubmit);
                }
            }

            // --- Form validation ---
            function validateForm() {
                let isValid = true;
                const rentType = document.getElementById('rent_type').value;
                
                // Reset all error states
                document.querySelectorAll('.error-message').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('input, select').forEach(input => {
                    input.classList.remove('border-red-500');
                });
                
                // Only validate date fields for daily rental
                if (rentType === 'daily') {
                    const checkInInput = document.getElementById('check_in');
                    const checkOutInput = document.getElementById('check_out');
                    
                    if (!checkInInput.value) {
                        document.getElementById('check_inError').textContent = 'Check-in date is required';
                        document.getElementById('check_inError').classList.remove('hidden');
                        checkInInput.classList.add('border-red-500');
                        isValid = false;
                    }
                    
                    if (!checkOutInput.value) {
                        document.getElementById('check_outError').textContent = 'Check-out date is required';
                        document.getElementById('check_outError').classList.remove('hidden');
                        checkOutInput.classList.add('border-red-500');
                        isValid = false;
                    } else if (checkInInput.value && new Date(checkOutInput.value) <= new Date(checkInInput.value)) {
                        document.getElementById('check_outError').textContent = 'Check-out date must be after check-in date';
                        document.getElementById('check_outError').classList.remove('hidden');
                        checkOutInput.classList.add('border-red-500');
                        isValid = false;
                    }
                }
                
                return isValid;
            }

            // --- Booking form submission ---
            async function handleFormSubmit(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    return;
                }

                // Show loading state
                loadingOverlay.classList.remove('hidden');
                if (submitButton) submitButton.disabled = true;
                if (bookNowButton) bookNowButton.disabled = true;
                
                try {
                    const formData = new FormData(bookingForm);
                    const rentType = document.getElementById('rent_type').value;
                    
                    // Set booking_months for monthly rentals
                    if (rentType === 'monthly') {
                        const months = document.getElementById('months').value;
                        formData.set('booking_months', months);
                    }
                    
                    // Add property_id to the form data if not already present
                    if (!formData.has('property_id') && bookNowButton) {
                        formData.set('property_id', bookNowButton.dataset.propertyId);
                    }
                    
                    const formObject = Object.fromEntries(formData);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    const response = await fetch('{{ route('api.booking.check-availability') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formObject)
                    });

                    const data = await response.json();
                    console.log('API Response:', data); // Log the complete response for debugging
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to check room availability');
                    }

                    // Handle response based on availability
                    if (data.status === 'success') {
                        if (data.data.is_available) {
                        // Build booking URL with all necessary parameters
                        const checkIn = formData.get('check_in');
                        const checkOut = formData.get('check_out');
                        const roomId = formData.get('room_id');
                        const propertyId = formData.get('property_id');
                        const bookingMonths = formData.get('booking_months') || '';
                        
                        const bookingUrl = new URL('{{ route("bookings.store") }}');
                        const bookingParams = new URLSearchParams();
                        
                        bookingParams.append('room_id', roomId);
                        bookingParams.append('property_id', propertyId);
                        bookingParams.append('check_in', checkIn);
                        bookingParams.append('check_out', checkOut);
                        bookingParams.append('rent_type', rentType);
                        
                        if (rentType === 'monthly' && bookingMonths) {
                            bookingParams.append('booking_months', bookingMonths);
                        }
                        
                        // Append all parameters to the URL
                        window.location.href = `${bookingUrl}?${bookingParams.toString()}`;
                            return;
                        } else {
                            // Room is not available
                            let errorMessage = 'Maaf, kamar tidak tersedia untuk tanggal yang dipilih.';
                            if (data.data.conflicting_bookings && data.data.conflicting_bookings.length > 0) {
                                errorMessage += ' Sudah ada pemesanan untuk tanggal tersebut.';
                                console.log('Conflicting bookings:', data.data.conflicting_bookings);
                            }
                            errorAlert.textContent = errorMessage;
                            errorAlert.classList.remove('hidden');
                        }
                        
                        // Log conflicting bookings if available
                        if (data.data && data.data.conflicting_bookings) {
                            console.log('Conflicting bookings:', data.data.conflicting_bookings);
                        }
                    } else {
                        throw new Error('Invalid response from server');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    errorAlert.textContent = error.message || 'Terjadi kesalahan saat memeriksa ketersediaan kamar. Silakan coba lagi.';
                    errorAlert.classList.remove('hidden');
                } finally {
                    loadingOverlay.classList.add('hidden');
                    if (submitButton) submitButton.disabled = false;
                    if (bookNowButton) bookNowButton.disabled = false;
                }
            }

            // Modal functionality
            if (showRulesButton) {
                showRulesButton.addEventListener('click', function() {
                    if (validateForm()) {
                        rulesModal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    }
                });
            }

            if (closeRulesModal) {
                closeRulesModal.addEventListener('click', function() {
                    rulesModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            }

            if (cancelBooking) {
                cancelBooking.addEventListener('click', function() {
                    rulesModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            }

            if (agreeTerms && confirmBooking) {
                agreeTerms.addEventListener('change', function() {
                    const isChecked = this.checked;
                    confirmBooking.disabled = !isChecked;
                    confirmBooking.classList.toggle('bg-teal-600', isChecked);
                    confirmBooking.classList.toggle('bg-gray-400', !isChecked);
                });
            }

            if (confirmBooking) {
                confirmBooking.addEventListener('click', function() {
                    if (agreeTerms.checked) {
                        rulesModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        // Trigger form submission
                        bookingForm.requestSubmit();
                    }
                });
            }

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === rulesModal) {
                    rulesModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });

            // Set default dates
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            // Format date as YYYY-MM-DD
            const formatDate = (date) => date.toISOString().split('T')[0];
            
            // Set initial values
            if (checkInInput) {
                checkInInput.value = formatDate(today);
                checkInInput.min = formatDate(today);
                
                // Handle check-in date changes
                checkInInput.addEventListener('change', function() {
                    if (checkInInput.value) {
                        const minCheckout = new Date(checkInInput.value);
                        minCheckout.setDate(minCheckout.getDate() + 1);
                        
                        if (checkOutInput) {
                            checkOutInput.min = formatDate(minCheckout);
                            // If current check-out is before new min date, update it
                            if (!checkOutInput.value || new Date(checkOutInput.value) <= new Date(checkInInput.value)) {
                                checkOutInput.value = formatDate(minCheckout);
                            }
                        }
                    }
                    updatePriceSummary();
                });
            }
            
            if (checkOutInput) {
                checkOutInput.value = formatDate(tomorrow);
                checkOutInput.min = formatDate(tomorrow);
                
                // Handle check-out date changes
                checkOutInput.addEventListener('change', updatePriceSummary);
            }
            
            // Initialize other form elements
            if (rentTypeSelect) {
                rentTypeSelect.addEventListener('change', updateRentalType);
            }
            
            if (monthsSelect) {
                monthsSelect.addEventListener('change', updatePriceSummary);
            }
            
            // Initialize form state
            updateRentalType();
            updatePriceSummary();
            
            // Add form submission handler
            if (bookingForm) {
                bookingForm.addEventListener('submit', handleFormSubmit);
            }
        });
    </script>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookButton = document.getElementById('bookNowButton');
            
            if (bookButton) {
                bookButton.addEventListener('click', function() {
                    const roomId = this.dataset.roomId;
                    const propertyId = this.dataset.propertyId;
                    
                    // Get check-in and check-out dates from your date picker
                    // Replace these with your actual date picker selectors
                    const checkIn = document.querySelector('input[name="check_in"]')?.value;
                    const checkOut = document.querySelector('input[name="check_out"]')?.value;
                    
                    if (!checkIn || !checkOut) {
                        alert('Silakan pilih tanggal check-in dan check-out terlebih dahulu');
                        return;
                    }
                    
                    // Show loading state
                    const originalText = bookButton.innerHTML;
                    bookButton.disabled = true;
                    bookButton.innerHTML = 'Memeriksa ketersediaan...';
                    
                    // Make API call to check availability
                    fetch('{{ route('api.booking.check-availability') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            property_id: propertyId,
                            room_id: roomId,
                            check_in: checkIn,
                            check_out: checkOut
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                console.error('API Error:', err);
                                throw new Error(err.message || 'Failed to check availability');
                            }).catch(() => {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('API Response:', data);
                        if (data.status === 'success' && data.data.is_available) {
                            // If available, redirect to booking page or show booking form
                            const rentType = document.getElementById('rent_type').value;
                            let url = '{{ route("bookings.store") }}';
                            const params = new URLSearchParams({
                                property_id: propertyId,
                                room_id: roomId,
                                check_in: checkIn,
                                check_out: checkOut,
                                rent_type: rentType
                            });

                            if (rentType === 'monthly') {
                                const months = document.getElementById('months').value;
                                params.append('booking_months', months);
                            }

                            window.location.href = `${url}?${params.toString()}`;
                        } else {
                            const errorMessage = data.data.message || 'Maaf, kamar tidak tersedia untuk tanggal yang dipilih';
                            alert(errorMessage);
                            
                            // Show conflicting bookings if any
                            if (data.data.conflicting_bookings && data.data.conflicting_bookings.length > 0) {
                                console.log('Conflicting bookings:', data.data.conflicting_bookings);
                                // You can show this in a more user-friendly way if needed
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan: ' + (error.message || 'Gagal memeriksa ketersediaan kamar'));
                    })
                    .finally(() => {
                        bookButton.disabled = false;
                        bookButton.innerHTML = originalText;
                    });
                });
            }
        });
    </script>
    @endpush
</body>
</html>