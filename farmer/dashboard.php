<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'farmer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Farmer Dashboard - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$firstName = $_SESSION['first_name'] ?? 'Ali';
$lastName = $_SESSION['last_name'] ?? 'Khan';
?>
<style>
    :root {
        --sidebar-bg: #451a03; /* Dark brown for farmer */
        --sidebar-hover: #78350f;
        --bg-color: #F8FAFC;
        --card-bg: #FFFFFF;
        --text-dark: #1F2937;
        --text-gray: #6B7280;
        --primary: #d97706; /* Amber */
    }
    
    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-color);
    }
    
    /* Remove default header for dashboard */
    header { display: none; }
    
    .dash-layout {
        display: flex;
        min-height: 100vh;
    }
    
    /* SIDEBAR */
    .sidebar {
        width: 250px;
        background-color: var(--sidebar-bg);
        color: white;
        display: flex;
        flex-direction: column;
        padding: 1.5rem 0;
        flex-shrink: 0;
    }
    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0 1.5rem 2rem 1.5rem;
        font-size: 1.25rem;
        font-weight: bold;
        color: white;
        text-decoration: none;
    }
    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        padding: 0 1rem;
        flex: 1;
    }
    .nav-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.8rem 1rem;
        color: #D1D5DB;
        text-decoration: none;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .nav-item:hover, .nav-item.active {
        background-color: var(--sidebar-hover);
        color: white;
    }
    .nav-item.active {
        background-color: var(--primary);
    }
    .sidebar-bottom {
        padding: 0 1rem;
        margin-top: auto;
    }
    
    /* MAIN CONTENT */
    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }
    
    /* TOPBAR */
    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2.5rem;
        background: white;
        border-bottom: 1px solid #E5E7EB;
    }
    .search-bar {
        position: relative;
        width: 400px;
    }
    .search-bar input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #E5E7EB;
        border-radius: 20px;
        background: #F3F4F6;
        outline: none;
    }
    .search-bar svg {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
    }
    .user-menu {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .user-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .user-profile img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .user-profile div {
        line-height: 1.2;
    }
    .user-profile h4 { margin: 0; font-size: 0.9rem; color: var(--text-dark); }
    .user-profile span { font-size: 0.8rem; color: var(--text-gray); }
    
    /* DASHBOARD BODY */
    .dash-body {
        padding: 2.5rem;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
    }
    .greeting { margin-bottom: 2rem; }
    .greeting h1 { margin: 0 0 0.25rem 0; font-size: 2rem; color: var(--text-dark); }
    .greeting p { margin: 0; color: var(--text-gray); }
    
    /* STATS ROW */
    .stats-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        flex: 1;
        background: white;
        padding: 1.25rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .stat-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-gray);
        font-weight: 500;
    }
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    
    /* HERO BANNER */
    .hero-banner {
        background: linear-gradient(90deg, #92400e 0%, #b45309 100%);
        border-radius: 16px;
        padding: 2.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .hero-text h2 { margin: 0 0 0.5rem 0; font-size: 1.75rem; }
    .hero-text p { margin: 0 0 1.5rem 0; opacity: 0.9; }
    .btn-amber {
        background: white;
        color: #92400e;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
    }
    
    /* SECTION HEADERS */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .section-header h3 { margin: 0; font-size: 1.25rem; color: var(--text-dark); }
    .section-header a { color: var(--text-gray); text-decoration: none; font-size: 0.9rem; }
    
    /* GRID ROW */
    .favorites-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .fav-card {
        flex: 1;
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        text-align: center;
    }
    .fav-card img {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        object-fit: cover;
        margin-bottom: 1rem;
    }
    .fav-card h4 { margin: 0 0 0.25rem 0; font-size: 1rem; color: var(--text-dark); }
    .fav-card p { margin: 0 0 0.75rem 0; font-size: 0.85rem; color: var(--text-gray); }
    .fav-btn {
        display: block;
        width: 100%;
        padding: 0.5rem;
        background: var(--primary);
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        box-sizing: border-box;
    }
</style>

<div class="dash-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <a href="../index.php" class="sidebar-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            MarketLink
        </a>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item active">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Dashboard
            </a>
            <a href="inventory.php" class="nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"></path><path d="M12 8v8"></path><path d="M8 12h8"></path></svg>
                My Inventory
            </a>
            <a href="orders.php" class="nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Pre-Orders
            </a>
            <a href="reviews.php" class="nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                Reviews
            </a>
            <a href="stall.php" class="nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Stall Profile
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a href="../logout.php" class="nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Logout
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- TOPBAR -->
        <header class="topbar">
            <div class="search-bar">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Search orders, products...">
            </div>
            <div class="user-menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                <div class="user-profile">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($firstName.' '.$lastName); ?>&background=d97706&color=fff" alt="User">
                    <div>
                        <h4><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></h4>
                        <span>Farmer</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- DASHBOARD BODY -->
        <div class="dash-body">
            <div class="greeting">
                <h1>Welcome back, <?php echo htmlspecialchars($firstName); ?>!</h1>
                <p>Manage your stock and pre-orders for the upcoming market day.</p>
            </div>

            <!-- STATS -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        Pending Orders
                    </div>
                    <div class="stat-value">8</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        Weekly Revenue
                    </div>
                    <div class="stat-value">Rs. 4,500</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><path d="M3 3h18v18H3z"></path><path d="M12 8v8"></path><path d="M8 12h8"></path></svg>
                        Low Stock Items
                    </div>
                    <div class="stat-value">2</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        New Reviews
                    </div>
                    <div class="stat-value">1</div>
                </div>
            </div>

            <!-- HERO -->
            <div class="hero-banner">
                <div class="hero-text">
                    <h2>Grow Your Customer Base</h2>
                    <p>Update your inventory before the weekend rush.</p>
                    <a href="inventory.php" class="btn-amber">Manage Inventory →</a>
                </div>
                <svg width="200" height="200" style="position:absolute; right: -50px; opacity: 0.15; transform: rotate(15deg);" viewBox="0 0 24 24" fill="currentColor"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path></svg>
            </div>

            <!-- QUICK ACTIONS / INVENTORY SUMMARY -->
            <div class="section-header">
                <h3>Top Selling Items</h3>
                <a href="inventory.php">View All →</a>
            </div>
            <div class="favorites-row">
                <!-- Item 1 -->
                <div class="fav-card">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🍅</div>
                    <h4>Organic Tomatoes</h4>
                    <p>Rs. 250 / kg</p>
                    <p style="color:#059669; font-weight:600; font-size:0.8rem;">15 kg In Stock</p>
                    <a href="inventory.php" class="fav-btn">Edit Details</a>
                </div>
                <!-- Item 2 -->
                <div class="fav-card">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🥬</div>
                    <h4>Fresh Spinach</h4>
                    <p>Rs. 150 / bunch</p>
                    <p style="color:#DC2626; font-weight:600; font-size:0.8rem;">2 bunches left!</p>
                    <a href="inventory.php" class="fav-btn">Restock</a>
                </div>
                <!-- Item 3 -->
                <div class="fav-card">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🥕</div>
                    <h4>Farm Carrots</h4>
                    <p>Rs. 120 / kg</p>
                    <p style="color:#059669; font-weight:600; font-size:0.8rem;">12 kg In Stock</p>
                    <a href="inventory.php" class="fav-btn">Edit Details</a>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
