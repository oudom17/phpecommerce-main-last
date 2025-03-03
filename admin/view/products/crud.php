<?php
require_once __DIR__ . '/../../config/Database.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

function getAllProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT p.*, c.name as category_name 
                         FROM products p 
                         LEFT JOIN categories c ON p.category_id = c.id 
                         ORDER BY p.id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveProduct($data, $file) {
    global $pdo;
    
    // Get existing image path if updating
    $image_path = null;
    if(!empty($data['id'])) {
        $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ?");
        $stmt->execute([$data['id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $image_path = $product['image_path']; // Keep existing image path
    }
    
    // Handle new image upload if provided
    if(isset($file['image']) && $file['image']['error'] == 0) {
        $upload_dir = 'uploads/products/';
        if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
        
        // Delete old image if exists
        if($image_path && file_exists($image_path)) {
            unlink($image_path);
        }
        
        $filename = uniqid() . '.' . pathinfo($file['image']['name'], PATHINFO_EXTENSION);
        $filepath = $upload_dir . $filename;
        
        if(move_uploaded_file($file['image']['tmp_name'], $filepath)) {
            $image_path = $filepath;
        }
    }

    try {
        if(!empty($data['id'])) {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, image_path = ? WHERE id = ?");
            $stmt->execute([
                $data['name'], 
                $data['description'], 
                $data['price'], 
                $data['category_id'], 
                $image_path, // This will now be either the old path or the new path
                $data['id']
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category_id, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['name'], 
                $data['description'], 
                $data['price'], 
                $data['category_id'], 
                $image_path
            ]);
        }
        return true;
    } catch(PDOException $e) {
        error_log("Error saving product: " . $e->getMessage());
        return false;
    }
}

function deleteProduct($id) {
    global $pdo;
    try {
        // Get the image path before deleting
        $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete the image file if it exists
        if($product['image_path'] && file_exists($product['image_path'])) {
            unlink($product['image_path']);
        }
        
        // Delete the product record
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch(PDOException $e) {
        error_log("Error deleting product: " . $e->getMessage());
        return false;
    }
}
?> 