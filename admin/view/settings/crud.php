<?php
require_once __DIR__ . '/../../config/Database.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

function getSettings() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error getting settings: " . $e->getMessage());
        return false;
    }
}

function saveSettings($data, $file) {
    global $pdo;
    try {
        $settings = getSettings();
        $image_path = $settings['site_logo'] ?? 'theme-assets/images/logo/logo.png';
        
        // Handle file upload
        if(isset($file['site_logo']) && $file['site_logo']['error'] == 0) {
            $upload_dir = 'uploads/logo/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Get file extension
            $extension = strtolower(pathinfo($file['site_logo']['name'], PATHINFO_EXTENSION));
            
            // Validate file type
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowed)) {
                throw new Exception('Invalid file type. Only JPG, PNG and GIF allowed.');
            }
            
            $filename = 'site_logo.' . $extension;
            $filepath = $upload_dir . $filename;
            
            // Remove old file if it exists and is different
            if ($settings && $settings['site_logo'] && file_exists($settings['site_logo']) && $settings['site_logo'] != $filepath) {
                unlink($settings['site_logo']);
            }
            
            if(move_uploaded_file($file['site_logo']['tmp_name'], $filepath)) {
                $image_path = $filepath;
            } else {
                throw new Exception('Failed to upload file.');
            }
        }

        // Database operation
        if($settings) {
            $stmt = $pdo->prepare("UPDATE settings SET site_title = ?, site_logo = ? WHERE id = ?");
            $result = $stmt->execute([
                $data['site_title'],
                $image_path,
                $settings['id']
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO settings (site_title, site_logo) VALUES (?, ?)");
            $result = $stmt->execute([
                $data['site_title'],
                $image_path
            ]);
        }

        if (!$result) {
            throw new Exception('Database operation failed.');
        }

        return true;

    } catch(Exception $e) {
        error_log("Error saving settings: " . $e->getMessage());
        return false;
    }
}
?> 