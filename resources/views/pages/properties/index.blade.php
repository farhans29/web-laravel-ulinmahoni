<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Available Rooms - Ulin Mahoni</title>
    <meta name="description" content="Find available rooms for rent. Browse by daily or monthly rates with real-time availability.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.homepage.styles')

    @stack('styles')
</head>
<body class="bg-gray-50">
    @include('components.homepage.header')

    <main class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Kamar Tersedia</h1>
            <p class="text-gray-600">Cari kamar yang ideal untuk Anda</p>

            <!-- Active Filters -->
            <div id="activeFilters" class="hidden mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <span class="font-medium text-gray-700">Filter Aktif:</span>
                    <div id="filterBadges" class="flex flex-wrap gap-2"></div>
                    <button onclick="clearAllFilters()" class="ml-auto text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <i class="fas fa-times mr-1"></i> Hapus Semua Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- View Toggle and Filters -->
        <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- View Options -->
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">Tampilan:</span>
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button type="button" onclick="setView('categorical')" id="viewCategorical" class="px-4 py-1.5 text-sm font-medium rounded transition-colors">
                            <i class="fas fa-th-large mr-1"></i> Kategori
                        </button>
                        <button type="button" onclick="setView('grid')" id="viewGrid" class="px-4 py-1.5 text-sm font-medium rounded transition-colors">
                            <i class="fas fa-th mr-1"></i> Grid
                        </button>
                        <button type="button" onclick="setView('list')" id="viewList" class="px-4 py-1.5 text-sm font-medium rounded transition-colors">
                            <i class="fas fa-list mr-1"></i> List
                        </button>
                    </div>
                </div>
            </div>

            <form id="searchForm" class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Property Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Properti</label>
                        <div class="relative">
                            <i class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="type" id="filterType" class="w-full pl-10 pr-3 h-11 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                                <option value="">Semua Tipe</option>
                                <option value="Kos" {{ request('type') == 'Kos' ? 'selected' : '' }}>Kos</option>
                                <option value="Apartment" {{ request('type') == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="Villa" {{ request('type') == 'Villa' ? 'selected' : '' }}>Villa</option>
                                <option value="Hotel" {{ request('type') == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                            </select>
                        </div>
                    </div>

                    <!-- Rent Period (hidden in categorical view) -->
                    <div id="periodFilterContainer">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Periode Sewa</label>
                        <div class="relative">
                            <i class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="period" id="filterPeriod" class="w-full pl-10 pr-3 h-11 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                                <option value="monthly" {{ request('period') == 'monthly' || !request('period') ? 'selected' : '' }}>Bulanan</option>
                                <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>Harian</option>
                            </select>
                        </div>
                    </div>

                    <!-- Check-in Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Check In</label>
                        <div class="relative">
                            <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input
                                type="date"
                                name="check_in"
                                id="filterCheckIn"
                                value="{{ request('check_in') }}"
                                min="{{ now()->format('Y-m-d') }}"
                                class="w-full pl-10 pr-3 h-11 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                        </div>
                    </div>

                    <!-- Check-out Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Check Out</label>
                        <div class="relative">
                            <i class="far fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input
                                type="date"
                                name="check_out"
                                id="filterCheckOut"
                                value="{{ request('check_out') }}"
                                min="{{ request('check_in') ?? now()->format('Y-m-d') }}"
                                class="w-full pl-10 pr-3 h-11 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-sm">
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex justify-between items-center">
                    <button
                        type="button"
                        onclick="clearAllFilters()"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        Reset
                    </button>
                    <button
                        type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        Cari Kamar
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden text-center py-16">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-teal-600"></div>
            <p class="mt-4 text-gray-600">Mencari kamar tersedia...</p>
        </div>

        <!-- Results Container -->
        <div id="resultsContainer"></div>

        <!-- Pagination Container -->
        <div id="paginationContainer" class="mt-10"></div>
    </main>

    @include('components.homepage.footer')

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-6 right-6 w-12 h-12 bg-teal-600 hover:bg-teal-700 text-white rounded-full shadow-lg flex items-center justify-center opacity-0 invisible transition-all duration-300 z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script>
        // Global variables
        let currentPage = 1;
        let currentFilters = {};
        let currentView = 'categorical'; // categorical, grid, or list
        let allRoomsData = {}; // Store data for categorical view
        const apiUrl = '{{ route("api.search.rooms") }}';
        const apiKey = '{{ env("API_KEY") }}';
        const adminUrl = '{{ env("ADMIN_URL") }}';

        // Fallback images as data URIs
        const propertyPlaceholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="100" height="100"%3E%3Crect width="100" height="100" fill="%23f3f4f6"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="12" fill="%239ca3af"%3ENo Image%3C/text%3E%3C/svg%3E';
        const roomPlaceholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="400" height="300"%3E%3Crect width="400" height="300" fill="%23f3f4f6"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="16" fill="%239ca3af"%3ENo Image%3C/text%3E%3C/svg%3E';

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize filters from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            currentFilters = {
                type: urlParams.get('type') || '{{ request("type") }}' || '',
                period: urlParams.get('period') || '{{ request("period") }}' || 'monthly',
                check_in: urlParams.get('check_in') || '{{ request("check_in") }}' || '',
                check_out: urlParams.get('check_out') || '{{ request("check_out") }}' || ''
            };

            // Set form values
            if (currentFilters.type) document.getElementById('filterType').value = currentFilters.type;
            if (currentFilters.period) document.getElementById('filterPeriod').value = currentFilters.period;
            if (currentFilters.check_in) document.getElementById('filterCheckIn').value = currentFilters.check_in;
            if (currentFilters.check_out) document.getElementById('filterCheckOut').value = currentFilters.check_out;

            // Initialize view
            updateViewButtons();

            // Load initial results
            if (currentView === 'categorical') {
                searchRoomsCategorical();
            } else {
                searchRooms();
            }

            // Back to Top Button
            const backToTopBtn = document.getElementById('backToTop');
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.remove('opacity-0', 'invisible');
                    backToTopBtn.classList.add('opacity-100', 'visible');
                } else {
                    backToTopBtn.classList.remove('opacity-100', 'visible');
                    backToTopBtn.classList.add('opacity-0', 'invisible');
                }
            });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // Set minimum checkout date based on check-in date
            const checkInInput = document.getElementById('filterCheckIn');
            const checkOutInput = document.getElementById('filterCheckOut');

            if (checkInInput && checkOutInput) {
                checkInInput.addEventListener('change', function() {
                    if (this.value) {
                        checkOutInput.min = this.value;
                        if (checkOutInput.value && new Date(checkOutInput.value) < new Date(this.value)) {
                            checkOutInput.value = this.value;
                        }
                    }
                });
            }

            // Handle form submission
            document.getElementById('searchForm').addEventListener('submit', function(e) {
                e.preventDefault();
                currentPage = 1;
                currentFilters = {
                    type: document.getElementById('filterType').value,
                    period: document.getElementById('filterPeriod').value,
                    check_in: document.getElementById('filterCheckIn').value,
                    check_out: document.getElementById('filterCheckOut').value
                };
                updateURL();

                if (currentView === 'categorical') {
                    searchRoomsCategorical();
                } else {
                    searchRooms();
                }
            });
        });

        // Set view function
        function setView(view) {
            currentView = view;
            updateViewButtons();

            // Show/hide period filter based on view
            const periodContainer = document.getElementById('periodFilterContainer');
            if (view === 'categorical') {
                periodContainer.style.display = 'none';
                searchRoomsCategorical();
            } else {
                periodContainer.style.display = 'block';
                searchRooms();
            }
        }

        // Update view buttons
        function updateViewButtons() {
            const buttons = {
                categorical: document.getElementById('viewCategorical'),
                grid: document.getElementById('viewGrid'),
                list: document.getElementById('viewList')
            };

            Object.keys(buttons).forEach(key => {
                if (key === currentView) {
                    buttons[key].classList.add('bg-white', 'text-teal-600', 'shadow-sm');
                    buttons[key].classList.remove('text-gray-600');
                } else {
                    buttons[key].classList.remove('bg-white', 'text-teal-600', 'shadow-sm');
                    buttons[key].classList.add('text-gray-600');
                }
            });

            // Show/hide period filter
            const periodContainer = document.getElementById('periodFilterContainer');
            if (currentView === 'categorical') {
                periodContainer.style.display = 'none';
            } else {
                periodContainer.style.display = 'block';
            }
        }

        // Search rooms via API
        async function searchRooms(page = 1) {
            const loadingIndicator = document.getElementById('loadingIndicator');
            const resultsContainer = document.getElementById('resultsContainer');
            const paginationContainer = document.getElementById('paginationContainer');

            // Show loading
            loadingIndicator.classList.remove('hidden');
            resultsContainer.innerHTML = '';
            paginationContainer.innerHTML = '';

            try {
                // Build query parameters
                const params = new URLSearchParams();
                if (currentFilters.type) params.append('type', currentFilters.type);
                if (currentFilters.period) params.append('period', currentFilters.period);
                if (currentFilters.check_in) params.append('check_in', currentFilters.check_in);
                if (currentFilters.check_out) params.append('check_out', currentFilters.check_out);
                params.append('page', page);
                params.append('per_page', 12);

                const response = await fetch(`${apiUrl}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-API-KEY': apiKey,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok || data.status === 'error') {
                    throw new Error(data.message || 'Failed to fetch rooms');
                }

                // Hide loading
                loadingIndicator.classList.add('hidden');

                // Update active filters display
                updateActiveFilters();

                // Render results
                if (data.data && data.data.length > 0) {
                    renderResults(data.data);
                    renderPagination(data.meta);
                } else {
                    renderNoResults();
                }

            } catch (error) {
                console.error('Error fetching rooms:', error);
                loadingIndicator.classList.add('hidden');
                resultsContainer.innerHTML = `
                    <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-700 mb-2">Terjadi Kesalahan</h3>
                            <p class="text-gray-500 mb-6">${error.message}</p>
                            <button onclick="searchRooms()" class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-sync-alt mr-2"></i> Coba Lagi
                            </button>
                        </div>
                    </div>
                `;
            }
        }

        // Search rooms categorical (both daily and monthly)
        async function searchRoomsCategorical() {
            const loadingIndicator = document.getElementById('loadingIndicator');
            const resultsContainer = document.getElementById('resultsContainer');
            const paginationContainer = document.getElementById('paginationContainer');

            loadingIndicator.classList.remove('hidden');
            resultsContainer.innerHTML = '';
            paginationContainer.innerHTML = '';

            try {
                // Fetch both daily and monthly rooms
                const [dailyData, monthlyData] = await Promise.all([
                    fetchRoomsByPeriod('daily'),
                    fetchRoomsByPeriod('monthly')
                ]);

                loadingIndicator.classList.add('hidden');
                updateActiveFilters();

                // Render categorical view
                renderCategoricalView(dailyData, monthlyData);

            } catch (error) {
                console.error('Error fetching rooms:', error);
                loadingIndicator.classList.add('hidden');
                resultsContainer.innerHTML = `
                    <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-700 mb-2">Terjadi Kesalahan</h3>
                            <p class="text-gray-500 mb-6">${error.message}</p>
                            <button onclick="searchRoomsCategorical()" class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-sync-alt mr-2"></i> Coba Lagi
                            </button>
                        </div>
                    </div>
                `;
            }
        }

        // Fetch rooms by period
        async function fetchRoomsByPeriod(period) {
            const params = new URLSearchParams();
            if (currentFilters.type) params.append('type', currentFilters.type);
            params.append('period', period);
            if (currentFilters.check_in) params.append('check_in', currentFilters.check_in);
            if (currentFilters.check_out) params.append('check_out', currentFilters.check_out);
            params.append('per_page', 100); // Get all for categorical view

            const response = await fetch(`${apiUrl}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-API-KEY': apiKey,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            if (!response.ok || data.status === 'error') {
                throw new Error(data.message || 'Failed to fetch rooms');
            }

            return data.data || [];
        }

        // Render categorical view
        function renderCategoricalView(dailyProperties, monthlyProperties) {
            const resultsContainer = document.getElementById('resultsContainer');
            let html = '<div class="space-y-8">';

            // Daily Section
            if (dailyProperties && dailyProperties.length > 0) {
                html += `
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-day text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Sewa Harian</h2>
                                <p class="text-sm text-gray-600">${dailyProperties.length} properti tersedia</p>
                            </div>
                        </div>
                        <div class="space-y-6">
                            ${dailyProperties.map(property => renderPropertyCard(property, 'daily')).join('')}
                        </div>
                    </div>
                `;
            }

            // Monthly Section
            if (monthlyProperties && monthlyProperties.length > 0) {
                html += `
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Sewa Bulanan</h2>
                                <p class="text-sm text-gray-600">${monthlyProperties.length} properti tersedia</p>
                            </div>
                        </div>
                        <div class="space-y-6">
                            ${monthlyProperties.map(property => renderPropertyCard(property, 'monthly')).join('')}
                        </div>
                    </div>
                `;
            }

            // No results
            if ((!dailyProperties || dailyProperties.length === 0) && (!monthlyProperties || monthlyProperties.length === 0)) {
                html += renderNoResults();
            }

            html += '</div>';
            resultsContainer.innerHTML = html;
        }

        // Render property card (for categorical view)
        function renderPropertyCard(property, period) {
            const periodLabel = period === 'daily' ? 'hari' : 'bulan';
            const propertyRoute = getPropertyRoute(property);
            const thumbnail = getPropertyThumbnail(property);

            return `
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <a href="${propertyRoute}" class="text-xl font-bold text-gray-800 hover:text-teal-600 transition-colors">
                                        ${property.name}
                                    </a>
                                    <span class="bg-teal-100 text-teal-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                        ${property.tags}
                                    </span>
                                </div>
                                <div class="flex items-center text-gray-600 text-sm mb-2">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span>${property.address}</span>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span>
                                        <i class="fas fa-door-open mr-1"></i>
                                        ${property.available_rooms_count} kamar tersedia
                                    </span>
                                    ${property.lowest_price ? `
                                        <span>
                                            <i class="fas fa-tag mr-1"></i>
                                            Mulai dari <strong class="text-teal-600">Rp ${formatRupiah(property.lowest_price)}</strong>/${periodLabel}
                                        </span>
                                    ` : ''}
                                </div>
                            </div>
                            ${thumbnail ? `
                                <a href="${propertyRoute}" class="hidden md:block flex-shrink-0">
                                    <img src="${adminUrl}/storage/${thumbnail}"
                                        alt="${property.name}"
                                        class="w-24 h-24 object-cover rounded-lg"
                                        onerror="this.onerror=null; this.src=propertyPlaceholder;">
                                </a>
                            ` : ''}
                        </div>
                    </div>
                    <div class="p-5 bg-gray-50">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            ${property.available_rooms.map(room => renderRoomCard(room, property)).join('')}
                        </div>
                    </div>
                </div>
            `;
        }

        // Render results (grid or list view)
        function renderResults(properties) {
            const resultsContainer = document.getElementById('resultsContainer');
            const periodLabel = currentFilters.period === 'daily' ? 'hari' : 'bulan';

            if (currentView === 'list') {
                renderListView(properties);
                return;
            }

            let html = '<div class="space-y-8">';

            properties.forEach(property => {
                const propertyRoute = getPropertyRoute(property);
                const thumbnail = getPropertyThumbnail(property);

                html += `
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Property Header -->
                        <div class="p-5 border-b border-gray-100">
                            <div class="flex items-start justify-between gap-4">
                                <!-- Property Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <a href="${propertyRoute}" class="text-xl font-bold text-gray-800 hover:text-teal-600 transition-colors">
                                            ${property.name}
                                        </a>
                                        <span class="bg-teal-100 text-teal-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                            ${property.tags}
                                        </span>
                                    </div>

                                    <div class="flex items-center text-gray-600 text-sm mb-2">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <span>${property.address}</span>
                                    </div>

                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span>
                                            <i class="fas fa-door-open mr-1"></i>
                                            ${property.available_rooms_count} kamar tersedia
                                        </span>
                                        ${property.lowest_price ? `
                                            <span>
                                                <i class="fas fa-tag mr-1"></i>
                                                Mulai dari <strong class="text-teal-600">Rp ${formatRupiah(property.lowest_price)}</strong>/${periodLabel}
                                            </span>
                                        ` : ''}
                                    </div>
                                </div>

                                <!-- Property Image -->
                                ${thumbnail ? `
                                    <a href="${propertyRoute}" class="hidden md:block flex-shrink-0">
                                        <img src="${adminUrl}/storage/${thumbnail}"
                                            alt="${property.name}"
                                            class="w-24 h-24 object-cover rounded-lg"
                                            onerror="this.onerror=null; this.src=propertyPlaceholder;">
                                    </a>
                                ` : ''}
                            </div>
                        </div>

                        <!-- Available Rooms Grid -->
                        <div class="p-5 bg-gray-50">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                ${property.available_rooms.map(room => renderRoomCard(room, property)).join('')}
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            resultsContainer.innerHTML = html;
        }

        // Render list view
        function renderListView(properties) {
            const resultsContainer = document.getElementById('resultsContainer');
            const periodLabel = currentFilters.period === 'daily' ? 'hari' : 'bulan';

            let html = '<div class="space-y-4">';

            properties.forEach(property => {
                const propertyRoute = getPropertyRoute(property);
                const thumbnail = getPropertyThumbnail(property);

                property.available_rooms.forEach(room => {
                    const roomRoute = `/rooms/${room.slug || room.id}`;
                    const roomThumbnail = getRoomThumbnail(room, property);

                    html += `
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
                            <div class="flex flex-col md:flex-row">
                                <!-- Room Image -->
                                <a href="${roomRoute}" class="room-link md:w-48 flex-shrink-0">
                                    <div class="relative aspect-[4/3] md:aspect-square bg-gray-100">
                                        ${roomThumbnail ? `
                                            <img src="${adminUrl}/storage/${roomThumbnail}"
                                                alt="${room.name}"
                                                class="w-full h-full object-cover"
                                                onerror="this.onerror=null; this.src=roomPlaceholder;">
                                        ` : `
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i class="fas fa-door-open text-4xl"></i>
                                            </div>
                                        `}
                                        ${currentFilters.check_in && currentFilters.check_out ? `
                                            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs px-3 py-1.5 rounded-full font-medium shadow-md">
                                                <i class="fas fa-check mr-1"></i>Tersedia
                                            </span>
                                        ` : ''}
                                    </div>
                                </a>

                                <!-- Room Info -->
                                <div class="flex-1 p-5">
                                    <div class="flex flex-col h-full">
                                        <!-- Property Badge -->
                                        <div class="flex items-center gap-2 mb-2">
                                            <a href="${propertyRoute}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                                                <i class="fas fa-building mr-1"></i>${property.name}
                                            </a>
                                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded">
                                                ${property.tags}
                                            </span>
                                        </div>

                                        <!-- Room Name -->
                                        <a href="${roomRoute}" class="room-link">
                                            <h3 class="text-xl font-bold text-gray-800 mb-2 hover:text-teal-600 transition-colors">
                                                ${room.name}
                                            </h3>
                                        </a>

                                        <!-- Location -->
                                        <div class="flex items-center text-gray-600 text-sm mb-3">
                                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                            <span>${property.address}</span>
                                        </div>

                                        <!-- Room Details -->
                                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                                            ${room.bed_count ? `
                                                <span><i class="fas fa-bed mr-1"></i>${room.bed_count} kasur</span>
                                            ` : ''}
                                            ${room.room_size ? `
                                                <span><i class="fas fa-expand-arrows-alt mr-1"></i>${room.room_size}m²</span>
                                            ` : ''}
                                        </div>

                                        <!-- Price and Action -->
                                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-100">
                                            <div>
                                                <div class="text-2xl font-bold text-teal-600">
                                                    Rp ${formatRupiah(room.current_price)}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    per ${periodLabel}
                                                </div>
                                            </div>
                                            <a href="${roomRoute}" class="room-link bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors duration-200 flex items-center">
                                                Lihat Detail
                                                <i class="fas fa-arrow-right ml-2 text-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            });

            html += '</div>';
            resultsContainer.innerHTML = html;
        }

        // Render room card
        function renderRoomCard(room, property) {
            const roomRoute = `/rooms/${room.slug || room.id}`;
            const roomThumbnail = getRoomThumbnail(room, property);
            const periodLabel = room.current_period === 'daily' ? 'hari' : 'bulan';

            return `
                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200">
                    <!-- Room Image -->
                    <a href="${roomRoute}" class="room-link block overflow-hidden">
                        <div class="relative aspect-[4/3] bg-gray-100">
                            ${roomThumbnail ? `
                                <img src="${adminUrl}/storage/${roomThumbnail}"
                                    alt="${room.name}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                    onerror="this.onerror=null; this.src=roomPlaceholder;">
                            ` : `
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-door-open text-3xl"></i>
                                </div>
                            `}

                            ${currentFilters.check_in && currentFilters.check_out ? `
                                <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium shadow-md">
                                    <i class="fas fa-check mr-1"></i>Tersedia
                                </span>
                            ` : ''}
                        </div>
                    </a>

                    <!-- Room Info -->
                    <div class="p-3">
                        <a href="${roomRoute}" class="room-link">
                            <h4 class="font-semibold text-gray-800 mb-1 line-clamp-1 hover:text-teal-600 transition-colors">
                                ${room.name}
                            </h4>
                        </a>

                        ${room.bed_count || room.room_size ? `
                            <div class="flex items-center gap-3 text-xs text-gray-500 mb-2">
                                ${room.bed_count ? `<span><i class="fas fa-bed mr-1"></i>${room.bed_count}</span>` : ''}
                                ${room.room_size ? `<span><i class="fas fa-expand-arrows-alt mr-1"></i>${room.room_size}m²</span>` : ''}
                            </div>
                        ` : ''}

                        <!-- Price -->
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                            <div>
                                <div class="text-lg font-bold text-teal-600">
                                    Rp ${formatRupiah(room.current_price)}
                                </div>
                                <div class="text-xs text-gray-500">
                                    /${periodLabel}
                                </div>
                            </div>
                            <a href="${roomRoute}" class="room-link text-teal-600 hover:text-teal-700 text-sm font-medium">
                                Lihat <i class="fas fa-arrow-right text-xs ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }

        // Render no results
        function renderNoResults() {
            const resultsContainer = document.getElementById('resultsContainer');
            const hasDateFilter = currentFilters.check_in && currentFilters.check_out;

            resultsContainer.innerHTML = `
                <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="max-w-md mx-auto">
                        <div class="w-20 h-20 mx-auto mb-4 text-gray-300">
                            <i class="fas fa-search text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-700 mb-2">Tidak ada kamar tersedia</h3>
                        <p class="text-gray-500 mb-6">
                            ${hasDateFilter
                                ? 'Tidak ada kamar tersedia untuk tanggal yang dipilih. Coba ubah tanggal check-in atau check-out.'
                                : 'Tidak ada kamar yang sesuai dengan kriteria pencarian Anda. Coba ubah filter pencarian.'}
                        </p>
                        <button onclick="clearAllFilters()" class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Reset Filter
                        </button>
                    </div>
                </div>
            `;
        }

        // Render pagination
        function renderPagination(meta) {
            if (meta.last_page <= 1) return;

            const paginationContainer = document.getElementById('paginationContainer');
            let html = '<div class="flex justify-center items-center gap-2">';

            // Previous button
            html += `
                <button
                    onclick="changePage(${meta.current_page - 1})"
                    ${meta.current_page === 1 ? 'disabled' : ''}
                    class="px-4 py-2 border rounded-lg ${meta.current_page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            // Page numbers
            for (let i = 1; i <= meta.last_page; i++) {
                if (i === 1 || i === meta.last_page || (i >= meta.current_page - 2 && i <= meta.current_page + 2)) {
                    html += `
                        <button
                            onclick="changePage(${i})"
                            class="px-4 py-2 border rounded-lg ${i === meta.current_page ? 'bg-teal-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                            ${i}
                        </button>
                    `;
                } else if (i === meta.current_page - 3 || i === meta.current_page + 3) {
                    html += '<span class="px-2">...</span>';
                }
            }

            // Next button
            html += `
                <button
                    onclick="changePage(${meta.current_page + 1})"
                    ${meta.current_page === meta.last_page ? 'disabled' : ''}
                    class="px-4 py-2 border rounded-lg ${meta.current_page === meta.last_page ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;

            html += '</div>';
            paginationContainer.innerHTML = html;
        }

        // Change page
        function changePage(page) {
            currentPage = page;
            searchRooms(page);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Update active filters display
        function updateActiveFilters() {
            const activeFiltersDiv = document.getElementById('activeFilters');
            const filterBadges = document.getElementById('filterBadges');

            const hasFilters = currentFilters.type || currentFilters.check_in || currentFilters.check_out;

            if (!hasFilters) {
                activeFiltersDiv.classList.add('hidden');
                return;
            }

            activeFiltersDiv.classList.remove('hidden');
            let badges = '';

            if (currentFilters.type) {
                badges += `
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1.5 rounded-full flex items-center">
                        ${currentFilters.type}
                        <button onclick="removeFilter('type')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                `;
            }

            if (currentFilters.period && currentFilters.period !== 'monthly') {
                badges += `
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full flex items-center">
                        ${currentFilters.period === 'daily' ? 'Harian' : 'Bulanan'}
                        <button onclick="removeFilter('period')" class="ml-1.5 text-green-600 hover:text-green-800">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                `;
            }

            if (currentFilters.check_in && currentFilters.check_out) {
                badges += `
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-3 py-1.5 rounded-full flex items-center">
                        <i class="far fa-calendar-alt mr-1"></i>
                        ${formatDate(currentFilters.check_in)} - ${formatDate(currentFilters.check_out)}
                        <button onclick="removeFilter('dates')" class="ml-1.5 text-purple-600 hover:text-purple-800">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                `;
            }

            filterBadges.innerHTML = badges;
        }

        // Remove filter
        function removeFilter(filterType) {
            if (filterType === 'type') {
                currentFilters.type = '';
                document.getElementById('filterType').value = '';
            } else if (filterType === 'period') {
                currentFilters.period = 'monthly';
                document.getElementById('filterPeriod').value = 'monthly';
            } else if (filterType === 'dates') {
                currentFilters.check_in = '';
                currentFilters.check_out = '';
                document.getElementById('filterCheckIn').value = '';
                document.getElementById('filterCheckOut').value = '';
            }

            currentPage = 1;
            updateURL();
            searchRooms();
        }

        // Clear all filters
        function clearAllFilters() {
            currentFilters = {
                type: '',
                period: 'monthly',
                check_in: '',
                check_out: ''
            };

            document.getElementById('filterType').value = '';
            document.getElementById('filterPeriod').value = 'monthly';
            document.getElementById('filterCheckIn').value = '';
            document.getElementById('filterCheckOut').value = '';

            currentPage = 1;
            updateURL();
            searchRooms();
        }

        // Update URL
        function updateURL() {
            const params = new URLSearchParams();
            if (currentFilters.type) params.set('type', currentFilters.type);
            if (currentFilters.period) params.set('period', currentFilters.period);
            if (currentFilters.check_in) params.set('check_in', currentFilters.check_in);
            if (currentFilters.check_out) params.set('check_out', currentFilters.check_out);

            const newUrl = params.toString() ? `${window.location.pathname}?${params.toString()}` : window.location.pathname;
            window.history.pushState({}, '', newUrl);
        }

        // Helper functions
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }

        function getPropertyRoute(property) {
            const routes = {
                'Kos': `/houses/${property.id}`,
                'Apartment': `/apartments/${property.slug || property.id}`,
                'Villa': `/villas/${property.slug || property.id}`,
                'Hotel': `/hotels/${property.slug || property.id}`
            };
            return routes[property.tags] || `/properties/${property.slug || property.id}`;
        }

        function getPropertyThumbnail(property) {
            if (property.images && property.images.length > 0) {
                for (let image of property.images) {
                    if (image.image) return image.image;
                }
            }
            return property.image || null;
        }

        function getRoomThumbnail(room, property) {
            if (room.images && room.images.length > 0) {
                for (let image of room.images) {
                    if (image.image) return image.image;
                }
            }
            if (room.image) return room.image;
            return getPropertyThumbnail(property);
        }
    </script>

    @stack('scripts')
</body>
</html>
