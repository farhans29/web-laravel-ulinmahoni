<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Berjangka - Ulin Mahoni</title>
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
                        Sewa Berjangka
                    </h1>
                    <div class="max-w-3xl mx-auto">
                        <p class="text-xl text-gray-600 mb-8">
                            Solusi penyewaan properti jangka panjang yang fleksibel dan terpercaya
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Sections -->
        <section class="relative pb-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="grid md:grid-cols-2 gap-12 md:gap-20">
                    <!-- Features -->
                    <div>
                        <h3 class="h3 mb-6">Keuntungan Sewa Berjangka</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-teal-500 mr-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="text-xl font-bold mb-1">Harga Lebih Hemat</h4>
                                    <p class="text-gray-600">Dapatkan penawaran khusus untuk penyewaan jangka panjang</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-teal-500 mr-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="text-xl font-bold mb-1">Fleksibel</h4>
                                    <p class="text-gray-600">Pilih periode sewa yang sesuai dengan kebutuhan Anda</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-teal-500 mr-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="text-xl font-bold mb-1">Terpercaya</h4>
                                    <p class="text-gray-600">Properti terverifikasi dengan jaminan kualitas terbaik</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- How it Works -->
                    <div>
                        <h3 class="h3 mb-6">Cara Kerja</h3>
                        <div class="space-y-8">
                            <div class="flex items-center">
                                <span class="flex-shrink-0 w-10 h-10 rounded-full bg-teal-100 text-teal-500 flex items-center justify-center font-bold text-lg">1</span>
                                <div class="ml-4">
                                    <h4 class="text-xl font-bold mb-1">Pilih Properti</h4>
                                    <p class="text-gray-600">Temukan properti yang sesuai dengan kebutuhan Anda</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="flex-shrink-0 w-10 h-10 rounded-full bg-teal-100 text-teal-500 flex items-center justify-center font-bold text-lg">2</span>
                                <div class="ml-4">
                                    <h4 class="text-xl font-bold mb-1">Tentukan Periode</h4>
                                    <p class="text-gray-600">Pilih periode sewa yang Anda inginkan</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="flex-shrink-0 w-10 h-10 rounded-full bg-teal-100 text-teal-500 flex items-center justify-center font-bold text-lg">3</span>
                                <div class="ml-4">
                                    <h4 class="text-xl font-bold mb-1">Lakukan Pembayaran</h4>
                                    <p class="text-gray-600">Proses pembayaran yang aman dan mudah</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="text-center mt-16">
                    <a href="{{ route('homepage') }}" class="btn text-white bg-teal-600 hover:bg-teal-700 rounded-lg px-8 py-4 text-lg font-semibold">
                        Mulai Mencari Properti
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    @include('components.homepage.footer')
</body>
</html> 