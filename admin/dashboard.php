<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Admin Dashboard - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

// Fetch quick stats for Admin
$stats = [
    'farmers' => 0,
    'customers' => 0,
    'markets' => 0,
    'orders' => 0
];

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'farmer'");
    $stats['farmers'] = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
    $stats['customers'] = $stmt->fetchColumn();
} catch (Exception $e) {}

?>
<style>
    .admin-layout {
        display: flex;
        min-height: calc(100vh - 80px);
        background: #f8fafc;
    }
    .admin-sidebar {
        width: 260px;
        background: white;
        border-right: 1px solid var(--border-color);
        padding: 2rem 1rem;
    }
    .admin-main {
        flex: 1;
        padding: 2rem 3rem;
    }
    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.8rem 1rem;
        color: var(--text-light);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s;
        font-weight: 500;
    }
    .sidebar-link:hover, .sidebar-link.active {
        background: #eef7ef;
        color: var(--primary-color);
    }
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #eef7ef;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-info h3 { margin: 0; font-size: 1.8rem; color: var(--text-dark); }
    .stat-info p { margin: 0; color: var(--text-light); font-size: 0.9rem; }
</style>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link active">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Dashboard Overview
            </a>
            <a href="users.php" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Manage Users
            </a>
            <a href="markets.php" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Manage Markets
            </a>
            <a href="reports.php" class="sidebar-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                Reports & Analytics
            </a>
        </nav>
    </aside>

    <main class="admin-main">
        <h2>Admin Dashboard</h2>
        <p class="text-light" style="margin-bottom: 2rem;">Welcome back, System Administrator.</p>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['farmers']; ?></h3>
                    <p>Total Farmers</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#e0f2fe; color:#0284c7;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['customers']; ?></h3>
                    <p>Total Customers</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#fef3c7; color:#d97706;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['markets']; ?></h3>
                    <p>Active Markets</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#f3e8ff; color:#9333ea;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stats['orders']; ?></h3>
                    <p>Total Pre-Orders</p>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
