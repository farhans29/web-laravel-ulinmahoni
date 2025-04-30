<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ulin Mahoni</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
  @include('components.homepage.styles')
</head>
<body>
  @include('components.homepage.header')

  <main>
    <!-- Hero Section with Search -->
    <div class="hero-section">
      @if($heroMedia['type'] == 'video')
      <div class="hero-content">
        <video id="heroVideo" class="hero-media" autoplay loop muted playsinline>
          <source src="{{ asset($heroMedia['sources']['video']) }}" type="video/mp4">
          Your browser does not support the video tag.  
        </video>
        <div class="absolute bottom-4 right-4 p-4">
          <button id="playPauseBtn" class="bg-black/50 hover:bg-black/70 text-white p-3 rounded-full">
            <i id="playPauseIcon" class="fas fa-pause"></i>
          </button>
        </div>
      </div> 
      @else
      <div class="hero-content">
          <img id="heroImage" 
              src="{{ asset('images/assets/pics/WhatsApp Image 2025-02-20 at 14.30.45.jpeg') }}" 
              alt="Hero Image"
          class="hero-media">
        </div>
      @endif

      <!-- Overlay with text -->
      <div class="absolute inset-0 gradient-overlay flex flex-col justify-center md:justify-end p-8 md:p-12 lg:p-12  text-white">
        <h1 class="text-2xl md:text-5xl lg:text-4xl font-light mb-3 max-w-4xl">A safe and harmonious environment</h1>
        <p class="text-xl md:text-xl font-light mb-24">#UlinMahoni</p>
    </div>

      <!-- Search Section -->
      <section class="search-section">
        <div class="search-container">
          <div class="search-box">
            <div class="flex flex-col md:flex-row gap-4">
              <!-- Property Types -->
              <div class="md:w-48 relative">
                <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select class="w-full pl-10 h-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none bg-white transition-all duration-200">
                  <option value="">Property Types</option>
                  <option value="house">House & Room</option>
                  <option value="apartment">Apartment</option>
                  <option value="villa">Villa</option>
                  <option value="hotel">Hotel</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
              </div>

              <!-- Rent Period -->
              <div class="md:w-48 relative">
                <i class="fas fa-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <select class="w-full pl-10 h-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none bg-white transition-all duration-200">
                  <option value="">Rent Period</option>
                  <option value="daily">Daily</option>
                  <option value="weekly">Weekly</option>
                  <option value="monthly">Monthly</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
              </div>

              <!-- Check-in Check-out Dates -->
              <div class="flex-1 flex gap-4">
                <div class="w-1/2 relative">
                  <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                  <input type="date" 
                    placeholder="Check-in" 
                    class="w-full pl-10 h-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                </div>
                <div class="w-1/2 relative">
                  <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                  <input type="date" 
                    placeholder="Check-out" 
                    class="w-full pl-10 h-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                </div>
              </div>
              
              <div class="md:w-48">
                <button class="w-full h-12 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-all duration-200 flex items-center justify-center">
                  <i class="fas fa-search mr-2"></i>
                  <span>Cari Hunian</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    @include('components.homepage.property-types')
    @include('components.homepage.promos')
    @include('components.homepage.areas')
    @include('components.homepage.featured')
    @include('components.homepage.special-offers')
    {{-- @include('components.homepage.liu-house') --}}
  </main>

  @include('components.homepage.footer')

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @include('components.homepage.scripts')
    });
  </script>
</body>
</html>