<?php
include 'db.php';
include 'includes/categories.php';


// Initialize error messages
$errors = [];
$formSubmitted = false;
$orderSuccess = false;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formSubmitted = true;
    
    // Validate recipient name
    if (empty($_POST['recipient-name'])) {
        $errors['recipient-name'] = 'Recipient name is required';
    }
    
    // Validate address
    if (empty($_POST['address'])) {
        $errors['address'] = 'Address is required';
    }
    
    // Validate mobile number (basic Australian format)
    if (empty($_POST['mobile'])) {
        $errors['mobile'] = 'Mobile number is required';
    } elseif (!preg_match('/^04\d{8}$/', $_POST['mobile'])) {
        $errors['mobile'] = 'Please enter a valid Australian mobile number (e.g., 0412345678)';
    }
    
    // Validate email
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email address is required';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    // Get cart data
    $cartData = isset($_POST['cart-data']) ? $_POST['cart-data'] : '';
    $cart = json_decode($cartData, true);
    
    if (empty($cart)) {
        $errors['cart'] = 'Your shopping cart is empty';
    }
    
    // If no errors, process the order
    if (empty($errors)) {
        // Validate product availability and stock
        $stockErrors = false;
        
        foreach ($cart as $item) {
            $productId = $item['id'];
            $requestedQty = $item['quantity'];
            
            // Check current stock
            $sql = "SELECT in_stock FROM products WHERE product_id = $productId";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $currentStock = $row['in_stock'];
                
                // Check if enough stock
                if ($currentStock < $requestedQty) {
                    $stockErrors = true;
                    break;
                }
            } else {
                $stockErrors = true;
                break;
            }
        }
        
        if ($stockErrors) {
            $errors['stock'] = 'Some items in your cart are no longer available in the requested quantity.';
        } else {
            // Update stock for each product
            foreach ($cart as $item) {
                $productId = $item['id'];
                $requestedQty = $item['quantity'];
                
                $sql = "UPDATE products SET in_stock = in_stock - $requestedQty WHERE product_id = $productId";
                $conn->query($sql);
            }
            
            $recipientName = $_POST['recipient-name'];
            $email = $_POST['email'];
            $subject = "GroceryHub Order Confirmation";
            
            $message = "Thank you for your order, $recipientName!\n\n";
            $message .= "Order Details:\n";
            
            $total = 0;
            foreach ($cart as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                $message .= "{$item['name']} - {$item['unitQuantity']} - Qty: {$item['quantity']} - $" . number_format($subtotal, 2) . "\n";
            }
            
            $message .= "\nTotal: $" . number_format($total, 2) . "\n\n";
            $message .= "Your order will be delivered to:\n";
            $message .= $_POST['address'] . "\n\n";
            $message .= "If you have any questions, please contact us.";
            
            // In a real application, you would send the email here
            // mail($email, $subject, $message);
            
            // For demo purposes, let's just show success
            $orderSuccess = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - GroceryHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">
            <img src="images/groceryLogo.jpg" >
            </a>
        </div>
        <div class="search-container">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search for products...">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="cart-container">
            <button id="cart-button">
                Shopping Cart (<span id="cart-count">0</span>)
            </button>
        </div>
    </header>

    <nav class="categories">
        <ul class="main-categories">
            <?php foreach ($categories as $category => $info): ?>
            <li class="category-item">
                <a href="category.php?name=<?php echo urlencode($category); ?>&min=<?php echo $info['range'][0]; ?>&max=<?php echo $info['range'][1]; ?>">
                    <?php echo $category; ?>
                </a>
                
                <div class="subcategories">
                    <ul>
                        <?php foreach ($info['subcategories'] as $subcategory => $subRange): ?>
                        <li>
                            <a href="category.php?name=<?php echo urlencode($category); ?>&subname=<?php echo urlencode($subcategory); ?>&min=<?php echo $subRange[0]; ?>&max=<?php echo $subRange[1]; ?>">
                                <?php echo $subcategory; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <main>
        <?php if ($orderSuccess): ?>
        <section class="order-success">
            <h2>Order Placed Successfully!</h2>
            <p>Thank you for your order. A confirmation email has been sent to your email address.</p>
            <p>Your items will be delivered to the address you provided.</p>
            <p><a href="index.php" class="continue-shopping">Continue Shopping</a></p>
        </section>
        <?php else: ?>
        <section class="checkout-container">
            <h2>Checkout</h2>
            
            <?php if (!empty($errors['cart']) && $formSubmitted): ?>
            <div class="error-message">
                <p><?php echo $errors['cart']; ?></p>
                <p><a href="index.php">Continue Shopping</a></p>
            </div>
            <?php else: ?>
            
            <?php if (!empty($errors['stock']) && $formSubmitted): ?>
            <div class="error-message">
                <p><?php echo $errors['stock']; ?></p>
                <p><a href="index.php">Continue Shopping</a></p>
            </div>
            <?php endif; ?>
            
            <div class="checkout-summary">
                <h3>Order Summary</h3>
                <div id="checkout-items">
                    <!-- Cart items will be displayed here -->
                </div>
                <p class="checkout-total">Total: $<span id="checkout-total">0.00</span></p>
            </div>
            
            <form class="checkout-form" method="POST" action="checkout.php" id="checkout-form">
                <div class="form-group">
                    <label for="recipient-name">Recipient Name *</label>
                    <input type="text" id="recipient-name" name="recipient-name" required>
                    <?php if (!empty($errors['recipient-name']) && $formSubmitted): ?>
                    <p class="error"><?php echo $errors['recipient-name']; ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="address">Delivery Address *</label>
                    <input type="text" id="address" name="address" required>
                    <?php if (!empty($errors['address']) && $formSubmitted): ?>
                    <p class="error"><?php echo $errors['address']; ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="mobile">Australian Mobile Number *</label>
                    <input type="text" id="mobile" name="mobile" placeholder="0412345678" required>
                    <?php if (!empty($errors['mobile']) && $formSubmitted): ?>
                    <p class="error"><?php echo $errors['mobile']; ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required>
                    <?php if (!empty($errors['email']) && $formSubmitted): ?>
                    <p class="error"><?php echo $errors['email']; ?></p>
                    <?php endif; ?>
                </div>
                
                <input type="hidden" name="cart-data" id="cart-data">
                
                <button type="submit" class="place-order-btn" id="place-order-btn">Place Order</button>
            </form>
            <?php endif; ?>
        </section>
        <?php endif; ?>
    </main>

    <div id="cart-popup" class="cart-popup">
        <div class="cart-content">
            <h2>Shopping Cart</h2>
            <div id="cart-items">
                <!-- Cart items will be displayed here -->
                <p class="empty-cart">Your cart is empty</p>
            </div>
            <div class="cart-footer">
                <p>Total: $<span id="cart-total">0.00</span></p>
                <button id="clear-cart">Clear Cart</button>
                <button id="checkout-btn" disabled>Checkout</button>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 GroceryHub - Online Grocery Shopping</p>
    </footer>

    <script src="js/cart.js"></script>
    <script>
        // Additional checkout page JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const checkoutItems = document.getElementById('checkout-items');
            const checkoutTotal = document.getElementById('checkout-total');
            const cartData = document.getElementById('cart-data');
            const placeOrderBtn = document.getElementById('place-order-btn');
            
            // Update checkout summary
            if (checkoutItems) {
                updateCheckoutSummary();
            }
            
            function updateCheckoutSummary() {
                if (cart.length === 0) {
                    checkoutItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
                    checkoutTotal.textContent = '0.00';
                    placeOrderBtn.disabled = true;
                    return;
                }
                
                checkoutItems.innerHTML = '';
                let total = 0;
                
                cart.forEach(item => {
                    const subtotal = item.price * item.quantity;
                    total += subtotal;
                    
                    const itemElement = document.createElement('div');
                    itemElement.className = 'checkout-item';
                    itemElement.innerHTML = `
                        <div class="checkout-item-name">${item.name} - ${item.unitQuantity}</div>
                        <div class="checkout-item-price">$${item.price.toFixed(2)} x ${item.quantity} = $${subtotal.toFixed(2)}</div>
                    `;
                    
                    checkoutItems.appendChild(itemElement);
                });
                
                checkoutTotal.textContent = total.toFixed(2);
                cartData.value = JSON.stringify(cart);
                placeOrderBtn.disabled = false;
            }
            
            // Form validation
            const form = document.getElementById('checkout-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    let valid = true;
                    
                    const recipientName = document.getElementById('recipient-name').value;
                    const address = document.getElementById('address').value;
                    const mobile = document.getElementById('mobile').value;
                    const email = document.getElementById('email').value;
                    
                    if (!recipientName) {
                        valid = false;
                    }
                    
                    if (!address) {
                        valid = false;
                    }
                    
                    if (!mobile || !/^04\d{8}$/.test(mobile)) {
                        valid = false;
                    }
                    
                    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        valid = false;
                    }
                    
                    if (cart.length === 0) {
                        valid = false;
                    }
                    
                    if (!valid) {
                        e.preventDefault();
                    }
                });
            }
            
            // If order was successful, clear the cart
            <?php if ($orderSuccess): ?>
            localStorage.removeItem('cart');
            <?php endif; ?>
        });
    </script>
</body>
</html>