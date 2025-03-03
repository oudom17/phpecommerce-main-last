<?php
require_once __DIR__ . '/../../config/Database.php';

function deleteCategory($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function saveCategory($data) {
    global $pdo;
    try {
        if(isset($data['id']) && $data['id'] != '') {
            // Update
            $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$data['name'], $data['description'], $data['id']]);
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $stmt->execute([$data['name'], $data['description']]);
        }
        return true;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getAllCategories() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}
?> 