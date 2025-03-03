<?php
session_start();
require_once __DIR__ . '/admin/config/Database.php';
require_once __DIR__ . '/functions/product_functions.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();
?> 