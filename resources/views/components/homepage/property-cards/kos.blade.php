<!-- Swiper container -->
<div class="swiper property-swiper">
    <div class="swiper-wrapper mb-8">
        @forelse($kos as $kosan)
        <div class="swiper-slide">
            <a href="{{ route('houses.show', ['id' => $kosan['id']]) }}" class="block h-full group">
                <div class="property-card bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 h-full flex flex-col">
                    <!-- Image Container -->
                    <div class="relative overflow-hidden">
                        <div class="relative aspect-[4/3] bg-gray-100">
                            @php
                                $mainImage = $kosan['images'][0]['image'] ?? $kosan['image'] ?? null;
                            @endphp

                            @if($mainImage)
                                <img src="{{ env('ADMIN_URL') }}/storage/{{ $mainImage }}"
                                     alt="{{ $kosan['name'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-400\'><i class=\'fas fa-building text-4xl\'></i></div>';">

                                @if(count($kosan['images'] ?? []) > 1)
                                    <div class="absolute top-3 right-3 bg-black/60 text-white text-xs px-2.5 py-1.5 rounded-full font-medium shadow-md backdrop-blur-sm">
                                        <i class="fas fa-images mr-1"></i>{{ count($kosan['images']) }}
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-building text-4xl mb-2"></i>
                                    <span class="text-sm">No Image</span>
                                </div>
                            @endif

                            <!-- Type Badge -->
                            <span class="absolute top-3 left-3 bg-teal-100 text-teal-800 px-2.5 py-1 rounded-full text-xs font-medium shadow-md">
                                {{ $kosan['type'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4 flex-1 flex flex-col">
                        <!-- Property Name -->
                        <h3 class="text-base font-bold text-gray-800 mb-1.5 line-clamp-1 group-hover:text-teal-600 transition-colors">
                            {{ $kosan['name'] }}
                        </h3>

                        <!-- Location -->
                        <div class="flex items-start text-gray-600 text-sm mb-2">
                            <i class="fas fa-map-marker-alt mt-0.5 mr-2 text-gray-400 flex-shrink-0"></i>
                            <span class="line-clamp-2">{{ $kosan['subLocation'] }}</span>
                        </div>

                        @if(!empty($kosan['distance']))
                        <p class="text-gray-500 text-xs mb-2">
                            <i class="fas fa-route mr-1"></i>{{ $kosan['distance'] }}
                        </p>
                        @endif

                        <!-- Features -->
                        @if(!empty($kosan['features']))
                        <div class="flex flex-wrap gap-2 mb-2">
                            @php
                                $features = $kosan['features'] ?? [];
                                $displayFeatures = array_slice($features, 0, 3);
                            @endphp
                            @foreach($displayFeatures as $feature)
                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                    {{ $feature }}
                                </span>
                            @endforeach
                            @if(count($features) > 3)
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded">
                                    +{{ count($features) - 3 }}
                                </span>
                            @endif
                        </div>
                        @endif

                        <!-- Price Section -->
                        <div class="mt-auto pt-2.5 border-t border-gray-100">
                            @php
                                $roomPrice = $kosan['room_price_original_monthly'];
                            @endphp
                            @if(!empty($roomPrice))
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Mulai dari</div>
                                    <div class="text-lg font-bold text-teal-600">
                                        Rp{{ number_format($roomPrice, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">per bulan</div>
                                </div>
                                <div class="flex items-center text-teal-600 text-sm font-medium group-hover:translate-x-1 transition-transform">
                                    Lihat
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-2">
                                <span class="text-teal-600 font-medium text-sm group-hover:translate-x-1 transition-transform inline-flex items-center">
                                    Lihat Detail
                                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="swiper-slide">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                    <i class="fas fa-home text-5xl"></i>
                </div>
                <p class="text-gray-500">Tidak ada kosan yang tersedia saat ini.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <!-- Pagination -->
    <div class="swiper-pagination"></div>
</div>

<style>
    .property-swiper {
        padding: 20px 40px;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #0d9488;
        background: white;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 18px;
        font-weight: bold;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: #0d9488;
        color: white;
        transform: scale(1.1);
    }

    .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        background: #d1d5db;
        opacity: 1;
        transition: all 0.3s ease;
    }

    .swiper-pagination-bullet-active {
        background: #0d9488;
        width: 24px;
        border-radius: 5px;
    }

    .property-card {
        height: 100%;
        min-height: 450px;
    }

    /* Line clamp utilities */
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }

    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    @media (min-width: 1024px) {
        .property-swiper .swiper-slide {
            width: 20%;
            height: auto;
        }
    }

    @media (min-width: 768px) and (max-width: 1023px) {
        .property-swiper .swiper-slide {
            width: 50%;
            height: auto;
        }
    }

    @media (max-width: 767px) {
        .property-swiper {
            padding: 20px 20px;
        }

        .property-swiper .swiper-slide {
            width: 100%;
            height: auto;
        }

        .swiper-button-next,
        .swiper-button-prev {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.property-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                // Mobile
                320: {
                    slidesPerView: 1,
                    spaceBetween: 16
                },
                // Tablet
                768: {
                    slidesPerView: 2,
                    spaceBetween: 16
                },
                // Desktop
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 16
                }
            }
        });
    });
</script>
