<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulin Mahoni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-white text-gray-900">
    <!-- Topbar -->
    <header
        class="bg-white text-gray-900 p-4 fixed 
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

        <div class="relative w-full">
            <video id="heroVideo" class="w-full h-auto">
                <source src="videos/assets/hero-video.mp4" type="video/mp4">
                Browser Anda tidak mendukung video.
            </video>

            <!-- Tombol Play/Pause -->
            <button id="playPauseBtn"
                class="absolute bottom-5 left-5 bg-white text-gray-800 px-4 py-2 rounded-lg shadow-lg">
                Play
            </button>
        </div>

        <!-- Text Section -->
        <section class="text-section bg-white p-4 mt-16 text-center">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16" style="width: 780px">
                <!-- First Image + Text -->
                <div class="flex flex-col items-center justify-center">
                    <img src="images/assets/luxoria-living-01.png" alt="Image 1" class="w-full">
                    {{-- <div class="mt-4 bg-gray-100 p-6 w-full">
                        <h2 class="text-2xl font-semibold mb-2">Text Box 1</h2>
                        <p class="text-gray-600">Description for the first image.</p>
                    </div> --}}
                </div>

                <!-- Full Text Box -->
                <div class="flex flex-col mt-4 bg-gray-100 p-8 w-full text-center" style="width: 760px">
                    <h2 class="text-4xl font-bold">
                        <span class="text-red-800 font-light">ULIN</span> <span
                            class="text-green-800 font-light">MAHONI</span>
                    </h2>
                    <h3 class="text-xl text-gray-600 font-light italic mt-2">LUXORIA LIVING</h3>
                    <p class="text-gray-600 font-light mt-4 text-justify max-w-3xl mx-auto leading-relaxed">
                        Ulin Mahoni adalah hunian eksklusif yang menghadirkan kemewahan, kenyamanan, dan ketenangan
                        dalam satu tempat. Dengan desain interior yang elegan dan fasilitas premium, setiap sudutnya
                        dirancang untuk memberikan pengalaman tinggal yang istimewa.
                        Suasana yang tenang dan asri menciptakan lingkungan yang harmonis, menjadikan Ulin Mahoni
                        pilihan sempurna bagi mereka yang mengutamakan gaya hidup mewah tanpa mengorbankan kenyamanan.
                        Nikmati keseimbangan antara kemewahan modern dan ketentraman alami, hanya di Ulin Mahoni –
                        tempat di mana tinggal menjadi pengalaman yang lebih dari sekadar menetap. ✨
                    </p>
                </div>

            </div>
            {{-- <div>
                <img src="images\assets\luxoria-living-01.png" alt="">
            </div>
            <div>
                <h2>text boxs</h2>
            </div> --}}
            {{-- <h3 class="text-3xl font-semibold mb-4">Why Choose Us?</h3>
            <p class="text-gray-600 max-w-2xl mx-auto">We offer the best services with top-notch quality and customer satisfaction.</p> --}}
        </section>

        <!-- Gallery Section -->
        <section class="gallery-section grid grid-cols-1 md:grid-cols-3 gap-4 p-8">
            <div class="bg-white p-6 shadow-md rounded-lg">
                <p>Feature 1</p>
            </div>
            <div class="bg-white p-6 shadow-md rounded-lg">
                <p>Feature 2</p>
            </div>
            <div class="bg-white p-6 shadow-md rounded-lg">
                <p>Feature 3</p>
            </div>
        </section>

        <!-- Footer -->
        <footer style="margin-top: 500px" class="bg-gray-800 text-white p-6 text-center mt-8">
            <p>&copy; 2025 Your Brand. All rights reserved.</p>
        </footer>
    </main>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const video = document.getElementById("heroVideo");
        const playPauseBtn = document.getElementById("playPauseBtn");

        // Atur teks tombol saat pertama kali dimuat
        playPauseBtn.textContent = "Play";

        // Event listener untuk tombol Play/Pause
        playPauseBtn.addEventListener("click", function() {
            if (video.paused) {
                video.play();
                playPauseBtn.textContent = "Pause";
            } else {
                video.pause();
                playPauseBtn.textContent = "Play";
            }
        });
    });
</script>

</html>
