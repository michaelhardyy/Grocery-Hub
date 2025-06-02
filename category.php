<?php
include 'db.php';
include 'includes/categories.php';


// Get category and subcategory from URL parameters
$categoryName = isset($_GET['name']) ? $_GET['name'] : '';
$subcategoryName = isset($_GET['subname']) ? $_GET['subname'] : '';
$minId = isset($_GET['min']) ? intval($_GET['min']) : 0;
$maxId = isset($_GET['max']) ? intval($_GET['max']) : 0;

// Determine the heading
$pageHeading = $categoryName;
if (!empty($subcategoryName)) {
    $pageHeading .= ' - ' . $subcategoryName;
}


// Fetch products in the category/subcategory
$products = [];
if ($minId > 0 && $maxId > 0) {
    $sql = "SELECT * FROM products WHERE product_id BETWEEN $minId AND $maxId ORDER BY product_name";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageHeading; ?> - GroceryHub</title>
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
            <li class="category-item <?php echo ($category == $categoryName) ? 'active' : ''; ?>">
                <a href="category.php?name=<?php echo urlencode($category); ?>&min=<?php echo $info['range'][0]; ?>&max=<?php echo $info['range'][1]; ?>">
                    <?php echo $category; ?>
                </a>
                
                <div class="subcategories">
                    <ul>
                        <?php foreach ($info['subcategories'] as $subcategory => $subRange): ?>
                        <li>
                            <a href="category.php?name=<?php echo urlencode($category); ?>&subname=<?php echo urlencode($subcategory); ?>&min=<?php echo $subRange[0]; ?>&max=<?php echo $subRange[1]; ?>"
                               class="<?php echo ($subcategory == $subcategoryName) ? 'active' : ''; ?>">
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
        <section class="category-products">
            <h2><?php echo $pageHeading; ?> Products</h2>
            
            <?php if (empty($products)): ?>
            <p class="no-products">No products found in this category.</p>
            <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>">
                    <h3><?php echo $product["product_name"]; ?></h3>
                    <p class="product-quantity"><?php echo $product["unit_quantity"]; ?></p>
                    <p class="product-price">$<?php echo number_format($product["unit_price"], 2); ?></p>
                    <p class="product-stock">
                        <?php if ($product["in_stock"] > 0): ?>
                            In Stock: <?php echo $product["in_stock"]; ?>
                        <?php else: ?>
                            Out of Stock
                        <?php endif; ?>
                    </p>
                    <button class="add-to-cart" 
                            data-id="<?php echo $product["product_id"]; ?>"
                            data-name="<?php echo $product["product_name"]; ?>"
                            data-price="<?php echo $product["unit_price"]; ?>"
                            data-quantity="<?php echo $product["unit_quantity"]; ?>"
                            <?php if ($product["in_stock"] <= 0): ?>disabled<?php endif; ?>>
                        Add to Cart
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>
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
                <button id="checkout" disabled>Checkout</button>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 GroceryHub - Online Grocery Shopping</p>
    </footer>

    <script src="js/cart.js"></script>
</body>
</html>