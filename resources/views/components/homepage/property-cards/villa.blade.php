<!-- Villa Content -->
<div class="property-tab-content hidden" data-tab="villa">
    <!-- Swiper container -->
    <div class="swiper property-swiper">
        <div class="swiper-wrapper mb-8">
            @forelse($villas as $villa)
            <div class="swiper-slide">
                <a href="{{ route('villas.show', ['id' => $villa['id']]) }}" class="block hover:shadow-lg transition-shadow duration-300">
                    <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="relative">
                            <div class="relative h-48 overflow-hidden">
                                <img src="data:image/jpeg;base64,{{ $villa['image'] }}" 
                                     alt="{{ $villa['name'] }}" 
                                     class="card-image w-full h-full object-cover">
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                            <div class="absolute bottom-2 left-2">
                                <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">{{ $villa['type'] }}</span>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="font-medium text-gray-800 mb-1">{{ $villa['name'] }}</h3>
                            <p class="text-gray-500 text-sm mb-1">{{ $villa['subLocation'] }}</p>
                            <p class="text-gray-500 text-xs mb-3">{{ $villa['distance'] }}</p>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">
                                        mulai dari <span class="line-through">Rp{{ number_format($villa['price']['original'], 0, ',', '.') }}</span>
                                    </p>
                                    <div class="flex items-center">
                                        <p class="font-bold text-gray-800">
                                            Rp{{ number_format($villa['price']['discounted'], 0, ',', '.') }} <span class="text-xs font-normal">/bulan</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-2 text-xs text-gray-500">
                                    @foreach($villa['features'] as $feature)
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
                    <p class="text-gray-500">No villas available at the moment.</p>
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