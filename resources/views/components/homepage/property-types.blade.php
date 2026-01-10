<!-- Property Types Component -->
<section class="section-light">
    <div class="container mx-auto px-2 md:px-4 max-w-[98%] xl:max-w-[95%]">
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
            <div class="flex border-b border-gray-200 mb-8">
                <button class="property-tab-trigger px-6 py-3 text-lg font-medium text-teal-600 border-b-2 border-teal-600" data-tab="kos">
                    {{ __('homepage.property_types.Kos') }}
                </button>
                {{--
                <button class="property-tab-trigger px-6 py-3 text-lg font-medium text-gray-500 hover:text-gray-700" data-tab="house">
                    {{ __('homepage.property_types.House') }}
                </button>
                --}}
                <button class="property-tab-trigger px-6 py-3 text-lg font-medium text-gray-500 hover:text-gray-700" data-tab="apartment">
                    {{ __('homepage.property_types.Apartment') }}
                </button>
                <button class="property-tab-trigger px-6 py-3 text-lg font-medium text-gray-500 hover:text-gray-700" data-tab="villa">
                    {{ __('homepage.property_types.Villa') }}
                </button>
                <button class="property-tab-trigger px-6 py-3 text-lg font-medium text-gray-500 hover:text-gray-700" data-tab="hotel">
                    {{ __('homepage.property_types.Hotel') }}
                </button>
            </div>

            <!-- Property Type Content -->
            <div class="property-tab-contents">
                <div class="property-tab-content active" data-tab="kos">
                    @include('components.homepage.property-cards.kos')
                </div>
                <div class="property-tab-content hidden" data-tab="house">
                    @include('components.homepage.property-cards.house')
                </div> 
                <div class="property-tab-content hidden" data-tab="apartment">  
                    @include('components.homepage.property-cards.apartment')
                </div>
                <div class="property-tab-content hidden" data-tab="villa">
                    @include('components.homepage.property-cards.villa')
                </div>
                <div class="property-tab-content hidden" data-tab="hotel">
                    @include('components.homepage.property-cards.hotel')
                </div>
                <!-- Browse All Button -->
                <div class="text-center py-8">
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-all duration-300 hover:shadow-lg hover:shadow-teal-600/20">
                        <span>{{ __('homepage.actions.view_all_properties') }}</span>
                        <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section> 