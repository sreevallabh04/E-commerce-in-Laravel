// Products page functionality

// Sample product data
const products = [
    {
        id: 1,
        name: 'Premium Velvet Sofa',
        price: 299999,
        category: 'living-room',
        image: 'https://images.pexels.com/photos/1866149/pexels-photo-1866149.jpeg?auto=compress&cs=tinysrgb&w=600',
        rating: 5,
        reviews: 24,
        description: 'Luxurious velvet sofa with premium comfort and elegant design.'
    },
    {
        id: 2,
        name: 'Marble Dining Table',
        price: 189999,
        category: 'dining',
        image: 'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg?auto=compress&cs=tinysrgb&w=600',
        rating: 5,
        reviews: 18,
        description: 'Elegant marble dining table perfect for luxury dining experiences.'
    },
    {
        id: 3,
        name: 'Royal Bedroom Set',
        price: 499999,
        category: 'bedroom',
        image: 'https://images.pexels.com/photos/1350789/pexels-photo-1350789.jpeg?auto=compress&cs=tinysrgb&w=600',
        rating: 5,
        reviews: 31,
        description: 'Complete royal bedroom set with premium materials and craftsmanship.'
    },
    {
        id: 4,
        name: 'Executive Office Chair',
        price: 89999,
        category: 'office',
        image: 'https://images.pexels.com/photos/1571470/pexels-photo-1571470.jpeg?auto=compress&cs=tinysrgb&w=600',
        rating: 4,
        reviews: 12,
        description: 'Ergonomic executive chair with premium leather and advanced features.'
    },
    {
        id: 5,
        name: 'Designer Coffee Table',
        price: 79999,
        category: 'living-room',
        image: 'https://images.pexels.com/photos/1571453/pexels-photo-1571453.jpeg?auto=compress&cs=tinysrgb&w=600',
        rating: 4,
        reviews: 15,
        description: 'Modern designer coffee table with unique artistic design.'
    },
    {
        id: 6,
        name: 'Luxury Wardrobe',
        price: 349999,
        category: 'bedroom',
        image: 'https://images.pexels.com/photos/1571468/pexels-photo-1571468.jpeg?auto=compress&cs=tinysrgb&w=600',
        rating: 5,
        reviews: 22,
        description: 'Spacious luxury wardrobe with premium finishes and smart storage.'
    },
    {
        id: 7,
        name: 'Leather Recliner',
        price: 159999,
        category: 'living-room',
        image: 'https://images.pexels.com/photos/1571467/pexels-photo-1571467.jpeg?auto=compress&cs=tinysrgb&w=600',
        rating: 4,
        reviews: 19,
        description: 'Premium leather recliner with massage and heating features.'
    },
    {
        id: 8,
        name: 'Glass Dining Set',
        price: 229999,
        category: 'dining',
        image: 'https://images.pexels.com/photos/1571463/pexels-photo-1571463.jpeg?auto=compress&cs=tinysrgb&w=600',
        rating: 4,
        reviews: 14,
        description: 'Modern glass dining set with contemporary design and comfort.'
    },
    {
        id: 9,
        name: 'Wooden Bed Frame',
        price: 199999,
        category: 'bedroom',
        image: 'https://images.pexels.com/photos/1571458/pexels-photo-1571458.jpeg?auto=compress&cs=tinysrgb&w=600',
        rating: 5,
        reviews: 28,
        description: 'Handcrafted wooden bed frame with premium oak wood construction.'
    }
];

let currentPage = 1;
const itemsPerPage = 6;
let filteredProducts = [...products];

// Initialize products page
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    setupFilters();
    setupPagination();
    
    // Check for URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');
    const search = urlParams.get('search');
    
    if (category) {
        document.getElementById('category-filter').value = category;
        applyFilters();
    }
    
    if (search) {
        document.getElementById('search-input').value = search;
        applyFilters();
    }
});

function loadProducts() {
    const productsGrid = document.getElementById('products-grid');
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const productsToShow = filteredProducts.slice(startIndex, endIndex);
    
    if (productsToShow.length === 0) {
        productsGrid.innerHTML = `
            <div class="no-products">
                <i class="fas fa-search"></i>
                <h3>No products found</h3>
                <p>Try adjusting your filters or search terms.</p>
            </div>
        `;
        return;
    }
    
    productsGrid.innerHTML = productsToShow.map(product => `
        <div class="product-card" data-product-id="${product.id}">
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}" loading="lazy">
                <div class="product-overlay">
                    <button class="btn btn-small quick-view" onclick="showQuickView(${product.id})">
                        Quick View
                    </button>
                    <button class="btn btn-small add-to-cart" onclick="addToCart(${product.id})">
                        Add to Cart
                    </button>
                </div>
            </div>
            <div class="product-info">
                <h3>${product.name}</h3>
                <p class="product-price">₹${product.price.toLocaleString()}</p>
                <div class="product-rating">
                    ${generateStars(product.rating)}
                    <span>(${product.reviews} reviews)</span>
                </div>
            </div>
        </div>
    `).join('');
    
    // Initialize animations for new products
    setTimeout(() => {
        initializeAnimations();
    }, 100);
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += `<i class="fas fa-star${i <= rating ? '' : '-o'}"></i>`;
    }
    return stars;
}

function setupFilters() {
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const sortFilter = document.getElementById('sort-filter');
    
    categoryFilter.addEventListener('change', applyFilters);
    priceFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);
}

function applyFilters() {
    const categoryFilter = document.getElementById('category-filter').value;
    const priceFilter = document.getElementById('price-filter').value;
    const sortFilter = document.getElementById('sort-filter').value;
    const searchQuery = document.getElementById('search-input').value.toLowerCase();
    
    // Filter products
    filteredProducts = products.filter(product => {
        // Category filter
        if (categoryFilter && product.category !== categoryFilter) {
            return false;
        }
        
        // Price filter
        if (priceFilter) {
            const [min, max] = priceFilter.split('-').map(p => p.replace('+', ''));
            if (max) {
                if (product.price < parseInt(min) || product.price > parseInt(max)) {
                    return false;
                }
            } else {
                if (product.price < parseInt(min)) {
                    return false;
                }
            }
        }
        
        // Search filter
        if (searchQuery && !product.name.toLowerCase().includes(searchQuery)) {
            return false;
        }
        
        return true;
    });
    
    // Sort products
    switch (sortFilter) {
        case 'price-low':
            filteredProducts.sort((a, b) => a.price - b.price);
            break;
        case 'price-high':
            filteredProducts.sort((a, b) => b.price - a.price);
            break;
        case 'rating':
            filteredProducts.sort((a, b) => b.rating - a.rating);
            break;
        case 'newest':
            filteredProducts.sort((a, b) => b.id - a.id);
            break;
        default:
            filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
    }
    
    currentPage = 1;
    loadProducts();
    updatePagination();
}

function setupPagination() {
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            loadProducts();
            updatePagination();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    
    nextBtn.addEventListener('click', () => {
        const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            loadProducts();
            updatePagination();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
}

function updatePagination() {
    const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    const paginationInfo = document.querySelector('.pagination-info');
    
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    
    if (totalPages > 0) {
        paginationInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    } else {
        paginationInfo.textContent = 'No results';
    }
}

function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {
        cartManager.addToCart(product);
    }
}

function showQuickView(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    
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
                        <p class="modal-price">₹${product.price.toLocaleString()}</p>
                        <div class="modal-rating">
                            ${generateStars(product.rating)}
                            <span>(${product.reviews} Reviews)</span>
                        </div>
                        <p class="modal-description">${product.description}</p>
                        <div class="modal-actions">
                            <button class="btn btn-primary" onclick="addToCart(${product.id}); closeQuickView();">
                                Add to Cart
                            </button>
                            <button class="btn btn-secondary" onclick="addToWishlist(${product.id})">
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

function addToWishlist(productId) {
    // Wishlist functionality would be implemented here
    showNotification('Product added to wishlist!', 'success');
}

// Search functionality specific to products page
document.getElementById('search-input').addEventListener('input', debounce(applyFilters, 300));

function debounce(func, wait) {
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