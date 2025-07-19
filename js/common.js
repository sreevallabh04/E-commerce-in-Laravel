// Common functionality across all pages

// Theme Toggle
const themeToggle = document.getElementById('theme-toggle');
const body = document.body;

// Initialize theme from localStorage
const savedTheme = localStorage.getItem('theme') || 'dark';
body.className = savedTheme + '-mode';
updateThemeIcon();

themeToggle.addEventListener('click', () => {
    if (body.classList.contains('dark-mode')) {
        body.classList.remove('dark-mode');
        body.classList.add('light-mode');
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.remove('light-mode');
        body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
    }
    updateThemeIcon();
});

function updateThemeIcon() {
    const icon = themeToggle.querySelector('i');
    if (body.classList.contains('light-mode')) {
        icon.className = 'fas fa-sun';
    } else {
        icon.className = 'fas fa-moon';
    }
}

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

// Close mobile menu when clicking on a link
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        hamburger.classList.remove('active');
    });
});

// Search Functionality
const searchInput = document.getElementById('search-input');
const searchSuggestions = document.getElementById('search-suggestions');

const sampleProducts = [
    'Premium Velvet Sofa',
    'Marble Dining Table',
    'Royal Bedroom Set',
    'Executive Office Chair',
    'Luxury Wardrobe',
    'Designer Coffee Table',
    'Ergonomic Desk Chair',
    'Modern Bookshelf',
    'Leather Recliner',
    'Glass Dining Set',
    'Wooden Bed Frame',
    'Fabric Armchair',
    'Steel Dining Chair',
    'Oak Wood Table',
    'Velvet Ottoman'
];

searchInput.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase();
    
    if (query.length > 0) {
        const filtered = sampleProducts.filter(product => 
            product.toLowerCase().includes(query)
        );
        
        if (filtered.length > 0) {
            searchSuggestions.innerHTML = filtered.slice(0, 5).map(product => 
                `<div class="search-suggestion" onclick="selectSuggestion('${product}')">${product}</div>`
            ).join('');
            searchSuggestions.style.display = 'block';
        } else {
            searchSuggestions.style.display = 'none';
        }
    } else {
        searchSuggestions.style.display = 'none';
    }
});

function selectSuggestion(product) {
    searchInput.value = product;
    searchSuggestions.style.display = 'none';
    // Redirect to products page with search query
    window.location.href = `pages/products.html?search=${encodeURIComponent(product)}`;
}

// Hide suggestions when clicking outside
document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
        searchSuggestions.style.display = 'none';
    }
});

// Voice Search
const voiceSearch = document.getElementById('voice-search');
let recognition;

if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.lang = 'en-US';

    recognition.onstart = () => {
        voiceSearch.classList.add('active');
    };

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        searchInput.value = transcript;
        searchInput.dispatchEvent(new Event('input'));
        voiceSearch.classList.remove('active');
    };

    recognition.onerror = () => {
        voiceSearch.classList.remove('active');
        showNotification('Voice search not supported or microphone access denied.', 'error');
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
        showNotification('Voice search is not supported in this browser.', 'error');
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

// Initialize language from localStorage
const savedLang = localStorage.getItem('language') || 'en';
langButtons.forEach(btn => {
    btn.classList.toggle('active', btn.dataset.lang === savedLang);
});

langButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        langButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        const lang = btn.dataset.lang;
        localStorage.setItem('language', lang);
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

// Cart Management
class CartManager {
    constructor() {
        this.cart = JSON.parse(localStorage.getItem('cart')) || [];
        this.updateCartCount();
    }

    addToCart(product) {
        const existingItem = this.cart.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.cart.push({
                ...product,
                quantity: 1
            });
        }
        
        this.saveCart();
        this.updateCartCount();
        showNotification('Product added to cart!', 'success');
    }

    removeFromCart(productId) {
        this.cart = this.cart.filter(item => item.id !== productId);
        this.saveCart();
        this.updateCartCount();
        showNotification('Product removed from cart!', 'info');
    }

    updateQuantity(productId, quantity) {
        const item = this.cart.find(item => item.id === productId);
        if (item) {
            if (quantity <= 0) {
                this.removeFromCart(productId);
            } else {
                item.quantity = quantity;
                this.saveCart();
                this.updateCartCount();
            }
        }
    }

    getCart() {
        return this.cart;
    }

    getCartTotal() {
        return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    clearCart() {
        this.cart = [];
        this.saveCart();
        this.updateCartCount();
    }

    saveCart() {
        localStorage.setItem('cart', JSON.stringify(this.cart));
    }

    updateCartCount() {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            const totalItems = this.cart.reduce((total, item) => total + item.quantity, 0);
            cartCount.textContent = totalItems;
        }
    }
}

// Initialize cart manager
const cartManager = new CartManager();

// Notification System
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${getNotificationIcon(type)}"></i>
        <span>${message}</span>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

function getNotificationIcon(type) {
    switch (type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        default: return 'info-circle';
    }
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Loading animation
function showLoading() {
    const loader = document.createElement('div');
    loader.className = 'loading-overlay';
    loader.innerHTML = `
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Loading...</p>
        </div>
    `;
    document.body.appendChild(loader);
    return loader;
}

function hideLoading(loader) {
    if (loader && loader.parentElement) {
        loader.remove();
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', () => {
    // Apply saved language
    translatePage(savedLang);
    
    // Initialize animations
    initializeAnimations();
});

// Animation observer
function initializeAnimations() {
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
    document.querySelectorAll('.product-card, .feature-card, .category-card, .value-card, .team-member').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        observer.observe(el);
    });
}