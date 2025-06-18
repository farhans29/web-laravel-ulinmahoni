<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - Ulin Mahoni</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @include('components.homepage.styles')
    @livewireStyles
</head>

<body>
    @include('components.homepage.header')

    <main>
        <!-- Hero Section -->
        <div class="hero-section h-72 relative">
            <img src="{{ asset('images/assets/pics/WhatsApp Image 2025-02-20 at 14.30.45.jpeg') }}" 
                alt="Profile Hero" 
                class="w-full h-full object-cover">
            <div class="absolute inset-0 gradient-overlay flex items-center justify-center">
                <h1 class="text-2xl text-white font-medium">My Profile</h1>
            </div>
        </div>

        <!-- Profile Section -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Google Calendar Integration -->
                {{-- 
                
                <div x-data="{ modalOpen: false }" class="mb-8">
                    <button @click="modalOpen = true" 
                        class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-teal-800 focus:outline-none focus:border-teal-800 focus:ring focus:ring-teal-200 disabled:opacity-25 transition">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Set Google Calendar
                    </button>

                    <!-- Modal -->
                    <div class="fixed inset-0 bg-black bg-opacity-50 z-50" x-show="modalOpen" x-cloak></div>
                    <div class="fixed inset-0 z-50 flex items-center justify-center" x-show="modalOpen" x-cloak>
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" @click.away="modalOpen = false">
                            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Google Calendar Integration</h3>
                                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                                <form action="{{ route('update.gcal') }}" method="post" class="p-6">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Calendar ID</label>
                                            <input type="text" name="calendar_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 active:bg-teal-800 focus:outline-none focus:border-teal-800 focus:ring focus:ring-teal-200 disabled:opacity-25 transition">
                                                Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                
                        </div>
                    </div>
                </div>
                --}}

                <!-- Jetstream Profile Information -->
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="p-8">
                            @livewire('profile.update-profile-information-form')
                        </div>
                    </div>
                @endif

                <!-- Password Update -->
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="p-8">
                            @livewire('profile.update-password-form')
                        </div>
                    </div>
                @endif

                <!-- Two Factor Authentication -->
                {{-- @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="p-8">
                            @livewire('profile.two-factor-authentication-form')
                        </div>
                    </div>
                @endif --}}

                <!-- Browser Sessions -->
                 {{-- 
                 <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                     <div class="p-8">
                         @livewire('profile.logout-other-browser-sessions-form')
                     </div>
                 </div>
                 --}}

                <!-- Account Deletion -->
                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-8">
                            @livewire('profile.delete-user-form')
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>

    @include('components.homepage.footer')

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @include('components.homepage.scripts')
        });
    </script>
</body>
</html>
                                      
