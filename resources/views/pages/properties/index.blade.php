<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties for Rent - Ulin Mahoni</title>
    <meta name="description" content="Find your perfect rental property. Browse houses, apartments, villas, and hotels for rent.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.homepage.styles')
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    @include('components.homepage.header')

    <main class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Properti Tersedia</h1>
            <p class="text-gray-600">Cari properti yang ideal untuk Anda</p>
            
            <!-- Active Filters -->
            @if(request()->anyFilled(['type', 'period', 'check_in', 'check_out']))
                <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                        <span class="font-medium text-gray-700">Filter  :</span>
                        
                        @if(request('type'))
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1.5 rounded-full flex items-center">
                                {{ ucfirst(request('type')) }}
                                <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times text-xs"></i>
                                </a>
                            </span>
                        @endif
                        
                        @if(request('period'))
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full flex items-center">
                                {{ ucfirst(request('period')) }} Rent
                                <a href="{{ request()->fullUrlWithQuery(['period' => null]) }}" class="ml-1.5 text-green-600 hover:text-green-800">
                                    <i class="fas fa-times text-xs"></i>
                                </a>
                            </span>
                        @endif
                        
                        @if(request('check_in') && request('check_out'))
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-3 py-1.5 rounded-full flex items-center">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ \Carbon\Carbon::parse(request('check_in'))->format('M d') }} - {{ \Carbon\Carbon::parse(request('check_out'))->format('M d, Y') }}
                                <a href="{{ request()->fullUrlWithQuery(['check_in' => null, 'check_out' => null]) }}" class="ml-1.5 text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-times text-xs"></i>
                                </a>
                            </span>
                        @endif
                        
                        <a href="{{ route('properties.index') }}" class="ml-auto text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                            <i class="fas fa-times mr-1"></i> Hapus Filter
                        </a>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Search and Filters -->
        <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('properties.index') }}" method="GET" class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Property Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Properti</label>
                        <div class="relative">
                            <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="type" class="w-full pl-10 pr-3 h-11 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                                <option value="">Semua Tipe Properti</option>
                                <option value="house" {{ request('type') == 'house' ? 'selected' : '' }}>House & Room</option>
                                <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                <option value="hotel" {{ request('type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Rent Period -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Periode Sewa</label>
                        <div class="relative">
                            <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="period" class="w-full pl-10 pr-3 h-11 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                                <option value="">Semua Periode</option>
                                <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Check-in Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Check In</label>
                        <div class="relative">
                            <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input 
                                type="date" 
                                name="check_in" 
                                value="{{ request('check_in') }}"
                                min="{{ now()->format('Y-m-d') }}"
                                class="w-full pl-10 pr-3 h-11 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm"
                                onchange="document.querySelector('input[name=check_out]').min = this.value">
                        </div>
                    </div>
                    
                    <!-- Check-out Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Check Out</label>
                        <div class="relative">
                            <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input 
                                type="date" 
                                name="check_out" 
                                value="{{ request('check_out') }}"
                                min="{{ request('check_in') ?? now()->format('Y-m-d') }}"
                                class="w-full pl-10 pr-3 h-11 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 flex justify-between items-center">
                    <button 
                        type="button" 
                        onclick="window.location.href='{{ route('properties.index') }}'" 
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        Reset All
                    </button>
                    <button 
                        type="submit" 
                        class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        Cari Properti
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Property Listings -->
        @if($properties->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($properties as $property)
                    @php
                        $route = match($property->tags) {
                            'Kos' => route('houses.show', $property->idrec),
                            'Apartment' => route('apartments.show', $property->idrec),
                            'Villa' => route('villas.show', $property->idrec),
                            'Hotel' => route('hotels.show', $property->idrec),
                            default => route('properties.show', $property->idrec)
                        };
                        
                        $features = is_string($property->features) ? json_decode($property->features, true) : ($property->features ?? []);
                    @endphp
                    
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 border border-gray-100 flex flex-col h-full">
                        <!-- Image Section -->
                        <a href="{{ $route }}" class="block overflow-hidden">
                            <div class="relative aspect-[4/3] bg-gray-100">
                                @if($property->image)
                                    <div class="w-full h-full">
                                        <img 
                                            src="data:image/jpeg;base64,{{ $property->image }}" 
                                            alt="{{ $property->name }}" 
                                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                                            loading="lazy"
                                            style="aspect-ratio: 4/3;">
                                    </div>
                                @else
                                    <div class="w-full h-full">
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100" style="aspect-ratio: 4/3;">
                                            <i class="fas fa-image text-4xl mb-2"></i>
                                            <span class="text-sm">No Image Available</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Property Type Badge -->
                                <span class="absolute top-3 left-3 bg-teal-600 text-white px-3 py-1 rounded-full text-xs font-medium shadow-md">
                                    {{ $property->tags }}
                                </span>
                                
                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                                
                            </div>
                        </a>
                        
                        <!-- Content Section -->
                        <div class="p-4 flex-grow flex flex-col">
                            <a href="{{ $route }}" class="block">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1.5 line-clamp-1 hover:text-teal-600 transition-colors">
                                    {{ $property->name }}
                                </h3>
                                
                                <div class="flex items-center text-gray-500 text-sm mb-2">
                                    <i class="fas fa-map-marker-alt mr-1.5"></i>
                                    <span class="line-clamp-1">{{ $property->address }}</span>
                                </div>
                                
                                @if($property->distance)
                                    <div class="flex items-center text-gray-500 text-xs mb-3">
                                        <i class="fas fa-route mr-1.5"></i>
                                        <span>{{ number_format((float)$property->distance, 1) }} km from {{ $property->location }}</span>
                                    </div>
                                @endif
                                
                                <!-- Price -->
                                <div class="mt-auto pt-3 border-t border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-lg font-bold text-teal-600">
                                                Rp {{ number_format($property->price_original_monthly, 0, ',', '.') }}
                                            </span>
                                            <span class="text-sm text-gray-500">/month</span>
                                        </div>
                                        <a href="{{ $route }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                                            View Details <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Features -->
                            @if(!empty($features))
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(array_slice($features, 0, 4) as $feature)
                                            <span class="inline-block bg-gray-50 text-gray-600 text-xs px-2.5 py-1 rounded-full border border-gray-200">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                        @if(count($features) > 4)
                                            <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-500 text-xs rounded-full">
                                                +{{ count($features) - 4 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($properties->hasPages())
                <div class="mt-10">
                    {{ $properties->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 mx-auto mb-4 text-gray-300">
                        <i class="fas fa-search text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">Properti tidak ditemukan</h3>
                    <p class="text-gray-500 mb-6">Tidak ada properti yang sesuai dengan kriteria pencarian Anda. Coba perbarui filter pencarian Anda.</p>
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Reset Filters
                    </a>
                </div>
            </div>
        @endif
    </main>

    @include('components.homepage.footer')

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-6 right-6 w-12 h-12 bg-teal-600 hover:bg-teal-700 text-white rounded-full shadow-lg flex items-center justify-center opacity-0 invisible transition-all duration-300">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script>
        // Back to Top Button
        const backToTopBtn = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.remove('opacity-0', 'invisible');
                backToTopBtn.classList.add('opacity-100', 'visible');
            } else {
                backToTopBtn.classList.remove('opacity-100', 'visible');
                backToTopBtn.classList.add('opacity-0', 'invisible');
            }
        });
        
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Set minimum checkout date based on check-in date
        const checkInInput = document.querySelector('input[name=check_in]');
        const checkOutInput = document.querySelector('input[name=check_out]');
        
        if (checkInInput && checkOutInput) {
            checkInInput.addEventListener('change', function() {
                if (this.value) {
                    checkOutInput.min = this.value;
                    if (checkOutInput.value && new Date(checkOutInput.value) < new Date(this.value)) {
                        checkOutInput.value = this.value;
                    }
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html> 