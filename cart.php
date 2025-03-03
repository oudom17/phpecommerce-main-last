<?php
session_start();
require_once __DIR__ . '/admin/config/Database.php';
require_once __DIR__ . '/functions/product_functions.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

// Check if user is logged in before proceeding to checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'proceed_to_checkout') {
    if (!isset($_SESSION['user_id'])) {
        // User not logged in, redirect to login.php
        header('Location: login.php');
        exit;
    } else {
        // User is logged in, redirect to checkout.php
        header('Location: checkout.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "include/head.php" ?>
<body>
    <?php include "include/header.php" ?>

    <div class="container py-5">
        <h1>Shopping Cart</h1>
        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $index => $item): 
                        $product = getProductById($item['product_id']);
                        if ($product):
                            $itemTotal = $item['quantity'] * $item['price'];
                            $total += $itemTotal;
                    ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if ($product['image_path']): ?>
                                        <img src="admin/<?= htmlspecialchars($product['image_path']) ?>" 
                                             alt="<?= htmlspecialchars($item['name']) ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                    <?php endif; ?>
                                    <?= htmlspecialchars($item['name']) ?>
                                </div>
                            </td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>$<?= number_format($itemTotal, 2) ?></td>
                            <td>
                                <form method="POST" action="remove_from_cart.php">
                                    <input type="hidden" name="index" value="<?= $index ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td>$<?= number_format($total, 2) ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-end mt-4">
               
                <form method="POST" action="">
                    <a href="shop.php" class="btn btn-secondary">Continue Shopping</a>
                    <input type="hidden" name="action" value="proceed_to_checkout">
                    <button type="submit" class="btn btn-success">Proceed to Checkout</button>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Your cart is empty. <a href="shop.php">Continue shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include "include/footer.php" ?>
    
    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <!-- End Script -->
</body>
</html>