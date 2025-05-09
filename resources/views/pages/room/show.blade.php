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
                            @if(isset($room['attachment']['images'][0]))
                                <img src="{{ asset($room['attachment']['images'][0]) }}" 
                                     alt="{{ $room['name'] }}"
                                     class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('images/rooms/default-room.jpg') }}" 
                                     alt="{{ $room['name'] }}"
                                     class="w-full h-full object-cover">
                            @endif
                            <span class="absolute top-4 left-4 bg-teal-600 text-white px-3 py-1 rounded-full text-sm">
                                {{ ucfirst($room['type'])  }}
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
                                            <span class="text-gray-600">{{ $facility }}</span>
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
                        
                        <form id="bookingForm" class="space-y-6">
                            <input type="hidden" name="room_id" value="{{ $room['id'] }}">
                            <input type="hidden" name="prices" value='{{ json_encode(["daily" => $room["price"]["discounted"]["daily"], "monthly" => $room["price"]["discounted"]["monthly"]]) }}'>
                            <!-- Price Summary -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                <!-- Daily Rate -->
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-1">
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
                                </div>

                                <!-- Monthly Rate -->
                                <div>
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
                                </div>
                            </div>

                            <!-- Dates -->
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

                            <!-- Number of Guests -->
                            <div>
                                <label for="guests" class="block text-sm font-medium text-gray-700 mb-2">Guests</label>
                                <div class="relative">
                                    <select id="guests" name="guests" required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 appearance-none">
                                        @for ($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                                        @endfor
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div id="guestsError" class="text-red-500 text-xs mt-1 hidden error-message"></div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Special Requests</label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                                    placeholder="Let us know if you have any special requests..."></textarea>
                                <div id="notesError" class="text-red-500 text-xs mt-1 hidden error-message"></div>
                            </div>

                            <!-- Error Alert -->
                            <div id="errorAlert" class="hidden bg-red-50 text-red-800 p-4 rounded-lg text-sm"></div>

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
                                        class="w-full bg-teal-600 text-white py-4 px-6 rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors duration-200 text-lg font-medium"
                                        {{ $room['status'] == 0 ? 'disabled' : '' }}>
                                        {{ $room['status'] == 1 ? 'Book Now' : 'Room Not Available' }}
                                    </button>
                                @endguest
                            </div>

                            <!-- Price Summary -->
                            <div class="bg-gray-50 p-4 rounded-lg mt-6">
                                <h4 class="font-medium text-gray-900 mb-3">Price Summary</h4>
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600" id="rateTypeDisplay">Daily Rate</span>
                                        <div class="text-right">
                                            <div class="text-gray-900" id="rateDisplay">
                                                <div class="flex flex-col items-end">
                                                    <div class="text-sm">
                                                        <span class="line-through text-gray-500">Rp {{ number_format($room['price']['original']['daily'], 0, ',', '.') }}</span>
                                                        <span class="ml-2 text-teal-600">Rp {{ number_format($room['price']['discounted']['daily'], 0, ',', '.') }}</span>
                                                    </div>
                                                    <!-- <div class="text-sm">
                                                        <span class="line-through text-gray-500">Rp {{ number_format($room['price']['original']['monthly'], 0, ',', '.') }}</span>
                                                        <span class="ml-2 text-teal-600">Rp {{ number_format($room['price']['discounted']['monthly'], 0, ',', '.') }}</span>
                                                    </div> -->
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

                            @guest
                                <div class="mt-6">
                                    <a href="{{ route('login') }}" class="block w-full text-center bg-teal-600 hover:bg-teal-700 text-white font-medium py-2.5 px-4 rounded-md transition-colors">
                                        Login to Book
                                    </a>
                                    <p class="text-gray-500 text-sm text-center mt-2">You need to be logged in to book this room</p>
                                </div>
                            @else
                                <button type="submit" 
                                        class="w-full mt-6 py-2.5 px-4 text-sm font-medium text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors {{ $room['status'] == 0 
                                            ? 'bg-gray-400 cursor-not-allowed' 
                                            : 'bg-teal-600 hover:bg-teal-700 focus:ring-teal-500' }}"
                                        {{ $room['status'] == 0 ? 'disabled' : '' }}>
                                    {{ $room['status'] == 0 ? 'Room Already Booked' : 'Proceed to Payment' }}
                                </button>

                                <!-- Error Alert -->
                                <div id="errorAlert" class="hidden mt-4 bg-red-50 text-red-600 p-4 rounded-md text-sm"></div>
                            @endguest
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
            const bookingForm = document.getElementById('bookingForm');
            const errorAlert = document.getElementById('errorAlert');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const submitButton = bookingForm.querySelector('button[type="submit"]');
            const rentTypeSelect = document.getElementById('rentType');
            const checkInInput = document.getElementById('checkIn');
            const checkOutInput = document.getElementById('checkOut');
            const monthsSelect = document.getElementById('months');
            const prices = JSON.parse(document.querySelector('input[name="prices"]').value);

            // Set default check-in date (today + 1 day)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            checkInInput.valueAsDate = tomorrow;

            function updateDateInputs() {
                const rentType = rentTypeSelect.value;
                const isMonthly = rentType === 'monthly';
                const dateInputs = document.getElementById('dateInputs');
                const monthInput = document.getElementById('monthInput');

                if (isMonthly) {
                    dateInputs.classList.add('hidden');
                    monthInput.classList.remove('hidden');
                    // Set check-out date based on selected months
                    const months = parseInt(monthsSelect.value);
                    if (checkInInput.value) {
                        const startDate = new Date(checkInInput.value);
                        const endDate = new Date(startDate);
                        endDate.setMonth(startDate.getMonth() + months);
                        checkOutInput.value = endDate.toISOString().split('T')[0];
                    }
                } else {
                    dateInputs.classList.remove('hidden');
                    monthInput.classList.add('hidden');
                    // For daily rentals, ensure minimum 1-day stay
                    if (checkInInput.value) {
                        const minCheckOut = new Date(checkInInput.value);
                        minCheckOut.setDate(minCheckOut.getDate() + 1);
                        checkOutInput.min = minCheckOut.toISOString().split('T')[0];
                        if (!checkOutInput.value || new Date(checkOutInput.value) <= new Date(checkInInput.value)) {
                            checkOutInput.value = minCheckOut.toISOString().split('T')[0];
                        }
                    }
                }
                calculateTotal();
            }

            function calculateTotal() {
                const rentType = rentTypeSelect.value;
                let duration = 0;
                let total = 0;

                if (rentType === 'monthly') {
                    const months = parseInt(monthsSelect.value) || 0;
                    duration = months * 30; // Convert months to days (30-day intervals)
                    total = prices.monthly * months;
                } else {
                    if (checkInInput.value && checkOutInput.value) {
                        const start = new Date(checkInInput.value);
                        const end = new Date(checkOutInput.value);
                        duration = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                        total = prices.daily * duration;
                    }
                }

                const adminFee = total * 0.1; // 10% admin fee
                const grandTotal = total + adminFee;

                // Update display
                document.getElementById('rateTypeDisplay').textContent = 
                    `${rentType.charAt(0).toUpperCase() + rentType.slice(1)} Rate`;
                document.getElementById('durationDisplay').textContent = 
                    rentType === 'monthly' ? `${duration} days (${monthsSelect.value} month(s))` : `${duration} night(s)`;
                document.getElementById('roomTotal').textContent = 
                    `Rp ${total.toLocaleString('id-ID')}`;
                document.getElementById('adminFee').textContent = 
                    `Rp ${adminFee.toLocaleString('id-ID')}`;
                document.getElementById('grandTotal').textContent = 
                    `Rp ${grandTotal.toLocaleString('id-ID')}`;
            }

            if (bookingForm) {
                rentTypeSelect.addEventListener('change', updateDateInputs);
                monthsSelect.addEventListener('change', updateDateInputs);
                checkInInput.addEventListener('change', updateDateInputs);
                checkOutInput.addEventListener('change', calculateTotal);

                bookingForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    // Reset error states
                    document.querySelectorAll('.error-message').forEach(el => el.classList.add('hidden'));
                    errorAlert.classList.add('hidden');
                    errorAlert.textContent = '';
                    loadingOverlay.classList.remove('hidden');
                    submitButton.disabled = true;
                    
                    try {
                        const formData = new FormData(this);
                        const formObject = Object.fromEntries(formData);
                        
                        // Convert prices back to a JSON string if it's not already
                        if (typeof formObject.prices === 'string' && !formObject.prices.startsWith('{')) {
                            formObject.prices = JSON.stringify(JSON.parse(formObject.prices));
                        }
                        
                        // Add CSRF token to form data
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
                        console.log('Server response:', data);
                        
                        if (data.success && data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else if (data.errors) {
                            console.log('Validation errors:', data.errors);
                            let errorMessages = [];
                            
                            Object.entries(data.errors).forEach(([field, messages]) => {
                                const errorElement = document.getElementById(`${field}Error`);
                                if (errorElement) {
                                    errorElement.textContent = messages[0];
                                    errorElement.classList.remove('hidden');
                                }
                                errorMessages.push(`${field}: ${messages.join(', ')}`);
                            });
                            
                            errorAlert.textContent = errorMessages.join('\n');
                            errorAlert.classList.remove('hidden');
                        } else {
                            console.log('Unexpected response format:', data);
                            errorAlert.textContent = 'Unexpected server response. Please try again.';
                            errorAlert.classList.remove('hidden');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        errorAlert.textContent = 'An error occurred while processing your booking. Please try again.';
                        errorAlert.classList.remove('hidden');
                    } finally {
                        loadingOverlay.classList.add('hidden');
                        submitButton.disabled = false;
                    }
                });

                // Initialize the form
                updateDateInputs();
            }
        });
    </script>


</body>
</html>