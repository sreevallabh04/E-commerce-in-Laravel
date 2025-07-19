// API Configuration
const API_BASE_URL = '/api';
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// API Helper Functions
async function apiCall(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(CSRF_TOKEN && { 'X-CSRF-TOKEN': CSRF_TOKEN })
        }
    };
    
    try {
        const response = await fetch(url, { ...defaultOptions, ...options });
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.error || 'API request failed');
        }
        
        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Global state
let products = [];
let categories = [];
let cart = [];

// Theme Toggle
const themeToggle = document.getElementById('theme-toggle');
const body = document.body;

themeToggle.addEventListener('click', () => {
    body.classList.toggle('light-mode');
    body.classList.toggle('dark-mode');
    
    const icon = themeToggle.querySelector('i');
    if (body.classList.contains('light-mode')) {
        icon.className = 'fas fa-sun';
    } else {
        icon.className = 'fas fa-moon';
    }
});

// Navbar Scroll Effect
const navbar = document.getElementById('navbar');
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('nav-menu');

window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Mobile Menu Toggle
hamburger.addEventListener('click', () => {
    navMenu.classList.toggle('active');
    hamburger.classList.toggle('active');
});

// Parallax Effect
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.parallax-layer');
    
    parallaxElements.forEach(element => {
        const speed = element.dataset.speed;
        const yPos = -(scrolled * speed);
        element.style.transform = `translateY(${yPos}px)`;
    });
});

// Load Products from API
async function loadProducts() {
    try {
        const response = await apiCall('/products');
        products = response.data.data;
        updateProductDisplay();
        updateSearchSuggestions();
    } catch (error) {
        console.error('Failed to load products:', error);
        showNotification('Failed to load products', 'error');
    }
}

// Load Categories from API
async function loadCategories() {
    try {
        const response = await apiCall('/categories');
        categories = response.data;
    } catch (error) {
        console.error('Failed to load categories:', error);
    }
}

// Update Product Display
function updateProductDisplay() {
    const productsGrid = document.querySelector('.products-grid');
    if (!productsGrid) return;

    productsGrid.innerHTML = products.map(product => `
        <div class="product-card" data-product-id="${product.id}">
            <div class="product-image">
                <img src="${product.images?.[0]?.url || 'https://via.placeholder.com/400x300'}" alt="${product.name}">
                <div class="product-overlay">
                    <button class="btn btn-small quick-view-btn" data-product-id="${product.id}">Quick View</button>
                    <button class="btn btn-small add-to-cart-btn" data-product-id="${product.id}">Add to Cart</button>
                </div>
            </div>
            <div class="product-info">
                <h3>${product.name}</h3>
                <p class="product-price">₹${product.price.toLocaleString()}</p>
                <div class="product-rating">
                    ${generateStarRating(product.rating || 0)}
                    <span>(${product.review_count || 0} reviews)</span>
                </div>
            </div>
        </div>
    `).join('');

    // Reattach event listeners
    attachProductEventListeners();
}

// Generate Star Rating
function generateStarRating(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    let stars = '';
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    
    return stars;
}

// Update Search Suggestions
function updateSearchSuggestions() {
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');

    if (!searchInput || !searchSuggestions) return;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        
        if (query.length > 0) {
            const filtered = products.filter(product => 
                product.name.toLowerCase().includes(query) ||
                product.description?.toLowerCase().includes(query)
            );
            
            if (filtered.length > 0) {
                searchSuggestions.innerHTML = filtered.map(product => 
                    `<div class="search-suggestion" data-product-id="${product.id}">${product.name}</div>`
                ).join('');
                searchSuggestions.style.display = 'block';
            } else {
                searchSuggestions.style.display = 'none';
            }
        } else {
            searchSuggestions.style.display = 'none';
        }
    });

    // Handle suggestion clicks
    searchSuggestions.addEventListener('click', (e) => {
        if (e.target.classList.contains('search-suggestion')) {
            const productId = e.target.dataset.productId;
            const product = products.find(p => p.id == productId);
            if (product) {
                showQuickView(product);
            }
            searchSuggestions.style.display = 'none';
            searchInput.value = '';
        }
    });
}

// Hide suggestions when clicking outside
document.addEventListener('click', (e) => {
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');
    
    if (searchInput && searchSuggestions && !searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
        searchSuggestions.style.display = 'none';
    }
});

// Voice Search
const voiceSearch = document.getElementById('voice-search');
let recognition;

if ('webkitSpeechRecognition' in window) {
    recognition = new webkitSpeechRecognition();
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.lang = 'en-US';

    recognition.onstart = () => {
        voiceSearch.classList.add('active');
    };

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.value = transcript;
            searchInput.dispatchEvent(new Event('input'));
        }
        voiceSearch.classList.remove('active');
    };

    recognition.onerror = () => {
        voiceSearch.classList.remove('active');
        alert('Voice search not supported or microphone access denied.');
    };

    recognition.onend = () => {
        voiceSearch.classList.remove('active');
    };

    voiceSearch.addEventListener('click', () => {
        if (voiceSearch.classList.contains('active')) {
            recognition.stop();
        } else {
            recognition.start();
        }
    });
} else {
    voiceSearch.addEventListener('click', () => {
        alert('Voice search is not supported in this browser.');
    });
}

// Language Toggle
const langButtons = document.querySelectorAll('.lang-btn');
const translations = {
    en: {
        'Home': 'Home',
        'Products': 'Products',
        'About': 'About',
        'Contact': 'Contact',
        'Luxury Redefined': 'Luxury Redefined',
        'Experience premium furniture that transforms your space into a masterpiece': 'Experience premium furniture that transforms your space into a masterpiece',
        'Explore Collection': 'Explore Collection',
        'Watch Story': 'Watch Story',
        'Featured Collection': 'Featured Collection',
        'Curated pieces that define elegance': 'Curated pieces that define elegance',
        'Shop by Category': 'Shop by Category',
        'Discover our premium collections': 'Discover our premium collections',
        'Living Room': 'Living Room',
        'Bedroom': 'Bedroom',
        'Dining': 'Dining',
        'Shop Now': 'Shop Now',
        'Free Delivery': 'Free Delivery',
        '10 Year Warranty': '10 Year Warranty',
        '24/7 Support': '24/7 Support',
        'Premium Quality': 'Premium Quality'
    },
    hi: {
        'Home': 'होम',
        'Products': 'उत्पाद',
        'About': 'बारे में',
        'Contact': 'संपर्क',
        'Luxury Redefined': 'विलासिता पुनर्परिभाषित',
        'Experience premium furniture that transforms your space into a masterpiece': 'प्रीमियम फर्नीचर का अनुभव करें जो आपके स्थान को एक कलाकृति में बदल देता है',
        'Explore Collection': 'संग्रह देखें',
        'Watch Story': 'कहानी देखें',
        'Featured Collection': 'विशेष संग्रह',
        'Curated pieces that define elegance': 'शानदार टुकड़े जो भव्यता को परिभाषित करते हैं',
        'Shop by Category': 'श्रेणी के अनुसार खरीदें',
        'Discover our premium collections': 'हमारे प्रीमियम संग्रह की खोज करें',
        'Living Room': 'बैठक कक्ष',
        'Bedroom': 'शयनकक्ष',
        'Dining': 'भोजन कक्ष',
        'Shop Now': 'अभी खरीदें',
        'Free Delivery': 'मुफ्त डिलीवरी',
        '10 Year Warranty': '10 साल की गारंटी',
        '24/7 Support': '24/7 सहायता',
        'Premium Quality': 'प्रीमियम गुणवत्ता'
    }
};

langButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        langButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        const lang = btn.dataset.lang;
        translatePage(lang);
    });
});

function translatePage(lang) {
    const elements = document.querySelectorAll('[data-translate]');
    elements.forEach(element => {
        const key = element.dataset.translate;
        if (translations[lang] && translations[lang][key]) {
            element.textContent = translations[lang][key];
        }
    });
}

// Cart Functionality
const cartBtn = document.querySelector('.cart-btn');
const cartCount = document.querySelector('.cart-count');

// Load Cart from API
async function loadCart() {
    try {
        const response = await apiCall('/cart');
        cart = response.data.items;
        updateCartCount(response.data.cart_count);
    } catch (error) {
        console.error('Failed to load cart:', error);
        updateCartCount(0);
    }
}

// Add to Cart
async function addToCart(productId, quantity = 1) {
    try {
        const response = await apiCall('/cart/add', {
            method: 'POST',
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });
        
        updateCartCount(response.cart_count);
        showNotification(response.message);
        
        // Reload cart data
        await loadCart();
    } catch (error) {
        console.error('Failed to add to cart:', error);
        showNotification('Failed to add to cart', 'error');
    }
}

// Update Cart Item
async function updateCartItem(productId, quantity) {
    try {
        const response = await apiCall('/cart/update', {
            method: 'PUT',
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        });
        
        updateCartCount(response.cart_count);
        showNotification(response.message);
        
        // Reload cart data
        await loadCart();
    } catch (error) {
        console.error('Failed to update cart:', error);
        showNotification('Failed to update cart', 'error');
    }
}

// Remove from Cart
async function removeFromCart(productId) {
    try {
        const response = await apiCall('/cart/remove', {
            method: 'DELETE',
            body: JSON.stringify({
                product_id: productId
            })
        });
        
        updateCartCount(response.cart_count);
        showNotification(response.message);
        
        // Reload cart data
        await loadCart();
    } catch (error) {
        console.error('Failed to remove from cart:', error);
        showNotification('Failed to remove from cart', 'error');
    }
}

// Clear Cart
async function clearCart() {
    try {
        const response = await apiCall('/cart/clear', {
            method: 'POST'
        });
        
        updateCartCount(0);
        showNotification(response.message);
        
        // Reload cart data
        await loadCart();
    } catch (error) {
        console.error('Failed to clear cart:', error);
        showNotification('Failed to clear cart', 'error');
    }
}

function updateCartCount(count) {
    if (cartCount) {
        cartCount.textContent = count;
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'error' ? '#e74c3c' : 'var(--primary-color)'};
        color: var(--secondary-color);
        padding: 1rem 2rem;
        border-radius: 10px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add notification styles
const notificationStyles = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);

// Floating Button
const floatingBtn = document.getElementById('floating-btn');
let isFloatingBtnVisible = false;

window.addEventListener('scroll', () => {
    if (window.scrollY > 300 && !isFloatingBtnVisible) {
        floatingBtn.style.display = 'flex';
        floatingBtn.style.animation = 'fadeInUp 0.5s ease forwards';
        isFloatingBtnVisible = true;
    } else if (window.scrollY <= 300 && isFloatingBtnVisible) {
        floatingBtn.style.animation = 'fadeOut 0.5s ease forwards';
        setTimeout(() => {
            floatingBtn.style.display = 'none';
        }, 500);
        isFloatingBtnVisible = false;
    }
});

floatingBtn.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Add fadeOut animation
const fadeOutStyles = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }
`;

const fadeOutStyleSheet = document.createElement('style');
fadeOutStyleSheet.textContent = fadeOutStyles;
document.head.appendChild(fadeOutStyleSheet);

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.8s ease forwards';
        }
    });
}, observerOptions);

// Observe elements for animation
document.querySelectorAll('.product-card, .feature-card, .category-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    observer.observe(el);
});

// Smooth scrolling for navigation links
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Attach Product Event Listeners
function attachProductEventListeners() {
    // Quick View Buttons
    document.querySelectorAll('.quick-view-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const productId = btn.dataset.productId;
            const product = products.find(p => p.id == productId);
            if (product) {
                showQuickView(product);
            }
        });
    });

    // Add to Cart Buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const productId = btn.dataset.productId;
            addToCart(productId, 1);
        });
    });
}

// Product Quick View
function showQuickView(product) {
    const modal = document.createElement('div');
    modal.className = 'quick-view-modal';
    modal.innerHTML = `
        <div class="modal-overlay">
            <div class="modal-content">
                <button class="close-modal">&times;</button>
                <div class="modal-grid">
                    <div class="modal-image">
                        <img src="${product.images?.[0]?.url || 'https://via.placeholder.com/400x300'}" alt="${product.name}">
                    </div>
                    <div class="modal-info">
                        <h2>${product.name}</h2>
                        <p class="modal-price">₹${product.price.toLocaleString()}</p>
                        <div class="modal-rating">
                            ${generateStarRating(product.rating || 0)}
                            <span>(${product.review_count || 0} reviews)</span>
                        </div>
                        <p class="modal-description">
                            ${product.description || 'This premium piece combines luxury and functionality, crafted with the finest materials and attention to detail.'}
                        </p>
                        <div class="modal-actions">
                            <button class="btn btn-primary modal-add-to-cart" data-product-id="${product.id}">Add to Cart</button>
                            <button class="btn btn-secondary">Add to Wishlist</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    
    document.body.appendChild(modal);
    
    // Close modal
    modal.querySelector('.close-modal').addEventListener('click', () => {
        modal.remove();
    });
    
    modal.querySelector('.modal-overlay').addEventListener('click', (e) => {
        if (e.target === modal.querySelector('.modal-overlay')) {
            modal.remove();
        }
    });

    // Add to cart from modal
    modal.querySelector('.modal-add-to-cart').addEventListener('click', () => {
        addToCart(product.id, 1);
        modal.remove();
    });
}

// Add modal styles
const modalStyles = `
    .modal-overlay {
        background: rgba(0, 0, 0, 0.9);
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }
    
    .modal-content {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 2rem;
        max-width: 800px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        animation: slideInUp 0.3s ease;
    }
    
    .close-modal {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        font-size: 2rem;
        color: var(--text-light);
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
    
    .close-modal:hover {
        opacity: 1;
    }
    
    .modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: start;
    }
    
    .modal-image img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 10px;
    }
    
    .modal-info h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: var(--text-light);
    }
    
    .modal-price {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .modal-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }
    
    .modal-rating span {
        color: var(--text-light);
        opacity: 0.7;
    }
    
    .modal-description {
        color: var(--text-light);
        opacity: 0.8;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .modal-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    @keyframes slideInUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @media (max-width: 768px) {
        .modal-grid {
            grid-template-columns: 1fr;
        }
        
        .modal-actions {
            flex-direction: column;
        }
    }
`;

const modalStyleSheet = document.createElement('style');
modalStyleSheet.textContent = modalStyles;
document.head.appendChild(modalStyleSheet);

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
    // Load initial data
    await Promise.all([
        loadProducts(),
        loadCategories(),
        loadCart()
    ]);
    
    // Set initial cart count
    updateCartCount(cart.length);
    
    // Initialize theme
    if (localStorage.getItem('theme') === 'light') {
        body.classList.add('light-mode');
        body.classList.remove('dark-mode');
        themeToggle.querySelector('i').className = 'fas fa-sun';
    }
    
    // Save theme preference
    themeToggle.addEventListener('click', () => {
        if (body.classList.contains('light-mode')) {
            localStorage.setItem('theme', 'light');
        } else {
            localStorage.setItem('theme', 'dark');
        }
    });
});