<?php
session_start();
require_once __DIR__ . '/admin/config/Database.php';
require_once 'PayWayApiCheckout.php'; // Include PayWay API class

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input fields
    $required_fields = ['name', 'email', 'address', 'city', 'state', 'zip', 'country'];
    $valid = true;
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $valid = false;
            break;
        }
    }

    if ($valid) {
        // Store order details in session
        $_SESSION['order'] = [
            'customer' => [
                'name' => htmlspecialchars($_POST['name']),
                'email' => htmlspecialchars($_POST['email']),
                'address' => htmlspecialchars($_POST['address']),
                'city' => htmlspecialchars($_POST['city']),
                'state' => htmlspecialchars($_POST['state']),
                'zip' => htmlspecialchars($_POST['zip']),
                'country' => htmlspecialchars($_POST['country'])
            ],
            'items' => $_SESSION['cart'],
            'total' => $total
        ];

        // Prepare PayWay payment data
        $req_time = time();
        $merchant_id = ABA_PAYWAY_MERCHANT_ID;
        $transactionId = time();
        $amount = number_format($total, 2, '.', ''); // Use the calculated total
        $firstName = $_SESSION['order']['customer']['name']; // Use full name as first name
        $lastName = ''; // No last name for simplicity (you can split if needed)
        $phone = '0973835841'; // Hardcoded for now; consider adding to the form
        $email = $_SESSION['order']['customer']['email'];
        $return_params = "tran_id=" . $transactionId . "&status=success&return_url=" . urlencode("http://yourdomain.com/payment_callback.php"); // Custom return parameters with transaction ID

        // Generate hash for PayWay security
        $hash = PayWayApiCheckout::getHash($req_time . $merchant_id . $transactionId . $amount . $firstName . $lastName . $email . $phone . $return_params);

        // Redirect to PayWay checkout using a form submission
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to PayWay...</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body onload="document.getElementById(\'paywayForm\').submit();">
    <form id="paywayForm" method="POST" action="' . PayWayApiCheckout::getApiUrl() . '" style="display: none;">
        <input type="hidden" name="hash" value="' . htmlspecialchars($hash) . '">
        <input type="hidden" name="tran_id" value="' . htmlspecialchars($transactionId) . '">
        <input type="hidden" name="amount" value="' . htmlspecialchars($amount) . '">
        <input type="hidden" name="firstname" value="' . htmlspecialchars($firstName) . '">
        <input type="hidden" name="lastname" value="' . htmlspecialchars($lastName) . '">
        <input type="hidden" name="phone" value="' . htmlspecialchars($phone) . '">
        <input type="hidden" name="email" value="' . htmlspecialchars($email) . '">
        <input type="hidden" name="return_params" value="' . htmlspecialchars($return_params) . '">
        <input type="hidden" name="merchant_id" value="' . htmlspecialchars($merchant_id) . '">
        <input type="hidden" name="req_time" value="' . htmlspecialchars($req_time) . '">
    </form>
    <p>Redirecting to PayWay payment gateway...</p>
</body>
</html>';
        exit;
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "include/head.php" ?>
<body>
    <?php include "include/header.php" ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <h1>Checkout</h1>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <h2>Contact Information</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <h2>Shipping Address</h2>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="zip" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="zip" name="zip" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-control" id="country" name="country" required>
                                <option value="">Choose...</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Laos">Laos</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Continue to Payment</button>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Order Summary</h3>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                                <span>$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong>$<?= number_format($total, 2) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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