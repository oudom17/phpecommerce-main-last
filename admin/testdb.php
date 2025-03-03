<?php
require_once 'view/settings/crud.php';
$settings = getSettings();
?>

<div class="container">
    <?php if(isset($settings['site_logo'])): ?>
        <div class="mt-2">
            <img src="<?= htmlspecialchars($settings['site_logo']) ?>" 
                 alt="Website Logo" 
                 style="max-width: 200px; margin-top: 10px;">
        </div>
    <?php else: ?>
        <p>No logo has been uploaded yet.</p>
    <?php endif; ?>
</div>