// Luxury Furniture E-commerce JavaScript
class LuxuryFurnitureApp {
    constructor() {
        this.currentTheme = localStorage.getItem('theme') || 'light';
        this.isSearchOpen = false;
        this.isVoiceRecording = false;
        this.init();
    }

    init() {
        this.setupTheme();
        this.setupNavigation();
        this.setupSearch();
        this.setupVoiceSearch();
        this.setupAnimations();
        this.setupScrollEffects();
        this.setupNotifications();
        this.setupCart();
        this.setupWishlist();
        this.setupModals();
        this.setupCarousels();
        this.setupLazyLoading();
        this.setupAccessibility();
    }

    // Theme Management
    setupTheme() {
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }

        // Apply saved theme
        this.applyTheme(this.currentTheme);
    }

    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.currentTheme = newTheme;
        localStorage.setItem('theme', newTheme);
        this.applyTheme(newTheme);

        // Send to server
        fetch('/theme/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': this.getCsrfToken(),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ theme: newTheme })
        });
    }

    applyTheme(theme) {
        document.documentElement.classList.remove('light', 'dark');
        document.documentElement.classList.add(theme);
        
        // Update theme toggle icon
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            const sunIcon = themeToggle.querySelector('.fa-sun');
            const moonIcon = themeToggle.querySelector('.fa-moon');
            
            if (theme === 'dark') {
                sunIcon?.classList.add('hidden');
                moonIcon?.classList.remove('hidden');
            } else {
                sunIcon?.classList.remove('hidden');
                moonIcon?.classList.add('hidden');
            }
        }
    }

    // Navigation
    setupNavigation() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const languageToggle = document.getElementById('language-toggle');
        const languageDropdown = document.getElementById('language-dropdown');
        const userMenuToggle = document.getElementById('user-menu-toggle');
        const userMenu = document.getElementById('user-menu');

        // Mobile menu
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Language dropdown
        if (languageToggle && languageDropdown) {
            languageToggle.addEventListener('click', () => {
                languageDropdown.classList.toggle('hidden');
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!languageToggle.contains(e.target)) {
                    languageDropdown.classList.add('hidden');
                }
            });
        }

        // User menu
        if (userMenuToggle && userMenu) {
            userMenuToggle.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!userMenuToggle.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }

        // Back to top button
        const backToTop = document.getElementById('back-to-top');
        if (backToTop) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTop.classList.remove('hidden');
                } else {
                    backToTop.classList.add('hidden');
                }
            });

            backToTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }

    // Search Functionality
    setupSearch() {
        const searchToggle = document.getElementById('search-toggle');
        const searchOverlay = document.getElementById('search-overlay');
        const searchInput = document.getElementById('search-input');
        const searchClose = document.getElementById('search-close');
        const searchSuggestions = document.getElementById('search-suggestions');

        if (searchToggle && searchOverlay) {
            searchToggle.addEventListener('click', () => this.openSearch());
        }

        if (searchClose) {
            searchClose.addEventListener('click', () => this.closeSearch());
        }

        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                
                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        this.performSearch(query);
                    }, 300);
                } else {
                    searchSuggestions.classList.add('hidden');
                }
            });

            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeSearch();
                } else if (e.key === 'Enter') {
                    this.submitSearch();
                }
            });
        }

        // Close on overlay click
        if (searchOverlay) {
            searchOverlay.addEventListener('click', (e) => {
                if (e.target === searchOverlay) {
                    this.closeSearch();
                }
            });
        }
    }

    openSearch() {
        const searchOverlay = document.getElementById('search-overlay');
        const searchInput = document.getElementById('search-input');
        
        if (searchOverlay && searchInput) {
            searchOverlay.classList.remove('hidden');
            searchInput.focus();
            this.isSearchOpen = true;
        }
    }

    closeSearch() {
        const searchOverlay = document.getElementById('search-overlay');
        const searchInput = document.getElementById('search-input');
        const searchSuggestions = document.getElementById('search-suggestions');
        
        if (searchOverlay && searchInput) {
            searchOverlay.classList.add('hidden');
            searchInput.value = '';
            searchSuggestions.classList.add('hidden');
            this.isSearchOpen = false;
        }
    }

    async performSearch(query) {
        try {
            const response = await fetch(`/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const suggestions = await response.json();
                this.displaySearchSuggestions(suggestions);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    displaySearchSuggestions(suggestions) {
        const searchSuggestions = document.getElementById('search-suggestions');
        if (!searchSuggestions) return;

        if (suggestions.length === 0) {
            searchSuggestions.innerHTML = '<div class="p-4 text-gray-500">No results found</div>';
        } else {
            searchSuggestions.innerHTML = suggestions.map(product => `
                <div class="p-4 hover:bg-gray-100 dark:hover:bg-navy-700 cursor-pointer border-b border-gray-200 dark:border-navy-700 last:border-b-0">
                    <div class="flex items-center space-x-3">
                        <img src="${product.images?.[0]?.url || '/images/product-placeholder.jpg'}" 
                             alt="${product.name}" 
                             class="w-12 h-12 object-cover rounded">
                        <div class="flex-1">
                            <h4 class="font-semibold text-navy-900 dark:text-white">${product.name}</h4>
                            <p class="text-sm text-gray-500">₹${product.current_price}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        searchSuggestions.classList.remove('hidden');
    }

    submitSearch() {
        const searchInput = document.getElementById('search-input');
        if (searchInput && searchInput.value.trim()) {
            window.location.href = `/search?q=${encodeURIComponent(searchInput.value.trim())}`;
        }
    }

    // Voice Search
    setupVoiceSearch() {
        const voiceSearchBtn = document.getElementById('voice-search');
        if (!voiceSearchBtn) return;

        voiceSearchBtn.addEventListener('click', () => {
            if (this.isVoiceRecording) {
                this.stopVoiceRecording();
            } else {
                this.startVoiceRecording();
            }
        });
    }

    startVoiceRecording() {
        if (!('webkitSpeechRecognition' in window)) {
            this.showNotification('Voice search is not supported in your browser', 'error');
            return;
        }

        const voiceSearchBtn = document.getElementById('voice-search');
        if (!voiceSearchBtn) return;

        const recognition = new webkitSpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        recognition.onstart = () => {
            this.isVoiceRecording = true;
            voiceSearchBtn.classList.add('bg-red-500', 'text-white');
            voiceSearchBtn.classList.remove('bg-gray-100', 'text-navy-700');
            this.showNotification('Listening... Speak now', 'info');
        };

        recognition.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            this.processVoiceSearch(transcript);
        };

        recognition.onerror = (event) => {
            this.showNotification('Voice recognition error', 'error');
            this.stopVoiceRecording();
        };

        recognition.onend = () => {
            this.stopVoiceRecording();
        };

        recognition.start();
    }

    stopVoiceRecording() {
        this.isVoiceRecording = false;
        const voiceSearchBtn = document.getElementById('voice-search');
        if (voiceSearchBtn) {
            voiceSearchBtn.classList.remove('bg-red-500', 'text-white');
            voiceSearchBtn.classList.add('bg-gray-100', 'text-navy-700');
        }
    }

    async processVoiceSearch(transcript) {
        try {
            const response = await fetch('/voice-search', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ audio: transcript })
            });

            if (response.ok) {
                const data = await response.json();
                this.showNotification(`Searching for: "${data.transcribed_text}"`, 'success');
                // Redirect to search results
                window.location.href = `/search?q=${encodeURIComponent(data.transcribed_text)}`;
            }
        } catch (error) {
            this.showNotification('Voice search error', 'error');
        }
    }

    // Animations
    setupAnimations() {
        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe elements with animation classes
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.parallax');
            
            parallaxElements.forEach(element => {
                const speed = element.dataset.speed || 0.5;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
    }

    // Scroll Effects
    setupScrollEffects() {
        let ticking = false;

        function updateScrollEffects() {
            const scrolled = window.pageYOffset;
            const nav = document.querySelector('nav');
            
            if (nav) {
                if (scrolled > 100) {
                    nav.classList.add('bg-white/95', 'dark:bg-navy-900/95', 'shadow-lg');
                } else {
                    nav.classList.remove('bg-white/95', 'dark:bg-navy-900/95', 'shadow-lg');
                }
            }

            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateScrollEffects);
                ticking = true;
            }
        });
    }

    // Notifications
    setupNotifications() {
        this.notificationContainer = document.createElement('div');
        this.notificationContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(this.notificationContainer);
    }

    showNotification(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `px-6 py-3 rounded-lg text-white font-semibold transform translate-x-full transition-transform duration-300 ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;

        this.notificationContainer.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, duration);
    }

    // Cart Management
    setupCart() {
        this.updateCartCount();
    }

    async updateCartCount() {
        try {
            const response = await fetch('/cart/summary');
            if (response.ok) {
                const data = await response.json();
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count || 0;
                }
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }

    // Wishlist Management
    setupWishlist() {
        this.updateWishlistCount();
    }

    async updateWishlistCount() {
        try {
            const response = await fetch('/wishlist/count');
            if (response.ok) {
                const data = await response.json();
                const wishlistCount = document.getElementById('wishlist-count');
                if (wishlistCount) {
                    wishlistCount.textContent = data.count || 0;
                }
            }
        } catch (error) {
            console.error('Error updating wishlist count:', error);
        }
    }

    // Modal Management
    setupModals() {
        // Close modals on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });

        // Close modals on outside click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                this.closeAllModals();
            }
        });
    }

    closeAllModals() {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.classList.add('hidden');
        });
    }

    // Carousel Management
    setupCarousels() {
        const carousels = document.querySelectorAll('[data-carousel]');
        
        carousels.forEach(carousel => {
            const container = carousel.querySelector('[data-carousel-container]');
            const prevBtn = carousel.querySelector('[data-carousel-prev]');
            const nextBtn = carousel.querySelector('[data-carousel-next]');
            const items = carousel.querySelectorAll('[data-carousel-item]');
            
            if (!container || !prevBtn || !nextBtn) return;

            let currentIndex = 0;
            const itemWidth = items[0]?.offsetWidth || 320;
            const visibleItems = Math.floor(container.offsetWidth / itemWidth);

            const updateCarousel = () => {
                const translateX = -currentIndex * itemWidth;
                container.style.transform = `translateX(${translateX}px)`;
                
                // Update button states
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= items.length - visibleItems;
            };

            prevBtn.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            });

            nextBtn.addEventListener('click', () => {
                if (currentIndex < items.length - visibleItems) {
                    currentIndex++;
                    updateCarousel();
                }
            });

            // Auto-play
            if (carousel.dataset.autoplay === 'true') {
                setInterval(() => {
                    if (currentIndex < items.length - visibleItems) {
                        currentIndex++;
                    } else {
                        currentIndex = 0;
                    }
                    updateCarousel();
                }, 5000);
            }

            updateCarousel();
        });
    }

    // Lazy Loading
    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    // Accessibility
    setupAccessibility() {
        // Skip link functionality
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(skipLink.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView();
                }
            });
        }

        // Keyboard navigation for dropdowns
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                // Handle dropdown focus management
                const dropdowns = document.querySelectorAll('[data-dropdown]');
                dropdowns.forEach(dropdown => {
                    const trigger = dropdown.querySelector('[data-dropdown-trigger]');
                    const menu = dropdown.querySelector('[data-dropdown-menu]');
                    
                    if (trigger && menu) {
                        if (document.activeElement === trigger) {
                            // Focus first menu item when dropdown opens
                            const firstItem = menu.querySelector('a, button');
                            if (firstItem) {
                                firstItem.focus();
                            }
                        }
                    }
                });
            }
        });
    }

    // Utility Methods
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
}

// Initialize the app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.luxuryFurnitureApp = new LuxuryFurnitureApp();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LuxuryFurnitureApp;
} 