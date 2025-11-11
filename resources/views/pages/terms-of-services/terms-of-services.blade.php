<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat dan Ketentuan - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @include('components.property.styles')
    <style>
        .policy-section {
            margin-bottom: 2.5rem;
        }
        .policy-section h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
        }
        .policy-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 1.5rem 0 0.75rem 0;
        }
        .policy-content {
            color: #4b5563;
            line-height: 1.75;
            margin-bottom: 1rem;
        }
        .policy-list {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin: 1rem 0;
        }
        .policy-list li {
            margin-bottom: 0.5rem;
        }
        .policy-sub-list {
            list-style-type: lower-latin;
            padding-left: 1.5rem;
            margin: 0.5rem 0;
        }
    </style>
</head>
<body class="font-inter antialiased bg-white text-gray-900 tracking-tight">
    <!-- Header -->
    @include('components.homepage.header')

    <main class="py-12">
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('homepage') }}" class="hover:text-gray-700">Beranda</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900">Syarat dan Ketentuan</li>
                </ol>
            </nav>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 md:p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">SYARAT DAN KETENTUAN</h1>
                
                <div class="prose max-w-none">
                    <div class="policy-section">
                        <p class="policy-content">Selamat datang di Ulin Mahoni dan terima kasih telah mengunjungi Ulin Mahoni, baik melalui situs web maupun aplikasi. Anda diharuskan untuk membaca, memahami, dan mencermati Syarat dan Ketentuan Penggunaan ini (selanjutnya disebut "S&K"). S&K ini merupakan bagian yang tidak terpisahkan dari Kebijakan Privasi dan memiliki kekuatan hukum yang mengikat.</p>
                        <p class="policy-content">Dengan mengunjungi, menggunakan, mengakses dan/atau mendaftar di Ulin Mahoni, maka Anda dianggap telah membaca, memahami, mencermati dan menyetujui keseluruhan isi S&K ini tanpa terkecuali dengan ketentuan sebagai berikut:</p>
                    </div>

                    <!-- Add your Terms of Service content here -->
                    <div class="policy-section">
                        <h2>A. Definisi</h2>
                        <p class="policy-content">Istilah-istilah di bawah ini memiliki pengertian sebagai berikut:</p>
                        <ol class="policy-list">
                            <li class="policy-content"><strong>Pengunjung</strong> adalah perorangan dan/atau badan usaha, baik yang berbadan hukum maupun yang tidak berbadan hukum, yang mengunjungi atau mengakses Ulin Mahoni.</li>
                            <li class="policy-content"><strong>Pemilik Akun</strong> adalah perorangan dan/atau badan usaha, baik yang berbadan hukum maupun yang tidak berbadan hukum, yang mengakses, menggunakan dan terdaftar sebagai pemilik akun Ulin Mahoni.</li>
                            <li class="policy-content"><strong>Ulin Mahoni</strong> adalah situs web www.ulinmahoni.com dan/atau aplikasi Ulin Mahoni. Situs web dan/atau aplikasi tersebut dimiliki, dikelola, dan dioperasikan oleh KAP.</li>
                            <li class="policy-content"><strong>KAP</strong> adalah PT Kelola Aset Properti yang merupakan badan hukum berbentuk Perseroan Terbatas, yang didirikan berdasarkan ketentuan perundang-undangan yang berlaku di Negara Kesatuan Republik Indonesia, sebagai pemilik, pengelola dan pengoperasi Ulin Mahoni.</li>
                            <li class="policy-content"><strong>Properti</strong> adalah bangunan milik Pemilik yang disewakan melalui Ulin Mahoni termasuk namun tidak terbatas pada rumah kos, kamar kos, rumah kontrakan, unit apartemen dan/atau rumah.</li>
                            <li class="policy-content"><strong>Penyewa</strong> adalah Pemilik Akun yang menyewa dan/atau menempati 1 (satu) atau lebih dari 1 (satu) Properti melalui Ulin Mahoni.</li>
                            <li class="policy-content"><strong>Pemilik</strong> adalah setiap orang dan/atau badan usaha, baik yang berbadan hukum maupun yang tidak berbadan hukum, yang secara hukum memiliki properti berdasarkan peraturan perundang-undangan Negara Kesatuan Republik Indonesia.</li>
                            <li class="policy-content"><strong>Tanggal Masuk</strong> adalah tanggal dimulainya Penyewa untuk pindah (check in) dan menempati Properti.</li>
                            <li class="policy-content"><strong>Tanggal Keluar</strong> adalah tanggal Penyewa melakukan check out dari Properti.</li>
                            <li class="policy-content"><strong>Jangka Waktu</strong> adalah jangka waktu sewa Properti.</li>
                            <li class="policy-content"><strong>Biaya Layanan</strong> adalah biaya yang dibebankan kepada Pemilik Akun dan dimaksudkan untuk memungkinkan Ulin Mahoni meningkatkan layanan dengan memelihara dan mengembangkan sistem Ulin Mahoni.</li>
                            <li class="policy-content"><strong>Perjanjian</strong> adalah perjanjian penggunaan Ulin Mahoni dan/atau sewa-menyewa Properti yang dilakukan, disepakati dan disetujui antara Penyewa dengan KAP.</li>
                        </ol>
                    </div>

                    <!-- Continue with other sections as needed -->

                    <div class="policy-section">
                        <h2>B. Ketentuan Umum</h2>
                        <ol class="policy-list">
                            <li class="policy-content">Dengan mengakses dan/atau menggunakan Ulin Mahoni, maka Anda menyatakan bahwa Anda telah membaca, memahami, dan menyetujui untuk terikat dengan seluruh ketentuan dalam S&K ini.</li>
                            <li class="policy-content">KAP berhak untuk mengubah, memodifikasi, menambah, atau menghapus bagian dari S&K ini dari waktu ke waktu tanpa pemberitahuan terlebih dahulu. Perubahan tersebut akan berlaku segera setelah diposting di Ulin Mahoni.</li>
                            <li class="policy-content">Dengan tetap mengakses atau menggunakan Ulin Mahoni setelah adanya perubahan tersebut, maka Anda dianggap telah menyetujui perubahan-perubahan dalam S&K.</li>
                        </ol>
                    </div>

                    <!-- Add more sections as needed -->

                    <div class="pt-6 border-t border-gray-100 mt-8">
                        <p class="text-sm text-gray-500">Terakhir diperbarui: {{ date('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('components.homepage.footer')

    @stack('scripts')
</body>
</html>