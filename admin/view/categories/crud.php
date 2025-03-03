<?php
require_once __DIR__ . '/../../config/Database.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

function getAllCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveCategory($data) {
    global $pdo;
    if(!empty($data['id'])) {
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['description'], $data['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$data['name'], $data['description']]);
    }
}

function deleteCategory($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
}
?> 