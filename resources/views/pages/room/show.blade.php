<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details - {{ $room['name'] ?? 'Deluxe Room 101' }}</title>
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
                    <li class="text-gray-900">{{ $room['name'] ?? 'Deluxe Room 101' }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Room Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Room Image Gallery -->
                        <div class="relative h-96">
                            <img src="{{ asset('images/rooms/deluxe-room.jpg') }}" 
                                 alt="Deluxe Room"
                                 class="w-full h-full object-cover">
                            <span class="absolute top-4 left-4 bg-teal-600 text-white px-3 py-1 rounded-full text-sm">
                                Deluxe
                            </span>
                        </div>

                        <div class="p-6">
                            <!-- Room Title & Price -->
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Deluxe Room 101</h1>
                                    <p class="text-gray-600">Spacious room with city view</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">starts from</p>
                                    <p class="text-2xl font-bold text-gray-900">Rp1.500.000</p>
                                    <p class="text-sm text-gray-500">/night</p>
                                </div>
                            </div>

                            <!-- Quick Info -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-500">Room Size</p>
                                    <p class="font-semibold">32 m²</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Occupancy</p>
                                    <p class="font-semibold">2 Adults</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Bed Type</p>
                                    <p class="font-semibold">1 King Bed</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">View</p>
                                    <p class="font-semibold">City View</p>
                                </div>
                            </div>

                            <!-- Room Facilities -->
                            <div class="mb-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Room Facilities</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-wifi text-teal-600"></i>
                                        <span>Free WiFi</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-snowflake text-teal-600"></i>
                                        <span>Air Conditioning</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-tv text-teal-600"></i>
                                        <span>Smart TV</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-bath text-teal-600"></i>
                                        <span>Private Bathroom</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Room Description -->
                            <div class="mb-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Room Description</h2>
                                <p class="text-gray-600">
                                    Experience luxury and comfort in our spacious Deluxe Room. Perfect for business travelers 
                                    or couples, this room offers stunning city views and modern amenities. The room features 
                                    a comfortable king-size bed, elegant furnishings, and a well-appointed bathroom with 
                                    premium toiletries.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 sticky top-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Book This Room</h2>
                        
                        <form action="{{ route('rooms.book') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                                <input type="date" name="check_in" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                                <input type="date" name="check_out" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Guests</label>
                                <select name="guests" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                    <option value="1">1 Guest</option>
                                    <option value="2">2 Guests</option>
                                </select>
                            </div>

                            <!-- Price Summary -->
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Price per night</span>
                                    <span class="font-semibold">Rp1.500.000</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Service fee</span>
                                    <span class="font-semibold">Rp150.000</span>
                                </div>
                                <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-2 mt-2">
                                    <span>Total</span>
                                    <span>Rp1.650.000</span>
                                </div>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-teal-600 text-white py-3 px-4 rounded-md hover:bg-teal-700 
                                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                Book Now
                            </button>
                        </form>

                        <!-- Booking Policies -->
                        <div class="mt-6 text-sm text-gray-500">
                            <h3 class="font-semibold text-gray-700 mb-2">Booking Policies:</h3>
                            <ul class="space-y-1">
                                <li>• Check-in: 2:00 PM - 11:00 PM</li>
                                <li>• Check-out: 12:00 PM</li>
                                <li>• Free cancellation up to 24 hours before check-in</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('components.homepage.footer')
</body>
</html> 