<!-- Property Types Component -->
<section class="section-light mt-4">
    <div class="section-container">
        <div class="section-title">
            <h3 class="text-4xl font-medium">Properties</h3>
            <div class="divider mt-2 md-2">
                <div class="divider-line"></div>
                <p class="divider-text">Exclusive, Cozy and Premium</p>
                <div class="divider-line"></div>
            </div>
        </div>

        <div class="w-full">
            <!-- Property Type Tabs -->
            <div class="flex border-b border-gray-200 mb-6">
                <button class="property-tab-trigger px-6 py-3 text-sm font-medium text-teal-600 border-b-2 border-teal-600" data-tab="house">
                    House & Room
                </button>
                <button class="property-tab-trigger px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="apartment">
                    Apartment
                </button>
                <button class="property-tab-trigger px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="villa">
                    Villa
                </button>
                <button class="property-tab-trigger px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="hotel">
                    Hotel
                </button>
            </div>

            <!-- Property Type Content -->
            <div class="property-tab-contents">
                @include('components.homepage.property-cards.house')
                @include('components.homepage.property-cards.apartment')
                @include('components.homepage.property-cards.villa')
                @include('components.homepage.property-cards.hotel')
            </div>
        </div>
    </div>
</section> 