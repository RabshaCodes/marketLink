<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Notifications - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = ''; // notifications is reached via the topbar bell
include __DIR__ . '/includes/dashboard_layout.php';

$tabs = ['All', 'Orders', 'Farmers', 'System'];
$active_tab = isset($_GET['tab']) && in_array($_GET['tab'], $tabs) ? $_GET['tab'] : 'All';

/* type: orders | farmers | system */
$notifications = [
    ['type' => 'orders',   'text' => 'Your order #1004 has been delivered',            'date' => 'Apr 22, 2026 - 10:45 AM', 'link' => 'orders.php?tab=Delivered', 'icon' => '<polyline points="20 6 9 17 4 12"></polyline>', 'bg' => '#22C55E', 'fg' => '#ffffff'],
    ['type' => 'farmers',  'text' => 'Sara Ahmed Farms added new products',             'date' => 'Apr 20, 2026 - 09:12 AM', 'link' => 'farmer_profile.php?id=2',    'icon' => '<line x1="6" y1="12" x2="18" y2="12"></line><line x1="12" y1="6" x2="12" y2="18"></line>', 'bg' => '#F97316', 'fg' => '#ffffff'],
    ['type' => 'orders',   'text' => 'Order #1003 is now ready for pickup',             'date' => 'Apr 18, 2026 - 04:20 PM', 'link' => 'orders.php?tab=Pending',     'icon' => '<circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>', 'bg' => '#8B5CF6', 'fg' => '#ffffff'],
    ['type' => 'system',   'text' => 'New review on Organic Tomatoes',                  'date' => 'Apr 15, 2026 - 11:05 AM', 'link' => 'reviews.php',                'icon' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>', 'bg' => '#3B82F6', 'fg' => '#ffffff'],
    ['type' => 'farmers',  'text' => 'Ali Khan Farms is open this weekend at Gulshan',  'date' => 'Apr 14, 2026 - 08:30 AM', 'link' => 'farmer_profile.php?id=1',    'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle>', 'bg' => '#22C55E', 'fg' => '#ffffff'],
    ['type' => 'system',   'text' => 'Welcome to MarketLink! Complete your profile',    'date' => 'Apr 10, 2026 - 09:00 AM', 'link' => 'profile.php',                'icon' => '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line>', 'bg' => '#EAB308', 'fg' => '#ffffff'],
];
$type_map = ['Orders' => 'orders', 'Farmers' => 'farmers', 'System' => 'system'];
$filtered = $active_tab === 'All' ? $notifications : array_values(array_filter($notifications, fn($n) => $n['type'] === $type_map[$active_tab]));
?>
<style>
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
    .page-header h1 { margin: 0; font-size: 1.6rem; font-weight: 800; color: var(--ink); }

    /* Custom Tab Row for Notifications to match design */
    .tab-row { background: #fff; border: none; padding: .4rem; box-shadow: 0 2px 10px rgba(0,0,0,.03); gap: .4rem; margin-bottom: 2rem; border-radius: 999px; }
    .tab-pill { color: #334155; font-weight: 600; font-size: .88rem; padding: .55rem 1.4rem; }
    .tab-pill.active { background: var(--sb-1); color: #fff; box-shadow: 0 4px 10px rgba(6,64,43,.3); }
    .tab-pill:hover:not(.active) { color: var(--sb-1); background: #F1F5F9; }

    .notif-list {
        display: flex; flex-direction: column;
        background: #fff; border-radius: 24px; border: 1px solid var(--line);
        padding: 0; box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .notif-card {
        position: relative; background: transparent; border: none; border-radius: 0;
        border-bottom: 1px solid var(--line); box-shadow: none;
        padding: 1.25rem 1.6rem;
        display: flex; align-items: center; gap: 1.25rem; text-decoration: none; color: inherit;
        transition: background .2s;
    }
    .notif-card:last-child { border-bottom: none; }
    .notif-card:hover { transform: none; box-shadow: none; background: #F8FAFC; }
    
    .notif-icon { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .notif-body { flex: 1; min-width: 0; }
    .notif-body .msg { margin: 0 0 0.35rem 0; font-size: .95rem; color: var(--ink); font-weight: 700; line-height: 1.4; }
    .notif-body .when { font-size: 0.82rem; color: #64748B; font-weight: 600; }
    
    .notif-chev { color: #CBD5E1; flex-shrink: 0; transition: transform .2s, color .2s; }
    .notif-card:hover .notif-chev { color: var(--sb-1); transform: translateX(3px); }
    
    .empty-state { background: #fff; border: 1.5px dashed #CFE3D6; border-radius: 24px; padding: 4rem 2rem; text-align: center; color: var(--muted); }
    .empty-state .es-ico { width: 56px; height: 56px; border-radius: 50%; background: #EAF6EE; color: var(--sb-1); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
</style>

<div class="page-body">
    <div class="page-header reveal">
        <h1>Notifications</h1>
    </div>

    <!-- TABS -->
    <div class="tab-row reveal">
        <?php foreach ($tabs as $t): ?>
            <a href="notifications.php<?php echo $t === 'All' ? '' : '?tab=' . urlencode($t); ?>" class="tab-pill<?php echo $active_tab === $t ? ' active' : ''; ?>"><?php echo $t; ?></a>
        <?php endforeach; ?>
    </div>

    <!-- LIST -->
    <?php if (empty($filtered)): ?>
        <div class="empty-state reveal">
            <div class="es-ico">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            </div>
            You're all caught up - no <?php echo strtolower($active_tab); ?> notifications.
        </div>
    <?php else: ?>
    <div class="notif-list reveal">
        <?php foreach ($filtered as $i => $n): ?>
        <a href="<?php echo $n['link']; ?>" class="notif-card" data-delay="<?php echo $i * 70; ?>">
            <div class="notif-icon" style="background:<?php echo $n['bg']; ?>; color:<?php echo $n['fg']; ?>;">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $n['icon']; ?></svg>
            </div>
            <div class="notif-body">
                <p class="msg"><?php echo $n['text']; ?></p>
                <span class="when"><?php echo $n['date']; ?></span>
            </div>
            <svg class="notif-chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
