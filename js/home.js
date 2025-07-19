// Home page specific functionality

document.addEventListener('DOMContentLoaded', () => {
    initializeHomePage();
});

function initializeHomePage() {
    // Initialize hero animations
    initializeHeroAnimations();
    
    // Setup product interactions
    setupProductInteractions();
    
    // Initialize parallax effects
    initializeParallax();
    
    // Setup floating button
    setupFloatingButton();
}

function initializeHeroAnimations() {
    // Animate hero elements on load
    const heroElements = document.querySelectorAll('.hero-title, .hero-subtitle, .hero-buttons, .hero-product');
    
    heroElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            element.style.transition = 'all 1s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 300);
    });
}

function setupProductInteractions() {
    // Add to cart functionality for featured products
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productCard = this.closest('.product-card');
            const productName = productCard.querySelector('.product-info h3').textContent;
            const productPrice = productCard.querySelector('.product-price').textContent;
            const productImage = productCard.querySelector('.product-image img').src;
            
            // Create product object
            const product = {
                id: Date.now(), // Simple ID generation for demo
                name: productName,
                price: parseInt(productPrice.replace(/[₹,]/g, '')),
                image: productImage,
                description: 'Premium luxury furniture piece'
            };
            
            cartManager.addToCart(product);
        });
    });
    
    // Quick view functionality
    document.querySelectorAll('.product-overlay .btn').forEach(btn => {
        if (btn.textContent.includes('Quick View')) {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const productCard = btn.closest('.product-card');
                const productName = productCard.querySelector('.product-info h3').textContent;
                const productPrice = productCard.querySelector('.product-price').textContent;
                const productImage = productCard.querySelector('.product-image img').src;
                
                showQuickView({
                    name: productName,
                    price: productPrice,
                    image: productImage,
                    description: 'This premium piece combines luxury and functionality, crafted with the finest materials and attention to detail.',
                    rating: 5,
                    reviews: 24
                });
            });
        }
    });
}

function initializeParallax() {
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.parallax-layer');
        
        parallaxElements.forEach(element => {
            const speed = element.dataset.speed || 0.5;
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
        
        // Update navbar on scroll
        const navbar = document.getElementById('navbar');
        if (scrolled > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

function setupFloatingButton() {
    const floatingBtn = document.getElementById('floating-btn');
    let isVisible = false;
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        
        if (scrolled > 300 && !isVisible) {
            floatingBtn.style.display = 'flex';
            floatingBtn.style.animation = 'fadeInUp 0.5s ease forwards';
            isVisible = true;
        } else if (scrolled <= 300 && isVisible) {
            floatingBtn.style.animation = 'fadeOut 0.5s ease forwards';
            setTimeout(() => {
                floatingBtn.style.display = 'none';
            }, 500);
            isVisible = false;
        }
    });
    
    floatingBtn.addEventListener('click', () => {
        window.location.href = 'pages/products.html';
    });
}

function showQuickView(product) {
    const modal = document.createElement('div');
    modal.className = 'quick-view-modal';
    modal.innerHTML = `
        <div class="modal-overlay" onclick="closeQuickView()">
            <div class="modal-content" onclick="event.stopPropagation()">
                <button class="close-modal" onclick="closeQuickView()">&times;</button>
                <div class="modal-grid">
                    <div class="modal-image">
                        <img src="${product.image}" alt="${product.name}">
                    </div>
                    <div class="modal-info">
                        <h2>${product.name}</h2>
                        <p class="modal-price">${product.price}</p>
                        <div class="modal-rating">
                            ${generateStars(product.rating || 5)}
                            <span>(${product.reviews || 24} Reviews)</span>
                        </div>
                        <p class="modal-description">${product.description}</p>
                        <div class="modal-actions">
                            <button class="btn btn-primary" onclick="addToCartFromModal('${product.name}', '${product.price}', '${product.image}'); closeQuickView();">
                                Add to Cart
                            </button>
                            <button class="btn btn-secondary" onclick="addToWishlist()">
                                Add to Wishlist
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

function closeQuickView() {
    const modal = document.querySelector('.quick-view-modal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += `<i class="fas fa-star${i <= rating ? '' : '-o'}"></i>`;
    }
    return stars;
}

function addToCartFromModal(name, price, image) {
    const product = {
        id: Date.now(),
        name: name,
        price: parseInt(price.replace(/[₹,]/g, '')),
        image: image,
        description: 'Premium luxury furniture piece'
    };
    
    cartManager.addToCart(product);
}

function addToWishlist() {
    showNotification('Product added to wishlist!', 'success');
}

// Watch Story button functionality
document.querySelector('.btn-secondary').addEventListener('click', () => {
    showNotification('Video story feature coming soon!', 'info');
});

// Smooth scrolling for scroll indicator
document.querySelector('.scroll-indicator').addEventListener('click', () => {
    document.querySelector('.featured-products').scrollIntoView({
        behavior: 'smooth'
    });
});