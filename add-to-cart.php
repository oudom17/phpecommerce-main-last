<?php

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = (int)$_POST['quantity'];

    // Check if product already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] === $product_id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    // If product not found, add it to cart
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }

    // If it's an AJAX request, return JSON response
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Item added to cart!',
            'cartCount' => array_sum(array_column($_SESSION['cart'], 'quantity'))
        ]);
        exit;
    }

    // If it's a regular form submission, redirect to cart page
    header('Location: cart.php');
    exit;
}

// If accessed directly without POST data, redirect to shop
header('Location: shop.php');
exit;

?>