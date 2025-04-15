<!-- villa Content -->
<div class="property-tab-content hidden" data-tab="villa">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Property Card -->
        <a href="{{ route('villas.show', ['id' => 1]) }}" class="block hover:shadow-lg transition-shadow duration-300">
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="relative">
                    <div class="relative h-48 overflow-hidden">
                        <img src="images/assets/apt.jpg" 
                             alt="Royal Mediteranian Villa" 
                             class="card-image w-full h-full object-cover">
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                    <div class="absolute bottom-2 left-2">
                        <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Coliving</span>
                    </div>
                </div>

                <div class="p-4">
                    <h3 class="font-medium text-gray-800 mb-1">Royal Mediteranian Villa</h3>
                    <p class="text-gray-500 text-sm mb-1">Cilandak Barat, Cilandak</p>
                    <p class="text-gray-500 text-xs mb-3">976 m dari Stasiun MRT Fatmawati</p>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">
                                mulai dari <span class="line-through">Rp4.250.000</span>
                            </p>
                            <div class="flex items-center">
                                <p class="font-bold text-gray-800">
                                    Rp4.025.000 <span class="text-xs font-normal">/bulan</span>
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

        <!-- Property Card -->
        <a href="{{ route('villas.show', ['id' => 2]) }}" class="block hover:shadow-lg transition-shadow duration-300">
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="relative">
                    <div class="relative h-48 overflow-hidden">
                        <img src="images/assets/apt.jpg" 
                             alt="Royal Mediteranian Villa" 
                             class="card-image w-full h-full object-cover">
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                    <div class="absolute bottom-2 left-2">
                        <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Coliving</span>
                    </div>
                </div>

                <div class="p-4">
                    <h3 class="font-medium text-gray-800 mb-1">Royal Mediteranian Villa</h3>
                    <p class="text-gray-500 text-sm mb-1">Cilandak Barat, Cilandak</p>
                    <p class="text-gray-500 text-xs mb-3">976 m dari Stasiun MRT Fatmawati</p>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">
                                mulai dari <span class="line-through">Rp4.250.000</span>
                            </p>
                            <div class="flex items-center">
                                <p class="font-bold text-gray-800">
                                    Rp4.025.000 <span class="text-xs font-normal">/bulan</span>
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
</div> 