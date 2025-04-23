<!-- House & Room Content -->
<div class="property-tab-content active" data-tab="house">
    <!-- Swiper container -->
    <div class="swiper property-swiper">
        <div class="swiper-wrapper mb-8">
            @forelse($houses as $house)
            <div class="swiper-slide">
                <a href="{{ route('houses.show', ['id' => $house['id']]) }}" class="block hover:shadow-lg transition-shadow duration-300">
                    <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative">
                            <div class="relative h-48 overflow-hidden">
                                <img src="data:image/jpeg;base64,{{ $house['image'] }}" 
                                     alt="{{ $house['name'] }}" 
                                     class="card-image w-full h-full object-cover">
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                            <div class="absolute bottom-2 left-2">
                                <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">{{ $house['type'] }}</span>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="font-medium text-gray-800 mb-1">{{ $house['name'] }}</h3>
                            <p class="text-gray-500 text-sm mb-1">{{ $house['subLocation'] }}</p>
                            <p class="text-gray-500 text-xs mb-3">{{ $house['distance'] }}</p>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">
                                        mulai dari <span class="line-through">Rp{{ number_format($house['price']['original'], 0, ',', '.') }}</span>
                                    </p>
                                    <div class="flex items-center">
                                        <p class="font-bold text-gray-800">
                                            Rp{{ number_format($house['price']['discounted'], 0, ',', '.') }} <span class="text-xs font-normal">/bulan</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-2 text-xs text-gray-500">
                                    @foreach($house['features'] as $feature)
                                        <span class="border border-gray-300 rounded-lg px-3 py-1.5 text-center">{{ $feature }}</span>
                                    @endforeach
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
    
    @media (min-width: 1024px) {
        .property-swiper .swiper-slide {
            width: 25%; /* 4 cards per view on large screens */
        }
    }
    
    @media (min-width: 768px) and (max-width: 1023px) {
        .property-swiper .swiper-slide {
            width: 50%; /* 2 cards per view on medium screens */
        }
    }
    
    @media (max-width: 767px) {
        .property-swiper .swiper-slide {
            width: 100%; /* 1 card per view on small screens */
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.property-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 24,
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
                    spaceBetween: 20
                },
                // Desktop
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 24
                }
            }
        });
    });
</script>
