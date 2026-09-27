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
    ['type' => 'orders',   'text' => 'Your order #1004 has been delivered',            'date' => 'Apr 22, 2026 - 10:45 AM', 'link' => 'orders.php?tab=Delivered', 'icon' => '<polyline points="20 6 9 17 4 12"></polyline>', 'bg' => '#D1FAE5', 'fg' => '#065F46'],
    ['type' => 'farmers',  'text' => 'Sara Ahmed Farms added new products',             'date' => 'Apr 20, 2026 - 09:12 AM', 'link' => 'farmer_profile.php?id=2',    'icon' => '<line x1="6" y1="12" x2="18" y2="12"></line><line x1="12" y1="6" x2="12" y2="18"></line>', 'bg' => '#DBEAFE', 'fg' => '#1E40AF'],
    ['type' => 'orders',   'text' => 'Order #1003 is now ready for pickup',             'date' => 'Apr 18, 2026 - 04:20 PM', 'link' => 'orders.php?tab=Pending',     'icon' => '<circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>', 'bg' => '#EDE9FE', 'fg' => '#5B21B6'],
    ['type' => 'system',   'text' => 'New review on Organic Tomatoes',                  'date' => 'Apr 15, 2026 - 11:05 AM', 'link' => 'reviews.php',                'icon' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>', 'bg' => '#FCE7F3', 'fg' => '#9D174D'],
    ['type' => 'farmers',  'text' => 'Ali Khan Farms is open this weekend at Gulshan',  'date' => 'Apr 14, 2026 - 08:30 AM', 'link' => 'farmer_profile.php?id=1',    'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle>', 'bg' => '#D1FAE5', 'fg' => '#065F46'],
    ['type' => 'system',   'text' => 'Welcome to MarketLink! Complete your profile',    'date' => 'Apr 10, 2026 - 09:00 AM', 'link' => 'profile.php',                'icon' => '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line>', 'bg' => '#FEF3C7', 'fg' => '#92400E'],
];
$type_map = ['Orders' => 'orders', 'Farmers' => 'farmers', 'System' => 'system'];
$filtered = $active_tab === 'All' ? $notifications : array_values(array_filter($notifications, fn($n) => $n['type'] === $type_map[$active_tab]));
?>
<style>
    .page-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; }
    .mark-read { font-size: .82rem; font-weight: 700; color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: .35rem; white-space: nowrap; }
    .mark-read:hover { text-decoration: underline; }
    .notif-list { display: flex; flex-direction: column; gap: .8rem; }
    .notif-card {
        position: relative; background: var(--surface); border-radius: var(--r-md); border: 1px solid var(--line);
        box-shadow: var(--shadow-sm); padding: 1.05rem 1.3rem;
        display: flex; align-items: center; gap: 1rem; text-decoration: none; color: inherit;
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }
    .notif-card:hover { transform: translateX(4px); box-shadow: var(--shadow-md); border-color: #CBE7D5; }
    .notif-card.unread { background: linear-gradient(90deg, #F1FBF4, var(--surface) 60%); border-color: #CDEBD8; }
    .notif-card.unread::before { content: ''; position: absolute; left: 0; top: 14px; bottom: 14px; width: 3px; border-radius: 0 3px 3px 0; background: linear-gradient(#22C55E, #15803D); }
    .notif-icon { width: 44px; height: 44px; border-radius: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: inset 0 1px 0 rgba(255,255,255,.5); }
    .notif-body { flex: 1; min-width: 0; }
    .notif-body .msg { margin: 0 0 0.25rem 0; font-size: 0.93rem; color: var(--ink); font-weight: 600; line-height: 1.4; }
    .notif-card.unread .notif-body .msg { font-weight: 800; }
    .notif-body .when { font-size: 0.78rem; color: var(--muted); font-weight: 600; }
    .unread-dot { width: 8px; height: 8px; border-radius: 50%; background: #22C55E; box-shadow: 0 0 0 3px rgba(34,197,94,.18); flex-shrink: 0; }
    .notif-chev { color: #C2CFc7; flex-shrink: 0; transition: transform .2s, color .2s; }
    .notif-card:hover .notif-chev { color: var(--primary); transform: translateX(3px); }
    .empty-state { background: var(--surface); border: 1.5px dashed #CFE3D6; border-radius: var(--r-lg); padding: 3rem 2rem; text-align: center; color: var(--muted); }
    .empty-state .es-ico { width: 56px; height: 56px; border-radius: 50%; background: #EAF6EE; color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
</style>

<div class="page-body" style="max-width: 800px;">
    <div class="page-header reveal">
        <div>
            <h1>Notifications</h1>
            <p>Stay updated on your orders, farmers and activity.</p>
        </div>
        <a href="notifications.php" class="mark-read">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Mark all as read
        </a>
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
    <div class="notif-list">
        <?php foreach ($filtered as $i => $n): $unread = $i < 2; ?>
        <a href="<?php echo $n['link']; ?>" class="notif-card reveal<?php echo $unread ? ' unread' : ''; ?>" data-delay="<?php echo $i * 70; ?>">
            <div class="notif-icon" style="background:<?php echo $n['bg']; ?>; color:<?php echo $n['fg']; ?>;">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $n['icon']; ?></svg>
            </div>
            <div class="notif-body">
                <p class="msg"><?php echo $n['text']; ?></p>
                <span class="when"><?php echo $n['date']; ?></span>
            </div>
            <?php if ($unread): ?><span class="unread-dot"></span><?php endif; ?>
            <svg class="notif-chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
