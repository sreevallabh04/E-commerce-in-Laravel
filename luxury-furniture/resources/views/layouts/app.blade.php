<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Luxury Furniture - Premium Home Furnishings')</title>
    <meta name="description" content="@yield('description', 'Discover exquisite luxury furniture and premium home furnishings. Transform your space with our curated collection of high-end furniture.')">
    <meta name="keywords" content="@yield('keywords', 'luxury furniture, premium furniture, home furnishings, designer furniture')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="font-sans antialiased transition-all duration-300">
    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 z-50 flex items-center justify-center bg-gradient-to-br from-navy-900 to-navy-800">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-champagne border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <h2 class="text-2xl font-playfair text-white mb-2">Luxury Furniture</h2>
            <p class="text-champagne/80">Loading your premium experience...</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-40 transition-all duration-300 backdrop-blur-md bg-white/80 dark:bg-navy-900/80 border-b border-gray-200/20 dark:border-navy-700/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-champagne to-yellow-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-crown text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-playfair font-bold text-navy-900 dark:text-white">LuxuryFurniture</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        {{ __('Home') }}
                    </a>
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        {{ __('Products') }}
                    </a>
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        {{ __('Categories') }}
                    </a>
                    <a href="{{ route('deals') }}" class="nav-link {{ request()->routeIs('deals') ? 'active' : '' }}">
                        {{ __('Deals') }}
                    </a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        {{ __('About') }}
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        {{ __('Contact') }}
                    </a>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Language Toggle -->
                    <div class="relative">
                        <button id="language-toggle" class="flex items-center space-x-1 text-sm text-navy-700 dark:text-gray-300 hover:text-champagne transition-colors">
                            <i class="fas fa-globe"></i>
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="language-dropdown" class="absolute right-0 mt-2 w-32 bg-white dark:bg-navy-800 rounded-lg shadow-lg border border-gray-200 dark:border-navy-700 hidden">
                            <a href="{{ route('language.change', 'en') }}" class="block px-4 py-2 text-sm text-navy-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-navy-700">English</a>
                            <a href="{{ route('language.change', 'hi') }}" class="block px-4 py-2 text-sm text-navy-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-navy-700">हिंदी</a>
                        </div>
                    </div>

                    <!-- Theme Toggle -->
                    <button id="theme-toggle" class="p-2 rounded-lg bg-gray-100 dark:bg-navy-800 text-navy-700 dark:text-gray-300 hover:bg-champagne hover:text-white transition-all duration-300">
                        <i class="fas fa-sun dark:hidden"></i>
                        <i class="fas fa-moon hidden dark:block"></i>
                    </button>

                    <!-- Search -->
                    <button id="search-toggle" class="p-2 rounded-lg bg-gray-100 dark:bg-navy-800 text-navy-700 dark:text-gray-300 hover:bg-champagne hover:text-white transition-all duration-300">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Voice Search -->
                    <button id="voice-search" class="p-2 rounded-lg bg-gray-100 dark:bg-navy-800 text-navy-700 dark:text-gray-300 hover:bg-champagne hover:text-white transition-all duration-300">
                        <i class="fas fa-microphone"></i>
                    </button>

                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" class="relative p-2 rounded-lg bg-gray-100 dark:bg-navy-800 text-navy-700 dark:text-gray-300 hover:bg-champagne hover:text-white transition-all duration-300">
                        <i class="fas fa-heart"></i>
                        <span id="wishlist-count" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">0</span>
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 rounded-lg bg-gray-100 dark:bg-navy-800 text-navy-700 dark:text-gray-300 hover:bg-champagne hover:text-white transition-all duration-300">
                        <i class="fas fa-shopping-bag"></i>
                        <span id="cart-count" class="absolute -top-1 -right-1 w-5 h-5 bg-champagne text-white text-xs rounded-full flex items-center justify-center">0</span>
                    </a>

                    <!-- User Menu -->
                    @auth
                        <div class="relative">
                            <button id="user-menu-toggle" class="flex items-center space-x-2 p-2 rounded-lg bg-gray-100 dark:bg-navy-800 text-navy-700 dark:text-gray-300 hover:bg-champagne hover:text-white transition-all duration-300">
                                <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="w-6 h-6 rounded-full">
                                <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-navy-800 rounded-lg shadow-lg border border-gray-200 dark:border-navy-700 hidden">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-navy-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-navy-700">
                                    <i class="fas fa-user mr-2"></i>{{ __('Profile') }}
                                </a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-navy-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-navy-700">
                                    <i class="fas fa-box mr-2"></i>{{ __('Orders') }}
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-navy-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-navy-700">
                                    <i class="fas fa-heart mr-2"></i>{{ __('Wishlist') }}
                                </a>
                                <hr class="border-gray-200 dark:border-navy-700">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-navy-700">
                                        <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}" class="btn-primary">{{ __('Register') }}</a>
                    @endauth

                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-menu-toggle" class="md:hidden p-2 rounded-lg bg-gray-100 dark:bg-navy-800 text-navy-700 dark:text-gray-300 hover:bg-champagne hover:text-white transition-all duration-300">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden bg-white dark:bg-navy-900 border-t border-gray-200 dark:border-navy-700">
            <div class="px-4 py-2 space-y-1">
                <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('products.index') }}" class="mobile-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    {{ __('Products') }}
                </a>
                <a href="{{ route('categories.index') }}" class="mobile-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    {{ __('Categories') }}
                </a>
                <a href="{{ route('deals') }}" class="mobile-nav-link {{ request()->routeIs('deals') ? 'active' : '' }}">
                    {{ __('Deals') }}
                </a>
                <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    {{ __('About') }}
                </a>
                <a href="{{ route('contact') }}" class="mobile-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                    {{ __('Contact') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Search Overlay -->
    <div id="search-overlay" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-2xl">
                <div class="relative">
                    <input type="text" id="search-input" placeholder="Search for luxury furniture..." 
                           class="w-full px-6 py-4 text-lg bg-white dark:bg-navy-800 rounded-2xl border-2 border-gray-200 dark:border-navy-700 focus:border-champagne focus:outline-none text-navy-900 dark:text-white">
                    <button id="search-close" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-navy-900 dark:hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="search-suggestions" class="mt-4 bg-white dark:bg-navy-800 rounded-2xl shadow-lg max-h-96 overflow-y-auto hidden"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-navy-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-champagne to-yellow-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-crown text-white"></i>
                        </div>
                        <span class="text-lg font-playfair font-bold">LuxuryFurniture</span>
                    </div>
                    <p class="text-gray-300 mb-4">Transform your space with our curated collection of premium furniture and luxury home furnishings.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-champagne transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-champagne transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-champagne transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-champagne transition-colors">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products.index') }}" class="text-gray-300 hover:text-champagne transition-colors">All Products</a></li>
                        <li><a href="{{ route('deals') }}" class="text-gray-300 hover:text-champagne transition-colors">Special Deals</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-champagne transition-colors">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-champagne transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-champagne transition-colors">Shipping Info</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-champagne transition-colors">Returns & Exchanges</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-champagne transition-colors">Size Guide</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-champagne transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Stay Updated</h3>
                    <p class="text-gray-300 mb-4">Subscribe to our newsletter for exclusive offers and updates.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your email" 
                               class="flex-1 px-4 py-2 bg-navy-800 border border-navy-700 rounded-l-lg focus:outline-none focus:border-champagne text-white">
                        <button type="submit" class="px-4 py-2 bg-champagne text-white rounded-r-lg hover:bg-yellow-600 transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <hr class="border-navy-700 my-8">

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-300 text-sm">&copy; {{ date('Y') }} LuxuryFurniture. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-300 hover:text-champagne transition-colors text-sm">Privacy Policy</a>
                    <a href="#" class="text-gray-300 hover:text-champagne transition-colors text-sm">Terms of Service</a>
                    <a href="#" class="text-gray-300 hover:text-champagne transition-colors text-sm">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-6 z-30 space-y-3">
        <!-- Back to Top -->
        <button id="back-to-top" class="w-12 h-12 bg-champagne text-white rounded-full shadow-lg hover:bg-yellow-600 transition-all duration-300 transform hover:scale-110 hidden">
            <i class="fas fa-arrow-up"></i>
        </button>

        <!-- Quick Contact -->
        <button id="quick-contact" class="w-12 h-12 bg-navy-900 text-white rounded-full shadow-lg hover:bg-navy-800 transition-all duration-300 transform hover:scale-110">
            <i class="fas fa-headset"></i>
        </button>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html> 