<!-- Hotel Content -->
<div class="property-tab-content hidden" data-tab="hotel">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Property Card -->
        <a href="{{ route('hotels.show', ['id' => 1]) }}" class="block hover:shadow-lg transition-shadow duration-300">
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="relative">
                    <div class="relative h-48 overflow-hidden">
                        <img src="images/assets/hotel.jpg" 
                             alt="Kvlarya Hotel" 
                             class="card-image w-full h-full object-cover">
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                    <div class="absolute bottom-2 left-2">
                        <span class="bg-pink-600 text-white text-xs px-2 py-1 rounded-full">Coliving Wanita</span>
                    </div>
                </div>

                <div class="p-4">
                    <h3 class="font-medium text-gray-800 mb-1">Kvlarya Hotel</h3>
                    <p class="text-gray-500 text-sm mb-1">Tegalrejo, Bogor Utara</p>
                    <p class="text-gray-500 text-xs mb-3">2.3 km dari Stasiun Bogor</p>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">
                                mulai dari <span class="line-through">Rp2.000.000</span>
                            </p>
                            <div class="flex items-center">
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full mr-2">HOT</span>
                                <p class="font-bold text-gray-800">
                                    Rp1.891.999 <span class="text-xs font-normal">/bulan</span>
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
        <!-- Additional hotel cards can be added here -->
    </div>
</div> 