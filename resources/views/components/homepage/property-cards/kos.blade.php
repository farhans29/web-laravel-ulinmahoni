<!-- Grid container -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($kos as $kosan)
    <div>
        <a href="{{ route('houses.show', ['id' => $kosan['id']]) }}" class="block h-full group">
            <div class="property-card bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 h-full flex flex-col">
                    <!-- Image Container -->
                    <div class="relative overflow-hidden">
                        <div class="relative aspect-[4/3] bg-gray-100">
                            @php
                                $mainImage = $kosan['images'][0]['image'] ?? $kosan['image'] ?? null;
                            @endphp

                            @if($mainImage)
                                <img src="{{ env('ADMIN_URL') }}/storage/{{ $mainImage }}"
                                     alt="{{ $kosan['name'] }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-gray-400\'><i class=\'fas fa-building text-3xl\'></i></div>';">

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

                            @if(isset($kosan['check_in']) && isset($kosan['check_out']))
                            <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium shadow-md">
                                <i class="fas fa-check mr-1"></i>Tersedia
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-3 flex-1 flex flex-col">
                        <!-- Property Name -->
                        <h4 class="font-semibold text-gray-800 mb-1 line-clamp-1 hover:text-teal-600 transition-colors">
                            {{ $kosan['name'] }}
                        </h4>

                        <!-- Location -->
                        <div class="flex items-start text-gray-600 text-sm mb-2">
                            <i class="fas fa-map-marker-alt mt-0.5 mr-1.5 text-gray-400 flex-shrink-0"></i>
                            <span class="line-clamp-2">{{ $kosan['subLocation'] }}</span>
                        </div>

                        <!-- Price Section -->
                        <div class="mt-auto flex items-center justify-between pt-2 border-t border-gray-100">
                            @php
                                $roomPrice = $kosan['room_price_original_monthly'];
                            @endphp
                            @if(!empty($roomPrice))
                            <div>
                                <div class="text-lg font-bold text-teal-600">
                                    Rp{{ number_format($roomPrice, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500">/bulan</div>
                            </div>
                            <span class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                                Lihat <i class="fas fa-arrow-right text-xs ml-1"></i>
                            </span>
                            @else
                            <span class="text-teal-600 hover:text-teal-700 text-sm font-medium w-full text-center">
                                Lihat Detail <i class="fas fa-arrow-right text-xs ml-1"></i>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-4 text-gray-300">
                <i class="fas fa-home text-5xl"></i>
            </div>
            <p class="text-gray-500">Tidak ada kosan yang tersedia saat ini.</p>
        </div>
    </div>
    @endforelse
</div>
