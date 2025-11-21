<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Ulin Mahoni</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Make confirmFileUpload available globally
    window.confirmFileUpload = async function(input) {
        if (!input.files || input.files.length === 0) {
            return;
        }

        const file = input.files[0];
        const validTypes = ['image/jpeg', 'image/png'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        // Check file type
        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Only JPG and PNG images are allowed.',
                confirmButtonColor: '#0d9488',
            });
            input.value = '';
            return;
        }

        // Check file size
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'Maximum file size is 10MB.',
                confirmButtonColor: '#0d9488',
            });
            input.value = '';
            return;
        }

        // Show preview and confirmation
        const reader = new FileReader();
        reader.onload = async function(e) {
            try {
                const result = await Swal.fire({
                    title: 'Confirm Upload',
                    html: `
                        <div class="text-center">
                            <p class="mb-4">Are you sure you want to upload this image?</p>
                            <img src="${e.target.result}" class="max-w-full h-auto rounded-lg mx-auto mb-4" style="max-height: 300px;" alt="Preview">
                            <p class="text-sm text-gray-600">${file.name} (${(file.size / 1024).toFixed(2)} KB)</p>
                            <div id="upload-progress" class="hidden mt-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div id="progress-bar" class="bg-teal-600 h-2.5 rounded-full" style="width: 0%"></div>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">Uploading...</p>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#0d9488',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, upload it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    showLoaderOnConfirm: false,
                    allowOutsideClick: false,
                    preConfirm: async () => {
                        // Show progress bar
                        document.getElementById('upload-progress').classList.remove('hidden');
                        const progressBar = document.getElementById('progress-bar');
                        
                        // Simulate progress (in a real app, you'd use actual upload progress events)
                        for (let i = 0; i <= 100; i += 10) {
                            await new Promise(resolve => setTimeout(resolve, 100));
                            progressBar.style.width = `${i}%`;
                        }
                        
                        // Submit the form
                        return new Promise((resolve) => {
                            input.form.submit();
                            // The form submission will navigate away, so we don't need to resolve
                        });
                    }
                });

                if (result.dismiss === Swal.DismissReason.cancel) {
                    // Reset the input if canceled
                    input.value = '';
                }
            } catch (error) {
                console.error('Upload error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'An error occurred while uploading the file. Please try again.',
                    confirmButtonColor: '#0d9488',
                });
                input.value = '';
            }
        };
        reader.readAsDataURL(file);
    };
    </script>
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
                <h1 class="text-4xl text-white font-medium">Pemesanan Saya</h1>
            </div>
        </div>

        <!-- Bookings Section -->

        
    <section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center gap-2 p-3 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="font-semibold">Mohon upload bukti pembayaran Anda untuk menghindari pembayaran yang terlewat dan untuk memastikan pesanan Anda!</span>
        </div>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <div x-data="{ tab: 'all' }">
                    <div class="flex border-b border-gray-200 mb-4">
                        <button @click="tab = 'all'" :class="tab === 'all' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="w-1/2 py-3 px-1 text-center border-b-2 font-medium text-sm focus:outline-none transition">Mendatang</button>
                        <button @click="tab = 'completed'" :class="tab === 'completed' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="w-1/2 py-3 px-1 text-center border-b-2 font-medium text-sm focus:outline-none transition">Selesai</button>
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
                                        Total Biaya
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
                                    $filteredBookings = $allBookings->filter(function($booking) {
                                        $status = strtolower($booking->transaction_status);
                                        return !in_array($status, ['paid', 'completed']);
                                    });
                                @endphp
                                @forelse ($filteredBookings as $booking)
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
                                                {{ $booking->formatted_grandtotal_price }}
                                            </div>
                                            @if($booking->paid_at)
                                                <div class="text-xs text-green-600 flex items-center">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Paid: {{ \Carbon\Carbon::parse($booking->paid_at)->format('d M Y H:i') }}
                                                </div>
                                            @endif
                                            {{-- 
                                            <div class="text-xs text-gray-500">
                                                {{ $booking->formatted_daily_price }} / day
                                            </div>
                                            --}}
                                        </td>
                                        <!-- Transaction StatusS -->
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center h-full">
                                                @php
                                                    $status = strtolower($booking->transaction_status);
                                                    $badgeBg = $badgeText = $dot = '';
                                                    $transactionDate = \Carbon\Carbon::parse($booking->transaction_date);
                                                    $currentTime = now();
                                                    $hoursDifference = $currentTime->diffInHours($transactionDate);
                                                    $shouldShowTimer = false;
                                                    $expiresAt = $transactionDate->copy()->addHour();
                                                    $remainingMinutes = $currentTime->diffInMinutes($expiresAt, false);
                                                    $isExpired = false;

                                                    // Handle status-specific styling and text
                                                    if ($status === 'pending') {
                                                        $badgeBg = 'bg-red-50'; $badgeText = 'text-red-700'; $dot = 'bg-red-400';
                                                        $transactionText = 'Menunggu Pembayaran';
                                                        $shouldShowTimer = true;
                                                        
                                                        // Check if expired
                                                        if ($hoursDifference >= 1) {
                                                            $isExpired = true;
                                                            $status = 'expired';
                                                            $shouldShowTimer = false;
                                                        }
                                                    } 
                                                    elseif ($status === 'waiting') {
                                                        $badgeBg = 'bg-yellow-50'; $badgeText = 'text-yellow-700'; $dot = 'bg-yellow-400';
                                                        $transactionText = 'Menunggu Konfirmasi';
                                                        $shouldShowTimer = false;
                                                    }
                                                    elseif ($status === 'success' || $status === 'paid') {
                                                        $badgeBg = 'bg-green-50'; $badgeText = 'text-green-700'; $dot = 'bg-green-500';
                                                        $transactionText = 'Berhasil Dibayar';
                                                    } 
                                                    elseif ($status === 'canceled') {
                                                        $badgeBg = 'bg-gray-50'; $badgeText = 'text-gray-700'; $dot = 'bg-gray-500';
                                                        $transactionText = 'Dibatalkan';
                                                    } 
                                                    elseif ($status === 'expired') {
                                                        $badgeBg = 'bg-gray-100'; $badgeText = 'text-gray-500'; $dot = 'bg-gray-400';
                                                        $transactionText = 'Expired';
                                                    } 
                                                    else {
                                                        $badgeBg = 'bg-gray-100'; $badgeText = 'text-gray-700'; $dot = 'bg-gray-400';
                                                        $transactionText = 'Gagal';
                                                    }
                                                    
                                                    // Add countdown timer if needed
                                                    if ($shouldShowTimer && $remainingMinutes > 0) {
                                                        $remainingTime = $expiresAt->diffForHumans($currentTime, [
                                                            'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                                            'parts' => 3,
                                                            'short' => true
                                                        ]);
                                                        $transactionText .= ' (' . $remainingTime . ')';
                                                    }
                                                @endphp
                                                <span class="flex items-center gap-2 px-4 py-1 rounded-2xl shadow-sm font-semibold text-sm {{ $badgeBg }} {{ $badgeText }} border border-gray-200"
                                                      data-booking-id="{{ $booking->idrec }}"
                                                      data-expires-at="{{ $shouldShowTimer && $remainingMinutes > 0 ? $expiresAt->toIso8601String() : '' }}"
                                                      data-initial-text="Menunggu Pembayaran"
                                                      data-status="{{ $status }}">
                                                    <span class="w-2 h-2 rounded-full {{ $dot }} inline-block"></span>
                                                    <span class="tracking-wide capitalize text-center">{{ $transactionText }}</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if(!$booking->attachment)
                                                <form action="{{ route('bookings.upload-attachment', $booking->idrec) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2" id="upload-form-{{ $booking->idrec }}">
                                                    @csrf
                                                    <div class="relative">
                                                        <input type="file"
                                                            name="attachment_file"
                                                            id="attachment-{{ $booking->idrec }}"
                                                            class="hidden"
                                                            accept="image/jpeg, image/png"
                                                            onchange="handleFileUpload(this, '{{ $booking->idrec }}')">
                                                        <label for="attachment-{{ $booking->idrec }}"
                                                            class="cursor-pointer inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                                            <i class="fas fa-paperclip mr-2"></i>
                                                            <span class="upload-text">Upload</span>
                                                            <span class="upload-loader hidden ml-2">
                                                                <i class="fas fa-spinner fa-spin"></i> Uploading...
                                                            </span>
                                                        </label>
                                                    </div>
                                                </form>
                                                <script>
                                                function handleFileUpload(input, bookingId) {
                                                    if (!input.files || input.files.length === 0) {
                                                        return;
                                                    }

                                                    const file = input.files[0];
                                                    const validTypes = ['image/jpeg', 'image/png'];
                                                    const maxSize = 10 * 1024 * 1024; // 10MB

                                                    // Check file type
                                                    if (!validTypes.includes(file.type)) {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Invalid file type',
                                                            text: 'Please upload a JPEG or PNG image.',
                                                        });
                                                        input.value = '';
                                                        return;
                                                    }

                                                    // Check file size
                                                    if (file.size > maxSize) {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'File too large',
                                                            text: 'Maximum file size is 10MB.',
                                                        });
                                                        input.value = '';
                                                        return;
                                                    }


                                                    // Show confirmation
                                                    Swal.fire({
                                                        title: 'Upload Payment Proof',
                                                        text: 'Are you sure you want to upload this file?',
                                                        icon: 'question',
                                                        showCancelButton: true,
                                                        confirmButtonText: 'Yes, upload it!',
                                                        cancelButtonText: 'Cancel',
                                                        reverseButtons: true
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            // Show loading state
                                                            const form = document.getElementById(`upload-form-${bookingId}`);
                                                            const uploadText = form.querySelector('.upload-text');
                                                            const uploadLoader = form.querySelector('.upload-loader');
                                                            const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
                                                            
                                                            if (submitButton) submitButton.disabled = true;
                                                            if (uploadText) uploadText.classList.add('hidden');
                                                            if (uploadLoader) uploadLoader.classList.remove('hidden');

                                                            // Submit the form
                                                            form.submit();
                                                        } else {
                                                            // Reset the file input if user cancels
                                                            input.value = '';
                                                        }
                                                    });
                                                }
                                                </script>
                                            @else
                                                <!-- Original image display (commented for reference)
                                                <div class="flex justify-center items-center h-full">
                                                    @php
                                                        $mime = 'image/png';
                                                        $decoded = base64_decode($booking->attachment, true);
                                                        if ($decoded !== false && strlen($decoded) > 2) {
                                                            if (substr($decoded, 0, 2) === "\xFF\xD8") $mime = 'image/jpeg';
                                                            elseif (substr($decoded, 0, 8) === "\x89PNG\x0D\x0A\x1A\x0A") $mime = 'image/png';
                                                        }
                                                    @endphp
                                                    <a href="{{ route('bookings.view-attachment', $booking->idrec) }}" target="_blank" class="inline-block hover:opacity-90 transition-opacity">
                                                        <img src="data:{{ $mime }};base64,{{ $booking->attachment }}" alt="Attachment" style="max-width:100px;max-height:100px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);" class="cursor-pointer" />
                                                    </a>
                                                </div>
                                                -->
                                                
                                                <!-- Eye icon for viewing attachment -->
                                                <div class="flex justify-center items-center h-full">
                                                    <a href="{{ route('bookings.view-attachment', $booking->idrec) }}" target="_blank" 
                                                       class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-teal-50 text-teal-600 hover:bg-teal-100 transition-colors duration-200"
                                                       title="View Attachment">
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
                                                <p class="text-lg">Tidak ada pemesanan</p>
                                                <p class="text-sm mt-2">Pemesanan Anda akan muncul di sini</p>
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
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Biaya</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attachment</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    // Filter to only show paid and completed bookings in this tab
                                    $filteredBookings = $allBookings->filter(function($booking) {
                                        $status = strtolower($booking->transaction_status);
                                        return in_array($status, ['paid', 'completed']);
                                    });
                                    
                                @endphp
                                @forelse ($filteredBookings as $booking)
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
                                            {{-- <div class="text-xs text-gray-500">
                                                {{ $booking->daily_price }} / day
                                            </div> --}}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center items-center h-full">
                                                @if(strtolower($booking->transaction_status) === 'paid')
                                                <span class="flex items-center gap-2 px-4 py-1 rounded-2xl shadow-sm font-semibold text-sm bg-green-50 text-green-700 border border-gray-200">
                                                    <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                                                    <span class="tracking-wide capitalize">Paid</span>
                                                </span>
                                                @else
                                                <span class="flex items-center gap-2 px-4 py-1 rounded-2xl shadow-sm font-semibold text-sm bg-blue-50 text-blue-700 border border-gray-200">
                                                    <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
                                                    <span class="tracking-wide capitalize">{{ ucfirst($booking->transaction_status) }}</span>
                                                </span>
                                                @endif
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
                                                <p class="text-lg">Tidak ada pemesanan selesai</p>
                                                <p class="text-sm mt-2">Pemesanan selesai akan muncul di sini</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        @include('components.homepage.scripts')
        
        // Initialize any global SweetAlert2 defaults if needed
        window.Swal = Swal.mixin({
            customClass: {
                confirmButton: 'px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500',
                cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 ml-3'
            },
            buttonsStyling: false
        });

        // Function to mark a booking as expired via AJAX
        function markBookingAsExpired(bookingId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/bookings/${bookingId}/mark-expired`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ _method: 'POST' })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Reload the page to reflect the changes
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to update booking status',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'An error occurred while updating the booking status',
                    confirmButtonText: 'OK'
                });
            });
        }

        // Check for expired bookings on page load
        document.querySelectorAll('.booking-row[data-expired="true"]').forEach(row => {
            const bookingId = row.dataset.bookingId;
            if (bookingId) {
                markBookingAsExpired(bookingId);
            }
        });

        // Simple ticking timer for pending bookings (similar to your example)
        function updateTimers() {
            document.querySelectorAll('[data-expires-at]').forEach(element => {
                const expiresAt = new Date(element.dataset.expiresAt);
                const now = new Date();
                const remainingMs = expiresAt.getTime() - now.getTime();
                
                if (remainingMs <= 0) {
                    // Timer has expired
                    const bookingId = element.dataset.bookingId;
                    const timerText = element.querySelector('.timer-text');
                    if (timerText) {
                        timerText.textContent = element.dataset.initialText + ' (Expired)';
                    }
                    
                    // Update styling to expired state
                    element.classList.remove('bg-red-50', 'text-red-700');
                    element.classList.add('bg-gray-100', 'text-gray-500');
                    const dot = element.querySelector('span:first-child');
                    if (dot) {
                        dot.classList.remove('bg-red-400');
                        dot.classList.add('bg-gray-400');
                    }
                    
                    // Mark as expired in backend if not already done
                    if (element.dataset.status === 'pending') {
                        markBookingAsExpired(bookingId);
                        element.dataset.status = 'expired';
                    }
                } else {
                    // Calculate remaining time with zero padding like your example
                    const hours = Math.floor(remainingMs / (1000 * 60 * 60));
                    const minutes = Math.floor((remainingMs % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((remainingMs % (1000 * 60)) / 1000);
                    
                    // Format time with zero padding
                    const h = ("0" + hours).substr(-2);
                    const m = ("0" + minutes).substr(-2);
                    const s = ("0" + seconds).substr(-2);
                    
                    let timeString = '';
                    if (hours > 0) {
                        timeString = `${h}:${m}:${s}`;
                    } else if (minutes > 0) {
                        timeString = `00:${m}:${s}`;
                    } else {
                        timeString = `00:00:${s}`;
                    }
                    
                    const timerText = element.querySelector('.timer-text');
                    if (timerText) {
                        timerText.textContent = element.dataset.initialText + ' (' + timeString + ')';
                    }
                }
            });
        }

        // Start the ticking timer (like your example)
        updateTimers(); // Initial update
        setInterval(updateTimers, 1000); // Update every second (ticking)
    });
</script>
</body>
</html>
