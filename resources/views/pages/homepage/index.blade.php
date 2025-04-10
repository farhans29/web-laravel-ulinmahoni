<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ulin Mahoni</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Custom styles */
    :root {
      --teal-600: #0d9488;
      --teal-700: #0f766e;
      --red-500: #ef4444;
      --red-700: #b91c1c;
    }
    
    body {
      background-color: #f5f2ea;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    
    .bg-teal-600 {
      background-color: var(--teal-600);
    }
    
    .bg-teal-700 {
      background-color: var(--teal-700);
    }
    
    .text-teal-600 {
      color: var(--teal-600);
    }
    
    .hover\:bg-teal-700:hover {
      background-color: var(--teal-700);
    }
    
    .bg-red-500 {
      background-color: var(--red-500);
    }
    
    .bg-red-700 {
      background-color: var(--red-700);
    }
    
    .property-card {
      transition: transform 0.3s ease;
    }
    
    .property-card:hover .card-image {
      transform: scale(1.05);
    }
    
    .card-image {
      transition: transform 0.5s ease;
    }
    
    .tab-trigger {
      position: relative;
    }
    
    .tab-trigger.active {
      border-bottom: 2px solid var(--teal-600);
    }
    
    .tab-content {
      display: none;
    }
    
    .tab-content.active {
      display: block;
    }
    
    .gradient-overlay {
      background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
    }
    
    .feature-icon {
      width: 40px;
      height: 40px;
      background-color: #f3f4f6;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0.25rem;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="bg-[#f5f2ea] py-4 px-6 flex items-center justify-between border-b border-gray-200">
    <div class="flex items-center space-x-8">
      <img src="images/assets/ulinmahoni-logo.svg" alt="Ulin Mahoni Logo" class="h-10 w-auto">
      <nav class="hidden md:flex">
        <ul class="flex space-x-6">
          <li>
            <a href="/sewa" class="text-sm text-gray-600 hover:text-gray-900">
              Sewa
            </a>
          </li>
          <li>
            <a href="/kerjasama" class="text-sm text-gray-600 hover:text-gray-900">
              Kerjasama Ulin Mahoni
            </a>
          </li>
          <li>
            <a href="/business" class="text-sm text-gray-600 hover:text-gray-900">
              Ulin Mahoni for Business
            </a>
          </li>
          <li>
            <a href="/tentang" class="text-sm text-gray-600 hover:text-gray-900">
              Tentang Ulin Mahoni
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <div class="flex items-center space-x-4">
      <div class="flex items-center space-x-2">
        {{-- <img src="https://via.placeholder.com/24" alt="ID Flag" class="rounded-full"> --}}
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 64 64" class="mr-2">
            <image alt="ID Flag" class="rounded-full mr-2" xlink:href="data:image/png;base64,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" x="0" y="0" width="64" height="64"/>
          </svg>
        <span class="text-sm text-gray-600">ID</span>
      </div>
      <button class="border border-gray-300 rounded-md px-4 py-2 text-sm">
        Masuk / Daftar
      </button>
    </div>
  </header>

  <main>
    <!-- Search Section -->
    <section class="max-w-7xl mx-auto px-4 py-6 relative z-10">
      <div class="bg-white rounded-lg shadow-lg p-4">
        <div class="flex flex-col md:flex-row gap-2">
          <div class="flex-1 relative">
            <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Cari lokasi, nama gedung atau landmark..." class="w-full pl-10 h-12 border border-gray-200 rounded-md">
          </div>
          <div class="md:w-48 relative">
            <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="date" placeholder="Pilih tanggal" class="w-full pl-10 h-12 border border-gray-200 rounded-md">
          </div>
          <div class="md:w-48 relative">
            <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <select class="w-full pl-10 h-12 border border-gray-200 rounded-md">
              <option value="">Semua tipe</option>
              <option value="house">House & Room</option>
              <option value="apartment">Apartment</option>
              <option value="villa">Villa</option>
              <option value="hotel">Hotel</option>
            </select>
          </div>
          <div class="md:w-48">
            <button class="w-full h-12 bg-teal-600 hover:bg-teal-700 text-white rounded-md">
              <i class="fas fa-search mr-2"></i>
              Cari Hunian
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Hero Section with Video (moved below search) -->
    <div class="relative w-full h-[500px] bg-gray-900 mt-4">
      <video id="heroVideo" class="absolute inset-0 w-full h-full object-cover" muted loop>
        {{-- <source src="videos/assets/hero-video.mp4" type="video/mp4"> --}}
        <source src="{{ asset('videos/assets/hero-video.mp4') }}" type="video/mp4">
          
        {{-- <source src="videos/assets/" type="video/mp4"> --}}
        Your browser does not support the video tag.
      </video>
      {{-- <img src="images/assets/foto_project_ulin_mahoni/RENDER HALUS PROJECT BAPAK LIU KOS BOGOR_pages-to-jpg-0006.jpg"  --}}

      <!-- Overlay with text -->
      <div class="absolute inset-0 gradient-overlay flex flex-col justify-end p-8 text-white">
        <h1 class="text-3xl font-light mb-2">A safe and harmonious environment</h1>
        <p class="text-lg font-light">#UlinMahoni</p>
      </div>

      <!-- Play/Pause Button -->
      <button id="playPauseBtn" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white/50 rounded-full w-12 h-12 flex items-center justify-center">
        <i id="playPauseIcon" class="fas fa-pause text-white"></i>
      </button>
    </div>

    <!-- Property Types Section -->
    <section class="max-w-7xl mx-auto px-4 py-12">

      <div class="w-full">
        <div class="flex justify-between items-center mb-6 mt-12">
          <div class="flex space-x-2">
            <button class="tab-trigger active" data-tab="kost">
              <div class="flex items-center">
                {{-- <img src="https://via.placeholder.com/24" alt="Kost Icon" class="mr-2"> --}}
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 64 64" class="mr-2 ml-2">
                    <image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAAXNSR0IArs4c6QAAAj5JREFUeF7tmj1OAzEQhb8cAAlRA1egR0CF+GkREkfgCgjRUtNzBBCiQgJBB/TUdPy1SMABAKPdKDjZjJ312pt40qAoszNvnt+MPWY7ZP7pZJ4/SoAqIHMGtAQyF4A2QS0BLQGZgTngCFgDpmTzVlh8ATfAHvA4DJFUAib5B2CmFWn5g3gHFoDXqkclAk6BbeAC2AXe/DEkeWIWOAY2gRNgZ1QCPgvZGyVUspgkRTmowfwMfADToxLwXTwoKUWGk8ZCxC8lJjpIk5dzVBG/EiBwKTLovBZpDEX8qgBVwHAGRAmlUbZzVBG/loCWgH8J1BmOhg0pTfgNXgKhhiN7SGnKb3AC6g5HVUNKU36DExBiOBo0pDTlNzgBokPHDcr2k8yv7zaYDGhTxCoBnueALBRQZz92VGp0s8rzh10Cofbj6Bk6Buy7JLUJqLsfO+KIblZ5SWoTEGI/jp6dY8CBl6Q2AaGanCOm6GZ9+SkB1hqoAsb8/wBSTWkJ2AusPUB7wH8Gsm2C98Ci1EEm7Pc7YLnsAeXKT1iOYjodmwDpfkD0OCYG3VJXAooVy74Ebn/fqloaE/mGgmlyXhl2ENoCzoDL4mWjUIFj+jHY1wGTyzngdRQ+BPYB8/cgJuqAsewcvAi4Kl6OLNkLiCuaq1LFJpcNHwX0IpwHXqJBDhvIYH8a4LJb+nYPsJvhdaGCsLDiejM5rPaE/Gt+5fdcDj6VlCsBcdXYvmiqgPatSVxEqoC4fLcvmiqgfWsSF1H2CvgBq3GlQZNUFbMAAAAASUVORK5CYII=" x="0" y="0" width="64" height="64"/>
                </svg>
                <span>House & Room</span>
              </div>
            </button>

            <button class="tab-trigger" data-tab="apartment">
              <div class="flex items-center">
                {{-- <img src="https://via.placeholder.com/24" alt="Apartemen Icon" class="mr-2"> --}}
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 64 64" class="mr-2 ml-2">
                    <image xlink:href="data:image/png;base64,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" x="0" y="0" width="64" height="64"/>
                  </svg>
                <span>Apartment</span>
              </div>
            </button>

            <button class="tab-trigger" data-tab="villa">
              <div class="flex items-center">
                {{-- <img src="https://via.placeholder.com/24" alt="Apartemen Icon" class="mr-2"> --}}
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 64 64" class="mr-2 ml-2">
                    <image xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAAXNSR0IArs4c6QAABK5JREFUeF7tmmmoVVUUx38maB8UUywUFUNJLUvxi2amQU4kBUmUQ+GACg5BfVA/VJaV6AcVx0otQzFQKNLCcFacp4hIcKjUpEjEbFRTc9r/x7pxeL537znnnnvuO+fc/cXhnb3fXr+z9lp7/depR8ZHvYzbTwVAxQMyTqByBDLuAJUgGOcRaAB8AnQDngR+rgveFxeAu4DVwAtm9LdAb+BiuSHEBeB9YALwG3DBwegIfAkMBm6WE0IcAN4F3gD+MdcXgAPAfcAcYEqaAUwE3gOuAc8Am83YXu4IbAMammcsKReEUnrAMAt6t4ChwGfVjBxiceE68JQBiZ1DqQD0A9YDivzjgOW1WJY7Hr8DPYHv4yZQCgA9gK3ufDey861zXtvQ71dqHA6cAh4FzscJIWoAnV2g2wU0c29/JvC6D2PuBnaY8buB/sBVH/MieSRKAG3c5vc6d9efS4HxAXbYwjJDW2AFMDrA3KIejQrAvYDenvL7WuB54EbAnT0E7AOaAFOB2QHnh3q8OgCd2yPuLdwfarWaJ/3qoHS1S1ChZQfZBUn7eg5YV2hCsT+vDqADcKLYRWuYr8B4yOe6rwDzgX9dbHgCOOxzXqjHagPwnb21UIt6Junio6AWBICmL3bZYBIg79HcX4rdSL405P1ZzgPKDaC+uf/TwDdAH5dSL5UCQl31ANna2LLKI8DnFlgjL5zqMgBBUDA+aIWT33tFIEcJAkCRXKlObybf+NMVPY8Bx6z4CRMDvOtrLRVOujCNAlYGsrDAw0EAvASs8vnLJXx8GhEA/Uqtt8aV1SqcBtrN0edW8j8WBsBHVuDUtPKHLmKPtQ1HCUC/620nnrxpgooKpx+iIJAkANqrPPBFV14ft2P2R7EQkgRAtioObLfSeae7Og8wsSU0h6QBkKHNrXBqbzqDjlzokUQAMlaFkyrPe4BXgQVhCSQVgOxVNpDqJMld6rJU5sAjyQBk7MvAIlOcHwd0hQ804gIQaFMhHz5jhdO5IPPTBEB2f20l9GW/EOICUKgclnSuNydpLOiYYdqj1pA9ujFKZNW/C440AVADRldmyXO6NU4vaL0R8z6XTw/I1QKluApH4QGTTXXaYv2IESa55+WQJg8QgLmmKH8MXHE6QherGZQt9Hf1If/yEkkjANn3joFQo+Vhlx02uDjxn8WHkVkA4LVRjRplhf1WTbZzcJQyq0ZaPSBnnxQltdwUD9RzOOmKp9dcV3pWVgCMAaRRtALOGozTTtnqmxUACoa6g6hnqSEY0hMk61V1rsIcAT/pNagkFlUaVBbwDjVjjpqWqP9Xv/ID4AHgx7QD0MtVynvL9RvnGRUJrCqjn3Vfq30RFkBdvgh5PUCd5p/s65ONBqApoI8x/g+EYY5AUgComyTZTHK+t0xWh0naYlX7Ps0ApBduMvWok9UIy0xV/spSY6oBtLQLjzpKD1qXSZWjlCN1oBem3QNknwzWef8bUMNVn+XpQ6zudjtMtQfk0qE+t5kGtLa2u6AoEGbiKpyzU1+qqZy/Q3BREFReVH7M4tgjAOr4SlHN4tgd1VdiiYVXAZDYVxfRxiseEBHIxC5T8YDEvrqINp55D7gNN9bZ1fJkozMAAAAASUVORK5CYII=" x="0" y="0" width="64" height="64"/>
                </svg>
                <span>Villa</span>
              </div>
            </button>
            
            <button class="tab-trigger" data-tab="hotel">
              <div class="flex items-center">
                {{-- <img src="https://via.placeholder.com/24" alt="Apartemen Icon" class="mr-2"> --}}
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 64 64" class="mr-2 ml-2">
                    <image xlink:href="data:image/png;base64,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" x="0" y="0" width="64" height="64"/>
                </svg>
                <span>Hotel</span>
              </div>
            </button>
          </div>
          <button class="text-teal-600">
            Lihat Semua
          </button>
        </div>

        {{-- ICONS --}}
        {{-- <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Populer">
            </div>
            <span class="text-sm text-gray-700">Populer</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Terbaru">
            </div>
            <span class="text-sm text-gray-700">Terbaru</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Bandung">
            </div>
            <span class="text-sm text-gray-700">Bandung</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Surabaya">
            </div>
            <span class="text-sm text-gray-700">Surabaya</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Dekat MRT">
            </div>
            <span class="text-sm text-gray-700">Dekat MRT</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Dekat KRL">
            </div>
            <span class="text-sm text-gray-700">Dekat KRL</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Dekat LRT">
            </div>
            <span class="text-sm text-gray-700">Dekat LRT</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Tomang">
            </div>
            <span class="text-sm text-gray-700">Tomang</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="BSD City">
            </div>
            <span class="text-sm text-gray-700">BSD City</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="UPH Karawaci">
            </div>
            <span class="text-sm text-gray-700">UPH Karawaci</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Pasutri">
            </div>
            <span class="text-sm text-gray-700">Pasutri</span>
          </div>
          <div class="flex flex-col items-center justify-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
            <div class="feature-icon">
              <img src="https://via.placeholder.com/24" alt="Mewah">
            </div>
            <span class="text-sm text-gray-700">Mewah</span>
          </div>
        </div> --}}

        <div id="kost-tab" class="tab-content active">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Property Card 1 -->
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
              <div class="relative">
                <div class="relative h-48 overflow-hidden">
                  <img src="images/assets/foto_project_ulin_mahoni/RENDER HALUS PROJECT BAPAK LIU KOS BOGOR_pages-to-jpg-0006.jpg" 
                       alt="Rexucia House & Room" 
                       class="card-image w-full h-full object-cover">
                  <div class="absolute top-2 right-2 z-10">
                  
                  </div>
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

            <!-- Property Card 2 -->
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
              <div class="relative">
                <div class="relative h-48 overflow-hidden">
                  <img src="images/assets/apt.jpg" 
                       alt="Royal Mediteranian Apartment" 
                       class="card-image w-full h-full object-cover">
                  <div class="absolute top-2 right-2 z-10">
                  
                  </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                <div class="absolute bottom-2 left-2">
                  <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Coliving</span>
                </div>
              </div>

              <div class="p-4">
                <h3 class="font-medium text-gray-800 mb-1">Royal Mediteranian Apartment</h3>
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

            <!-- Property Card 3 -->
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
              <div class="relative">
                <div class="relative h-48 overflow-hidden">
                  <img src="images/assets/villa.jpg" 
                       alt="Xilonen Villa" 
                       class="card-image w-full h-full object-cover">
                  <div class="absolute top-2 right-2 z-10">
                  
                  </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                <div class="absolute bottom-2 left-2">
                  <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Coliving</span>
                </div>
              </div>

              <div class="p-4">
                <h3 class="font-medium text-gray-800 mb-1">Xilonen Villa</h3>
                <p class="text-gray-500 text-sm mb-1">Kelurahan Fatmawati, Fatmawati</p>
                <p class="text-gray-500 text-xs mb-3">3.3 km dari Stasiun MRT Bundaran Senayan</p>

                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-xs text-gray-500">
                      mulai dari <span class="line-through">Rp2.400.000</span>
                    </p>
                    <div class="flex items-center">
                      <p class="font-bold text-gray-800">
                        Rp2.275.000 <span class="text-xs font-normal">/bulan</span>
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

            <!-- Property Card 4 -->
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
              <div class="relative">
                <div class="relative h-48 overflow-hidden">
                  <img src="images/assets/hotel.jpg" 
                       alt="Kvlarya Hotel" 
                       class="card-image w-full h-full object-cover">
                  <div class="absolute top-2 right-2 z-10">
                  
                  </div>
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
          </div>
        </div>

        <div id="apartment-tab" class="tab-content">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Apartment Card 1 -->
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
              <div class="relative">
                <div class="relative h-48 overflow-hidden">
                  <img src="images/assets/Modern Japanese House_ Minimalist and Harmonious - Quiet Minimal.jpg" 
                       alt="Luxoria Apartment" 
                       class="card-image w-full h-full object-cover">
                  <div class="absolute top-2 right-2 z-10">
                  
                  </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                <div class="absolute bottom-2 left-2">
                  <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Apartemen</span>
                </div>
              </div>

              <div class="p-4">
                <h3 class="font-medium text-gray-800 mb-1">Luxoria Apartment</h3>
                <p class="text-gray-500 text-sm mb-1">Kemang, Jakarta Selatan</p>
                <p class="text-gray-500 text-xs mb-3">1.2 km dari Stasiun MRT Fatmawati</p>

                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-xs text-gray-500">
                      mulai dari <span class="line-through">Rp6.300.000</span>
                    </p>
                    <div class="flex items-center">
                      <p class="font-bold text-gray-800">
                        Rp5.975.000 <span class="text-xs font-normal">/bulan</span>
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

            <!-- Apartment Card 2 -->
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
              <div class="relative">
                <div class="relative h-48 overflow-hidden">
                  <img src="images/assets/0e127752-6073-4445-89cc-e9f47f7122f8.jpg" 
                       alt="Mahoni Heights" 
                       class="card-image w-full h-full object-cover">
                  <div class="absolute top-2 right-2 z-10">
                  
                  </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                <div class="absolute bottom-2 left-2">
                  <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Apartemen</span>
                </div>
              </div>

              <div class="p-4">
                <h3 class="font-medium text-gray-800 mb-1">Mahoni Heights</h3>
                <p class="text-gray-500 text-sm mb-1">BSD City, Tangerang</p>
                <p class="text-gray-500 text-xs mb-3">500 m dari Mall BSD</p>

                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-xs text-gray-500">
                      mulai dari <span class="line-through">Rp3.750.000</span>
                    </p>
                    <div class="flex items-center">
                      <p class="font-bold text-gray-800">
                        Rp3.500.000 <span class="text-xs font-normal">/bulan</span>
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
          </div>
        </div>

        {{-- VILLA TAB --}}
        <div id="villa-tab" class="tab-content">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Apartment Card 1 -->
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
              <div class="relative">
                <div class="relative h-48 overflow-hidden">
                  <img src="images/assets/Modern Japanese House_ Minimalist and Harmonious - Quiet Minimal.jpg" 
                       alt="Luxoria Apartment" 
                       class="card-image w-full h-full object-cover">
                  <div class="absolute top-2 right-2 z-10">
                  
                  </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                <div class="absolute bottom-2 left-2">
                  <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Villa</span>
                </div>
              </div>

              <div class="p-4">
                <h3 class="font-medium text-gray-800 mb-1">Luxoria Villas</h3>
                <p class="text-gray-500 text-sm mb-1">Kemang, Jakarta Selatan</p>
                <p class="text-gray-500 text-xs mb-3">1.2 km dari Stasiun MRT Fatmawati</p>

                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-xs text-gray-500">
                      mulai dari <span class="line-through">Rp6.300.000</span>
                    </p>
                    <div class="flex items-center">
                      <p class="font-bold text-gray-800">
                        Rp5.975.000 <span class="text-xs font-normal">/bulan</span>
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

            <!-- Apartment Card 2 -->
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden">
              <div class="relative">
                <div class="relative h-48 overflow-hidden">
                  <img src="images/assets/0e127752-6073-4445-89cc-e9f47f7122f8.jpg" 
                       alt="Mahoni Heights" 
                       class="card-image w-full h-full object-cover">
                  <div class="absolute top-2 right-2 z-10">
                  
                  </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 gradient-overlay h-16"></div>
                <div class="absolute bottom-2 left-2">
                  <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full">Villa</span>
                </div>
              </div>

              <div class="p-4">
                <h3 class="font-medium text-gray-800 mb-1">Mahoni Villas</h3>
                <p class="text-gray-500 text-sm mb-1">BSD City, Tangerang</p>
                <p class="text-gray-500 text-xs mb-3">500 m dari Mall BSD</p>

                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-xs text-gray-500">
                      mulai dari <span class="line-through">Rp3.750.000</span>
                    </p>
                    <div class="flex items-center">
                      <p class="font-bold text-gray-800">
                        Rp3.500.000 <span class="text-xs font-normal">/bulan</span>
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
          </div>
        </div>
      </div>
    </section>

    <!-- Living Space Section -->
    {{-- <section class="py-12 px-4 bg-white">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
          <h2 class="text-3xl font-light text-gray-800 mb-2">Living Space</h2>
          <div class="flex items-center justify-center">
            <div class="w-12 h-px bg-gray-300"></div>
            <p class="mx-4 text-teal-600 italic">Exclusive & Cozy</p>
            <div class="w-12 h-px bg-gray-300"></div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Feature Card 1 -->
          <div class="bg-white shadow-md overflow-hidden rounded-lg">
            <div class="relative">
              <img src="images/assets/foto_project_ulin_mahoni/RENDER HALUS PROJECT BAPAK LIU KOS BOGOR_pages-to-jpg-0006.jpg" 
                   alt="Rexucia" 
                   class="w-full h-64 object-cover">
              <div class="absolute bottom-0 left-0 right-0 bg-red-700 text-white p-3 text-center">
                <h3 class="text-xl font-light">Rexucia</h3>
              </div>
            </div>
            <div class="p-6 bg-gray-50">
              <h4 class="text-xl font-light text-center text-gray-700 mb-4">HOUSE & ROOM</h4>
              <p class="text-gray-600 text-center text-sm mb-6">Mykonos Riviera Hotel & Spa offers a large selection of suites, most with heated pools, scintillating sunset views, and utmost privacy.</p>
              <div class="text-center">
                <a href="#" class="text-teal-600 hover:text-teal-700">View</a>
              </div>
            </div>
          </div>

          <!-- Feature Card 2 -->
          <div class="bg-white shadow-md overflow-hidden rounded-lg">
            <div class="relative">
              <img src="images/assets/apt.jpg" 
                   alt="Royal Mediteranian" 
                   class="w-full h-64 object-cover">
              <div class="absolute bottom-0 left-0 right-0 bg-teal-700 text-white p-3 text-center">
                <h3 class="text-xl font-light">Royal Mediteranian</h3>
              </div>
            </div>
            <div class="p-6 bg-gray-50">
              <h4 class="text-xl font-light text-center text-gray-700 mb-4">APARTMENT</h4>
              <p class="text-gray-600 text-center text-sm mb-6">Mykonos Riviera Hotel & Spa offers a large selection of suites, most with heated pools, scintillating sunset views, and utmost privacy.</p>
              <div class="text-center">
                <a href="#" class="text-teal-600 hover:text-teal-700">View</a>
              </div>
            </div>
          </div>

          <!-- Feature Card 3 -->
          <div class="bg-white shadow-md overflow-hidden rounded-lg">
            <div class="relative">
              <img src="images/assets/villa.jpg" 
                   alt="Xilonen" 
                   class="w-full h-64 object-cover">
              <div class="absolute bottom-0 left-0 right-0 bg-teal-700 text-white p-3 text-center">
                <h3 class="text-xl font-light">Xilonen</h3>
              </div>
            </div>
            <div class="p-6 bg-gray-50">
              <h4 class="text-xl font-light text-center text-gray-700 mb-4">VILLA</h4>
              <p class="text-gray-600 text-center text-sm mb-6">Mykonos Riviera Hotel & Spa offers a large selection of suites, most with heated pools, scintillating sunset views, and utmost privacy.</p>
              <div class="text-center">
                <a href="#" class="text-teal-600 hover:text-teal-700">View</a>
              </div>
            </div>
          </div>

          <!-- Feature Card 4 -->
          <div class="bg-white shadow-md overflow-hidden rounded-lg">
            <div class="relative">
              <img src="images/assets/hotel.jpg" 
                   alt="Kvlarya" 
                   class="w-full h-64 object-cover">
              <div class="absolute bottom-0 left-0 right-0 bg-red-700 text-white p-3 text-center">
                <h3 class="text-xl font-light">Kvlarya</h3>
              </div>
            </div>
            <div class="p-6 bg-gray-50">
              <h4 class="text-xl font-light text-center text-gray-700 mb-4">HOTEL</h4>
              <p class="text-gray-600 text-center text-sm mb-6">Mykonos Riviera Hotel & Spa offers a large selection of suites, most with heated pools, scintillating sunset views, and utmost privacy.</p>
              <div class="text-center">
                <a href="#" class="text-teal-600 hover:text-teal-700">View</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> --}}

    <!-- Promo berlangsung Section -->
    <section class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-medium">Promo berlangsung</h2>
        <a href="#" class="text-sm text-gray-600 hover:text-teal-600 flex items-center">
          Lihat Semua
          <i class="fas fa-chevron-right ml-1 text-xs"></i>
        </a>
      </div>

      <div class="promo-slider relative">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Promo Card 1 -->
          <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Screenshot%202025-04-11%20at%2001.38.16-ONQfSyRpYU314aVuFnhY5tCbMSbnlQ.png" alt="Rukita x Grab Promo" class="w-full h-48 object-cover">
            <div class="p-4">
              <div class="flex items-center mb-2">
                <span class="bg-yellow-400 text-xs px-2 py-1 rounded-full text-gray-800 font-medium">Hemat 90%</span>
              </div>
              <h3 class="font-medium text-gray-800 mb-1">Hemat Waktu Hemat Ongkos</h3>
              <p class="text-gray-600 text-sm">Pakai Grab Hemat</p>
            </div>
          </div>

          <!-- Promo Card 2 -->
          <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Screenshot%202025-04-11%20at%2001.38.16-ONQfSyRpYU314aVuFnhY5tCbMSbnlQ.png" alt="Rukita x Prabu Promo" class="w-full h-48 object-cover">
            <div class="p-4">
              <div class="flex items-center mb-2">
                <span class="bg-yellow-400 text-xs px-2 py-1 rounded-full text-gray-800 font-medium">Diskon 100rb</span>
              </div>
              <h3 class="font-medium text-gray-800 mb-1">Dompet tetep anteng beli sepatu ganteng</h3>
              <p class="text-gray-600 text-sm">Nikmati diskon</p>
            </div>
          </div>

          <!-- Promo Card 3 -->
          <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Screenshot%202025-04-11%20at%2001.38.16-ONQfSyRpYU314aVuFnhY5tCbMSbnlQ.png" alt="Rukita x Primecare Promo" class="w-full h-48 object-cover">
            <div class="p-4">
              <div class="flex items-center mb-2">
                <span class="bg-yellow-400 text-xs px-2 py-1 rounded-full text-gray-800 font-medium">499ribu</span>
              </div>
              <h3 class="font-medium text-gray-800 mb-1">Jadi gak gampang sakit pake promo vaksin</h3>
              <p class="text-gray-600 text-sm">Vaksin Flu 560ribu</p>
            </div>
          </div>
        </div>

        <!-- Slider Navigation Dots -->
        <div class="promo-nav-dots">
          <div class="nav-dot active"></div>
          <div class="nav-dot"></div>
          <div class="nav-dot"></div>
        </div>
      </div>
    </section>

    <!-- Area Kost Coliving Terpopuler Section -->
    <section class="max-w-7xl mx-auto px-4 py-8">
      <h2 class="text-2xl font-medium mb-6">Area Kost Coliving Terpopuler</h2>

      <div class="mb-6 border-b border-gray-200">
        <div class="flex space-x-6">
          <button class="area-tab active" data-area="kota">Kota</button>
          <button class="area-tab" data-area="universitas">Universitas</button>
          <button class="area-tab" data-area="perkantoran">Perkantoran</button>
          <button class="area-tab" data-area="stasiun">Stasiun & Halte</button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Area Card 1 -->
        <div class="relative rounded-lg overflow-hidden group">
          <img src="https://via.placeholder.com/300x200" alt="Jakarta Barat" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
          <div class="absolute bottom-0 left-0 right-0 gradient-overlay p-4">
            <h3 class="text-white font-medium">Jakarta Barat</h3>
          </div>
        </div>

        <!-- Area Card 2 -->
        <div class="relative rounded-lg overflow-hidden group">
          <img src="https://via.placeholder.com/300x200" alt="Jakarta Selatan" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
          <div class="absolute bottom-0 left-0 right-0 gradient-overlay p-4">
            <h3 class="text-white font-medium">Jakarta Selatan</h3>
          </div>
        </div>

        <!-- Area Card 3 -->
        <div class="relative rounded-lg overflow-hidden group">
          <img src="https://via.placeholder.com/300x200" alt="Jakarta Pusat" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
          <div class="absolute bottom-0 left-0 right-0 gradient-overlay p-4">
            <h3 class="text-white font-medium">Jakarta Pusat</h3>
          </div>
        </div>

        <!-- Area Card 4 -->
        <div class="relative rounded-lg overflow-hidden group">
          <img src="https://via.placeholder.com/300x200" alt="Tangerang" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
          <div class="absolute bottom-0 left-0 right-0 gradient-overlay p-4">
            <h3 class="text-white font-medium">Tangerang</h3>
          </div>
        </div>
      </div>

      <!-- Area Navigation Dots -->
      <div class="area-nav-dots mt-6">
        <div class="nav-dot active"></div>
        <div class="nav-dot"></div>
        <div class="nav-dot"></div>
      </div>
    </section>

    <!-- Featured Properties Section -->
    <section class="py-12 px-4 bg-[#f5f2ea]">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
          <h2 class="text-3xl font-light text-gray-800 mb-2">Featured Properties</h2>
          <div class="flex items-center justify-center">
            <div class="w-12 h-px bg-gray-300"></div>
            <p class="mx-4 text-teal-600 italic">Discover Our Collection</p>
            <div class="w-12 h-px bg-gray-300"></div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="relative rounded-lg overflow-hidden group">
            <img src="images/assets/0e127752-6073-4445-89cc-e9f47f7122f8.jpg"
                 alt="Japanese Style Interior"
                 class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 gradient-overlay"></div>
            <div class="absolute bottom-4 left-4">
              <button class="bg-transparent border border-white text-white px-4 py-2 rounded hover:bg-white/20 transition flex items-center">
                BOOK NOW
                <i class="fas fa-arrow-right ml-2"></i>
              </button>
            </div>
          </div>

          <div class="relative rounded-lg overflow-hidden group">
            <img src="images/assets/Modern Japanese House_ Minimalist and Harmonious - Quiet Minimal.jpg"
                 alt="Townhouses"
                 class="w-full h-[400px] object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 gradient-overlay"></div>
            <div class="absolute bottom-4 left-4">
              <button class="bg-transparent border border-white text-white px-4 py-2 rounded hover:bg-white/20 transition flex items-center">
                BOOK NOW
                <i class="fas fa-arrow-right ml-2"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="relative rounded-lg overflow-hidden group">
          <img src="images/assets/kos.jpg"
               alt="Mountain Cabin"
               class="w-full h-[500px] object-cover transition-transform duration-500 group-hover:scale-105">
          <div class="absolute inset-0 gradient-overlay"></div>
          <div class="absolute bottom-4 left-4">
            <button class="bg-transparent border border-white text-white px-4 py-2 rounded hover:bg-white/20 transition flex items-center">
              BOOK NOW
              <i class="fas fa-arrow-right ml-2"></i>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Special Offers Section -->
    <section class="py-12 px-4 bg-white">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
          <h2 class="text-3xl font-light text-gray-800 mb-2">Special Offers</h2>
          <div class="flex items-center justify-center">
            <div class="w-12 h-px bg-gray-300"></div>
            <p class="mx-4 text-teal-600 italic">Trendy & Serene</p>
            <div class="w-12 h-px bg-gray-300"></div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- The Oqua Spa -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 overflow-hidden">
              <img src="images/assets/adheesha-paranagama-kOYh8C_xLUQ-unsplash.jpg"
                   alt="The Oqua Spa"
                   class="w-full h-full object-cover">
            </div>
            <div class="p-4">
              <h3 class="text-lg font-medium text-gray-800">THE OQUA SPA</h3>
              <p class="text-gray-500 text-sm mb-2">A journey of wellness and relaxation</p>
              <p class="text-gray-600 text-sm mb-10">
                The Oqua Spa at the newest luxury hotel in Mykonos is a 500 sqm heaven of wellness and relaxation.
              </p>
              <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded">LEARN MORE</button>
            </div>
          </div>

          <!-- Lafs Restaurant -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 overflow-hidden">
              <img src="images/assets/Mykonos_Riviera_11-758x900.jpg.png"
                   alt="Lafs Restaurant"
                   class="w-full h-full object-cover">
            </div>
            <div class="p-4">
              <h3 class="text-lg font-medium text-gray-800">LAFS RESTAURANT</h3>
              <p class="text-gray-500 text-sm mb-2">Waterfront dining under the stars</p>
              <p class="text-gray-600 text-sm mb-10">
                A new, sensational gastronomic concept in Mykonos with tradition inspired, seafood-based
                contemporary greek cuisine.
              </p>
              <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded">LEARN MORE</button>
            </div>
          </div>

          <!-- Pool Club -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 overflow-hidden">
              <img src="images/assets/LAVEER-POOLSUITE-DINNER-DURING-SUNSET-758x900.jpg.png"
                   alt="Pool Club"
                   class="w-full h-full object-cover">
            </div>
            <div class="p-4">
              <h3 class="text-lg font-medium text-gray-800">POOL CLUB</h3>
              <p class="text-gray-500 text-sm mb-2">An all day dining spot</p>
              <p class="text-gray-600 text-sm mb-10">
                Start with a healthy breakfast, served at your leisure until 11:30 am. Choose from an extensive
                casual all-day menu.
              </p>
              <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded">LEARN MORE</button>
            </div>
          </div>

          <!-- Private BBQ -->
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 overflow-hidden">
              <img src="images/assets/LAFS-RESTAURANT-OUTDOOR-VERANDA-1-1-758x900.jpg.png"
                   alt="Private BBQ"
                   class="w-full h-full object-cover">
            </div>
            <div class="p-4">
              <h3 class="text-lg font-medium text-gray-800">PRIVATE BBQ</h3>
              <p class="text-gray-500 text-sm mb-2">Dine under Mykonos starlit sky</p>
              <p class="text-gray-600 text-sm mb-10">
                The Suites with their beautiful outdoor barbecue setup are ideal for personalized BBQ experience.
              </p>
              <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded">LEARN MORE</button>
            </div>
          </div>
        </div>

        <!-- Pagination Dots -->
        <div class="flex justify-center mt-8 space-x-2">
          <div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center">1</div>
          <div class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center">2</div>
        </div>
      </div>
    </section>

    <!-- Liu House Section -->
    <section class="py-12 bg-[#f5f2ea]">
      <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg overflow-hidden shadow-md">
          <div class="flex flex-col lg:flex-row">
            <!-- Left Side - Property Image -->
            <div class="w-full lg:w-2/3">
              <img src="images/assets/foto_project_ulin_mahoni/RENDER HALUS PROJECT BAPAK LIU KOS BOGOR_pages-to-jpg-0012.jpg"
                   alt="Liu House West Jakarta"
                   class="w-full h-full object-cover">
            </div>

            <!-- Right Side - Property Details -->
            <div class="w-full lg:w-1/3 p-8">
              <h2 class="text-2xl font-light text-gray-800 mb-1">LIU HOUSE WEST JAKARTA</h2>
              <p class="text-gray-500 italic mb-4">Secret 3 Bedroom</p>

              <p class="text-gray-600 mb-6 text-sm">
                Gaze at the perfect sea-phony of blue and emerald green from the privacy of the stunning 125 square
                meter Secret Three Bedroom Pool Maisonette and listen to the wind audaciously, yet seductively
                humming in your ears.
              </p>

              <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Interior Image -->
                <div class="overflow-hidden rounded-lg">
                  <img src="images/assets/foto_project_ulin_mahoni/RENDER HALUS PROJECT BAPAK LIU KOS BOGOR_pages-to-jpg-0006.jpg"
                       alt="Liu House Interior"
                       class="w-full h-full object-cover">
                </div>

                <!-- Price Tag -->
                <div class="bg-teal-600 text-white p-4 rounded-lg flex flex-col justify-center items-center">
                  <p class="text-xs mb-1">Starting from</p>
                  <p class="text-xl font-light">Rp 1.500.000</p>
                  <p class="mt-1 text-xs">ULIN MAHONI</p>
                </div>
              </div>

              <button class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded">BOOK NOW</button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white py-12 px-4">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
      <div>
        <h4 class="text-xl font-medium mb-4">Ulin Mahoni</h4>
        <p class="text-gray-400 text-sm">
          Luxury living redefined for those who appreciate the finer things in life.
        </p>
      </div>

      <div>
        <h4 class="text-xl font-medium mb-4">Quick Links</h4>
        <ul class="space-y-2 text-gray-400 text-sm">
          <li>
            <a href="#" class="hover:text-white transition">
              Home
            </a>
          </li>
          <li>
            <a href="#" class="hover:text-white transition">
              About Us
            </a>
          </li>
          <li>
            <a href="#" class="hover:text-white transition">
              Rooms
            </a>
          </li>
          <li>
            <a href="#" class="hover:text-white transition">
              Facilities
            </a>
          </li>
        </ul>
      </div>

      <div>
        <h4 class="text-xl font-medium mb-4">Contact</h4>
        <ul class="space-y-2 text-gray-400 text-sm">
          <li>Jl. Luxury Boulevard No. 123</li>
          <li>+62 123 4567 890</li>
          <li>info@ulinmahoni.com</li>
        </ul>
      </div>

      <div>
        <h4 class="text-xl font-medium mb-4">Follow Us</h4>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-400 hover:text-white transition">
            <div class="w-8 h-8 rounded-full border border-gray-600 flex items-center justify-center">F</div>
          </a>
          <a href="#" class="text-gray-400 hover:text-white transition">
            <div class="w-8 h-8 rounded-full border border-gray-600 flex items-center justify-center">I</div>
          </a>
          <a href="#" class="text-gray-400 hover:text-white transition">
            <div class="w-8 h-8 rounded-full border border-gray-600 flex items-center justify-center">T</div>
          </a>
          <a href="#" class="text-gray-400 hover:text-white transition">
            <div class="w-8 h-8 rounded-full border border-gray-600 flex items-center justify-center">Y</div>
          </a>
        </div>
      </div>
    </div>

    <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
      <p>&copy; 2025 Ulin Mahoni. All rights reserved.</p>
    </div>
  </footer>

  <script>
    // Video Play/Pause functionality
    document.addEventListener('DOMContentLoaded', function() {
      const video = document.getElementById('heroVideo');
      const playPauseBtn = document.getElementById('playPauseBtn');
      const playPauseIcon = document.getElementById('playPauseIcon');
      let isPlaying = true;

      // Try to play video on load
      video.play().catch(error => {
        console.error("Video autoplay failed:", error);
        isPlaying = false;
        playPauseIcon.classList.remove('fa-pause');
        playPauseIcon.classList.add('fa-play');
      });

      playPauseBtn.addEventListener('click', function() {
        if (isPlaying) {
          video.pause();
          playPauseIcon.classList.remove('fa-pause');
          playPauseIcon.classList.add('fa-play');
        } else {
          video.play();
          playPauseIcon.classList.remove('fa-play');
          playPauseIcon.classList.add('fa-pause');
        }
        isPlaying = !isPlaying;
      });

      // Tab functionality
      const tabTriggers = document.querySelectorAll('.tab-trigger');
      const tabContents = document.querySelectorAll('.tab-content');

      tabTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
          // Remove active class from all triggers and contents
          tabTriggers.forEach(t => t.classList.remove('active'));
          tabContents.forEach(c => c.classList.remove('active'));

          // Add active class to clicked trigger and corresponding content
          trigger.classList.add('active');
          const tabId = trigger.getAttribute('data-tab');
          document.getElementById(`${tabId}-tab`).classList.add('active');
        });
      });

      // Area tabs functionality
      const areaTabs = document.querySelectorAll('.area-tab');
      
      areaTabs.forEach(tab => {
        tab.addEventListener('click', () => {
          // Remove active class from all area tabs
          areaTabs.forEach(t => t.classList.remove('active'));
          
          // Add active class to clicked tab
          tab.classList.add('active');
        });
      });
    });
  </script>
</body>
</html>