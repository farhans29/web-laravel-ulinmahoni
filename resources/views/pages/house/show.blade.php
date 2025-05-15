<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $house['name'] }} - Property Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Styles -->
    @include('components.property.styles')
    <style>
    .image-gallery {
            --gap: 1rem;
            --transition: all 0.3s ease;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: var(--transition);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .gallery-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .gallery-item img {
            width: 60%;
            height: 100%;
            object-fit: cover;
            display: block;
            margin: 0 auto;
            transition: var(--transition);
        }

        .gallery-item.main-image {
            height: 400px;
        }

        .gallery-item.main-image img {
            width: 80%;
        }

        .gallery-item.side-image {
            height: 195px;
        }

        .gallery-item.side-image img {
            width: 70%;
        }

        .gallery-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.2) 100%);
            opacity: 0;
            transition: var(--transition);
        }

        .gallery-item:hover::after {
            opacity: 1;
        }

        .type-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 10;
            padding: 0.375rem 0.75rem;
            background-color: rgb(13 148 136);
            color: white;
            border-radius: 9999px;
            font-size: 0.875rem;
            line-height: 1.25rem;
            transform: translateY(-5px);
            opacity: 0;
            transition: var(--transition);
        }

        .gallery-item:hover .type-badge {
            transform: translateY(0);
            opacity: 1;
        }

        .image-count {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            z-index: 10;
            padding: 0.25rem 0.5rem;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            opacity: 0;
            transform: translateY(5px);
            transition: var(--transition);
        }

        .gallery-item:hover .image-count {
            opacity: 1;
            transform: translateY(0);
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
                    <li><a href="{{ route('homepage') }}" class="hover:text-gray-700">Properties</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900">{{ $house['name'] }}</li>
                </ol>
            </nav>

            <!-- Image Gallery Section -->
            <div class="image-gallery grid grid-cols-3 gap-4 mb-8">
                <!-- Main Large Image -->
                <div class="gallery-item main-image col-span-2">
                    <img src="data:image/jpeg;base64,{{ $house['image'] }}" 
                         alt="{{ $house['name'] }}">
                    <span class="type-badge">
                        {{ $house['type'] }}
                    </span>
                    <span class="image-count">
                        <i class="fas fa-camera mr-1"></i> 1/3
                    </span>
                </div>
                <!-- Right Side Smaller Images -->
                <div class="flex flex-col gap-4">
                    <div class="gallery-item side-image">
                        <img src="data:image/jpeg;base64,{{ $house['image_2'] ?? $house['image'] }}"
                             alt="{{ $house['name'] }}">
                        <span class="image-count">
                            <i class="fas fa-camera mr-1"></i> 2/3
                        </span>
                    </div>
                    <div class="gallery-item side-image">
                        <img src="data:image/jpeg;base64,{{ $house['image_3'] ?? $house['image'] }}"
                             alt="{{ $house['name'] }}">
                        <span class="image-count">
                            <i class="fas fa-camera mr-1"></i> 3/3
                        </span>
                    </div>
                </div>
            </div>

            <!-- Property Info Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-8">
                <div class="max-w-3xl mx-auto">
                    <div class="text-center mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $house['name'] }}</h1>
                        <p class="text-gray-600 mb-1">{{ $house['location'] }}</p>
                        <p class="text-gray-500 text-sm">{{ $house['distance'] }}</p>
                    </div>

                    <!-- Price Section -->
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-500 mb-2">
                            mulai dari <span class="line-through">Rp{{ number_format($house['price']['original'], 0, ',', '.') }}</span>
                        </p>
                        <div class="flex items-center justify-center space-x-2">
                            <p class="text-3xl font-bold text-gray-900">
                                Rp{{ number_format($house['price']['discounted'], 0, ',', '.') }}
                            </p>
                            <span class="text-sm text-gray-500">/bulan</span>
                        </div>

                        <!-- Features/Promotions -->
                        <div class="mt-4 flex flex-wrap justify-center gap-2">
                            @foreach($house['features'] as $feature)
                                <span class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-600">
                                    {{ $feature }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-center space-x-4 max-w-md mx-auto">
                        <button onclick="scrollToRooms()" class="flex-1 bg-teal-600 text-white py-3 px-6 rounded-lg hover:bg-teal-700 transition-colors">
                            Book Now
                        </button>
                        <button class="flex-1 border border-gray-300 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-50 transition-colors">
                            Contact Agent
                        </button>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Property Description -->
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">About this property</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-600">
                            {{ $house['description'] }}
                        </p>
                    </div>

                    <!-- Room Facilities -->
                    <div class="mt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Room Facilities</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($house['attributes']['room_facilities'] as $facility)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $facility }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- House Rules -->
                    <div class="mt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">House Rules</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($house['attributes']['rules'] as $rule)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    {{ $rule }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Location Map -->
                    <div class="mt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Location</h3>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                            <div class="aspect-w-16 aspect-h-9">
                                <iframe 
                                    class="w-full h-[400px] rounded-lg"
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.6421430731284!2d106.78854527648083!3d-6.178633360545854!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f6f59fa97dbb%3A0x9cdcd956ab8be06e!2sCentral%20Park%20Mall%2C%20Jl.%20Letjen%20S.%20Parman%20No.RT.12%2C%20RW.1%2C%20Tj.%20Duren%20Sel.%2C%20Kec.%20Grogol%20petamburan%2C%20Kota%20Jakarta%20Barat%2C%20Daerah%20Khusus%20Ibukota%20Jakarta%2011470!5e0!3m2!1sen!2sid!4v1744776680642!5m2!1sen!2sid"
                                    style="border:0"
                                    loading="lazy"
                                    allowfullscreen
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                            <div class="mt-4">
                                <h4 class="font-semibold text-gray-900">Address</h4>
                                <p class="text-gray-600 mt-1">{{ $house['location'] }}</p>
                                <div class="mt-4 flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $house['distance'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rooms Section -->
                    <div id="rooms-section" class="mt-12">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Rooms</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($house['rooms'] as $room)
                                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                                    <div class="relative pb-[56.25%] h-48">
                                        <div class="absolute inset-0">
                                            @if(isset($room['image']) && $room['image'])
                                                <img src="data:image/jpeg;base64,{{ $room['image'] }}" 
                                                    alt="{{ $room['name'] }}" 
                                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                            @else
                                                <div class="bg-gray-100 w-full h-full flex items-center justify-center">
                                                    <i class="fas fa-image text-4xl text-gray-400"></i>
                                                    <span class="ml-2 text-gray-500">No Image</span>
                                                </div>
                                            @endif
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent h-16"></div>
                                        </div>
                                        <span class="absolute top-2 left-2 bg-teal-600 text-white px-2 py-1 rounded-full text-sm">
                                            {{ ucfirst($room['type']) }}
                                        </span>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $room['name'] }}</h3>
                                        <p class="text-gray-600 text-sm mb-4">{{ $room['descriptions'] }}</p>

                                        <div class="mb-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Room Facilities:</h4>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($room['facility'] as $facility => $value)
                                                    @if($value === true)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 uppercase">
                                                            {{ strtoupper($facility) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Rental Periods -->
                                        <div class="mb-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Available Rental Periods:</h4>
                                            <div class="flex flex-wrap gap-2">
                                                @if($room['periode']['daily'])
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                        Daily
                                                    </span>
                                                @endif
                                                @if($room['periode']['monthly'])
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                        Monthly
                                                    </span>
                                                @endif
                                                @if(!$room['periode']['daily'] && !$room['periode']['weekly'] && !$room['periode']['monthly'])
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        No periods available
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $room['status'] === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $room['status'] === 1 ? 'Available' : 'Booked' }}
                                            </span>
                                            
                                            <a href="{{ route('rooms.show', $room['slug']) }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-8">
                                    <p class="text-gray-500">No rooms available for this property at the moment.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Property Amenities</h2>
                    <ul class="space-y-3">
                        @foreach($house['attributes']['amenities'] as $amenity)
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $amenity }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('components.homepage.footer')

    <!-- Include Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @include('components.homepage.scripts')
        });

        function scrollToRooms() {
            const roomsSection = document.getElementById('rooms-section');
            if (roomsSection) {
                roomsSection.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    </script>
</body>
</html> 