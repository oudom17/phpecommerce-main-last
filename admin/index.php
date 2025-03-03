<?php
include 'include/header.php';
include 'include/navbar.php';
include 'include/sidebar.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'products', 'categories', 'settings'];

if (!in_array($page, $allowed_pages)) $page = 'dashboard';

include "view/{$page}/index.php";
include 'include/footer.php';
include 'include/scripts.php';
?>