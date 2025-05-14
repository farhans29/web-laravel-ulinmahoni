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
        <div class="hero-section h-64 relative">
            <img src="{{ asset('images/assets/pics/WhatsApp Image 2025-02-20 at 14.30.45.jpeg') }}" 
                alt="Bookings Hero" 
                class="w-full h-full object-cover">
            <div class="absolute inset-0 gradient-overlay flex items-center justify-center">
                <h1 class="text-4xl text-white font-light">My Bookings</h1>
            </div>
        </div>

        <!-- Bookings Section -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
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
                                @forelse ($bookings as $booking)
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
                                                        <input type="file" name="attachment" id="attachment-{{ $booking->id }}" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                            onchange="this.form.submit()">
                                                        <label for="attachment-{{ $booking->id }}" class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                                            <i class="fas fa-paperclip mr-2"></i>
                                                            Upload
                                                        </label>
                                                    </div>
                                                </form>
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
                                                <p class="text-lg">No bookings found</p>
                                                <p class="text-sm mt-2">Your booking history will appear here</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('components.homepage.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @include('components.homepage.scripts')
        });
    </script>
</body>
</html>
