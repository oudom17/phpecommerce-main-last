<?php
session_start();

require_once 'PayWayApiCheckout.php';

// Get order details from session
$order = $_SESSION['order'] ?? null;
if (!$order) {
    // Redirect back to checkout if no order exists
    header('Location: checkout.php');
    exit;
}

$req_time = time();
$merchant_id = "ec438740";
$transactionId = time();
$amount = number_format($_SESSION['order_total'], 2, '.', ''); // Use the total from checkout
$firstName = $order['customer']['name']; // Split name if needed
$lastName = ''; // You might want to split the full name into first and last
$phone = '0973835841'; // You might want to add phone to checkout form
$email = $order['customer']['email'];
$return_params = "Hello World!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PayWay Checkout Sample</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="author" content="PayWay">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!-- Bootstrap CSS (assuming you're using it based on the classes) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="aba_main_modal" class="aba-modal">
        <div class="aba-modal-content">
            <form method="POST" target="aba_webservice" action="<?php echo PayWayApiCheckout::getApiUrl(); ?>" id="aba_merchant_request">
                <input type="hidden" name="hash" value="<?php echo PayWayApiCheckout::getHash($req_time . ABA_PAYWAY_MERCHANT_ID . $transactionId . $amount . $firstName  .$lastName .$email .$phone .$return_params); ?>" id="hash"/>
                <input type="hidden" name="tran_id" value="<?php echo $transactionId; ?>" id="tran_id"/>
                <input type="hidden" name="amount" value="<?php echo $amount; ?>" id="amount"/>
                <input type="hidden" name="firstname" value="<?php echo $firstName; ?>"/>
                <input type="hidden" name="lastname" value="<?php echo $lastName; ?>"/>
                <input type="hidden" name="phone" value="<?php echo $phone; ?>"/>
                <input type="hidden" name="email" value="<?php echo $email; ?>"/>
                <input type="hidden" name="return_params" value="<?php echo $return_params; ?>"/>
                <input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>"/>
                <input type="hidden" name="req_time" value="<?php echo $req_time; ?>"/>
            </form>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <!-- Payment Button -->
            <div class="col-lg-4 text-center">
                <h2>TOTAL: $<?php echo $amount; ?></h2>
                <input type="button" id="checkout_button" value="Checkout Now" class="btn btn-success btn-lg mt-3">
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Order Summary</h4>
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= htmlspecialchars($item['title']) ?> (<?= $item['size'] ?>)</span>
                                <span>$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>$<?= number_format(array_sum(array_map(function($item) {
                                return $item['price'] * $item['quantity'];
                            }, $order['items'])), 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://checkout.payway.com.kh/plugins/checkout2-0.js"></script>
    <script>
        $(document).ready(function () {
            $('#checkout_button').click(function () {
                AbaPayway.checkout();
            });
        });
    </script>
</body>
</html>