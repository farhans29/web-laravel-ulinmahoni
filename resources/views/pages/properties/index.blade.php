
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Properties - Ulin Mahoni</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @include('components.homepage.styles')
</head>
<body class="bg-gray-50">
    @include('components.homepage.header')

    <main class="container mx-auto px-4 py-8">
        <!-- Filters Section -->
        <div class="mb-8">
            <form action="{{ route('properties.index') }}" method="GET" class="bg-white rounded-lg shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Property Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                        <select name="type" class="w-full border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            <option value="">All Types</option>
                            <option value="House" {{ request('type') == 'House' ? 'selected' : '' }}>House & Room</option>
                            <option value="Apartment" {{ request('type') == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="Villa" {{ request('type') == 'Villa' ? 'selected' : '' }}>Villa</option>
                            <option value="Hotel" {{ request('type') == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search location..." 
                               class="w-full border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <!-- Price Range Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                        <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Minimum price" 
                               class="w-full border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                        <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Maximum price" 
                               class="w-full border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($properties as $property)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <a href="{{ route(match($property->tags) {
                    'House' => 'houses.show',
                    'Apartment' => 'apartments.show', 
                    'Villa' => 'villas.show',
                    'Hotel' => 'hotels.show',
                    default => 'property.show'
                }, $property->idrec) }}" class="block">
                    <!-- Image Section -->
                    <div class="relative pb-[75%]">
                        @if(is_string($property->image))
                            <img src="data:image/jpeg;base64,{{ $property->image }}"
                                 alt="{{ $property->name }}" 
                                 class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <img src="{{ $property->image }}"
                                 alt="{{ $property->name }}" 
                                 class="absolute inset-0 w-full h-full object-cover">
                        @endif
                        <span class="absolute top-2 left-2 bg-primary-600 text-white px-2 py-1 rounded-full text-sm">
                            {{ $property->tags }}
                        </span>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $property->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2">{{ $property->address }}</p>
                        @if($property->distance)
                        <p class="text-gray-500 text-sm mb-3">{{ $property->distance }} km dari {{ $property->location }}</p>
                        @endif

                        <!-- Price Section -->
                        <div class="mt-2">
                            @if(isset($property->price['discounted']) && $property->price['discounted'] > 0)
                                <p class="text-sm text-gray-500">
                                    <span class="line-through">Rp {{ number_format($property->price['original'], 0, ',', '.') }}</span>
                                </p>
                                <div class="flex items-center space-x-1">
                                    <span class="text-lg font-bold text-primary-600">
                                        Rp {{ number_format($property->price['discounted'], 0, ',', '.') }}
                                    </span>
                                    <span class="text-sm text-gray-500">/bulan</span>
                                </div>
                            @else
                                <div class="flex items-center space-x-1">
                                    <span class="text-lg font-bold text-primary-600">
                                        Rp {{ number_format($property->price['original'], 0, ',', '.') }}
                                    </span>
                                    <span class="text-sm text-gray-500">/bulan</span>
                                </div>
                            @endif
                        </div>

                        <!-- Features -->
                        @if(isset($property->features) && !empty($property->features))
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach(is_string($property->features) ? json_decode($property->features, true) : $property->features as $feature)
                                <span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs text-gray-600">
                                    {{ $feature }}
                                </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $properties->links() }}
        </div>
    </main>

    @include('components.homepage.footer')
</body>
<script>
    console.log('Properties:', @json($properties));
</script>
</html> 