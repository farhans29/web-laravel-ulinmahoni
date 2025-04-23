<!-- House & Room Content -->
<div class="property-tab-content active" data-tab="house">
    <!-- Swiper container -->
    <div class="swiper property-swiper">
        <div class="swiper-wrapper mb-8">
            <!-- Property Card 1 -->
            <div class="swiper-slide">
                <a href="{{ route('houses.show', ['id' => 1]) }}" class="block hover:shadow-lg transition-shadow duration-300">
                    <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative">
                            <div class="relative h-48 overflow-hidden">
                                <img src="images/assets/foto_project_ulin_mahoni/RENDER HALUS PROJECT BAPAK LIU KOS BOGOR_pages-to-jpg-0006.jpg" 
                                     alt="Rexucia House & Room" 
                                     class="card-image w-full h-full object-cover">
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                            <div class="absolute bottom-2 left-2">
                                <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Coliving</span>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="font-medium text-gray-800 mb-1">Rexucia House & Room</h3>
                            <p class="text-gray-500 text-sm mb-1">Petojo Selatan, Gambir</p>
                            <p class="text-gray-500 text-xs mb-3">2.4 km dari Stasiun MRT Bundaran HI</p>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">
                                        mulai dari <span class="line-through">Rp1.300.000</span>
                                    </p>
                                    <div class="flex items-center">
                                        <p class="font-bold text-gray-800">
                                            Rp975.000 <span class="text-xs font-normal">/bulan</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-2 text-xs text-gray-500">
                                    <span class="border border-gray-300 rounded-lg px-3 py-1.5 text-center">Diskon sewa 12 Bulan</span>
                                    <span class="border border-gray-300 rounded-lg px-3 py-1.5 text-center">S+ Voucher s.d. 2%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Additional Property Cards (2-5) -->
            @for ($i = 2; $i <= 5; $i++)
            <div class="swiper-slide">
                <a href="{{ route('houses.show', ['id' => $i]) }}" class="block hover:shadow-lg transition-shadow duration-300">
                    <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative">
                            <div class="relative h-48 overflow-hidden">
                                <img src="images/assets/foto_project_ulin_mahoni/RENDER HALUS PROJECT BAPAK LIU KOS BOGOR_pages-to-jpg-0006.jpg" 
                                     alt="House & Room {{ $i }}" 
                                     class="card-image w-full h-full object-cover">
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                            <div class="absolute bottom-2 left-2">
                                <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Coliving</span>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="font-medium text-gray-800 mb-1">House & Room {{ $i }}</h3>
                            <p class="text-gray-500 text-sm mb-1">Petojo Selatan, Gambir</p>
                            <p class="text-gray-500 text-xs mb-3">2.4 km dari Stasiun MRT Bundaran HI</p>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">
                                        mulai dari <span class="line-through">Rp1.300.000</span>
                                    </p>
                                    <div class="flex items-center">
                                        <p class="font-bold text-gray-800">
                                            Rp975.000 <span class="text-xs font-normal">/bulan</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-2 text-xs text-gray-500">
                                    <span class="border border-gray-300 rounded-lg px-3 py-1.5 text-center">Diskon sewa 12 Bulan</span>
                                    <span class="border border-gray-300 rounded-lg px-3 py-1.5 text-center">S+ Voucher s.d. 2%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endfor
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
