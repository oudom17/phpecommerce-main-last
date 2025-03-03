<?php
session_start();
require_once __DIR__ . '/admin/config/Database.php';
require_once __DIR__ . '/functions/product_functions.php';

$database = new Database();
$pdo = $database->getConnection();

$products = getAllProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    if (isset($_POST['product-title'], $_POST['product-size'], $_POST['product-quantity'], $_POST['product-price'])) {
        $product = [
            'title' => $_POST['product-title'],
            'size' => $_POST['product-size'],
            'quantity' => (int)$_POST['product-quantity'],
            'price' => (float)$_POST['product-price']
        ];

        $found = false;
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['title'] === $product['title'] && $item['size'] === $product['size']) {
                    $item['quantity'] += $product['quantity'];
                    $found = true;
                    break;
                }
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = $product;
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
<?php include "include/head.php" ?>
</head>
<body>

<?php include "include/header.php" ?>
    <div class="row">
        <?php foreach($products as $product): ?>
        <div class="col-md-4 mb-4">
            <div class="card product-wap rounded-0 product-card">
                <div class="card rounded-0">
                    <a href="shop-single.php?id=<?= $product['id'] ?>" class="product-image-link" style="display: block; text-decoration: none;">
                        <div class="product-image-container">
                            <img class="card-img rounded-0 img-fluid" 
                                 src="admin/<?= htmlspecialchars($product['image_path'] ?: 'assets/img/default-product.jpg') ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                    </a>
                    <div class="card-body text-center">
                        <a href="shop-single.php?id=<?= $product['id'] ?>" class="card-title" style="text-decoration: none;">
                            <h5 class="product-title"><?= htmlspecialchars($product['name']) ?></h5>
                        </a>
                        <p class="card-text description-text">
                            <span class="text-muted"><?= htmlspecialchars($product['description']) ?></span><br>
                            $<?= number_format($product['price'], 2) ?>
                        </p>
                        <form method="POST" action="add-to-cart.php" class="product-form">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="price" value="<?= $product['price'] ?>">
                            <button type="submit" class="btn btn-outline-dark mt-2" name="submit" value="addtocard" 
                                    style="border-radius: 0; padding: 8px 20px;">
                                Add to cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<style>
    .product-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .product-image-container {
        height: 200px;  /* Fixed height for image container */
        background-color: #f0f8ff;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        transition: opacity 0.3s ease;
    }
    
    .product-image-container img {
        max-height: 160px;  /* Leave some padding space */
        width: auto;
        object-fit: contain;
    }
    
    .card-body {
        flex: 1;  /* Take remaining space */
        display: flex;
        flex-direction: column;
    }
    
    .product-title {
        height: 48px;  /* Fixed height for title - approximately 2 lines */
        overflow: hidden;
        margin-bottom: 10px;
    }
    
    .product-image-link:hover .product-image-container {
        opacity: 0.4;
    }
    .product-image-link:hover .card-img {
        cursor: pointer;
    }
    .description-text span {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 2.8em;
        margin-bottom: 10px;
    }
    body {
        overflow-x: hidden;
    }
</style>

    </div>
                <div div="row">
                    <ul class="pagination pagination-lg justify-content-end">
                        <li class="page-item">
                            <a class="page-link active rounded-0 mr-3 shadow-sm border-top-0 border-left-0" href="shop.php">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-0 mr-3 shadow-sm border-top-0 border-left-0 text-dark" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-0 shadow-sm border-top-0 border-left-0 text-dark" href="#">3</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

        <div class="container my-4">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Our Brands</h1>
                    <p>     
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        Lorem ipsum dolor sit amet.
                    </p>
                </div>
                <div class="col-lg-9 m-auto tempaltemo-carousel">
                    <div class="row d-flex flex-row">
                        <div class="col-1 align-self-center">
                            <a class="h1" href="#multi-item-example" role="button" data-bs-slide="prev">
                                <i class="text-light fas fa-chevron-left"></i>
                            </a>
                        </div>

                        <div class="col">
                            <div class="carousel slide carousel-multi-item pt-2 pt-md-0" id="multi-item-example" data-bs-ride="carousel">
                                <div class="carousel-inner product-links-wap" role="listbox">

                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-1 align-self-center">
                            <a class="h1" href="#multi-item-example" role="button" data-bs-slide="next">
                                <i class="text-light fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php include "include/footer.php" ?>
     
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    
    <script>
        document.querySelectorAll('.product-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch('add-to-cart.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('.cart-count').textContent = data.cartCount;
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>