<?php

$current_page = basename($_SERVER['PHP_SELF']);

if (session_status() === PHP_SESSION_NONE) session_start();

// Make sure BASE_URL is defined before this file is included
// If you move BASE_URL to a central config file (recommended),
// you won't need to define it here. For now, assume it's defined
// in the calling script like news_detail.php
// Example: define('BASE_URL', 'https://store.kodoku.me/');

?>
<div class="navbar-container">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container justify-content-between">
      <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>store.php">
        <img src="<?= BASE_URL ?>assets/images/logokodoku.png" alt="Kodoku Logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav d-flex align-items-center">
          <li class="nav-item">
            <a class="nav-link <?php if ($current_page == 'store.php' || $current_page == 'fake_buy.php') echo 'active-nav'; ?>" href="<?= BASE_URL ?>store.php">Store</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if ($current_page == 'news.php' || $current_page == 'changelog.php') echo 'active-nav'; ?>" href="<?= BASE_URL ?>news.php">News</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if ($current_page == 'serverinfo.php') echo 'active-nav'; ?>" href="<?= BASE_URL ?>serverinfo.php">Server Information</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://discord.gg/WbafFVcVdv" target="_blank">Support</a>
          </li>
          <?php if (isset($_SESSION['name'])): ?>
            <li class="nav-item">
              <a class="nav-link text-danger" href="<?= BASE_URL ?>logout.php">Logout (<?= htmlspecialchars($_SESSION['name']) ?>)</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</div>