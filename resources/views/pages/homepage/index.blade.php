<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulin Mahoni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <!-- Topbar -->
    <header class="bg-white text-gray-900 p-4 fixed 
    w-full top-0 left-0 shadow-md z-10 flex justify-between items-center h-16">
        <h2 class="text-xl font-light text-green-800">Ulin <span class="text-red-800">Mahoni</span> </h2>
        <img src="images\assets\ulinmahoni-logo.svg" alt="" width="45rem">
        <nav>
            <ul class="flex space-x-4">
                <li><a href="#" class="text-green-800">Home</a></li>
                <li><a href="#" class="text-green-800">About</a></li>
                {{-- <li><a href="#" class="text-green-800">Services</a></li>
                <li><a href="#" class="text-green-800">Contact</a></li> --}}
            </ul>
        </nav>
    </header>

    <main class="flex-1 mt-16">
        <!-- Hero Section -->
        <section class="hero-section text-center bg-white-900 text-white">
            <img src="images\assets\hero-01.png" alt="">
            {{-- <h2 class="text-4xl font-bold mb-4 mt-16">Your Modern Solution</h2> --}}
            {{-- <p class="mb-6">Experience innovation with us.</p> --}}
            {{-- <button class="bg-white text-blue-600 px-6 py-2 rounded-full hover:bg-gray-200">Learn More</button> --}}
        </section>

        <!-- Text Section -->
        <section class="text-section p-8 mt-16 text-center">
            <h3 class="text-3xl font-semibold mb-4">Why Choose Us?</h3>
            <p class="text-gray-600 max-w-2xl mx-auto">We offer the best services with top-notch quality and customer satisfaction.</p>
        </section>

        <!-- Gallery Section -->
        <section class="gallery-section grid grid-cols-1 md:grid-cols-3 gap-4 p-8">
            <div class="bg-white p-6 shadow-md rounded-lg"><p>Feature 1</p></div>
            <div class="bg-white p-6 shadow-md rounded-lg"><p>Feature 2</p></div>
            <div class="bg-white p-6 shadow-md rounded-lg"><p>Feature 3</p></div>
        </section>

        <!-- Footer -->
        <footer style="margin-top: 500px" class="bg-gray-800 text-white p-6 text-center mt-8">
            <p>&copy; 2025 Your Brand. All rights reserved.</p>
        </footer>
    </main>
</body>
</html>
