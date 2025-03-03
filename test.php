<?php
require_once 'admin/view/settings/crud.php';

// Get the settings data
$settings = getSettings();

// Display the logo
$logo_path = $settings['site_logo'] ?? 'admin/uploads/logo/site_logo.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['site_title'] ?? 'Site Logo'); ?></title>
</head>
<body>
    <div class="logo-container">
        <img src="<?php echo htmlspecialchars("admin/uploads/logo/site_logo.png"); ?>" alt="Site Logo">
    </div>
</body>
</html>
