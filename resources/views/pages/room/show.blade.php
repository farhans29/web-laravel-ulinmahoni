<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Room Details - {{ $room['name'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Styles -->
    @include('components.property.styles')
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
                        <div class="relative h-96">
                            <div class="absolute inset-0">
                                @if(isset($room['attachment']['images'][0]) && $room['attachment']['images'][0])
                                    <img src="{{ asset($room['attachment']['images'][0]) }}"
                                        alt="{{ $room['name'] }}"
                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                @else
                                    <div class="bg-gray-100 w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-gray-400"></i>
                                        <span class="ml-2 font-medium text-gray-500">No Image</span>
                                    </div>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent h-16"></div>
                            </div>
                            <span class="absolute top-4 left-4 bg-teal-600 text-white px-3 py-1 rounded-full text-sm">
                                {{ ucfirst($room['type']) }}
                            </span>
                        </div>

                        <div class="p-8">
                            <!-- Room Title and Price -->
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $room['name'] }}</h1>
                                    <p class="text-gray-500 uppercase tracking-wide">{{ $room['type'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-teal-600">Rp {{ number_format($room['price']['original']['daily'], 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500">per night</p>
                                </div>
                            </div>

                            <!-- Room Facilities -->
                            @if(!empty($room['facility']))
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Room Facilities</h2>
                                <div class="grid grid-cols-2 gap-6">
                                    @foreach($room['facility'] as $facility => $value)
                                        @if($value)
                                        <div class="flex items-center space-x-3 py-2">
                                            <i class="fas fa-check text-teal-600"></i>
                                            <span class="text-gray-600">{{ strtoupper($facility) }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Room Description -->
                            <div class="space-y-6">
                                <div class="prose prose-lg max-w-none">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">About this room</h3>
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
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Book Your Stay</h2>
                                <p class="text-gray-500">Fill in the details below</p>
                            </div>
                            <span class="px-4 py-2 rounded-full text-sm font-medium
                                {{ $room['status'] == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $room['status'] == 1 ? 'Available' : 'Booked' }}
                            </span>
                        </div>
                        
                        <form id="bookingForm" class="space-y-6" method="POST" action="{{ route('bookings.store') }}">
                            @csrf
                            <input type="hidden" name="property_name" value="{{ $room['property_name'] ?? ($property['name'] ?? $room['name']) }}">
                            <input type="hidden" name="room_name" value="{{ $room['name'] }}">
                            <input type="hidden" name="room_id" value="{{ $room['id'] }}">
                            <input type="hidden" name="prices" value='{{ json_encode(["daily" => $room["price"]["discounted"]["daily"], "monthly" => $room["price"]["discounted"]["monthly"]]) }}'>
                            <!-- Rental Type -->
                            <div class="mb-6">
                                <label for="rent_type" class="block text-sm font-medium text-gray-700 mb-2">Rental Type</label>
                                <select id="rent_type" name="rent_type" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                    onchange="updateRentalType()">
                                    <option value="daily" selected>Daily</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>

                            <!-- Months Selection (Hidden by default) -->
                            <div id="monthInput" class="hidden">
                                <label for="months" class="block text-sm font-medium text-gray-700 mb-2">Number of Months</label>
                                <select id="months" name="booking_months" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                    onchange="updatePriceSummary()">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Month' : 'Months' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Dates (Visible by default) -->
                            <div id="dateInputs">
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Check-in Date -->
                                    <div>
                                        <label for="check_in" class="block text-sm font-medium text-gray-700 mb-2">Check-in</label>
                                        <input type="date" id="check_in" name="check_in" required
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                            min="{{ date('Y-m-d') }}">
                                        <div id="check_inError" class="text-red-500 text-xs mt-1 hidden error-message"></div>
                                    </div>

                                    <!-- Check-out Date -->
                                    <div>
                                        <label for="check_out" class="block text-sm font-medium text-gray-700 mb-2">Check-out</label>
                                        <input type="date" id="check_out" name="check_out" required
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                            min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        <div id="check_outError" class="text-red-500 text-xs mt-1 hidden error-message"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Summary -->
                            <!-- Base Rates Info -->
                            <!-- <div class="bg-gray-50 rounded-lg p-4 mb-6">
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
                            </div> -->

                            <!-- Price Summary -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Price Summary</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600" id="rateTypeDisplay">Daily Rate</span>
                                        <div class="text-right">
                                            <div class="text-gray-900" id="rateDisplay">
                                                <!-- Daily Rate Display -->
                                                <div id="dailyRateDisplay" class="hidden">
                                                    @if($room['price']['discounted']['daily'] < $room['price']['original']['daily'])
                                                        <span class="line-through text-gray-500">Rp {{ number_format($room['price']['original']['daily'], 0, ',', '.') }}</span>
                                                        <span class="ml-2 text-teal-600">Rp {{ number_format($room['price']['discounted']['daily'], 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="text-teal-600">Rp {{ number_format($room['price']['original']['daily'], 0, ',', '.') }}</span>
                                                    @endif
                                                    <div class="text-xs text-gray-500 mt-1">per night</div>
                                                </div>
                                                <!-- Monthly Rate Display -->
                                                <div id="monthlyRateDisplay" class="hidden">
                                                    @if($room['price']['discounted']['monthly'] < $room['price']['original']['monthly'])
                                                        <span class="line-through text-gray-500">Rp {{ number_format($room['price']['original']['monthly'], 0, ',', '.') }}</span>
                                                        <span class="ml-2 text-teal-600">Rp {{ number_format($room['price']['discounted']['monthly'], 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="text-teal-600">Rp {{ number_format($room['price']['original']['monthly'], 0, ',', '.') }}</span>
                                                    @endif
                                                    <div class="text-xs text-gray-500 mt-1">per month</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Duration:</span>
                                        <span class="text-gray-900" id="durationDisplay">-</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Room Total:</span>
                                        <span class="text-gray-900" id="roomTotal">-</span>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Admin Fee (10%):</span>
                                        <span class="text-gray-900" id="adminFee">-</span>
                                    </div>
                                    
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
                                        class="w-full bg-gray-400 text-white py-4 px-6 rounded-lg cursor-not-allowed text-lg font-medium flex items-center justify-center gap-2"
                                        disabled
                                        onclick="window.location.href = '{{ route('login') }}'">
                                        <i class="fas fa-lock"></i>
                                        Login to Book
                                    </button>
                                    <p class="text-sm text-gray-500 text-center mt-2">Please login or register to make a booking</p>
                                @else
                                    <button type="submit" id="submitButton"
                                        class="w-full {{ $room['status'] == 0 ? 'bg-gray-400' : 'bg-teal-600' }} text-white py-4 px-6 rounded-lg {{ $room['status'] == 0 ? '' : 'hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors duration-200' }} text-lg font-medium"
                                        {{ $room['status'] == 0 ? 'disabled' : '' }}>
                                        {{ $room['status'] == 1 ? 'Book Now' : 'Room Not Available' }}
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
        document.addEventListener('DOMContentLoaded', function() {
            // --- Element references ---
            const bookingForm = document.getElementById('bookingForm');
            const errorAlert = document.getElementById('errorAlert');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const submitButton = bookingForm?.querySelector('button[type="submit"]');
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            const monthInput = document.getElementById('monthInput');
            const dateInputs = document.getElementById('dateInputs');
            const monthsSelect = document.getElementById('months');
            const rentTypeSelect = document.querySelector('select[name="rent_type"]');
            const prices = JSON.parse(document.querySelector('input[name="prices"]').value);

            // --- Date defaults ---
            const today = new Date();
            const defaultCheckIn = today.toISOString().split('T')[0];
            const defaultCheckOut = new Date(today);
            defaultCheckOut.setDate(today.getDate() + 3);
            const defaultCheckOutStr = defaultCheckOut.toISOString().split('T')[0];

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
                const rentType = rentTypeSelect.value;
                // Toggle rate display in summary
                document.getElementById('rateTypeDisplay').textContent = rentType === 'monthly' ? 'Monthly Rate' : 'Daily Rate';
                document.getElementById('dailyRateDisplay').classList.toggle('hidden', rentType !== 'daily');
                document.getElementById('monthlyRateDisplay').classList.toggle('hidden', rentType !== 'monthly');

                let duration = 0, rate = 0, roomTotal = 0;
                if (rentType === 'daily') {
                    if (!checkInInput.value || !checkOutInput.value) return resetSummary();
                    const nights = getDaysBetweenDates(checkInInput.value, checkOutInput.value);
                    if (nights <= 0) return resetSummary();
                    duration = nights;
                    rate = prices.daily;
                    roomTotal = duration * rate;
                    document.getElementById('durationDisplay').textContent = `${duration} night(s)`;
                } else {
                    duration = parseInt(monthsSelect.value || '1', 10);
                    rate = prices.monthly;
                    roomTotal = duration * rate;
                    document.getElementById('durationDisplay').textContent = `${duration} month(s)`;
                }
                const adminFee = Math.round(roomTotal * 0.1);
                const grandTotal = roomTotal + adminFee;
                document.getElementById('roomTotal').textContent = formatRupiah(roomTotal);
                document.getElementById('adminFee').textContent = formatRupiah(adminFee);
                document.getElementById('grandTotal').textContent = formatRupiah(grandTotal);
            }

            // --- Rental type toggle logic ---
            function updateRentalType() {
                const isMonthly = rentTypeSelect.value === 'monthly';
                monthInput.classList.toggle('hidden', !isMonthly);
                dateInputs.classList.toggle('hidden', isMonthly);
                // Reset fields appropriately
                if (isMonthly) {
                    checkInInput.value = '';
                    checkOutInput.value = '';
                    monthsSelect.value = '1';
                } else {
                    checkInInput.value = defaultCheckIn;
                    checkOutInput.value = defaultCheckOutStr;
                }
                updatePriceSummary();
            }

            // --- Date input logic ---
            function handleCheckInChange() {
                if (!checkInInput.value) return;
                const minCheckout = new Date(checkInInput.value);
                minCheckout.setDate(minCheckout.getDate() + 1);
                checkOutInput.min = minCheckout.toISOString().split('T')[0];
                if (!checkOutInput.value || new Date(checkOutInput.value) <= new Date(checkInInput.value)) {
                    checkOutInput.value = minCheckout.toISOString().split('T')[0];
                }
                updatePriceSummary();
            }
            function handleCheckOutChange() {
                updatePriceSummary();
            }

            // --- Initialization ---
            function initializeForm() {
                // Set initial values
                checkInInput.value = defaultCheckIn;
                checkInInput.min = defaultCheckIn;
                checkOutInput.value = defaultCheckOutStr;
                const minCheckout = new Date(defaultCheckIn);
                minCheckout.setDate(minCheckout.getDate() + 1);
                checkOutInput.min = minCheckout.toISOString().split('T')[0];
                // Bind events
                rentTypeSelect.addEventListener('change', updateRentalType);
                checkInInput.addEventListener('change', handleCheckInChange);
                checkOutInput.addEventListener('change', handleCheckOutChange);
                monthsSelect.addEventListener('change', updatePriceSummary);
                // Initial state
                updateRentalType();
            }

            // --- Booking form submission ---
            async function handleFormSubmit(e) {
                e.preventDefault();
                document.querySelectorAll('.error-message').forEach(el => el.classList.add('hidden'));
                errorAlert.classList.add('hidden');
                errorAlert.textContent = '';
                loadingOverlay.classList.remove('hidden');
                if (submitButton) submitButton.disabled = true;
                try {
                    const formData = new FormData(bookingForm);
                    const formObject = Object.fromEntries(formData);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch('{{ route("bookings.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formObject),
                        credentials: 'same-origin'
                    });
                    const data = await response.json();
                    if (data.success && data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else if (data.errors) {
                        // Debug: log the full errors object
                        console.error('Booking form errors:', data.errors);
                        Object.entries(data.errors).forEach(([field, messages]) => {
                            const errorElement = document.getElementById(`${field}Error`);
                            if (errorElement) {
                                errorElement.textContent = messages[0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                        // Show all error messages as a readable string
                        const allMessages = Object.entries(data.errors)
                            .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                            .join(' | ');
                        errorAlert.textContent = allMessages || 'Please fix the errors below';
                        errorAlert.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    errorAlert.textContent = 'An error occurred while processing your booking. Please try again.';
                    errorAlert.classList.remove('hidden');
                } finally {
                    loadingOverlay.classList.add('hidden');
                    if (submitButton) submitButton.disabled = false;
                }
            }

            // --- Run initialization ---
            initializeForm();
            if (bookingForm) bookingForm.addEventListener('submit', handleFormSubmit);
        });
    </script>
</body>
</html>