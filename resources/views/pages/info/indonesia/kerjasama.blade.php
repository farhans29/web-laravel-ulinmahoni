<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerjasama - Ulin Mahoni</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-inter antialiased bg-white text-gray-900 tracking-tight">
    <!-- Header -->
    @include('components.homepage.header')

    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="relative pt-32 pb-12 md:pt-40 md:pb-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="text-center pb-12 md:pb-16">
                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tighter tracking-tighter mb-4">
                        Kerjasama Ulin Mahoni
                    </h1>
                    <div class="max-w-3xl mx-auto">
                        <p class="text-xl text-gray-600 mb-8">
                            Mari bergabung dan kembangkan bisnis properti Anda bersama kami
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Sections -->
        <section class="relative pb-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <!-- Partnership Types -->
                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <!-- Property Owner -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Pemilik Properti</h3>
                        <p class="text-gray-600 mb-4">Daftarkan properti Anda dan dapatkan akses ke pasar yang lebih luas</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Manajemen properti profesional
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Pemasaran yang optimal
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Pembayaran terjamin
                            </li>
                        </ul>
                    </div>

                    <!-- Agent -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Agen Properti</h3>
                        <p class="text-gray-600 mb-4">Bergabunglah sebagai agen dan tingkatkan pendapatan Anda</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Komisi kompetitif
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Pelatihan profesional
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Akses ke properti eksklusif
                            </li>
                        </ul>
                    </div>

                    <!-- Investor -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Investor</h3>
                        <p class="text-gray-600 mb-4">Investasikan dana Anda dalam properti potensial</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Return investasi tinggi
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Portofolio terverifikasi
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-teal-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Manajemen risiko profesional
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center">
                    <h2 class="text-3xl font-bold mb-4">Siap Bergabung?</h2>
                    <p class="text-gray-600 mb-8">Hubungi tim kami untuk informasi lebih lanjut</p>
                    <a href="#" class="btn text-white bg-teal-600 hover:bg-teal-700 rounded-lg px-8 py-4 text-lg font-semibold">
                        Mulai Kerjasama
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    @include('components.homepage.footer')
</body>
</html> 