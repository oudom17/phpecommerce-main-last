<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart count
$cartCount = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
}
?>

<nav class="navbar navbar-expand-lg navbar-light shadow">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand text-success logo h1 align-self-center" href="index.php">
            <img src="admin/uploads/logo/site_logo.png" alt="" style="max-height: 60px;">
        </a>

        <button class="navbar-toggler border-0" typeავ

        type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
            <div class="flex-fill">
                <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="navbar align-self-center d-flex">
                <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
                        <div class="input-group-text">
                            <i class="fa fa-fw fa-search"></i>
                        </div>
                    </div>
                </div>
                <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal" data-bs-target="#templatemo_search">
                    <i class="fa fa-fw fa-search text-dark mr-2"></i>
                </a>
                <div class="cart-icon">
                    <a class="nav-icon position-relative text-decoration-none" href="cart.php">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <span class="cart-count position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">
                            <?= $cartCount ?>
                        </span>
                    </a>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Show logout icon when logged in -->
                    <a class="nav-icon d-none d-lg-inline" href="logout.php" title="Log out">
                        <i class="fa fa-fw fa-sign-out-alt text-dark mr-2"></i>
                    </a>
                <?php else: ?>
                    <!-- Show login icon when not logged in -->
                    <a class="nav-icon d-none d-lg-inline" href="login.php" title="Log in">
                        <i class="fa fa-fw fa-user text-dark mr-2"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>