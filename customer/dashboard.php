<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Customer Dashboard - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'dashboard';
include __DIR__ . '/includes/dashboard_layout.php';
?>
<style>
    /* Greeting */
    .greeting { margin-bottom: 1.6rem; }
    .greeting h1 { margin: 0 0 .3rem; font-size: 1.75rem; font-weight: 800; color: var(--ink); }
    .greeting h1 .wave { display: inline-block; animation: ml-wave 2.4s ease-in-out infinite; transform-origin: 70% 70%; }
    @keyframes ml-wave { 0%,60%,100% { transform: rotate(0); } 70% { transform: rotate(16deg); } 80% { transform: rotate(-8deg); } 90% { transform: rotate(12deg); } }
    .greeting p { margin: 0; color: var(--muted); font-size: .93rem; }

    /* STATS ROW */
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card {
        background: var(--surface); padding: 1.2rem; border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid var(--line);
        display: flex; align-items: center; gap: 1rem;
    }
    .stat-ico {
        width: 46px; height: 46px; flex-shrink: 0;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: var(--tint); color: var(--tint-ink);
    }
    .stat-text { display: flex; flex-direction: column; gap: .2rem; }
    .stat-label { font-size: .85rem; color: var(--tint-ink); font-weight: 700; white-space: nowrap; }
    .stat-value { font-size: 1.6rem; font-weight: 800; color: var(--ink); line-height: 1; }

    /* HERO BANNER */
    .hero-banner {
        border-radius: 16px; overflow: hidden; position: relative;
        margin-bottom: 2rem; min-height: 220px;
        display: flex; align-items: center; justify-content: space-between;
        background: #064E3B;
    }
    .hero-banner img.hero-bg { 
        position: absolute; right: 0; top: 0; bottom: 0;
        width: 55%; height: 100%; object-fit: cover;
        -webkit-mask-image: linear-gradient(to right, transparent, black 40%);
        mask-image: linear-gradient(to right, transparent, black 40%);
    }
    .hero-text { position: relative; z-index: 2; padding: 2.2rem 2.5rem; color: #fff; max-width: 50%; }
    .hero-text h2 { margin: 0 0 .5rem; font-size: 1.8rem; font-weight: 800; }
    .hero-text p { margin: 0; opacity: .9; font-size: 1rem; }
    .hero-action { position: absolute; bottom: 1.8rem; right: 2rem; z-index: 2; }
    .btn-green-action {
        background: #10B981; color: #fff; padding: .65rem 1.2rem;
        border-radius: 8px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: .5rem; font-size: .9rem;
    }

    /* SECTION HEADERS */
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; }
    .section-header h3 { margin: 0; font-size: 1.2rem; font-weight: 800; color: var(--ink); }
    .section-header a { color: var(--primary); text-decoration: none; font-size: .85rem; font-weight: 700; display: inline-flex; align-items: center; gap: .3rem; }

    /* FAVORITE FARMERS */
    .favorites-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.2rem; margin-bottom: 2.2rem; }
    .fav-card {
        background: var(--surface); padding: 1.8rem 1.2rem 1.5rem; border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid var(--line); text-align: center;
        display: flex; flex-direction: column; align-items: center;
    }
    .fav-ava { margin-bottom: 1rem; }
    .fav-ava img { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; }
    .fav-card h4 { margin: 0 0 .3rem; font-size: 1.05rem; font-weight: 800; color: var(--ink); }
    .fav-card .fav-loc { margin: 0 0 .6rem; font-size: .85rem; color: var(--muted); display: flex; align-items: center; justify-content: center; gap: .3rem; }
    .fav-badge {
        display: inline-flex; align-items: center; justify-content: center; gap: .3rem;
        color: #F59E0B; font-weight: 700; font-size: .9rem; margin-bottom: 1.2rem;
    }
    .fav-badge span { color: var(--muted); font-weight: 500; font-size: .8rem; }
    .fav-btn {
        display: block; width: 100%; padding: .7rem;
        background: var(--primary); color: #fff; text-decoration: none; border-radius: 8px; font-size: .9rem; font-weight: 700;
    }

    /* RECENT ORDERS */
    .order-list { display: flex; flex-direction: column; gap: 1rem; }
    .order-item {
        background: var(--surface); padding: 1rem 1.2rem; border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid var(--line);
        display: flex; align-items: center; justify-content: space-between;
    }
    .order-info { display: flex; align-items: center; gap: 1.2rem; }
    .order-img { width: 60px; height: 60px; border-radius: 12px; object-fit: cover; }
    .order-details h4 { margin: 0 0 .2rem; font-size: 1rem; font-weight: 700; color: var(--ink); }
    .order-details p { margin: 0; font-size: .9rem; color: var(--primary); font-weight: 700; }
    .order-details p span { color: var(--muted); font-weight: 500; font-size: .8rem; text-decoration: line-through; margin-left: 5px; }
    .order-actions { display: flex; align-items: center; gap: 1.5rem; }
    .btn-outline {
        border: 1px solid var(--line); padding: .55rem 1.2rem; border-radius: 8px;
        color: var(--ink); text-decoration: none; font-size: .85rem; font-weight: 700; background: #fff;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 1080px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .favorites-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .stats-row { grid-template-columns: 1fr 1fr; }
        .favorites-row { grid-template-columns: 1fr; }
        .hero-banner { flex-direction: column; text-align: center; }
        .hero-text { max-width: 100%; padding: 2rem; }
        .hero-banner img.hero-bg { position: relative; width: 100%; height: 120px; mask-image: none; -webkit-mask-image: none; }
        .hero-action { position: relative; bottom: 0; right: 0; margin-bottom: 2rem; }
        .order-item { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .order-actions { width: 100%; justify-content: space-between; }
    }
</style>

<div class="page-body">
    <div class="greeting reveal">
        <h1>Hello, <?php echo htmlspecialchars($firstName); ?>! <span class="wave">👋</span></h1>
        <p>Here's what's happening with your market journey</p>
    </div>

    <!-- STATS -->
    <div class="stats-row">
        <div class="stat-card reveal" style="--tint: rgba(21,128,61,.1); --tint-ink: #15803D;">
            <div class="stat-ico">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            </div>
            <div class="stat-text">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value countup" data-count="12">0</div>
            </div>
        </div>
        <div class="stat-card reveal" data-delay="70" style="--tint: rgba(244,63,94,.1); --tint-ink: #E11D48;">
            <div class="stat-ico">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </div>
            <div class="stat-text">
                <div class="stat-label">Favorites</div>
                <div class="stat-value countup" data-count="5">0</div>
            </div>
        </div>
        <div class="stat-card reveal" data-delay="140" style="--tint: rgba(37,99,235,.1); --tint-ink: #2563EB;">
            <div class="stat-ico">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            </div>
            <div class="stat-text">
                <div class="stat-label">Reviews</div>
                <div class="stat-value countup" data-count="3">0</div>
            </div>
        </div>
        <div class="stat-card reveal" data-delay="210" style="--tint: rgba(124,58,237,.1); --tint-ink: #7C3AED;">
            <div class="stat-ico">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div class="stat-text">
                <div class="stat-label">Upcoming Pickup</div>
                <div class="stat-value countup" data-count="2">0</div>
            </div>
        </div>
    </div>

    <!-- HERO -->
    <div class="hero-banner reveal">
        <div class="hero-text">
            <h2>Fresh &amp; Local Produce</h2>
            <p>Directly from Farmers</p>
        </div>
        <img class="hero-bg" src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=1400&q=80" alt="Fresh produce">
        <div class="hero-action">
            <a href="browse_markets.php" class="btn-green-action">Browse Markets
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- FAVORITE FARMERS -->
    <div class="section-header reveal">
        <h3>Favorite Farmers</h3>
        <a href="favorites.php">View All &rarr;</a>
    </div>
    <div class="favorites-row">
        <?php
        $fav_farmers = [
            ['id' => 1, 'name' => 'Ali Khan Farms',   'ava' => 'photo-1595078475343-1c03ade2f0f8', 'rating' => '4.8', 'reviews' => 16, 'market' => 'Clifton Karachi'],
            ['id' => 2, 'name' => 'Sara Ahmed Farms', 'ava' => 'photo-1621939514649-280e2ee25f60', 'rating' => '4.7', 'reviews' => 42, 'market' => 'Clifton Karachi'],
            ['id' => 3, 'name' => 'Usman Farm',       'ava' => 'photo-1500648767791-00dcc994a43e', 'rating' => '4.6', 'reviews' => 28, 'market' => 'Clifton Karachi'],
        ];
        foreach ($fav_farmers as $i => $f): ?>
        <div class="fav-card reveal" data-delay="<?php echo $i * 80; ?>">
            <div class="fav-ava">
                <img src="https://images.unsplash.com/<?php echo $f['ava']; ?>?w=200&q=80" alt="<?php echo htmlspecialchars($f['name']); ?>">
            </div>
            <h4><?php echo htmlspecialchars($f['name']); ?></h4>
            <p class="fav-loc">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <?php echo htmlspecialchars($f['market']); ?>
            </p>
            <div class="fav-badge">
                &#9733; <?php echo $f['rating']; ?> <span>(<?php echo $f['reviews']; ?> reviews)</span>
            </div>
            <a href="farmer_profile.php?id=<?php echo $f['id']; ?>" class="fav-btn">View Profile <?php if($i===1) echo '&nearr;'; ?></a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- RECENT ORDERS -->
    <div class="section-header reveal">
        <h3>Recent Orders</h3>
        <a href="orders.php">View All &rarr;</a>
    </div>
    <div class="order-list">
        <div class="order-item reveal">
            <div class="order-info">
                <img src="https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=200&q=80" alt="Tomatoes" class="order-img">
                <div class="order-details">
                    <h4>Organic Tomatoes</h4>
                    <p>Rs. 250 <span>Rs. 310</span></p>
                </div>
            </div>
            <div class="order-actions">
                <span class="status-pill pill-green">Delivered</span>
                <a href="orders.php" class="btn-outline">View Details</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
