<!-- Header Component -->
<header class="site-header py-4 px-6 flex items-center justify-between bg-white shadow-md sticky top-0 z-50">
    <div class="flex items-center space-x-8">
        <a href="/homepage">
            <img src="{{ asset('images/assets/ulinmahoni-logo.svg') }}" alt="Ulin Mahoni Logo" class="h-10 w-auto">
        </a>
        <nav class="hidden md:flex">
            <ul class="flex space-x-6">
                <li>
                    <a href="/sewa" class="text-sm text-gray-600 hover:text-gray-900">
                        Sewa Berjangka
                    </a>
                </li>
                <li>    
                    <a href="/kerjasama" class="text-sm text-gray-600 hover:text-gray-900">
                        Kerjasama Ulin Mahoni
                    </a>
                </li>
                <li>
                    <a href="/business" class="text-sm text-gray-600 hover:text-gray-900">
                        Ulin Mahoni untuk Bisnis
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
                <image alt="ID Flag" class="rounded-full mr-2" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAAAXNSR0IArs4c6QAABhtJREFUeF7lW1tsFFUY/v6ZJV7KzHQvXGS3eOm2JoJCw4sGCaQGCKBCjBokURMk+OIFJSagwQeiMQRUwCcfuDyY1AvECIi0IQgYLy+o3Iw02myDyi6Ryu52ZIiLd+e3Zdmu17HZmttt2t3Ne57993znnP7d/CCPQ9Cn1gWuSOV0G1zNTPcB+SFQFsLfHPSVh8hWA4kTcSqBzMnvOqLFf48MdHg2HA541a1zyT30hCAsY3AjgHoHSoS8GcBbgI8RSi3ey2kInTlx3aMNS3GlQgxpMButmMmElg58CELD07kCAgb8k0MfEtMMbaz3pQHVQ0ZIQkJhSO9uUaB0BS4roaedYCN+yyW8GYm1HnCvnagyJgI6p9XelzfR2gATwEW9M2O+R5Jerfz93vljnRRHAmOdJ1MTWg3k9gFuKdV4ivalM/Lb/Qs0mwtEupzYdExAPhkMANYH4QafOhln+OENaEYi2xpz4cUTA5WBtIxF9UuoE5yTgwWRFomTgyQnRyFG7Nm0TcLmmdhkxNQG42a7xUZL7F4yn/bHIp3b8/wc6q2KvTZvD6QAAAABJRU5ErkJggg==" x="0" y="0" width="64" height="64"/>
            </svg>
            <span class="text-sm text-gray-600">ID</span>
        </div>
        @guest
            <a href="{{ route('login') }}" class="text-gray-700 px-3 py-2 rounded-md hover:text-gray-900">Masuk</a>
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Daftar</a>
        @else
            <x-dropdown-profile align="right" />
        @endguest
    </div>
</header>
