<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'My Orders - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'orders';
include __DIR__ . '/includes/dashboard_layout.php';

$tabs = ['All', 'Pending', 'Delivered', 'Cancelled'];
$active_tab = isset($_GET['tab']) && in_array($_GET['tab'], $tabs) ? $_GET['tab'] : 'All';

$orders = [
    ['id' => 1004, 'name' => 'Organic Tomatoes', 'qty' => '2 kg',  'price' => 500,  'date' => 'Apr 10, 2026', 'status' => 'Delivered', 'img' => 'photo-1592924357228-1be254705e89'],
    ['id' => 1003, 'name' => 'Fresh Spinach',    'qty' => '1 kg',  'price' => 150,  'date' => 'Apr 10, 2026', 'status' => 'Pending',   'img' => 'photo-1576045057990-51b807586d5f'],
    ['id' => 1002, 'name' => 'Carrots',          'qty' => '3 kg',  'price' => 360,  'date' => 'Apr 15, 2026', 'status' => 'Delivered', 'img' => 'photo-1598170845058-32b9d0a6e70a'],
    ['id' => 1001, 'name' => 'Apples',           'qty' => '2 kg',  'price' => 400,  'date' => 'Apr 10, 2026', 'status' => 'Cancelled', 'img' => 'photo-1568613302984-26546e70224e'],
];
$status_pill = ['Delivered' => 'pill-green', 'Pending' => 'pill-orange', 'Cancelled' => 'pill-red', 'Ready for Pickup' => 'pill-blue'];
$filtered = $active_tab === 'All' ? $orders : array_values(array_filter($orders, fn($o) => $o['status'] === $active_tab));
?>
<style>
    .orders-list { display: flex; flex-direction: column; gap: 1rem; }
    .order-card {
        background: var(--surface); border-radius: var(--r-md); border: 1px solid var(--line);
        box-shadow: var(--shadow-sm); padding: 1rem 1.2rem;
        display: flex; align-items: center; gap: 1.1rem;
        transition: transform .2s, box-shadow .2s;
    }
    .order-card:hover { transform: translateX(4px); box-shadow: var(--shadow-md); }
    .order-card > img { width: 58px; height: 58px; border-radius: 14px; object-fit: cover; flex-shrink: 0; box-shadow: 0 0 0 3px #F1F7F2; }
    .oc-id { font-size: .72rem; font-weight: 800; color: var(--primary); background: #EAF7EE; padding: .15rem .5rem; border-radius: 6px; display: inline-block; margin-bottom: .3rem; }
    .oc-info { flex: 1; min-width: 0; }
    .oc-info h4 { margin: 0 0 .25rem; font-size: 1rem; font-weight: 800; color: var(--ink); }
    .oc-meta { font-size: .84rem; color: var(--muted); margin: 0 0 .35rem; font-weight: 600; }
    .oc-meta strong { color: var(--ink); font-weight: 800; }
    .oc-date { font-size: .78rem; color: #94A3B8; display: inline-flex; align-items: center; gap: .3rem; }
    .oc-right { display: flex; flex-direction: column; align-items: flex-end; gap: .6rem; flex-shrink: 0; }
    .oc-total { font-size: 1.05rem; font-weight: 800; color: var(--ink); }
    .oc-link { color: var(--primary); font-size: .84rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: .25rem; transition: gap .2s; }
    .oc-link:hover { gap: .5rem; }
    .empty-state { background: var(--surface); border: 1.5px dashed #CBD5D1; border-radius: var(--r-lg); padding: 3.5rem; text-align: center; color: var(--muted); }
    .empty-state .es-ico { width: 60px; height: 60px; border-radius: 50%; background: #EAF7EE; color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
</style>

<div class="page-body" style="max-width: 850px;">
    <div class="page-header reveal">
        <h1>My Orders</h1>
        <p>Track and revisit your recent market pickups.</p>
    </div>

    <div class="tab-row reveal">
        <?php foreach ($tabs as $t): ?>
            <a href="orders.php<?php echo $t === 'All' ? '' : '?tab=' . urlencode($t); ?>" class="tab-pill<?php echo $active_tab === $t ? ' active' : ''; ?>"><?php echo $t; ?></a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($filtered)): ?>
        <div class="empty-state reveal">
            <div class="es-ico"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg></div>
            No <?php echo strtolower($active_tab); ?> orders yet.
        </div>
    <?php else: ?>
    <div class="orders-list">
        <?php foreach ($filtered as $i => $o): ?>
        <div class="order-card reveal" data-delay="<?php echo $i * 70; ?>">
            <img src="https://images.unsplash.com/<?php echo $o['img']; ?>?w=150&q=80" alt="<?php echo htmlspecialchars($o['name']); ?>">
            <div class="oc-info">
                <span class="oc-id">#<?php echo $o['id']; ?></span>
                <h4><?php echo htmlspecialchars($o['name']); ?></h4>
                <p class="oc-meta"><?php echo $o['qty']; ?> &bull; <strong>Rs. <?php echo number_format($o['price']); ?></strong></p>
                <span class="oc-date">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <?php echo $o['date']; ?>
                </span>
            </div>
            <div class="oc-right">
                <span class="status-pill <?php echo $status_pill[$o['status']] ?? 'pill-gray'; ?>"><?php echo $o['status']; ?></span>
                <span class="oc-total">Rs. <?php echo number_format($o['price']); ?></span>
                <a href="#" class="oc-link">View Details
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
