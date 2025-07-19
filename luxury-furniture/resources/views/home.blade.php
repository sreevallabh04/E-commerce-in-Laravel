<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Luxury Furniture - Premium Home Furnishings</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-navy-900 via-navy-800 to-navy-900">
        <div class="container mx-auto px-4 py-20">
            <div class="text-center text-white">
                <h1 class="text-6xl font-bold mb-6">Luxury Furniture</h1>
                <p class="text-xl mb-8">Your premium furniture website is now running!</p>
                <div class="bg-white/10 backdrop-blur-md rounded-lg p-8 max-w-2xl mx-auto">
                    <h2 class="text-2xl font-bold mb-4 text-champagne">🎉 Success!</h2>
                    <p class="text-gray-300 mb-4">Your luxury furniture e-commerce website is now live and running successfully.</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        @foreach($featuredProducts as $product)
                        <div class="bg-white/5 backdrop-blur-md rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-champagne mb-2">{{ $product['name'] }}</h3>
                            <p class="text-gray-300 mb-2">{{ $product['category']['name'] }}</p>
                            <p class="text-2xl font-bold text-white">₹{{ number_format($product['price']) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        </div>
</body>
</html>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-playfair font-bold text-white mb-4">
                Exclusive Deals
            </h2>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                Limited time offers on our most popular pieces. Don't miss out on these incredible savings.
            </p>
        </div>

        <div class="relative">
            <div id="deals-carousel" class="flex space-x-6 overflow-x-auto scrollbar-hide pb-4">
                @foreach($deals as $product)
                <div class="flex-shrink-0 w-80 bg-white dark:bg-navy-800 rounded-2xl shadow-lg overflow-hidden">
                    <div class="relative">
                        <img src="{{ $product->images->first()->url ?? asset('images/product-placeholder.jpg') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover">
                        
                        <div class="absolute top-4 left-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold animate-pulse">
                                -{{ $product->discount_percentage }}% OFF
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-navy-900 dark:text-white mb-2">{{ $product->name }}</h3>
                        
                        <div class="flex items-center space-x-2 mb-4">
                            <span class="text-2xl font-bold text-champagne">₹{{ number_format($product->sale_price) }}</span>
                            <span class="text-lg text-gray-500 line-through">₹{{ number_format($product->price) }}</span>
                        </div>

                        <button onclick="addToCart({{ $product->id }})" 
                                class="w-full bg-champagne text-white py-3 rounded-lg font-semibold hover:bg-yellow-600 transition-all duration-300">
                            Buy Now
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Carousel Controls -->
            <button id="deals-prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-all duration-300">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="deals-next" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-all duration-300">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<!-- Latest Products -->
<section class="py-20 bg-gray-50 dark:bg-navy-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-playfair font-bold text-navy-900 dark:text-white mb-4">
                New Arrivals
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Be the first to discover our latest additions, featuring cutting-edge design and unparalleled craftsmanship.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($latestProducts as $product)
            <div class="group bg-white dark:bg-navy-700 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden">
                <div class="relative overflow-hidden">
                    <img src="{{ $product->images->first()->url ?? asset('images/product-placeholder.jpg') }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <div class="absolute top-4 left-4">
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            NEW
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-lg font-semibold text-navy-900 dark:text-white mb-2 group-hover:text-champagne transition-colors">
                        {{ $product->name }}
                    </h3>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-champagne">₹{{ number_format($product->current_price) }}</span>
                        <div class="flex items-center text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>

                    <button onclick="addToCart({{ $product->id }})" 
                            class="w-full bg-navy-900 dark:bg-white text-white dark:text-navy-900 py-3 rounded-lg font-semibold hover:bg-champagne hover:text-white transition-all duration-300">
                        Add to Cart
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-gradient-to-r from-champagne to-yellow-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-playfair font-bold text-navy-900 mb-6">
            Ready to Transform Your Space?
        </h2>
        <p class="text-xl text-navy-800 mb-8 max-w-2xl mx-auto">
            Join thousands of satisfied customers who have elevated their homes with our premium furniture collection.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('products.index') }}" 
               class="btn-primary bg-navy-900 text-white hover:bg-navy-800">
                <span>Start Shopping</span>
                <i class="fas fa-shopping-bag ml-2"></i>
            </a>
            <a href="{{ route('contact') }}" 
               class="btn-secondary bg-white text-navy-900 hover:bg-gray-100">
                <span>Get Consultation</span>
                <i class="fas fa-phone ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Quick View Modal -->
<div id="quick-view-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-navy-800 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-2xl font-playfair font-bold text-navy-900 dark:text-white">Quick View</h3>
                    <button id="quick-view-close" class="text-gray-400 hover:text-navy-900 dark:hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="quick-view-content" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .floating-element {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .animate-fade-in-up {
        animation: fadeInUp 1s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
// Quick View Functionality
function quickView(productId) {
    fetch(`/products/${productId}/quick-view`)
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById('quick-view-modal');
            const content = document.getElementById('quick-view-content');
            
            content.innerHTML = `
                <div class="space-y-4">
                    <img src="${data.product.images[0]?.url || '/images/product-placeholder.jpg'}" 
                         alt="${data.product.name}" 
                         class="w-full h-64 object-cover rounded-lg">
                    <div class="grid grid-cols-4 gap-2">
                        ${data.product.images.slice(1, 5).map(img => `
                            <img src="${img.url}" alt="${data.product.name}" 
                                 class="w-full h-16 object-cover rounded cursor-pointer hover:opacity-75 transition-opacity">
                        `).join('')}
                    </div>
                </div>
                <div class="space-y-4">
                    <h3 class="text-2xl font-playfair font-bold text-navy-900 dark:text-white">${data.product.name}</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-3xl font-bold text-champagne">₹${data.product.current_price}</span>
                        ${data.product.sale_price ? `<span class="text-lg text-gray-500 line-through">₹${data.product.price}</span>` : ''}
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="flex text-yellow-400">
                            ${Array.from({length: 5}, (_, i) => 
                                `<i class="fas fa-star ${i < data.average_rating ? 'text-yellow-400' : 'text-gray-300'}"></i>`
                            ).join('')}
                        </div>
                        <span class="text-gray-500">(${data.reviews_count} reviews)</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">${data.product.short_description}</p>
                    <div class="flex space-x-4">
                        <button onclick="addToCart(${data.product.id})" 
                                class="flex-1 bg-navy-900 dark:bg-white text-white dark:text-navy-900 py-3 rounded-lg font-semibold hover:bg-champagne hover:text-white transition-all duration-300">
                            Add to Cart
                        </button>
                        <button onclick="toggleWishlist(${data.product.id})" 
                                class="w-12 h-12 bg-gray-100 dark:bg-navy-700 rounded-lg flex items-center justify-center text-navy-700 dark:text-white hover:bg-red-500 hover:text-white transition-all duration-300">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
        });
}

// Wishlist Toggle
function toggleWishlist(productId) {
    fetch(`/products/${productId}/wishlist`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Show success message
            showNotification(data.message, 'success');
            updateWishlistCount(data.wishlist_count);
        }
    })
    .catch(error => {
        showNotification('Error updating wishlist', 'error');
    });
}

// Add to Cart
function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            showNotification(data.message, 'success');
            updateCartCount(data.cart_count);
        }
    })
    .catch(error => {
        showNotification('Error adding to cart', 'error');
    });
}

// Update counters
function updateCartCount(count) {
    document.getElementById('cart-count').textContent = count;
}

function updateWishlistCount(count) {
    document.getElementById('wishlist-count').textContent = count;
}

// Show notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-semibold ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Quick view modal close
    document.getElementById('quick-view-close').addEventListener('click', function() {
        document.getElementById('quick-view-modal').classList.add('hidden');
    });

    // Deals carousel
    const carousel = document.getElementById('deals-carousel');
    const prevBtn = document.getElementById('deals-prev');
    const nextBtn = document.getElementById('deals-next');

    if (prevBtn && nextBtn && carousel) {
        prevBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: -320, behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: 320, behavior: 'smooth' });
        });
    }

    // Hide loading screen
    setTimeout(() => {
        document.getElementById('loading-screen').style.opacity = '0';
        setTimeout(() => {
            document.getElementById('loading-screen').style.display = 'none';
        }, 300);
    }, 1000);
});
</script>
@endpush
@endsection 