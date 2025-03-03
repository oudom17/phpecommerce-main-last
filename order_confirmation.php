<?php
session_start();

if (!isset($_SESSION['order'])) {
    header('Location: cart.php');
    exit;
}

$order = $_SESSION['order'];
unset($_SESSION['order']);
?>
<!DOCTYPE html>
<html lang="en">
<?php include "include/head.php" ?>
<body>
    <?php include "include/header.php" ?>

    <div class="container py-5">
        <div class="card border-success">
            <div class="card-body text-center">
                <h1 class="card-title text-success mb-4">Order Placed Successfully!</h1>
                <p class="lead">Thank you for your purchase, <?= $order['customer']['name'] ?>!</p>
                <p>A confirmation email has been sent to <?= $order['customer']['email'] ?>.</p>
                
                <div class="mt-4">
                    <h4>Order Details</h4>
                    <p><strong>Shipping Address:</strong><br>
                    <?= $order['customer']['address'] ?><br>
                    <?= $order['customer']['city'] ?>, <?= $order['customer']['state'] ?> <?= $order['customer']['zip'] ?><br>
                    <?= $order['customer']['country'] ?></p>
                    
                    <h5 class="mt-4">Order Total: $<?= number_format($order['total'], 2) ?></h5>
                </div>

                <a href="shop.php" class="btn btn-success mt-4">Continue Shopping</a>
            </div>
        </div>
    </div>

    <?php include "include/footer.php" ?>
</body>
</html>