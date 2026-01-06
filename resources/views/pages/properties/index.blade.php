<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms - Ulin Mahoni</title>
    <meta name="description" content="Find available rooms for rent. Browse by daily or monthly rates with real-time availability.">

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
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Kamar Tersedia</h1>
            <p class="text-gray-600">Cari kamar yang ideal untuk Anda</p>

            <!-- Active Filters -->
            @if(request()->anyFilled(['type', 'period', 'check_in', 'check_out']))
                <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                        <span class="font-medium text-gray-700">Filter Aktif:</span>

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
                                {{ ucfirst(request('period')) }}
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
                            <i class="fas fa-times mr-1"></i> Hapus Semua Filter
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
                                <option value="">Semua Tipe</option>
                                <option value="Kos" {{ request('type') == 'Kos' ? 'selected' : '' }}>Kos</option>
                                <option value="Apartment" {{ request('type') == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="Villa" {{ request('type') == 'Villa' ? 'selected' : '' }}>Villa</option>
                                <option value="Hotel" {{ request('type') == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                            </select>
                        </div>
                    </div>

                    <!-- Rent Period -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Periode Sewa</label>
                        <div class="relative">
                            <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="period" class="w-full pl-10 pr-3 h-11 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                                <option value="monthly" {{ request('period') == 'monthly' || !request('period') ? 'selected' : '' }}>Bulanan</option>
                                <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>Harian</option>
                            </select>
                        </div>
                    </div>

                    <!-- Check-in Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Check In</label>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Check Out</label>
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
                        Reset
                    </button>
                    <button
                        type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        Cari Kamar
                    </button>
                </div>
            </form>
        </div>

        <!-- Property and Room Listings -->
        @if($properties->count() > 0)
            <div class="space-y-8">
                @foreach($properties as $property)
                    @php
                        $propertyRoute = match($property->tags) {
                            'Kos' => route('houses.show', $property->idrec),
                            'Apartment' => route('apartments.show', $property->idrec),
                            'Villa' => route('villas.show', $property->idrec),
                            'Hotel' => route('hotels.show', $property->idrec),
                            default => route('properties.show', $property->idrec)
                        };

                        $features = is_string($property->features) ? json_decode($property->features, true) : ($property->features ?? []);
                        $availableRooms = $property->available_rooms ?? collect();

                        // Get property thumbnail
                        $images = $property->images ?? [];
                        $thumbnail = null;
                        if (!empty($images)) {
                            foreach ($images as $image) {
                                if (!empty($image['image'])) {
                                    $thumbnail = $image['image'];
                                    break;
                                }
                            }
                        }
                        if (!$thumbnail) {
                            $thumbnail = $property->image;
                        }
                    @endphp

                    <!-- Property Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Property Header -->
                        <div class="p-5 border-b border-gray-100">
                            <div class="flex items-start justify-between gap-4">
                                <!-- Property Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <a href="{{ $propertyRoute }}" class="text-xl font-bold text-gray-800 hover:text-teal-600 transition-colors">
                                            {{ $property->name }}
                                        </a>
                                        <span class="bg-teal-100 text-teal-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                            {{ $property->tags }}
                                        </span>
                                    </div>

                                    <div class="flex items-center text-gray-600 text-sm mb-2">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <span>{{ $property->address }}</span>
                                    </div>

                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span>
                                            <i class="fas fa-door-open mr-1"></i>
                                            {{ $availableRooms->count() }} kamar tersedia
                                        </span>
                                        @if($property->lowest_price)
                                            <span>
                                                <i class="fas fa-tag mr-1"></i>
                                                Mulai dari <strong class="text-teal-600">Rp {{ number_format($property->lowest_price, 0, ',', '.') }}</strong>/{{ $filters['period'] == 'daily' ? 'hari' : 'bulan' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Property Image (Small) -->
                                @if($thumbnail)
                                    <a href="{{ $propertyRoute }}" class="hidden md:block flex-shrink-0">
                                        <img src="{{ env('ADMIN_URL') }}/storage/{{ $thumbnail }}"
                                            alt="{{ $property->name }}"
                                            class="w-24 h-24 object-cover rounded-lg"
                                            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2YzZjRmNiIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZm9udC1zaXplPSIxMCIgZmlsbD0iIzljYTZhYSI+Properti</dGV4dD48L3N2Zz4=';">
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Available Rooms Grid -->
                        <div class="p-5 bg-gray-50">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                @foreach($availableRooms as $room)
                                    @php
                                        $roomRoute = route('rooms.show', $room->slug ?? $room->idrec);
                                        $roomImages = $room->images ?? [];
                                        $roomThumbnail = null;

                                        if (!empty($roomImages)) {
                                            foreach ($roomImages as $img) {
                                                if (!empty($img['image'])) {
                                                    $roomThumbnail = $img['image'];
                                                    break;
                                                }
                                            }
                                        }
                                        if (!$roomThumbnail) {
                                            $roomThumbnail = $room->image ?? $thumbnail;
                                        }
                                    @endphp

                                    <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                                        <!-- Room Image -->
                                        <a href="{{ $roomRoute }}" class="block overflow-hidden">
                                            <div class="relative aspect-[4/3] bg-gray-100">
                                                @if($roomThumbnail)
                                                    <img src="{{ env('ADMIN_URL') }}/storage/{{ $roomThumbnail }}"
                                                        alt="{{ $room->name }}"
                                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MDAiIGhlaWdodD0iMzAwIj48cmVjdCB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgZmlsbD0iI2YzZjRmNiIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzljYTZhYSI+S2FtYXI8L3RleHQ+PC9zdmc+';">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <i class="fas fa-door-open text-3xl"></i>
                                                    </div>
                                                @endif

                                                @if(request('check_in') && request('check_out'))
                                                    <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium shadow-md">
                                                        <i class="fas fa-check mr-1"></i>Tersedia
                                                    </span>
                                                @endif
                                            </div>
                                        </a>

                                        <!-- Room Info -->
                                        <div class="p-3">
                                            <a href="{{ $roomRoute }}">
                                                <h4 class="font-semibold text-gray-800 mb-1 line-clamp-1 hover:text-teal-600 transition-colors">
                                                    {{ $room->name }}
                                                </h4>
                                            </a>

                                            <!-- Room Specs -->
                                            @if($room->bed_count || $room->room_size)
                                                <div class="flex items-center gap-3 text-xs text-gray-500 mb-2">
                                                    @if($room->bed_count)
                                                        <span><i class="fas fa-bed mr-1"></i>{{ $room->bed_count }}</span>
                                                    @endif
                                                    @if($room->room_size)
                                                        <span><i class="fas fa-expand-arrows-alt mr-1"></i>{{ $room->room_size }}m²</span>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Price -->
                                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                                <div>
                                                    <div class="text-lg font-bold text-teal-600">
                                                        Rp {{ number_format($room->current_price, 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        /{{ $room->current_period == 'daily' ? 'hari' : 'bulan' }}
                                                    </div>
                                                </div>
                                                <a href="{{ $roomRoute }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                                                    Lihat <i class="fas fa-arrow-right text-xs ml-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
                    <h3 class="text-xl font-medium text-gray-700 mb-2">Tidak ada kamar tersedia</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request('check_in') && request('check_out'))
                            Tidak ada kamar tersedia untuk tanggal yang dipilih. Coba ubah tanggal check-in atau check-out.
                        @else
                            Tidak ada kamar yang sesuai dengan kriteria pencarian Anda. Coba ubah filter pencarian.
                        @endif
                    </p>
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Reset Filter
                    </a>
                </div>
            </div>
        @endif
    </main>

    @include('components.homepage.footer')

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-6 right-6 w-12 h-12 bg-teal-600 hover:bg-teal-700 text-white rounded-full shadow-lg flex items-center justify-center opacity-0 invisible transition-all duration-300 z-50">
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
