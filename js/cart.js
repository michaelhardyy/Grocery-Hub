// Cart functionality
let cart = JSON.parse(localStorage.getItem('cart')) || [];
const cartButton = document.getElementById('cart-button');
const cartPopup = document.getElementById('cart-popup');
const cartItemsContainer = document.getElementById('cart-items');
const cartTotal = document.getElementById('cart-total');
const cartCount = document.getElementById('cart-count');
const clearCartButton = document.getElementById('clear-cart');
const checkoutButton = document.getElementById('checkout');

// Initialize the cart
updateCartDisplay();

// Event listeners
cartButton.addEventListener('click', toggleCart);
clearCartButton.addEventListener('click', clearCart);
checkoutButton.addEventListener('click', goToCheckout);

// Add event listeners to all "Add to Cart" buttons
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', addToCart);
});

// Function to toggle cart visibility
function toggleCart() {
    cartPopup.style.display = cartPopup.style.display === 'block' ? 'none' : 'block';
}

// Function to add a product to the cart
function addToCart(event) {
    const button = event.currentTarget;
    const productId = parseInt(button.getAttribute('data-id'));
    const productName = button.getAttribute('data-name');
    const productPrice = parseFloat(button.getAttribute('data-price'));
    const productQuantity = button.getAttribute('data-quantity');
    
    // Check if the product is already in the cart
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        // Increment quantity if product already in cart
        existingItem.quantity += 1;
    } else {
        // Add new item to cart
        cart.push({
            id: productId,
            name: productName,
            price: productPrice,
            unitQuantity: productQuantity,
            quantity: 1
        });
    }

    // Save cart to localStorage
    saveCart();
    
    // Update the cart display
    updateCartDisplay();
    
    // Provide visual feedback
    const originalText = button.textContent;
    button.textContent = 'Added!';
    button.style.backgroundColor = '#3e8e41';
    
    setTimeout(() => {
        button.textContent = originalText;
        button.style.backgroundColor = '';
    }, 1000);
}

// Function to remove an item from the cart
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    updateCartDisplay();
}

// Function to update item quantity
function updateItemQuantity(productId, newQuantity) {
    const item = cart.find(item => item.id === productId);
    
    if (item) {
        if (newQuantity > 0) {
            item.quantity = newQuantity;
        } else {
            // Remove item if quantity is zero or negative
            removeFromCart(productId);
        }
        
        saveCart();
        updateCartDisplay();
    }
}

// Function to clear the cart
function clearCart() {
    cart = [];
    saveCart();
    updateCartDisplay();
}

// Function to update the cart display
function updateCartDisplay() {
    // Update cart count
    cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);
    
    // Calculate total
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    cartTotal.textContent = total.toFixed(2);
    
    // Enable/disable checkout button
    checkoutButton.disabled = cart.length === 0;
    
    // Clear current cart items display
    cartItemsContainer.innerHTML = '';
    
    if (cart.length === 0) {
        // Show empty cart message
        cartItemsContainer.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
    } else {
        // Display cart items
        cart.forEach(item => {
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';
            cartItem.innerHTML = `
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">$${item.price.toFixed(2)} - ${item.unitQuantity}</div>
                    <div class="cart-item-quantity">Quantity: ${item.quantity}</div>
                </div>
                <div class="cart-item-controls">
                    <button class="decrease-quantity" data-id="${item.id}">-</button>
                    <button class="increase-quantity" data-id="${item.id}">+</button>
                    <button class="remove-item" data-id="${item.id}">×</button>
                </div>
            `;
            
            cartItemsContainer.appendChild(cartItem);
        });
        
        // Add event listeners to cart control buttons
        document.querySelectorAll('.decrease-quantity').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = parseInt(e.target.getAttribute('data-id'));
                const item = cart.find(item => item.id === id);
                if (item) {
                    updateItemQuantity(id, item.quantity - 1);
                }
            });
        });
        
        document.querySelectorAll('.increase-quantity').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = parseInt(e.target.getAttribute('data-id'));
                const item = cart.find(item => item.id === id);
                if (item) {
                    updateItemQuantity(id, item.quantity + 1);
                }
            });
        });
        
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = parseInt(e.target.getAttribute('data-id'));
                removeFromCart(id);
            });
        });
    }
}

// Function to save cart to localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Function to go to checkout
function goToCheckout() {
    if (cart.length > 0) {
        window.location.href = 'checkout.php';
    }
}




