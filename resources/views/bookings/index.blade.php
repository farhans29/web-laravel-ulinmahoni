<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Ulin Mahoni</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @include('components.homepage.styles')
</head>
<body>
    @include('components.homepage.header')

    <main>
        <!-- Hero Section -->
        <div class="hero-section-booking h-72 relative">
            <img src="{{ asset('images/assets/pics/WhatsApp Image 2025-02-20 at 14.30.45.jpeg') }}" 
                alt="Bookings Hero" 
                class="w-full h-full object-cover" >
            <div class="absolute inset-0 gradient-overlay flex items-center justify-center">
                <h1 class="text-2xl text-white font-medium">My Bookings</h1>
            </div>
        </div>

        <!-- Bookings Section -->

        
    <section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center gap-2 p-3 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="font-semibold">Please upload your payment proof to avoid missed payment and to confirm your booking!</span>
        </div>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <div x-data="{ tab: 'all' }">
                    <div class="flex border-b border-gray-200 mb-4">
                        <button @click="tab = 'all'" :class="tab === 'all' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="w-1/2 py-3 px-1 text-center border-b-2 font-medium text-sm focus:outline-none transition">All Bookings</button>
                        <button @click="tab = 'completed'" :class="tab === 'completed' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="w-1/2 py-3 px-1 text-center border-b-2 font-medium text-sm focus:outline-none transition">Completed</button>
                    </div>

                    <!-- All Bookings Tab -->
                    <div x-show="tab === 'all'">
                        
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order #
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Property
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Check In - Out
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Price
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Attachment
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $all = $bookings->filter(function($booking) {
                                        return strtolower($booking->transaction_status) !== 'paid';
                                    });
                                @endphp
                                @forelse ($all as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col space-y-1">
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-semibold text-xs text-gray-500">Order ID:</span>
                                                    <span class="text-xs text-gray-900">{{ $booking->order_id }}</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-semibold text-xs text-gray-500">Transaction Code:</span>
                                                    <span class="text-xs text-teal-600">{{ $booking->transaction_code }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="text-xs text-gray-500">
                                                <span class="font-semibold text-xs text-gray-500">Transaction Type:</span>
                                                <span class="text-xs text-gray-900">{{ strtoupper($booking->transaction_type) }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-4">
                                                <div class="flex items-center">
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $booking->user_name }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-phone mr-1"></i>
                                                    {{ $booking->user_phone_number }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-envelope mr-1"></i>
                                                    {{ $booking->user_email }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->property_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->room_name }}</div>
                                            <div class="text-xs text-gray-500 flex items-center">
                                                <i class="fas fa-building mr-1"></i>
                                                {{ ucfirst($booking->property_type) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 flex items-center">
                                                <i class="far fa-calendar-check mr-1 text-teal-500"></i>
                                                {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500 flex items-center">
                                                <i class="far fa-calendar-times mr-1 text-red-500"></i>
                                                {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $booking->booking_days }} days
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $booking->formatted_price }}
                                            </div>
                                            @if($booking->paid_at)
                                                <div class="text-xs text-green-600 flex items-center">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Paid: {{ \Carbon\Carbon::parse($booking->paid_at)->format('d M Y H:i') }}
                                                </div>
                                            @endif
                                            <div class="text-xs text-gray-500">
                                                {{ $booking->formatted_daily_price }} / day
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center h-full">
                                                @php
                                                    $status = strtolower($booking->transaction_status);
                                                    $badgeBg = $badgeText = $dot = '';
                                                    if ($status === 'pending') {
                                                        $badgeBg = 'bg-red-50'; $badgeText = 'text-red-700'; $dot = 'bg-red-500';
                                                    } elseif ($status === 'waiting') {
                                                        $badgeBg = 'bg-yellow-50'; $badgeText = 'text-yellow-700'; $dot = 'bg-yellow-400';
                                                    } elseif ($status === 'success' || $status === 'paid') {
                                                        $badgeBg = 'bg-green-50'; $badgeText = 'text-green-700'; $dot = 'bg-green-500';
                                                    } else {
                                                        $badgeBg = 'bg-gray-100'; $badgeText = 'text-gray-700'; $dot = 'bg-gray-400';
                                                    }
                                                @endphp
                                                <span class="flex items-center gap-2 px-4 py-1 rounded-2xl shadow-sm font-semibold text-sm {{ $badgeBg }} {{ $badgeText }} border border-gray-200">
                                                    <span class="w-2 h-2 rounded-full {{ $dot }} inline-block"></span>
                                                    <span class="tracking-wide capitalize">{{ $booking->transaction_status }}</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if(!$booking->attachment)
                                                <form action="{{ route('bookings.upload-attachment', $booking->idrec) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                                    @csrf
                                                    <div class="relative">
                                                        <input type="file"
                                                            name="attachment_file"
                                                            id="attachment-{{ $booking->id }}"
                                                            class="hidden"
                                                            accept=".jpg,.jpeg,.png"
                                                            onchange="this.form.submit()">
                                                        <label for="attachment-{{ $booking->id }}"
                                                            class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                                            <i class="fas fa-paperclip mr-2"></i>
                                                            Upload
                                                        </label>
                                                    </div>
                                                </form>
                                            @else
                                                <div class="flex justify-center items-center h-full">
                                                    @php
                                                        $mime = 'image/png';
                                                        $decoded = base64_decode($booking->attachment, true);
                                                        if ($decoded !== false && strlen($decoded) > 2) {
                                                            if (substr($decoded, 0, 2) === "\xFF\xD8") $mime = 'image/jpeg';
                                                            elseif (substr($decoded, 0, 8) === "\x89PNG\x0D\x0A\x1A\x0A") $mime = 'image/png';
                                                        }
                                                    @endphp
                                                    <img src="data:{{ $mime }};base64,{{ $booking->attachment }}" alt="Attachment" style="max-width:100px;max-height:100px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);" />
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-500">
                                                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                                <p class="text-lg">No bookings found</p>
                                                <p class="text-sm mt-2">Your booking history will appear here</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Completed Tab -->
                    <div x-show="tab === 'completed'">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In - Out</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attachment</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $completed = $bookings->filter(function($booking) {
                                        return strtolower($booking->transaction_status) === 'paid';
                                    });
                                @endphp
                                @forelse ($completed as $booking)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col space-y-1">
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-semibold text-xs text-gray-500">Order ID:</span>
                                                    <span class="text-xs text-gray-900">{{ $booking->order_id }}</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-semibold text-xs text-gray-500">Transaction Code:</span>
                                                    <span class="text-xs text-teal-600">{{ $booking->transaction_code }}</span>
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $booking->transaction_type }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <div class="flex items-center">
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $booking->user_name }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-phone mr-1"></i>
                                                    {{ $booking->user_phone_number }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-envelope mr-1"></i>
                                                    {{ $booking->user_email }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->property_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->room_name }}</div>
                                            <div class="text-xs text-gray-500 flex items-center">
                                                <i class="fas fa-building mr-1"></i>
                                                {{ ucfirst($booking->property_type) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 flex items-center">
                                                <i class="far fa-calendar-check mr-1 text-teal-500"></i>
                                                {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500 flex items-center">
                                                <i class="far fa-calendar-times mr-1 text-red-500"></i>
                                                {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $booking->booking_days }} days
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $booking->formatted_price }}
                                            </div>
                                            @if($booking->paid_at)
                                                <div class="text-xs text-green-600 flex items-center">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Paid: {{ \Carbon\Carbon::parse($booking->paid_at)->format('d M Y H:i') }}
                                                </div>
                                            @endif
                                            <div class="text-xs text-gray-500">
                                                {{ $booking->formatted_daily_price }} / day
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center h-full">
                                                <span class="flex items-center gap-2 px-4 py-1 rounded-2xl shadow-sm font-semibold text-sm bg-green-50 text-green-700 border border-gray-200">
                                                    <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                                                    <span class="tracking-wide capitalize">Paid</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if(!$booking->attachment)
                                                <span class="text-gray-400 italic">No attachment</span>
                                            @else
                                                <div class="flex justify-center items-center h-full">
                                                    <a href="{{ asset('storage/' . $booking->attachment) }}" target="_blank" class="text-teal-600 hover:text-teal-700">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-500">
                                                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                                <p class="text-lg">No completed bookings found</p>
                                                <p class="text-sm mt-2">Completed bookings will appear here</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    @include('components.homepage.footer')

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
    
    function handleFileUpload(input) {
    const file = input.files[0];
    if (!file) return;

    const allowedTypes = ['image/jpeg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        alert('Only JPG and PNG images are allowed.');
        input.value = '';
        return;
    }

    // Optionally disable the submit button to prevent double submit
    const submitBtn = input.form.querySelector('[type=submit]');
    if (submitBtn) submitBtn.disabled = true;

    const reader = new FileReader();
    reader.onload = function(evt) {
        const base64Input = input.form.querySelector('input[name=base64_attachment]');
        if (!base64Input) {
            alert('Upload error: hidden input not found.');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }
        const base64String = evt.target.result.split(',')[1];
        base64Input.value = base64String;
        input.form.submit();
    };
    reader.onerror = function() {
        alert('Failed to read file.');
        input.value = '';
        if (submitBtn) submitBtn.disabled = false;
    };
    reader.readAsDataURL(file);
}
    }

    document.addEventListener('DOMContentLoaded', function() {
        @include('components.homepage.scripts')
    });
</script>
</body>
</html>
