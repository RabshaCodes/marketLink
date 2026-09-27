<?php
// Ensure a session is available site-wide (guarded so pages that already
// started one don't trigger a notice).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Resolve the app's base URL automatically so the site works whether it is
// served from a vhost (DocumentRoot = this project -> "/") or a subfolder
// such as http://localhost/marketlinkh2/ (-> "/marketlinkh2/"). It used to be
// hardcoded as '/marketlinkh2/', which 404'd every asset when opened via the
// marketlinkh2.test vhost (the folder name is not part of that URL).
$projectDir = realpath(__DIR__ . '/..');                   // .../marketlinkh2
$docRoot    = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');   // .../www or .../marketlinkh2
$base_url   = '/';
if ($projectDir && $docRoot) {
    $projectDir = str_replace('\\', '/', $projectDir);
    $docRoot    = str_replace('\\', '/', rtrim($docRoot, '/\\'));
    if ($projectDir !== $docRoot && strpos($projectDir, $docRoot . '/') === 0) {
        $base_url = substr($projectDir, strlen($docRoot)) . '/';   // e.g. /marketlinkh2/
    }
}
// A visitor is "logged in" when the session carries a user id or a role
// (e.g. user / customer / farmer). This drives the Dashboard/Logout links.
$is_logged_in = !empty($_SESSION['user_id']) || !empty($_SESSION['user_role']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'MarketLink'; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($extra_css))
        echo $extra_css; ?>
</head>

<body>
    <header>
        <nav>
            <a href="<?php echo $base_url; ?>index.php" class="logo">
                <span class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                        <path d="M12 12c-3-3-6-3-6-3s0 3 3 6c3 3 6 3 6 3s0-3-3-6z" />
                    </svg>
                </span>
                MarketLink
            </a>
            <div class="nav-links">
                <a href="<?php echo $base_url; ?>index.php" <?php if (basename($_SERVER['PHP_SELF']) == 'index.php')
                       echo 'class="active"'; ?>>Home</a>
                <a href="<?php echo $base_url; ?>markets.php" <?php if (basename($_SERVER['PHP_SELF']) == 'markets.php')
                       echo 'class="active"'; ?>>Markets</a>
                <a href="<?php echo $base_url; ?>products.php" <?php if (basename($_SERVER['PHP_SELF']) == 'products.php')
                       echo 'class="active"'; ?>>Products</a>
                <a href="<?php echo $base_url; ?>map.php" <?php if (basename($_SERVER['PHP_SELF']) == 'map.php')
                       echo 'class="active"'; ?>>Map</a>
                <a href="<?php echo $base_url; ?>about.php" <?php if (basename($_SERVER['PHP_SELF']) == 'about.php')
                       echo 'class="active"'; ?>>About</a>
                <a href="<?php echo $base_url; ?>contact.php" <?php if (basename($_SERVER['PHP_SELF']) == 'contact.php')
                       echo 'class="active"'; ?>>Contact</a>
            </div>
            <form class="nav-search" id="navSearch" action="<?php echo $base_url; ?>products.php" method="GET"
                role="search">
                <input type="text" id="navSearchInput" name="q" placeholder="Search markets, products or farmers..."
                    autocomplete="off">
                <button type="submit" aria-label="Submit search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>
            <div class="nav-actions">
                <button class="icon-btn search-btn" id="searchBtn" aria-label="Search" aria-expanded="false"
                    aria-controls="navSearch">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                <button class="icon-btn cart-btn" id="cartBtn" aria-label="Cart" aria-haspopup="dialog"
                    aria-expanded="false">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-badge">1</span>
                </button>
                <button class="icon-btn mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu" aria-expanded="false"
                    aria-controls="mobileMenu">
                    <svg class="mm-open" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                    <svg class="mm-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <div class="divider"></div>
                <div class="auth-buttons">
                    <?php if ($is_logged_in): ?>
                        <a href="<?php echo $base_url; ?>dashboard.php" class="btn btn-outline">Dashboard</a>
                        <a href="<?php echo $base_url; ?>logout.php" class="btn btn-primary">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo $base_url; ?>login.php" class="btn btn-outline">Login</a>
                        <a href="<?php echo $base_url; ?>login.php?tab=register" class="btn btn-primary">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <!-- Mobile dropdown menu (overlays page content) -->
        <div class="mobile-menu" id="mobileMenu">
            <form class="mobile-search" action="<?php echo $base_url; ?>products.php" method="GET" role="search">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="q" placeholder="Search markets, products or farmers..." autocomplete="off">
            </form>
            <nav class="mobile-links" aria-label="Mobile">
                <a href="<?php echo $base_url; ?>index.php" <?php if (basename($_SERVER['PHP_SELF']) == 'index.php')
                       echo 'class="active"'; ?>>Home</a>
                <a href="<?php echo $base_url; ?>markets.php" <?php if (basename($_SERVER['PHP_SELF']) == 'markets.php')
                       echo 'class="active"'; ?>>Markets</a>
                <a href="<?php echo $base_url; ?>products.php" <?php if (basename($_SERVER['PHP_SELF']) == 'products.php')
                       echo 'class="active"'; ?>>Products</a>
                <a href="<?php echo $base_url; ?>about.php" <?php if (basename($_SERVER['PHP_SELF']) == 'about.php')
                       echo 'class="active"'; ?>>About</a>
                <a href="<?php echo $base_url; ?>contact.php" <?php if (basename($_SERVER['PHP_SELF']) == 'contact.php')
                       echo 'class="active"'; ?>>Contact</a>
            </nav>
            <div class="mobile-auth">
                <?php if ($is_logged_in): ?>
                    <a href="<?php echo $base_url; ?>dashboard.php" class="btn btn-outline">Dashboard</a>
                    <a href="<?php echo $base_url; ?>logout.php" class="btn btn-primary">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>login.php" class="btn btn-outline">Login</a>
                    <a href="<?php echo $base_url; ?>login.php?tab=register" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>