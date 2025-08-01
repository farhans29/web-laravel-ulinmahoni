<!-- Header Component -->
<header class="site-header py-4 px-6 flex items-center justify-between bg-white shadow-md sticky top-0 z-50">
    <div class="flex items-center space-x-8">
        <!-- Mobile Menu Button (Hidden on desktop) -->
        <div x-data="{ mobileMenuOpen: false }" class="md:hidden">
            <button @click="mobileMenuOpen = true" class="text-gray-600 hover:text-gray-900 p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Mobile Sidebar -->
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-50"
                 style="display: none;"
                 @click.away="mobileMenuOpen = false">
                <div class="flex items-center justify-between p-4 border-b">
                    <a href="/homepage" class="flex items-center">
                        <img src="{{ asset('images/assets/ulinmahoni-logo.svg') }}" alt="Ulin Mahoni Logo" class="h-8 w-auto">
                    </a>
                    <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <nav class="p-4">
                    <ul class="space-y-3">
                        <li>
                            <a href="/sewa" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">Sewa Berjangka</a>
                        </li>
                        <li>
                            <a href="/kerjasama" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">Kerjasama Ulin Mahoni</a>
                        </li>
                        <li>
                            <a href="/business" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">Ulin Mahoni untuk Bisnis</a>
                        </li>
                        <li>
                            <a href="/tentang" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">Tentang Ulin Mahoni</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- Overlay -->
            <div x-show="mobileMenuOpen" 
                 x-transition.opacity
                 class="fixed inset-0 bg-black bg-opacity-50 z-40"
                 style="display: none;"
                 @click="mobileMenuOpen = false">
            </div>
        </div>

        <!-- Logo for Desktop -->
        <a href="/homepage" class="hidden md:flex items-center">
            <img src="{{ asset('images/assets/ulinmahoni-logo.svg') }}" alt="Ulin Mahoni Logo" class="h-10 w-auto">
        </a>
        
        <!-- Navigation -->
        <nav class="hidden md:flex">
            <ul class="flex space-x-6">
                <li>
                    <a href="/sewa" class="text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        Sewa Berjangka
                    </a>
                </li>
                <li>    
                    <a href="/kerjasama" class="text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        Kerjasama Ulin Mahoni
                    </a>
                </li>
                <li>
                    <a href="/business" class="text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        Ulin Mahoni untuk Bisnis
                    </a>
                </li>
                <li>
                    <a href="/tentang" class="text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        Tentang Ulin Mahoni
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Language -->
    <div class="flex items-center space-x-4">
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M31,8c0-2.209-1.791-4-4-4H5c-2.209,0-4,1.791-4,4v9H31V8Z" fill="#ea3323"></path><path d="M5,28H27c2.209,0,4-1.791,4-4v-8H1v8c0,2.209,1.791,4,4,4Z" fill="#fff"></path><path d="M5,28H27c2.209,0,4-1.791,4-4V8c0-2.209-1.791-4-4-4H5c-2.209,0-4,1.791-4,4V24c0,2.209,1.791,4,4,4ZM2,8c0-1.654,1.346-3,3-3H27c1.654,0,3,1.346,3,3V24c0,1.654-1.346,3-3,3H5c-1.654,0-3-1.346-3-3V8Z" opacity=".15"></path><path d="M27,5H5c-1.657,0-3,1.343-3,3v1c0-1.657,1.343-3,3-3H27c1.657,0,3,1.343,3,3v-1c0-1.657-1.343-3-3-3Z" fill="#fff" opacity=".2"></path></svg>
            <span class="text-sm text-gray-600">ID</span>
        </div>
        @guest
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 px-4 py-2 rounded-lg transition-colors duration-200">Masuk</a>
            <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 px-4 py-2 rounded-lg transition-colors duration-200">Daftar</a>
        @else
            <!-- Profile dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" type="button" class="flex items-center space-x-2 rounded-lg p-1 hover:bg-gray-50 transition-colors duration-200">
                    <!-- <img class="w-8 h-8 rounded-full border-2 border-gray-200" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"> -->
                    <span class="text-sm font-medium text-gray-700">{{ Auth::user()->username }}</span>
                    <svg class="w-4 h-4 text-gray-400" :class="{ 'transform rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    
                </button>

                <!-- Dropdown menu -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                     style="display: none;">
                    <div class="py-1">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->username }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profil
                        </a>
                        <a href="/bookings" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Pemesanan Saya
                        </a>
                        @if(Auth::user()->is_admin)
                        <a href="/admin/dashboard" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            Admin Dashboard
                        </a>
                        @endif
                        <div class="border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    <svg class="mr-3 h-5 w-5 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endguest
    </div>
</header>


<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
