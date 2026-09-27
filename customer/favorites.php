<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Favorites - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'favorites';
include __DIR__ . '/includes/dashboard_layout.php';

$fav_farmers = [
    ['id' => 1, 'name' => 'Ali Khan Farms',   'avatar' => 'photo-1595078475343-1c03ade2f0f8', 'rating' => '4.8', 'reviews' => 16, 'market' => 'Gulshan Market'],
    ['id' => 2, 'name' => 'Sara Ahmed Farms', 'avatar' => 'photo-1621939514649-280e2ee25f60', 'rating' => '4.7', 'reviews' => 42, 'market' => 'Clifton Market'],
    ['id' => 3, 'name' => 'Usman Farm',       'avatar' => 'photo-1500648767791-00dcc994a43e', 'rating' => '4.6', 'reviews' => 28, 'market' => 'Saddar Market'],
];
$fav_products = [
    ['name' => 'Organic Tomatoes', 'price' => '250', 'unit' => 'kg',    'img' => 'photo-1592924357228-1be254705e89'],
    ['name' => 'Fresh Spinach',    'price' => '150', 'unit' => 'bunch', 'img' => 'photo-1576045057990-51b807586d5f'],
    ['name' => 'Strawberries',     'price' => '300', 'unit' => 'box',   'img' => 'photo-1464965398167-42c3f40863d7'],
];
?>
<style>
    .sub-heading { margin: 0 0 1.1rem; font-size: 1.18rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }

    /* FARMER CARDS */
    .favorites-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.4rem; margin-bottom: 2.6rem; }
    .fav-card {
        position: relative; background: var(--surface); padding: 1.7rem 1.4rem 1.4rem; border-radius: var(--r-lg);
        box-shadow: var(--shadow-sm); border: 1px solid var(--line); text-align: center;
        transition: transform .25s, box-shadow .25s;
    }
    .fav-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
    .fav-card img { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; margin-bottom: .8rem; border: 3px solid #fff; box-shadow: 0 0 0 3px #DCF0E3; }
    .fav-card h4 { margin: 0 0 .3rem; font-size: 1.02rem; font-weight: 800; color: var(--ink); }
    .fav-card .fav-loc { margin: 0 0 .55rem; font-size: .82rem; color: var(--muted); font-weight: 600; display: flex; align-items: center; justify-content: center; gap: .3rem; }
    .fav-card .fav-loc svg { color: var(--primary); }
    .fav-badge { display: inline-flex; align-items: center; gap: .3rem; background: #FFF7E6; color: #B45309; font-weight: 800; font-size: .78rem; padding: .22rem .6rem; border-radius: 999px; margin-bottom: 1rem; }
    .fav-badge .star { color: #F59E0B; }
    .fav-btn { display: block; width: 100%; padding: .62rem; background: linear-gradient(135deg,#1D9A50,#15803D); color: #fff; text-decoration: none; border-radius: 12px; font-size: .88rem; font-weight: 700; box-sizing: border-box; box-shadow: 0 8px 18px -8px rgba(21,128,61,.6); transition: filter .2s, transform .2s; }
    .fav-btn:hover { filter: brightness(1.06); transform: translateY(-1px); }
    .unfav { position: absolute; top: 12px; right: 12px; width: 30px; height: 30px; border-radius: 50%; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform .2s; }
    .unfav:hover { transform: scale(1.1); }

    /* SAVED PRODUCTS */
    .prod-row-card {
        background: var(--surface); border-radius: var(--r-md); border: 1px solid var(--line); box-shadow: var(--shadow-sm);
        padding: .9rem 1.15rem; display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; transition: transform .2s, box-shadow .2s;
    }
    .prod-row-card:hover { transform: translateX(4px); box-shadow: var(--shadow-md); }
    .prod-row-card img { width: 56px; height: 56px; border-radius: 13px; object-fit: cover; box-shadow: 0 0 0 3px #F1F7F2; }
    .prc-info { flex: 1; }
    .prc-info h4 { margin: 0 0 .2rem; font-size: .98rem; font-weight: 800; color: var(--ink); }
    .prc-info p { margin: 0; font-size: .85rem; color: var(--primary); font-weight: 800; }
    .btn-outline { border: 1.5px solid var(--line); padding: .5rem 1rem; border-radius: 10px; color: var(--ink-2); text-decoration: none; font-size: .84rem; font-weight: 700; background: #fff; transition: all .2s; }
    .btn-outline:hover { border-color: var(--primary-2); color: var(--primary); }
    .btn-outline.danger:hover { border-color: #FCA5A5; color: #DC2626; }
</style>

<div class="page-body" style="max-width: 950px;">
    <div class="page-header reveal">
        <h1>Favorites</h1>
        <p>Your saved farmers and products, ready when you are.</p>
    </div>

    <div class="tab-row reveal">
        <button class="tab-pill active" type="button">Farmers (<?php echo count($fav_farmers); ?>)</button>
        <button class="tab-pill" type="button">Products (<?php echo count($fav_products); ?>)</button>
    </div>

    <h3 class="sub-heading reveal">Saved Farmers</h3>
    <div class="favorites-grid">
        <?php foreach ($fav_farmers as $i => $f): ?>
        <div class="fav-card reveal" data-delay="<?php echo $i * 80; ?>">
            <a href="#" class="unfav" aria-label="Remove from favorites">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </a>
            <img src="https://images.unsplash.com/<?php echo $f['avatar']; ?>?w=200&q=80" alt="<?php echo htmlspecialchars($f['name']); ?>">
            <h4><?php echo htmlspecialchars($f['name']); ?></h4>
            <p class="fav-loc">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <?php echo htmlspecialchars($f['market']); ?>
            </p>
            <span class="fav-badge"><span class="star">&#9733;</span> <?php echo $f['rating']; ?> &middot; <?php echo $f['reviews']; ?> reviews</span>
            <a href="farmer_profile.php?id=<?php echo $f['id']; ?>" class="fav-btn">View Profile</a>
        </div>
        <?php endforeach; ?>
    </div>

    <h3 class="sub-heading reveal">Saved Products</h3>
    <?php foreach ($fav_products as $i => $p): ?>
    <div class="prod-row-card reveal" data-delay="<?php echo $i * 70; ?>">
        <img src="https://images.unsplash.com/<?php echo $p['img']; ?>?w=150&q=80" alt="<?php echo htmlspecialchars($p['name']); ?>">
        <div class="prc-info">
            <h4><?php echo htmlspecialchars($p['name']); ?></h4>
            <p>Rs. <?php echo $p['price']; ?>/<?php echo $p['unit']; ?></p>
        </div>
        <a href="products.php" class="btn-outline">View Product</a>
        <a href="orders.php" class="btn-outline danger">Remove</a>
    </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
