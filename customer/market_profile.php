<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Market Profile - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'markets';
include __DIR__ . '/includes/dashboard_layout.php';

$market_id = intval($_GET['id'] ?? 1);

$markets_dir = [
    1 => ['name' => 'Gulshan Farmers Market', 'area' => 'Gulshan, Karachi',  'days' => 'Mon, Wed, Sat', 'hours' => '8:00 AM - 2:00 PM'],
    2 => ['name' => 'Orangi Market',          'area' => 'Orangi, Karachi',   'days' => 'Tue, Thu, Sun', 'hours' => '8:00 AM - 3:00 PM'],
    3 => ['name' => 'Malir Market',           'area' => 'Malir, Karachi',    'days' => 'Mon, Thu, Sat', 'hours' => '6:00 AM - 1:00 PM'],
    4 => ['name' => 'Clifton Market',         'area' => 'Clifton, Karachi',  'days' => 'Sat, Sun, Mon', 'hours' => '9:00 AM - 5:00 PM'],
    5 => ['name' => 'North Karachi Market',   'area' => 'North Karachi',     'days' => 'Tue, Thu, Sat', 'hours' => '7:00 AM - 1:00 PM'],
    6 => ['name' => 'Saddar Market',          'area' => 'Saddar, Karachi',   'days' => 'Mon, Wed, Fri', 'hours' => '8:00 AM - 4:00 PM'],
];
$market = $markets_dir[$market_id] ?? $markets_dir[1];

$market_farmers = [
    ['id' => 1, 'name' => 'Ali Khan Farms',     'avatar' => 'photo-1595078475343-1c03ade2f0f8', 'rating' => '4.8', 'reviews' => 16, 'cats' => 'Vegetables • Fruits • Herbs'],
    ['id' => 2, 'name' => 'Sara Ahmed Farms',   'avatar' => 'photo-1621939514649-280e2ee25f60', 'rating' => '4.7', 'reviews' => 42, 'cats' => 'Dairy • Eggs • Vegetables'],
    ['id' => 3, 'name' => 'Usman Farm',         'avatar' => 'photo-1500648767791-00dcc994a43e', 'rating' => '4.6', 'reviews' => 28, 'cats' => 'Fruits • Seasonal'],
    ['id' => 4, 'name' => 'Bilal Organic Grow', 'avatar' => 'photo-1507003211169-0a1dd7228f2d', 'rating' => '4.5', 'reviews' => 19, 'cats' => 'Vegetables • Herbs'],
    ['id' => 5, 'name' => 'Karachi Green Farm', 'avatar' => 'photo-1519085360753-bf342679e516', 'rating' => '4.4', 'reviews' => 34, 'cats' => 'Fruits • Dairy'],
    ['id' => 6, 'name' => 'Malir Fresh Produce','avatar' => 'photo-1472099645785-818a24f532aa', 'rating' => '4.3', 'reviews' => 12, 'cats' => 'Vegetables • Seasonal'],
];
?>
<style>
    /* MARKET DETAIL CARD */
    .market-detail-card { background: var(--surface); border-radius: 24px; border: 1px solid var(--line); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 1.9rem; }
    .market-detail-inner { display: flex; gap: 2rem; padding: 1.9rem; }
    .market-detail-info { flex: 1; min-width: 0; }
    .md-eyebrow { display: inline-flex; align-items: center; gap: .35rem; font-size: .72rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: var(--primary); background: #EAF7EE; padding: .32rem .7rem; border-radius: 999px; margin-bottom: .8rem; }
    .market-detail-info h1 { margin: 0 0 .9rem; font-size: 1.75rem; font-weight: 800; color: var(--ink); letter-spacing: -.03em; }
    .md-meta { display: flex; align-items: center; gap: .6rem; color: var(--muted); font-size: .9rem; margin-bottom: .65rem; font-weight: 500; }
    .md-meta .mi { width: 30px; height: 30px; border-radius: 9px; background: #EAF7EE; color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .md-actions { display: flex; gap: .75rem; margin-top: 1.4rem; }
    .market-map { width: 340px; flex-shrink: 0; border-radius: 18px; overflow: hidden; border: 1px solid var(--line); box-shadow: var(--shadow-sm); position: relative; }
    .market-map img { width: 100%; height: 100%; object-fit: cover; display: block; min-height: 230px; }
    .map-pin { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-100%); color: var(--primary); filter: drop-shadow(0 4px 6px rgba(0,0,0,.3)); animation: ml-bob 2.4s ease-in-out infinite; }
    @keyframes ml-bob { 0%,100% { transform: translate(-50%,-100%); } 50% { transform: translate(-50%,-118%); } }

    /* TABS */
    .detail-tabs { display: flex; gap: .25rem; border-bottom: 1px solid var(--line); padding: 0 1.9rem; }
    .detail-tab { padding: .95rem 1.25rem; text-decoration: none; color: var(--muted); font-size: .92rem; font-weight: 600; border-bottom: 2.5px solid transparent; margin-bottom: -1px; transition: color .2s; }
    .detail-tab:hover { color: var(--ink); }
    .detail-tab.active { color: var(--primary); border-bottom-color: var(--primary); font-weight: 800; }

    /* FARMERS IN MARKET */
    .farmers-section h2 { margin: 0 0 1.25rem; font-size: 1.28rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .farmer-list { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .farmer-row {
        background: var(--surface); border-radius: var(--r-md); border: 1px solid var(--line); box-shadow: var(--shadow-sm);
        padding: 1.05rem 1.15rem; display: flex; align-items: center; gap: .9rem; transition: transform .2s, box-shadow .2s;
    }
    .farmer-row:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .farmer-row img { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid #DCF0E3; }
    .farmer-row-info { flex: 1; min-width: 0; }
    .farmer-row-info h4 { margin: 0 0 .2rem; font-size: .98rem; font-weight: 800; color: var(--ink); }
    .farmer-row-info .fr-rating { font-size: .8rem; color: #B45309; font-weight: 700; margin-bottom: .25rem; }
    .farmer-row-info .fr-rating .star { color: #F59E0B; }
    .farmer-row-info .fr-rating span { color: var(--muted); font-weight: 500; }
    .farmer-row-info .fr-cats { display: flex; align-items: center; gap: .35rem; font-size: .78rem; color: var(--muted); }
    .farmer-row-info .fr-cats svg { color: var(--primary); }
    .btn-view-sm {
        border: 1.5px solid #CBE7D5; color: var(--primary); background: #fff;
        padding: .5rem .95rem; border-radius: 10px; text-decoration: none; font-size: .82rem; font-weight: 700; white-space: nowrap; transition: all .2s;
    }
    .btn-view-sm:hover { background: #F0FDF4; border-color: var(--primary-2); }
    .heart-btn { color: #E5E7EB; display: flex; text-decoration: none; transition: color .2s, transform .2s; }
    .heart-btn:hover { color: #DC2626; transform: scale(1.15); }

    @media (max-width: 900px) { .market-detail-inner { flex-direction: column; } .market-map { width: 100%; } .farmer-list { grid-template-columns: 1fr; } }
</style>

<div class="page-body">
    <div class="market-detail-card reveal">
        <div class="market-detail-inner">
            <div class="market-detail-info">
                <span class="md-eyebrow">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path></svg>
                    Farmers Market
                </span>
                <h1><?php echo htmlspecialchars($market['name']); ?></h1>
                <div class="md-meta">
                    <span class="mi"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span>
                    <?php echo htmlspecialchars($market['area']); ?>
                </div>
                <div class="md-meta">
                    <span class="mi"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span>
                    <?php echo htmlspecialchars($market['days']); ?>
                </div>
                <div class="md-meta">
                    <span class="mi"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></span>
                    <?php echo htmlspecialchars($market['hours']); ?>
                </div>
                <div class="md-actions">
                    <a class="btn-green-solid" style="text-decoration:none;" href="../map.php">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                        Get Directions
                    </a>
                    <a class="btn-outline-green" href="favorites.php">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                        Save
                    </a>
                </div>
            </div>
            <div class="market-map">
                <img src="https://images.unsplash.com/photo-1524661135-4032ab7834d7?w=600&q=80" alt="Market map">
                <span class="map-pin"><svg width="34" height="34" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"></path><circle cx="12" cy="9" r="2.6" fill="#fff"></circle></svg></span>
            </div>
        </div>
        <div class="detail-tabs">
            <a href="#" class="detail-tab active">Farmers (<?php echo count($market_farmers); ?>)</a>
            <a href="products.php?market=<?php echo $market_id; ?>" class="detail-tab">Products</a>
            <a href="#" class="detail-tab">About</a>
            <a href="reviews.php" class="detail-tab">Reviews</a>
        </div>
    </div>

    <div class="farmers-section">
        <h2 class="reveal">Farmers in this Market</h2>
        <div class="farmer-list">
            <?php foreach ($market_farmers as $i => $f): ?>
            <div class="farmer-row reveal" data-delay="<?php echo ($i % 2) * 80; ?>">
                <img src="https://images.unsplash.com/<?php echo $f['avatar']; ?>?w=120&q=80" alt="<?php echo htmlspecialchars($f['name']); ?>">
                <div class="farmer-row-info">
                    <h4><?php echo htmlspecialchars($f['name']); ?></h4>
                    <div class="fr-rating"><span class="star">&#9733;</span> <?php echo $f['rating']; ?> <span>(<?php echo $f['reviews']; ?> reviews)</span></div>
                    <div class="fr-cats">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 12c-3-3-6-3-6-3s0 3 3 6c3 3 6 3 6 3s0-3-3-6z"/></svg>
                        <?php echo htmlspecialchars($f['cats']); ?>
                    </div>
                </div>
                <a href="farmer_profile.php?id=<?php echo $f['id']; ?>" class="btn-view-sm">View Profile</a>
                <a href="favorites.php" class="heart-btn" aria-label="Add to favorites">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
