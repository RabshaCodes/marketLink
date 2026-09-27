<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = 'Dashboard - MarketLink';
include 'includes/header.php';
require_once 'includes/database.php';

$role = $_SESSION['user_role'] ?? 'customer';
$firstName = $_SESSION['first_name'] ?? 'User';

?>
<style>
    body { background-color: #f3f6f4; }
    
    .dashboard-container {
        max-width: 1200px;
        margin: 2rem auto 4rem;
        padding: 0 2rem;
    }
    
    .dash-header {
        background: linear-gradient(135deg, #10523e 0%, #6eb793 100%);
        padding: 3rem 2rem;
        border-radius: 16px;
        color: white;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }
    .dash-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .dash-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    .role-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        text-transform: capitalize;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    
    .dash-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    .dash-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: transform 0.3s;
    }
    .dash-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }
    
    .dash-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: #eef7ef;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    
    .dash-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    .dash-card p {
        color: var(--text-light);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .dash-btn {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        background: var(--primary-color);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: background 0.3s;
    }
    .dash-btn:hover {
        background: var(--primary-dark);
    }
    
    .dash-btn-outline {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        background: transparent;
        color: var(--text-dark);
        border: 1px solid var(--border-color);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: background 0.3s;
    }
    .dash-btn-outline:hover {
        background: #f9fafb;
    }

</style>

<div class="dashboard-container">
    <div class="dash-header">
        <div>
            <h1>Welcome back, <?php echo htmlspecialchars($firstName); ?>!</h1>
            <p>Manage your account and view your activities here.</p>
        </div>
        <div class="role-badge">
            <?php echo htmlspecialchars($role); ?> Account
        </div>
    </div>

    <div class="dash-grid">
        
        <?php if ($role === 'customer'): ?>
            <!-- Customer Specific Cards -->
            <div class="dash-card">
                <div class="dash-card-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </div>
                <h3>My Pre-Orders</h3>
                <p>Track your current pre-orders, past purchases, and pick-up schedules.</p>
                <a href="#" class="dash-btn">View Orders</a>
            </div>

            <div class="dash-card">
                <div class="dash-card-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </div>
                <h3>Favorite Farms</h3>
                <p>Quick access to your favorite local farmers and stalls.</p>
                <a href="markets.php" class="dash-btn-outline">Browse Markets</a>
            </div>
            
            <div class="dash-card">
                <div class="dash-card-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <h3>Account Settings</h3>
                <p>Update your personal information, email, and password.</p>
                <a href="#" class="dash-btn-outline">Edit Profile</a>
            </div>

        <?php elseif ($role === 'farmer'): ?>
            <!-- Farmer Specific Cards -->
            <div class="dash-card">
                <div class="dash-card-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3z"></path><path d="M12 8v8"></path><path d="M8 12h8"></path></svg>
                </div>
                <h3>Manage Produce</h3>
                <p>Add new fresh stock, update prices, or mark items out of stock.</p>
                <a href="farmer/stall.php" class="dash-btn">Update Inventory</a>
            </div>

            <div class="dash-card">
                <div class="dash-card-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <h3>Incoming Pre-Orders</h3>
                <p>Check the pre-orders customers have placed for your next market day.</p>
                <a href="#" class="dash-btn-outline">View Pre-Orders</a>
            </div>

            <div class="dash-card">
                <div class="dash-card-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                </div>
                <h3>My Market Stall</h3>
                <p>Update your public farm profile, location, and market schedule.</p>
                <a href="#" class="dash-btn-outline">Edit Stall Profile</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
