// Cart page functionality

document.addEventListener('DOMContentLoaded', () => {
    loadCartPage();
});

function loadCartPage() {
    const cartContainer = document.getElementById('cart-container');
    const cartEmpty = document.getElementById('cart-empty');
    const cart = cartManager.getCart();
    
    if (cart.length === 0) {
        cartContainer.style.display = 'none';
        cartEmpty.style.display = 'block';
        return;
    }
    
    cartContainer.style.display = 'block';
    cartEmpty.style.display = 'none';
    
    cartContainer.innerHTML = `
        <div class="cart-items">
            ${cart.map(item => createCartItemHTML(item)).join('')}
        </div>
        <div class="cart-summary">
            <div class="summary-card">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>₹${cartManager.getCartTotal().toLocaleString()}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <div class="summary-row">
                    <span>Tax:</span>
                    <span>₹${Math.round(cartManager.getCartTotal() * 0.18).toLocaleString()}</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>₹${Math.round(cartManager.getCartTotal() * 1.18).toLocaleString()}</span>
                </div>
                <div class="summary-actions">
                    <button class="btn btn-primary btn-full" onclick="proceedToCheckout()">
                        Proceed to Checkout
                    </button>
                    <button class="btn btn-secondary btn-full" onclick="continueShopping()">
                        Continue Shopping
                    </button>
                </div>
            </div>
        </div>
    `;
}

function createCartItemHTML(item) {
    return `
        <div class="cart-item" data-product-id="${item.id}">
            <div class="item-image">
                <img src="${item.image}" alt="${item.name}">
            </div>
            <div class="item-details">
                <h3>${item.name}</h3>
                <p class="item-price">₹${item.price.toLocaleString()}</p>
                <p class="item-description">${item.description || 'Premium luxury furniture piece'}</p>
            </div>
            <div class="item-quantity">
                <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})">
                    <i class="fas fa-minus"></i>
                </button>
                <span class="quantity">${item.quantity}</span>
                <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="item-total">
                <p>₹${(item.price * item.quantity).toLocaleString()}</p>
            </div>
            <div class="item-actions">
                <button class="remove-btn" onclick="removeFromCart(${item.id})" title="Remove item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
}

function updateCartQuantity(productId, newQuantity) {
    cartManager.updateQuantity(productId, newQuantity);
    loadCartPage();
}

function removeFromCart(productId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        cartManager.removeFromCart(productId);
        loadCartPage();
    }
}

function proceedToCheckout() {
    // In a real application, this would redirect to a checkout page
    showNotification('Checkout functionality would be implemented here', 'info');
    
    // For demo purposes, show a checkout modal
    showCheckoutModal();
}

function continueShopping() {
    window.location.href = 'products.html';
}

function showCheckoutModal() {
    const modal = document.createElement('div');
    modal.className = 'checkout-modal';
    modal.innerHTML = `
        <div class="modal-overlay" onclick="closeCheckoutModal()">
            <div class="modal-content" onclick="event.stopPropagation()">
                <button class="close-modal" onclick="closeCheckoutModal()">&times;</button>
                <div class="checkout-content">
                    <h2>Checkout</h2>
                    <p>Thank you for choosing LuxFurn!</p>
                    <div class="checkout-summary">
                        <h3>Order Summary</h3>
                        <p>Total Items: ${cartManager.getCart().reduce((total, item) => total + item.quantity, 0)}</p>
                        <p>Total Amount: ₹${Math.round(cartManager.getCartTotal() * 1.18).toLocaleString()}</p>
                    </div>
                    <div class="checkout-actions">
                        <button class="btn btn-primary" onclick="completeOrder()">
                            Complete Order
                        </button>
                        <button class="btn btn-secondary" onclick="closeCheckoutModal()">
                            Continue Shopping
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

function closeCheckoutModal() {
    const modal = document.querySelector('.checkout-modal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
}

function completeOrder() {
    // Simulate order completion
    const orderNumber = Math.random().toString(36).substr(2, 9).toUpperCase();
    
    showNotification(`Order #${orderNumber} placed successfully! Thank you for your purchase.`, 'success');
    
    // Clear cart
    cartManager.clearCart();
    
    // Close modal and redirect
    closeCheckoutModal();
    setTimeout(() => {
        window.location.href = '../index.html';
    }, 2000);
}