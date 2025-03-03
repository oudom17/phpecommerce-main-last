<?php
// Start a session to keep track of the shopping cart
session_start();
require_once __DIR__ . '/admin/config/Database.php';
require_once __DIR__ . '/functions/product_functions.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get the product
$product = getProductById($product_id);

// Redirect if product not found
if (!$product) {
    header('Location: shop.php');
    exit;
}

// If someone clicks "Add to Cart" using a special request (AJAX), handle it here
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    if (isset($_POST['product-title'], $_POST['product-quantity'], $_POST['product-price'])) {
        $cartItem = [
            'product_id' => $product_id,
            'name' => $_POST['product-title'],
            'quantity' => (int)$_POST['product-quantity'],
            'price' => (float)$_POST['product-price']
        ];

        // Check if product already exists in cart
        $found = false;
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['product_id'] === $product_id) {
                    $item['quantity'] += $cartItem['quantity'];
                    $found = true;
                    break;
                }
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = $cartItem;
        }

        header('Content-Type: application/json');
        echo json_encode(['cartCount' => array_sum(array_column($_SESSION['cart'], 'quantity'))]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "include/head.php"; ?> <!-- Include common head content (like styles) -->
</head>
<body>
    <!-- Header with navigation and cart icon -->
    <?php include "include/header.php"; ?>

    <!-- Product Display -->
    <div class="container py-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <img src="admin/<?= htmlspecialchars($product['image_path'] ?: 'assets/img/default-product.jpg') ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" 
                     class="img-fluid" 
                     style="max-width: 100%; object-fit: contain;">
            </div>
            
            <!-- Product Details -->
            <div class="col-md-6">
                <h1><?= htmlspecialchars($product['name']) ?></h1>
                <p class="lead">$<?= number_format($product['price'], 2) ?></p>
                <p><?= htmlspecialchars($product['description']) ?></p>
                
                <form method="POST" action="" id="product-form">
                    <input type="hidden" name="product-title" value="<?= htmlspecialchars($product['name']) ?>">
                    <input type="hidden" name="product-price" value="<?= $product['price'] ?>">
                    
                    <!-- Quantity Buttons -->
                    <div class="mb-3">
                        <label>Quantity:</label>
                        <div style="display: flex; align-items: center;">
                            <button type="button" class="btn btn-success" id="btn-minus">-</button>
                            <span class="badge bg-secondary mx-2" id="var-value">1</span>
                            <button type="button" class="btn btn-success" id="btn-plus">+</button>
                            <input type="hidden" name="product-quantity" id="product-quantity" value="1">
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <button type="submit" class="btn btn-success btn-lg" name="submit" value="addtocard">Add To Cart</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Simple JavaScript for Quantity and Cart -->
    <script>
        // Quantity buttons functionality
        document.getElementById('btn-plus').addEventListener('click', function(e) {
            e.preventDefault();
            let qty = parseInt(document.getElementById('var-value').textContent);
            qty++;
            document.getElementById('var-value').textContent = qty;
            document.getElementById('product-quantity').value = qty;
        });

        document.getElementById('btn-minus').addEventListener('click', function(e) {
            e.preventDefault();
            let qty = parseInt(document.getElementById('var-value').textContent);
            if (qty > 1) {
                qty--;
                document.getElementById('var-value').textContent = qty;
                document.getElementById('product-quantity').value = qty;
            }
        });

        // AJAX add to cart
        document.getElementById('product-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'add_to_cart');

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.querySelector('.cart-count').textContent = data.cartCount;
            })
            .catch(error => console.error('Error:', error));
        });
    </script>

    <?php include "include/footer.php"; ?> <!-- Include common footer content -->
</body>
</html>