<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $promo['title'] }} - Promo Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Styles -->
    @include('components.property.styles')
    <style>
        .promo-hero {
            --transition: all 0.3s ease;
        }

        .promo-hero img {
            transition: var(--transition);
        }

        .promo-hero:hover img {
            transform: scale(1.05);
        }

        .badge {
            transform: translateY(-5px);
            opacity: 0;
            transition: var(--transition);
        }

        .promo-hero:hover .badge {
            transform: translateY(0);
            opacity: 1;
        }

        .cta-button {
            transition: var(--transition);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="font-inter antialiased bg-white text-gray-900 tracking-tight">
    <!-- Header -->
    @include('components.homepage.header')

    <main>
        <div class="container mx-auto px-4 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('homepage') }}" class="hover:text-gray-700">Home</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('promos.index') }}" class="hover:text-gray-700">Promos</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900">{{ $promo['title'] }}</li>
                </ol>
            </nav>

            <!-- Promo Content -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Hero Section -->
                <div class="relative h-96 promo-hero overflow-hidden">
                    <img src="data:image/png;base64,{{ $promo['image'] }}" 
                         alt="{{ $promo['title'] }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8 text-white">
                        <span class="badge bg-yellow-400 text-xs px-3 py-1 rounded-full text-gray-800 font-medium mb-4 inline-block">
                            {{ $promo['badge'] }}
                        </span>
                        <h1 class="text-4xl font-bold mb-2">{{ $promo['title'] }}</h1>
                        <p class="text-xl opacity-90">{{ $promo['description'] }}</p>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid md:grid-cols-2 gap-8 p-8">
                    <!-- Left Column -->
                    <div>
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold mb-4">Harga Spesial</h2>
                            <div class="space-y-2">
                                <p class="text-gray-500 line-through">Rp {{ number_format($promo['original_price'], 0, ',', '.') }}</p>
                                <p class="text-3xl font-bold text-teal-600">Rp {{ number_format($promo['discounted_price'], 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold mb-4">Cara Klaim</h2>
                            <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                @foreach($promo['how_to_claim'] as $step)
                                    <li class="flex items-center">
                                        <span class="mr-2">{{ $loop->iteration }}.</span>
                                        <span>{{ $step }}</span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold mb-4">Syarat & Ketentuan</h2>
                            <ul class="list-disc list-inside space-y-2 text-gray-600">
                                @foreach($promo['terms_conditions'] as $term)
                                    <li class="flex">
                                        <i class="fas fa-check text-teal-500 mt-1 mr-2"></i>
                                        <span>{{ $term }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold mb-4">Periode Promo</h2>
                            <div class="flex items-center text-gray-600">
                                <i class="far fa-calendar-alt text-teal-500 mr-2"></i>
                                <p>Berlaku sampai {{ \Carbon\Carbon::parse($promo['valid_until'])->format('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="bg-gray-50 p-8 text-center">
                    <button class="cta-button bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-8 rounded-lg">
                        Klaim Promo Sekarang
                    </button>
                    <p class="text-gray-500 mt-2">*Syarat dan ketentuan berlaku</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('components.homepage.footer')

    <!-- Include Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @include('components.homepage.scripts')
        });
    </script>
</body>
</html>
