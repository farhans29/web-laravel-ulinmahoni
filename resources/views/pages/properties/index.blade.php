
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
        <!-- Search Results Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Properties for Rent</h1>
            
            @if(request()->anyFilled(['type', 'period', 'check_in', 'check_out']))
                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 mb-4">
                    <span>Search Results for:</span>
                    
                    @if(request('type'))
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                            {{ ucfirst(request('type')) }}
                            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('period'))
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                            {{ ucfirst(request('period')) }} Rent
                            <a href="{{ request()->fullUrlWithQuery(['period' => null]) }}" class="ml-1.5 text-green-600 hover:text-green-800">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('check_in') && request('check_out'))
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                            {{ \Carbon\Carbon::parse(request('check_in'))->format('M d') }} - {{ \Carbon\Carbon::parse(request('check_out'))->format('M d, Y') }}
                            <a href="{{ request()->fullUrlWithQuery(['check_in' => null, 'check_out' => null]) }}" class="ml-1.5 text-purple-600 hover:text-purple-800">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    
                    <a href="{{ route('properties.index') }}" class="text-blue-600 hover:text-blue-800 text-sm ml-2">
                        <i class="fas fa-times mr-1"></i> Clear all filters
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Active Filters -->
        <div class="mb-6">
            <form action="{{ route('properties.index') }}" method="GET" class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Property Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                        <div class="relative">
                            <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="type" class="w-full pl-10 h-10 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none bg-white transition-all duration-200 text-sm">
                                <option value="">All Types</option>
                                <option value="house" {{ request('type') == 'house' ? 'selected' : '' }}>House & Room</option>
                                <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                <option value="hotel" {{ request('type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Rent Period Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rent Period</label>
                        <div class="relative">
                            <i class="fas fa-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="period" class="w-full pl-10 h-10 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none bg-white transition-all duration-200 text-sm">
                                <option value="">Any Period</option>
                                <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Check-in Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                        <div class="relative">
                            <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="date" 
                                name="check_in" 
                                value="{{ request('check_in') }}"
                                class="w-full pl-10 h-10 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                        </div>
                    </div>
                    
                    <!-- Check-out Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                        <div class="relative">
                            <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="date" 
                                name="check_out" 
                                value="{{ request('check_out') }}"
                                class="w-full pl-10 h-10 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Property Listings -->
        @if($properties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($properties as $property)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <a href="{{ route(match($property->tags) {
                            'House' => 'houses.show',
                            'Apartment' => 'apartments.show', 
                            'Villa' => 'villas.show',
                            'Hotel' => 'hotels.show',
                            default => 'properties.show'
                        }, $property->idrec) }}" class="block">
                            <!-- Image Section -->
                            <div class="relative pb-[56.25%] h-48">
                                <div class="absolute inset-0">
                                    @if($property->image)
                                        <img src="data:image/jpeg;base64,{{ $property->image }}" 
                                             alt="{{ $property->name }}" 
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
                                    <div class="flex items-center space-x-1">
                                        <span class="text-lg font-bold text-teal-600">
                                            Rp {{ number_format($property->price_original_monthly, 0, ',', '.') }}
                                        </span>
                                        <span class="text-sm text-gray-500">/bulan</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                                <div class="flex items-center space-x-1">
                                    <span class="text-lg font-bold text-primary-600">
                                        Rp {{ number_format($property->price_original_monthly, 0, ',', '.') }}
                                    </span>
                                    <span class="text-sm text-gray-500">/bulan</span>
                                </div>
                          {{-- 
                            @endif
                          --}}
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