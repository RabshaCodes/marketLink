<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Farmer Profile - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'farmers';
include __DIR__ . '/includes/dashboard_layout.php';

$farmer_id = intval($_GET['id'] ?? 1);

$farmers_dir = [
    1 => ['name' => 'Ali Khan Farms',     'avatar' => 'photo-1595078475343-1c03ade2f0f8', 'rating' => '4.8', 'reviews' => 56, 'market' => 'Gulshan Market'],
    2 => ['name' => 'Sara Ahmed Farms',   'avatar' => 'photo-1621939514649-280e2ee25f60', 'rating' => '4.7', 'reviews' => 42, 'market' => 'Clifton Market'],
    3 => ['name' => 'Usman Farm',         'avatar' => 'photo-1500648767791-00dcc994a43e', 'rating' => '4.6', 'reviews' => 28, 'market' => 'Saddar Market'],
    4 => ['name' => 'Bilal Organic Grow', 'avatar' => 'photo-1507003211169-0a1dd7228f2d', 'rating' => '4.5', 'reviews' => 19, 'market' => 'Malir Market'],
    5 => ['name' => 'Karachi Green Farm', 'avatar' => 'photo-1519085360753-bf342679e516', 'rating' => '4.4', 'reviews' => 34, 'market' => 'Orangi Market'],
    6 => ['name' => 'Malir Fresh Produce','avatar' => 'photo-1472099645785-818a24f532aa', 'rating' => '4.3', 'reviews' => 12, 'market' => 'North Karachi Market'],
];
$farmer = $farmers_dir[$farmer_id] ?? $farmers_dir[1];
?>
<style>
    /* FARMER HERO & INFO */
    .farmer-hero { position: relative; height: 230px; border-radius: 24px 24px 0 0; overflow: hidden; }
    .farmer-hero img { width: 100%; height: 100%; object-fit: cover; }
    .farmer-hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(6,64,43,.15), rgba(6,64,43,.45)); }

    .farmer-info-card {
        background: var(--surface); border-radius: 0 0 24px 24px; padding: 2rem;
        box-shadow: var(--shadow-sm); border: 1px solid var(--line); border-top: none; margin-bottom: 2rem;
    }
    .farmer-header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid var(--line); padding-bottom: 1.5rem; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
    .farmer-title { display: flex; gap: 1.1rem; align-items: center; }
    .farmer-title .ava-wrap { position: relative; }
    .farmer-title img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 0 0 3px #DCF0E3, var(--shadow-sm); }
    .farmer-title .verified { position: absolute; right: 0; bottom: 2px; width: 24px; height: 24px; border-radius: 50%; background: linear-gradient(135deg,#22C55E,#15803D); color: #fff; border: 2px solid #fff; display: flex; align-items: center; justify-content: center; }
    .farmer-title h2 { margin: 0 0 .3rem; font-size: 1.55rem; font-weight: 800; color: var(--ink); letter-spacing: -.03em; }
    .farmer-title .rating { color: #B45309; font-weight: 800; font-size: .9rem; }
    .farmer-title .rating .star { color: #F59E0B; }
    .farmer-title .rating span { color: var(--muted); font-weight: 500; }
    .farmer-title .tagline { color: var(--muted); font-size: .87rem; margin-top: .3rem; display: inline-flex; align-items: center; gap: .35rem; }
    .farmer-title .tagline svg { color: var(--primary); }

    .btn-favorite {
        background: linear-gradient(135deg,#1D9A50,#15803D); color: #fff; padding: .65rem 1.3rem; border-radius: 12px;
        text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: .5rem; font-size: .9rem;
        box-shadow: 0 10px 22px -8px rgba(21,128,61,.55); transition: transform .2s, filter .2s;
    }
    .btn-favorite:hover { transform: translateY(-2px); filter: brightness(1.06); }

    .farmer-details-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .detail-item { font-size: .9rem; background: #F6FBF7; border: 1px solid var(--line); border-radius: var(--r-md); padding: 1rem 1.1rem; }
    .detail-item .label { color: var(--muted); display: flex; align-items: center; gap: .5rem; margin-bottom: .4rem; font-weight: 600; font-size: .82rem; }
    .detail-item .label svg { color: var(--primary); }
    .detail-item .value { color: var(--ink); font-weight: 700; }
    .detail-item a { color: var(--primary); font-weight: 700; text-decoration: none; }

    /* TABS */
    .tabs { display: flex; gap: 2rem; border-bottom: 1px solid var(--line); margin-bottom: 1.75rem; }
    .tab { padding: .8rem 0; color: var(--muted); font-weight: 600; cursor: pointer; position: relative; transition: color .2s; }
    .tab:hover { color: var(--ink); }
    .tab.active { color: var(--primary); }
    .tab.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 3px; border-radius: 3px 3px 0 0; background: linear-gradient(90deg,#22C55E,#15803D); }

    .about-section p { color: var(--ink-2); line-height: 1.7; margin: 0 0 2.25rem; max-width: 660px; font-size: .98rem; }

    .section-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .section-title h3 { margin: 0; font-size: 1.28rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .section-title a { color: var(--primary); font-size: .88rem; text-decoration: none; font-weight: 700; }

    .stock-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.4rem; }
    .product-card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--r-lg); overflow: hidden; box-shadow: var(--shadow-sm); position: relative; transition: transform .25s, box-shadow .25s; }
    .product-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
    .product-card img { width: 100%; height: 140px; object-fit: cover; }
    .product-card-body { padding: 1rem 1.15rem 1.15rem; }
    .product-card h4 { margin: 0 0 .2rem; font-size: 1rem; font-weight: 800; color: var(--ink); }
    .product-card .stock { margin: 0 0 .5rem; font-size: .82rem; color: var(--muted); }
    .product-card .price { color: var(--primary); font-weight: 800; font-size: .95rem; }
    .fav-icon {
        position: absolute; top: .8rem; right: .8rem; color: #DC2626; cursor: pointer;
        background: rgba(255,255,255,.92); backdrop-filter: blur(4px); border-radius: 50%; padding: .38rem; display: flex; box-shadow: 0 3px 8px -2px rgba(0,0,0,.25); transition: transform .2s;
    }
    .fav-icon:hover { transform: scale(1.12); }

    @media (max-width: 900px) { .farmer-details-grid, .stock-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-body" style="max-width: 1000px;">
    <div class="farmer-hero reveal">
        <img src="https://images.unsplash.com/photo-1595841696677-6489ff3f8cd1?w=1200&q=80" alt="Farmer Background">
    </div>

    <div class="farmer-info-card reveal">
        <div class="farmer-header">
            <div class="farmer-title">
                <div class="ava-wrap">
                    <img src="https://images.unsplash.com/<?php echo $farmer['avatar']; ?>?w=200&q=80" alt="Avatar">
                    <span class="verified"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
                </div>
                <div>
                    <h2><?php echo htmlspecialchars($farmer['name']); ?></h2>
                    <div class="rating"><span class="star">&#9733;</span> <?php echo $farmer['rating']; ?> <span>(<?php echo $farmer['reviews']; ?> reviews)</span></div>
                    <div class="tagline">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <?php echo htmlspecialchars($farmer['market']); ?> &bull; Verified Farmer
                    </div>
                </div>
            </div>
            <a href="favorites.php" class="btn-favorite">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                Favorite
            </a>
        </div>

        <div class="farmer-details-grid">
            <div class="detail-item">
                <div class="label"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Location</div>
                <div class="value"><?php echo htmlspecialchars($farmer['market']); ?></div>
            </div>
            <div class="detail-item">
                <div class="label"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Operating Days</div>
                <div class="value">Mon, Wed - 2:00 PM</div>
            </div>
            <div class="detail-item">
                <div class="label"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg> Directions</div>
                <a href="../map.php">Get Directions</a>
            </div>
        </div>
    </div>

    <div class="tabs reveal">
        <div class="tab active">About</div>
        <div class="tab">Products</div>
        <div class="tab">Reviews</div>
        <div class="tab">Location</div>
    </div>

    <div class="about-section reveal">
        <p>We grow fresh and seasonal vegetables using natural farming methods. Our goal is to provide healthy and chemical-free produce to our local community.</p>
    </div>

    <div class="section-title reveal">
        <h3>Current Stock</h3>
        <a href="products.php">View All &rarr;</a>
    </div>

    <div class="stock-grid">
        <?php
        $stock = [
            ['name' => 'Tomatoes', 'qty' => '15 kg', 'price' => 'Rs. 300/kg', 'img' => 'photo-1592924357228-1be254705e89'],
            ['name' => 'Spinach', 'qty' => '10 bunches', 'price' => 'Rs. 150/bunch', 'img' => 'photo-1576045057990-51b807586d5f'],
            ['name' => 'Carrots', 'qty' => '12 kg', 'price' => 'Rs. 100/kg', 'img' => 'photo-1598170845058-32b9d0a6e70a'],
        ];
        foreach ($stock as $i => $s): ?>
        <div class="product-card reveal" data-delay="<?php echo $i * 80; ?>">
            <a href="products.php" class="fav-icon" aria-label="Add to favorites">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </a>
            <img src="https://images.unsplash.com/<?php echo $s['img']; ?>?w=400&q=80" alt="<?php echo htmlspecialchars($s['name']); ?>">
            <div class="product-card-body">
                <h4><?php echo htmlspecialchars($s['name']); ?></h4>
                <p class="stock"><?php echo $s['qty']; ?></p>
                <div class="price"><?php echo $s['price']; ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
