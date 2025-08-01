<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulin Mahoni for Business - Solusi Properti untuk Bisnis</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Styles -->
    @include('components.property.styles')
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
                        Ulin Mahoni for Business
                    </h1>
                    <div class="max-w-3xl mx-auto">
                        <p class="text-xl text-gray-600 mb-8">
                            Solusi properti lengkap untuk kebutuhan bisnis Anda
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Sections -->
        <section class="relative pb-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <!-- Business Solutions -->
                <div class="grid md:grid-cols-2 gap-12 mb-16">
                    <!-- Corporate Housing -->
                    <div class="bg-white p-8 rounded-lg shadow-md">
                        <h3 class="text-2xl font-bold mb-4">Corporate Housing</h3>
                        <p class="text-gray-600 mb-6">Solusi akomodasi untuk karyawan dan tamu perusahaan Anda</p>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-teal-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold mb-1">Fleksibilitas Tinggi</h4>
                                    <p class="text-gray-600">Pilihan periode sewa yang dapat disesuaikan</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-teal-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold mb-1">Lokasi Strategis</h4>
                                    <p class="text-gray-600">Dekat dengan pusat bisnis dan fasilitas penting</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-teal-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold mb-1">Layanan Lengkap</h4>
                                    <p class="text-gray-600">Termasuk perawatan dan manajemen properti</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Office Space -->
                    <div class="bg-white p-8 rounded-lg shadow-md">
                        <h3 class="text-2xl font-bold mb-4">Ruang Kantor</h3>
                        <p class="text-gray-600 mb-6">Ruang kerja yang dapat disesuaikan dengan kebutuhan bisnis</p>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-teal-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold mb-1">Ruang Fleksibel</h4>
                                    <p class="text-gray-600">Dapat disesuaikan dengan ukuran tim</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-teal-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold mb-1">Fasilitas Modern</h4>
                                    <p class="text-gray-600">Internet cepat dan ruang meeting</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-teal-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold mb-1">Lokasi Premium</h4>
                                    <p class="text-gray-600">Di area bisnis strategis</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Benefits Section -->
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold mb-8">Keuntungan untuk Bisnis Anda</h2>
                    <div class="grid md:grid-cols-4 gap-6">
                        <div class="p-4">
                            <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold mb-2">Efisiensi Biaya</h3>
                            <p class="text-gray-600 text-sm">Optimalisasi pengeluaran properti</p>
                        </div>
                        <div class="p-4">
                            <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold mb-2">Proses Cepat</h3>
                            <p class="text-gray-600 text-sm">Administrasi yang efisien</p>
                        </div>
                        <div class="p-4">
                            <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold mb-2">Keamanan</h3>
                            <p class="text-gray-600 text-sm">Jaminan keamanan properti</p>
                        </div>
                        <div class="p-4">
                            <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold mb-2">Dukungan 24/7</h3>
                            <p class="text-gray-600 text-sm">Tim support selalu siap</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center">
                    <h2 class="text-3xl font-bold mb-4">Mulai Sekarang</h2>
                    <p class="text-gray-600 mb-8">Hubungi tim bisnis kami untuk solusi yang sesuai dengan kebutuhan perusahaan Anda</p>
                    <a href="#" class="btn text-white bg-teal-600 hover:bg-teal-700 rounded-lg px-8 py-4 text-lg font-semibold">
                        Konsultasi Gratis
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    @include('components.homepage.footer')
</body>
</html> 