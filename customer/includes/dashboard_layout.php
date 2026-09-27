<?php
/**
 * Shared Customer Dashboard Layout (Design System v2)
 * ---------------------------------------------------
 * Renders the fixed sidebar + frosted topbar chrome used by every page in the
 * customer area. Pages set $dash_active before including this file, output
 * their own <style> + page markup, then include dashboard_layout_close.php.
 */

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$firstName = $_SESSION['first_name'] ?? 'Ayesha';
$lastName = $_SESSION['last_name'] ?? 'Khan';
$fullName = trim($firstName . ' ' . $lastName);
$userMail = $_SESSION['email'] ?? 'ayesha@gmail.com';

$dash_active = $dash_active ?? '';

/* Sidebar navigation definition */
$dash_nav = [
    'dashboard' => ['label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => '<rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect>'],
    'markets' => ['label' => 'Browse Markets', 'href' => 'browse_markets.php', 'icon' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>'],
    'farmers' => ['label' => 'Farmers', 'href' => 'farmers.php', 'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>'],
    'orders' => ['label' => 'My Orders', 'href' => 'orders.php', 'icon' => '<circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>'],
    'favorites' => ['label' => 'Favorites', 'href' => 'favorites.php', 'icon' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>'],
    'reviews' => ['label' => 'Reviews', 'href' => 'reviews.php', 'icon' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>'],
    'profile' => ['label' => 'Profile', 'href' => 'profile.php', 'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>'],
];
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="<?php echo htmlspecialchars($base_url ?? '/'); ?>customer/includes/dashboard.css?v=3">

<div class="dash-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <a href="../index.php" class="sidebar-logo" title="Back to MarketLink home">
            <span class="logo-icon">
                <!-- Same logo mark as the public homepage navbar -->
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                    <path d="M12 12c-3-3-6-3-6-3s0 3 3 6c3 3 6 3 6 3s0-3-3-6z" />
                </svg>
            </span>
            <span>MarketLink</span>
        </a>
        <nav class="sidebar-nav">
            <?php foreach ($dash_nav as $key => $item): ?>
                <a href="<?php echo $item['href']; ?>"
                    class="nav-item<?php echo $dash_active === $key ? ' active' : ''; ?>">
                    <span class="ni-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"><?php echo $item['icon']; ?></svg>
                    </span>
                    <span><?php echo $item['label']; ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-bottom">
            <div class="sidebar-card">
                <b>Fresh from the farm 🌾</b>
                Seasonal picks land every weekend.
            </div>
            <a href="../logout.php" class="nav-item" style="margin-top: .55rem;">
                <span class="ni-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </span>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- TOPBAR -->
        <div class="topbar">
            <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle menu"
                aria-expanded="false" aria-controls="sidebar">
                <svg class="mm-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                <svg class="mm-close" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <form class="search-bar" action="../products.php" method="get" role="search" style="display:flex;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="q" placeholder="Search markets, products, or farmers...">
            </form>
            <div class="user-menu">
                <a href="notifications.php" class="notif-link" aria-label="Notifications">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="notif-badge">3</span>
                </a>
                <a href="profile.php" class="user-chip">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fullName); ?>&background=15803D&color=fff"
                        alt="User">
                    <div class="up-name">
                        <h4><?php echo htmlspecialchars($fullName); ?></h4>
                        <span>Customer</span>
                    </div>
                </a>
            </div>
        </div>