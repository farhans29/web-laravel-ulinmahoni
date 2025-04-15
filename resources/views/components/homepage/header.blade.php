<!-- Header Component -->
<header class="site-header py-4 px-6 flex items-center justify-between">
    <div class="flex items-center space-x-8">
        <a href="/homepage">
            <img src="{{ asset('images/assets/ulinmahoni-logo.svg') }}" alt="Ulin Mahoni Logo" class="h-10 w-auto">
        </a>
        <nav class="hidden md:flex">
            <ul class="flex space-x-6">
                <li>
                    <a href="/sewa" class="text-sm text-gray-600 hover:text-gray-900">
                        Sewa
                    </a>
                </li>
                <li>
                    <a href="/kerjasama" class="text-sm text-gray-600 hover:text-gray-900">
                        Kerjasama Ulin Mahoni
                    </a>
                </li>
                <li>
                    <a href="/business" class="text-sm text-gray-600 hover:text-gray-900">
                        Ulin Mahoni for Business
                    </a>
                </li>
                <li>
                    <a href="/tentang" class="text-sm text-gray-600 hover:text-gray-900">
                        Tentang Ulin Mahoni
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="flex items-center space-x-4">
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 64 64" class="mr-2">
                <image alt="ID Flag" class="rounded-full mr-2" xlink:href="data:image/png;base64,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" x="0" y="0" width="64" height="64"/>
            </svg>
            <span class="text-sm text-gray-600">ID</span>
        </div>
        <button class="border border-gray-300 rounded-md px-4 py-2 text-sm">
            Masuk / Daftar
        </button>
    </div>
</header>
