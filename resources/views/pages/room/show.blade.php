<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Room Details -->
                <div class="lg:col-span-2">
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

                        <div class="p-6">
                            <!-- Room Title & Property Info -->
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $room['name'] }}</h1>
                                    <p class="text-gray-600">{{ $room['property']['name'] }}</p>
                                    <p class="text-gray-500">{{ $room['property']['location'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Floor Level</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $room['level'] }}</p>
                                </div>
                            </div>

                            <!-- Room Facilities -->
                            @if(!empty($room['facility']))
                            <div class="mb-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Room Facilities</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach($room['facility'] as $facility => $value)
                                        @if($value)
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-check text-teal-600"></i>
                                            <span>{{ ucfirst($facility) }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Room Description -->
                            @if($room['descriptions'])
                            <div class="mb-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Room Description</h2>
                                <p class="text-gray-600">
                                    {{ $room['descriptions'] }}
                                </p>
                            </div>
                            @endif

                            <!-- Additional Information -->
                            {{-- <div class="mt-6 text-sm text-gray-500">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p>Created by: {{ $room['created_by'] }}</p>
                                        <p>Created at: {{ $room['created_at'] }}</p>
                                    </div>
                                    <div>
                                        <p>Updated by: {{ $room['updated_by'] }}</p>
                                        <p>Updated at: {{ $room['updated_at'] }}</p>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 sticky top-8">
                        <!-- Status -->
                        <div class="mb-4">
                            <span class="px-3 py-1 rounded-full text-sm 
                                {{ $room['status'] == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $room['status'] == 1 ? 'Available' : 'Booked' }}
                            </span>
                        </div>

                        <h2 class="text-xl font-bold text-gray-900 mb-4">Book This Room</h2>
                        
                        <form action="{{ route('rooms.book') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room['id'] }}">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                                <input type="date" name="check_in" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                       required
                                       {{ $room['status'] == 0 ? 'disabled' : '' }}>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                                <input type="date" name="check_out" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                       required
                                       {{ $room['status'] == 0 ? 'disabled' : '' }}>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Guests</label>
                                <select name="guests" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        {{ $room['status'] == 0 ? 'disabled' : '' }}>
                                    <option value="1">1 Guest</option>
                                    {{-- <option value="2">2 Guests</option> --}}
                                </select>
                            </div>

                            <button type="submit" 
                                    class="w-full py-3 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $room['status'] == 0 
                                        ? 'bg-gray-400 cursor-not-allowed' 
                                        : 'bg-teal-600 hover:bg-teal-700 focus:ring-teal-500' }}"
                                    {{ $room['status'] == 0 ? 'disabled' : '' }}>
                                {{ $room['status'] == 0 ? 'Room Already Booked' : 'Book Now' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('components.homepage.footer')
</body>
</html> 