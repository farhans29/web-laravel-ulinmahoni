<!-- Swiper container -->
<div class="swiper property-swiper">
        <div class="swiper-wrapper mb-8">
            @forelse($kos as $kosan)
            <div class="swiper-slide">
                <a href="{{ route('en.houses.show', ['id' => $kosan['id']]) }}" class="block h-full">
                    <div class="property-card bg-white rounded-lg shadow-md overflow-hidden h-full flex flex-col">
                        <div class="relative">
                            <div class="relative pb-[56.25%] h-48">
                                <div class="absolute inset-0">
                                    @php
                                        $mainImage = $kosan['images'][0]['image'] ?? $kosan['image'] ?? null;
                                    @endphp
                                    
                                    @if($mainImage)
                                        <img src="data:image/jpeg;base64,{{ $mainImage }}" 
                                             alt="{{ $kosan['name'] }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                        
                                        @if(count($kosan['images'] ?? []) > 1)
                                            <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded-full">
                                                +{{ count($kosan['images']) - 1 }} more
                                            </div>
                                        @endif
                                    @else
                                        <div class="bg-gray-100 w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-4xl text-gray-400"></i>
                                            <span class="ml-2 text-gray-500">No Image</span>
                                        </div>
                                    @endif
                                    <!-- <p>{{ $kosan['image'] }}</p> -->
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent h-16"></div>
                                </div>
                                <span class="absolute top-2 left-2 bg-teal-600 text-white px-2 py-1 rounded-full text-sm">
                                    {{ $kosan['type'] }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex-1">
                                <h3 class="text-base font-medium text-gray-800 mb-1">{{ $kosan['name'] }}</h3>
                                <p class="text-gray-500 text-sm mb-1">{{ $kosan['subLocation'] }}</p>
                                <p class="text-gray-500 text-xs mb-3">{{ $kosan['distance'] }}</p>
                            </div>

                            <div class="mt-auto">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <!-- <p class="text-xs text-gray-500">
                                            mulai dari <span class="line-through">Rp{{ number_format($kosan['price_original_monthly'], 0, ',', '.') }}</span>
                                        </p> -->
                                        <div class="flex items-center">
                                            <p class="text-lg font-bold text-gray-800">
                                                @php
                                                    $roomPrice = $kosan['room_price_original_monthly'];
                                                @endphp
                                                @if(empty($roomPrice))
                                                    <span class="text-lg font-bold"></span>
                                                @else
                                                <!-- Rp{{ number_format($roomPrice, 0, ',', '.') }}
                                                    <span class="text-xs font-normal">/malam</span>     -->
                                                Rp{{ number_format($roomPrice, 0, ',', '.') }}
                                                    <span class="text-xs font-normal">/bulan</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col space-y-1 text-xs text-gray-500">
                                        @php
                                            $features = $kosan['features'] ?? [];
                                            $featureCount = count($features);
                                            $displayFeatures = array_slice($features, 0, 2);
                                            $remainingFeatures = $featureCount - 2;
                                        @endphp
                                        @foreach($displayFeatures as $feature)
                                            <span class="border border-gray-300 rounded-lg px-2 py-1 text-center whitespace-nowrap">{{ $feature }}</span>
                                        @endforeach
                                        @if($featureCount > 2)
                                            <span class="border border-gray-300 rounded-lg px-2 py-1 text-center bg-gray-100 text-gray-500 cursor-default" title="{{ implode(', ', $features) }}">
                                                +{{ $remainingFeatures }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="swiper-slide">
                <div class="text-center p-6">
                    <p class="text-gray-500">No houses available at the moment.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Add Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>

<style>
    .property-swiper {
        padding: 20px 40px;
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        color: #0d9488; /* teal-600 */
    }
    
    .swiper-pagination-bullet-active {
        background: #0d9488; /* teal-600 */
    }
    
    .property-card {
        height: 100%;
        min-height: 420px;
    }
    
    @media (min-width: 1024px) {
        .property-swiper .swiper-slide {
            width: 20%; /* 5 cards per view on large screens */
            height: auto;
        }
    }
    
    @media (min-width: 768px) and (max-width: 1023px) {
        .property-swiper .swiper-slide {
            width: 50%; /* 2 cards per view on medium screens */
            height: auto;
        }
    }
    
    @media (max-width: 767px) {
        .property-swiper .swiper-slide {
            width: 100%; /* 1 card per view on small screens */
            height: auto;
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
