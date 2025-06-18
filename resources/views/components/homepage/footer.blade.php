<!-- Footer Component -->
<footer class="bg-gray-800 text-white py-12 px-4">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
            <h4 class="text-2xl font-bold mb-6 text-white">Ulin Mahoni</h4>
            <p class="text-gray-400 text-sm">
                Tempat tinggal yang nyaman dan modern untuk para pencinta keindahan kota.
            </p>
        </div>

        <div>
            <h4 class="text-xl font-semibold mb-5 text-white">Quick Links</h4>
            <ul class="space-y-2 text-gray-400 text-sm">
                <li>
                    <a href="{{ route('homepage') }}" class="hover:text-white transition">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('properties.index') }}" class="hover:text-white transition">
                        Properti
                    </a>
                </li>
                <!-- <li>
                    <a href="#" class="hover:text-white transition">
                        Rooms
                    </a>
                </li>
                <li>
                    <a href="#" class="hover:text-white transition">
                        Facilities
                    </a>
                </li> -->
            </ul>
        </div>

        <div>
            <h4 class="text-xl font-semibold mb-5 text-white">Kontak Kami</h4>
            <ul class="space-y-2 text-gray-400 text-sm">
                <li>Jl. Luxury Boulevard No. 123</li>
                <li>+62 123 4567 890</li>
                <li>info@ulinmahoni.com</li>
            </ul>
        </div>

        <div>
            <h4 class="text-xl font-semibold mb-5 text-white">Ikuti Kami</h4>
            <div class="flex space-x-4">
                <!-- Facebook -->
                <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors duration-200" aria-label="Facebook">
                    <div class="w-10 h-10 rounded-full bg-gray-800 hover:bg-white flex items-center justify-center transition-colors duration-200">
                        <i class="fa-brands fa-facebook-f text-lg"></i>
                    </div>
                </a>
                <!-- Instagram -->
                <a href="#" class="text-gray-400 hover:text-pink-600 transition-colors duration-200" aria-label="Instagram">
                    <div class="w-10 h-10 rounded-full bg-gray-800 hover:bg-white flex items-center justify-center transition-colors duration-200">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </div>
                </a>
                <!-- Twitter (X) -->
                <a href="#" class="text-gray-400 hover:text-black transition-colors duration-200" aria-label="Twitter">
                    <div class="w-10 h-10 rounded-full bg-gray-800 hover:bg-white flex items-center justify-center transition-colors duration-200">
                        <i class="fa-brands fa-twitter text-lg"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
        <p>&copy; 2025 Ulin Mahoni. All rights reserved.</p>
    </div>
</footer> 