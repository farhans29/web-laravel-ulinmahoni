<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Ulin Mahoni</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @include('components.homepage.styles')
    <style>
        .login-container {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }
        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.5) 100%);
            z-index: 2;
        }
        .login-box {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2.5rem;
            max-width: 28rem;
            width: 100%;
            margin: 2rem;
        }
        @media (max-width: 640px) {
            .login-box {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('components.homepage.header')

    <div class="login-container">
        <!-- Video Background -->
        <div class="video-wrapper">
            <video class="video-background" autoplay loop muted playsinline>
                <source src="{{ asset('images/assets/My_Movie.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="video-overlay"></div>
        </div>
        
        <div class="login-box">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-light text-gray-900 mb-2">Create Account</h2>
                <p class="text-gray-600">#UlinMahoni</p>
            </div>

            <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="name" class="sr-only">{{ __('Full Name') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="name" name="name" type="text" required class="appearance-none rounded-lg relative block w-full px-3 py-3 pl-10 border border-gray-200 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Full Name" :value="old('name')" />
                        </div>
                    </div>

                    <div>
                        <label for="username" class="sr-only">{{ __('Username') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-at text-gray-400"></i>
                            </div>
                            <input id="username" name="username" type="text" required class="appearance-none rounded-lg relative block w-full px-3 py-3 pl-10 border border-gray-200 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Username" :value="old('username')" />
                        </div>
                    </div>

                    <div>
                        <label for="email" class="sr-only">{{ __('Email Address') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" required class="appearance-none rounded-lg relative block w-full px-3 py-3 pl-10 border border-gray-200 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Email address" :value="old('email')" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="sr-only">{{ __('Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required class="appearance-none rounded-lg relative block w-full px-3 py-3 pl-10 border border-gray-200 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Password" />
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="sr-only">{{ __('Confirm Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none rounded-lg relative block w-full px-3 py-3 pl-10 border border-gray-200 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Confirm Password" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="newsletter" name="newsletter" type="checkbox" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                    <label for="newsletter" class="ml-2 block text-sm text-gray-900">
                        Email me about product news
                    </label>
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded" required>
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-teal-600 hover:text-teal-500">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-teal-600 hover:text-teal-500">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </label>
                    </div>
                @endif

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-teal-500 group-hover:text-teal-400"></i>
                        </span>
                        {{ __('Sign Up') }}
                    </button>
                </div>
            </form>

            <x-jet-validation-errors class="mt-4"/>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('login') }}" class="font-medium text-teal-600 hover:text-teal-500">
                        {{ __('Sign in') }}
                    </a>
                </p>
            </div>
        </div>
    </div>

    @include('components.homepage.footer')
    
    <script>
        // Video play/pause functionality
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.querySelector('.video-background');
            
            // Ensure video plays when page loads
            video.play().catch(error => {
                console.log('Autoplay prevented:', error);
                // Show play button if autoplay is prevented
                video.controls = true;
            });
        });
    </script>
</body>
</html>
