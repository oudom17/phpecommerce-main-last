<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true" data-img="theme-assets/images/backgrounds/02.jpg">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row">       
      <li class="nav-item mr-auto">
        <a class="navbar-brand" href="index.php">
          <img class="brand-logo" alt="Site Logo" src="<?= htmlspecialchars($settings['site_logo'] ?? '/images/logo/logo.png') ?>"/>
          <h3 class="brand-text"><?= htmlspecialchars($settings['site_title'] ?? 'Chameleon') ?></h3>
        </a>
      </li>
      <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
    </ul>
  </div>
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
      <li class="<?php echo (!isset($_GET['page']) || $_GET['page'] == 'dashboard') ? 'active' : ''; ?>">
        <a href="index.php"><i class="ft-home"></i><span class="menu-title" data-i18n="">Dashboard</span></a>
      </li>
      <li class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'categories') ? 'active' : ''; ?>">
        <a href="index.php?page=categories"><i class="ft-pie-chart"></i><span class="menu-title" data-i18n="">Categories</span></a>
      </li> 
      <li class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'products') ? 'active' : ''; ?>">
        <a href="index.php?page=products"><i class="ft-pie-chart"></i><span class="menu-title" data-i18n="">Products</span></a>
      </li>
      <li class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'settings') ? 'active' : ''; ?>">
        <a href="index.php?page=settings"><i class="ft-settings"></i><span class="menu-title" data-i18n="">Settings</span></a>
      </li>
    </ul>
  </div>
  <div class="navigation-background"></div>
</div>