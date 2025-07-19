<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LuxFurn - Premium Furniture Collection</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🪑</text></svg>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="dark-mode">
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="{{ route('frontend.home') }}"><h2>LuxFurn</h2></a>
            </div>
            
            <div class="nav-menu" id="nav-menu">
                <a href="{{ route('frontend.home') }}" class="nav-link active">Home</a>
                <a href="{{ route('products.index') }}" class="nav-link">Products</a>
                <a href="{{ route('about') }}" class="nav-link">About</a>
                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
            </div>
            
            <div class="nav-actions">
                <div class="language-toggle">
                    <button class="lang-btn active" data-lang="en">EN</button>
                    <button class="lang-btn" data-lang="hi">हिं</button>
                </div>
                <button class="theme-toggle" id="theme-toggle">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="voice-search" id="voice-search">
                    <i class="fas fa-microphone"></i>
                </button>
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Search luxury furniture..." id="search-input">
                    <i class="fas fa-search search-icon"></i>
                    <div class="search-suggestions" id="search-suggestions"></div>
                </div>
                <button class="cart-btn" onclick="window.location.href='{{ route('cart.index') }}'">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </button>
            </div>
            
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-bg">
            <div class="parallax-layer" data-speed="0.5"></div>
            <div class="parallax-layer" data-speed="0.8"></div>
        </div>
        
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <span class="title-line">Luxury</span>
                    <span class="title-line">Redefined</span>
                </h1>
                <p class="hero-subtitle">Experience premium furniture that transforms your space into a masterpiece</p>
                <div class="hero-buttons">
                    <button class="btn btn-primary" onclick="window.location.href='{{ route('products.index') }}'">Explore Collection</button>
                    <button class="btn btn-secondary">Watch Story</button>
                </div>
            </div>
            
            <div class="hero-product">
                <div class="product-showcase">
                    <img src="https://images.pexels.com/photos/1866149/pexels-photo-1866149.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Luxury Sofa">
                    <div class="product-info">
                        <h3>Premium Velvet Sofa</h3>
                        <p>₹2,99,999</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="scroll-indicator">
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products" id="products">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Collection</h2>
                <p class="section-subtitle">Curated pieces that define elegance</p>
            </div>
            
            <div class="products-grid">
                <!-- Products will be loaded dynamically via JavaScript -->
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">Discover our premium collections</p>
            </div>
            
            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-image">
                        <img src="https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=600" alt="Living Room">
                    </div>
                    <div class="category-info">
                        <h3>Living Room</h3>
                        <p>Create the perfect gathering space</p>
                        <button class="btn btn-secondary">Shop Now</button>
                    </div>
                </div>
                
                <div class="category-card">
                    <div class="category-image">
                        <img src="https://images.pexels.com/photos/1350789/pexels-photo-1350789.jpeg?auto=compress&cs=tinysrgb&w=600" alt="Bedroom">
                    </div>
                    <div class="category-info">
                        <h3>Bedroom</h3>
                        <p>Design your dream sanctuary</p>
                        <button class="btn btn-secondary">Shop Now</button>
                    </div>
                </div>
                
                <div class="category-card">
                    <div class="category-image">
                        <img src="https://images.pexels.com/photos/1571470/pexels-photo-1571470.jpeg?auto=compress&cs=tinysrgb&w=600" alt="Dining">
                    </div>
                    <div class="category-info">
                        <h3>Dining</h3>
                        <p>Elevate your dining experience</p>
                        <button class="btn btn-secondary">Shop Now</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Free Delivery</h3>
                    <p>Free shipping on all orders above ₹50,000</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>10 Year Warranty</h3>
                    <p>Comprehensive warranty on all products</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Round-the-clock customer support</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3>Premium Quality</h3>
                    <p>Handcrafted with finest materials</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>LuxFurn</h3>
                    <p>Creating luxury living spaces with premium furniture that stands the test of time.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('frontend.home') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Categories</h4>
                    <ul>
                        <li><a href="#">Living Room</a></li>
                        <li><a href="#">Bedroom</a></li>
                        <li><a href="#">Dining</a></li>
                        <li><a href="#">Office</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Size Guide</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 LuxFurn. All rights reserved.</p>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Button -->
    <button class="floating-btn" id="floating-btn">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html> 