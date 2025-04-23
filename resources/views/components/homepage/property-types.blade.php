<!-- Property Types Component -->
<section class="section-light">
    <div class="section-container">
        {{-- <div class="section-title">
            <h3 class="text-4xl font-medium">Properties</h3>
            <div class="divider mt-2 md-2">
                <div class="divider-line"></div>
                <p class="divider-text">Exclusive, Cozy and Premium</p>
                <div class="divider-line"></div>
            </div>
        </div> --}}

        <div class="w-full">
            <!-- Property Type Tabs -->
            <div class="flex border-b border-gray-200 mb-6">
                <button class="property-tab-trigger px-6 py-3 text-lg font-medium text-teal-600 border-b-2 border-teal-600" data-tab="house">
                    House & Room
                </button>
                <button class="property-tab-trigger px-6 py-3 text-lg font-medium text-gray-500 hover:text-gray-700" data-tab="apartment">
                    Apartment
                </button>
                <button class="property-tab-trigger px-6 py-3 text-lg font-medium text-gray-500 hover:text-gray-700" data-tab="villa">
                    Villa
                </button>
                <button class="property-tab-trigger px-6 py-3 text-lg font-medium text-gray-500 hover:text-gray-700" data-tab="hotel">
                    Hotel
                </button>
            </div>

            <!-- Property Type Content -->
            <div class="property-tab-contents">
                @include('components.homepage.property-cards.house')
                @include('components.homepage.property-cards.apartment')
                @include('components.homepage.property-cards.villa')
                @include('components.homepage.property-cards.hotel')
                <!-- Browse All Button -->
                <div class="text-center py-8">
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 transition-colors duration-200">
                        <span>Browse All Properties</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section> 