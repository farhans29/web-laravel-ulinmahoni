
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
        <!-- <section class="search-section">
            <div class="search-container">
              <div class="search-box">
                <form action="{{ route('properties.search') }}" method="GET" id="propertySearchForm">
                  <div class="flex flex-col md:flex-row gap-4">
                    Property Types
                    <div class="md:w-48 relative">
                      <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                      <select name="property_type" class="w-full pl-10 h-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none bg-white transition-all duration-200">
                        <option value="">Property Types</option>
                        <option value="House" {{ request('property_type') === 'House' ? 'selected' : '' }}>House & Room</option>
                        <option value="Apartment" {{ request('property_type') === 'Apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="Villa" {{ request('property_type') === 'Villa' ? 'selected' : '' }}>Villa</option>
                        <option value="Hotel" {{ request('property_type') === 'Hotel' ? 'selected' : '' }}>Hotel</option>
                      </select>
                      <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    Rent Period
                    <div class="md:w-48 relative">
                      <i class="fas fa-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                      <select name="rent_period" class="w-full pl-10 h-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none bg-white transition-all duration-200">
                        <option value="">Rent Period</option>
                        <option value="daily" {{ request('rent_period') === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="monthly" {{ request('rent_period') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                      </select>
                      <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>

                    Check-in Check-out Dates
                    <div class="flex-1 flex gap-4">
                      <div class="w-1/2 relative">
                        <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="date" 
                          name="check_in"
                          value="{{ request('check_in') }}"
                          min="{{ date('Y-m-d') }}"
                          class="w-full pl-10 h-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                      </div>
                      <div class="w-1/2 relative">
                        <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="date" 
                          name="check_out"
                          value="{{ request('check_out') }}"
                          min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                          class="w-full pl-10 h-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                      </div>
                    </div>
                    
                    <div class="md:w-48">
                      <button type="submit" class="w-full h-12 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>
                        <span>Cari Hunian</span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </section> -->

        <!-- Properties Grid -->
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
                        {{-- 
                        @if(isset($property->price_discounted_monthly) && $property->price_discounted_monthly > 0)
                        <p class="text-sm text-gray-500">
                          <span class="line-through">Rp {{ number_format($property->price_original_monthly, 0, ',', '.') }}</span>
                                </p>
                                <div class="flex items-center space-x-1">
                                  <span class="text-lg font-bold text-primary-600">
                                    Rp {{ number_format($property->price_discounted_monthly, 0, ',', '.') }}
                                  </span>
                                  <span class="text-sm text-gray-500">/bulan</span>
                                </div>
                                @else
                            --}}    
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