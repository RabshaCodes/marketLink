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
    .greeting { margin-bottom: 1.9rem; }
    .greeting h1 { margin: 0 0 .3rem; font-size: 2rem; font-weight: 800; color: var(--ink); }
    .greeting h1 .wave { display: inline-block; animation: ml-wave 2.4s ease-in-out infinite; transform-origin: 70% 70%; }
    @keyframes ml-wave { 0%,60%,100% { transform: rotate(0); } 70% { transform: rotate(16deg); } 80% { transform: rotate(-8deg); } 90% { transform: rotate(12deg); } }
    .greeting p { margin: 0; color: var(--muted); font-size: .98rem; }

    /* STATS ROW */
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.15rem; margin-bottom: 1.9rem; }
    .stat-card {
        position: relative; overflow: hidden;
        background: var(--surface); padding: 1.35rem 1.35rem 1.3rem; border-radius: var(--r-lg);
        box-shadow: var(--shadow-sm); border: 1px solid var(--line);
        display: flex; flex-direction: column; gap: .55rem;
        transition: transform .25s, box-shadow .25s;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .stat-card::after {
        content: ''; position: absolute; right: -30px; top: -30px; width: 110px; height: 110px;
        border-radius: 50%; background: var(--tint, rgba(34,197,94,.1));
    }
    .stat-ico {
        width: 44px; height: 44px; border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        background: var(--tint, rgba(34,197,94,.12)); color: var(--tint-ink, var(--primary));
        position: relative; z-index: 1;
    }
    .stat-label { font-size: .82rem; color: var(--muted); font-weight: 600; }
    .stat-value { font-size: 2rem; font-weight: 800; color: var(--ink); line-height: 1; letter-spacing: -.03em; }

    /* HERO BANNER */
    .hero-banner {
        border-radius: 24px; overflow: hidden; position: relative;
        margin-bottom: 2.4rem; min-height: 210px;
        display: flex; align-items: center;
        box-shadow: var(--shadow-lg);
    }
    .hero-banner img.hero-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transform: scale(1.05); animation: ml-zoom 18s ease-in-out infinite alternate; }
    @keyframes ml-zoom { from { transform: scale(1.05) translate(0,0); } to { transform: scale(1.16) translate(-2%, -2%); } }
    .hero-banner::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(100deg, rgba(6,64,43,.94) 0%, rgba(11,95,55,.82) 45%, rgba(34,197,94,.35) 100%);
    }
    .hero-text { position: relative; z-index: 2; padding: 2.4rem 2.6rem; color: #fff; max-width: 60%; }
    .hero-eyebrow {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .72rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
        background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.25);
        padding: .35rem .8rem; border-radius: 999px; margin-bottom: .9rem; backdrop-filter: blur(4px);
    }
    .hero-text h2 { margin: 0 0 .35rem; font-size: 2rem; font-weight: 800; letter-spacing: -.03em; }
    .hero-text p { margin: 0 0 1.4rem; opacity: .92; font-size: 1rem; }
    .btn-white {
        background: #fff; color: #065F46; padding: .72rem 1.4rem;
        border-radius: 12px; text-decoration: none; font-weight: 800; display: inline-flex; align-items: center; gap: .5rem; font-size: .9rem;
        box-shadow: 0 10px 24px -8px rgba(0,0,0,.4); transition: transform .2s, gap .2s;
    }
    .btn-white:hover { transform: translateY(-2px); gap: .75rem; }

    /* SECTION HEADERS */
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.15rem; }
    .section-header h3 { margin: 0; font-size: 1.28rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .section-header a { color: var(--primary); text-decoration: none; font-size: .86rem; font-weight: 700; display: inline-flex; align-items: center; gap: .3rem; transition: gap .2s; }
    .section-header a:hover { gap: .55rem; }

    /* FAVORITE FARMERS */
    .favorites-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 2.4rem; }
    .fav-card {
        background: var(--surface); padding: 1.6rem 1.4rem; border-radius: var(--r-lg);
        box-shadow: var(--shadow-sm); border: 1px solid var(--line); text-align: center;
        transition: transform .25s, box-shadow .25s;
    }
    .fav-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
    .fav-ava { position: relative; display: inline-block; margin-bottom: .85rem; }
    .fav-ava img { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 0 0 3px #DCF0E3; }
    .fav-ava .verified {
        position: absolute; right: -2px; bottom: -2px; width: 22px; height: 22px; border-radius: 50%;
        background: linear-gradient(135deg, #22C55E, #15803D); color: #fff; border: 2px solid #fff;
        display: flex; align-items: center; justify-content: center;
    }
    .fav-card h4 { margin: 0 0 .3rem; font-size: 1.02rem; font-weight: 800; color: var(--ink); }
    .fav-card .fav-loc { margin: 0 0 .55rem; font-size: .82rem; color: var(--muted); display: flex; align-items: center; justify-content: center; gap: .3rem; }
    .fav-card .fav-loc svg { color: var(--primary); }
    .fav-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        background: #FFF7E6; color: #B45309; font-weight: 800; font-size: .78rem;
        padding: .22rem .6rem; border-radius: 999px; margin-bottom: .95rem;
    }
    .fav-btn {
        display: block; width: 100%; padding: .62rem;
        background: linear-gradient(135deg, #1D9A50, #15803D);
        color: #fff; text-decoration: none; border-radius: 11px; font-size: .88rem; font-weight: 700; box-sizing: border-box;
        box-shadow: 0 8px 18px -8px rgba(21,128,61,.6); transition: filter .2s, transform .2s;
    }
    .fav-btn:hover { filter: brightness(1.06); transform: translateY(-1px); }

    /* RECENT ORDERS */
    .order-list { display: flex; flex-direction: column; gap: .9rem; }
    .order-item {
        background: var(--surface); padding: .95rem 1.2rem; border-radius: var(--r-md);
        box-shadow: var(--shadow-sm); border: 1px solid var(--line);
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        transition: transform .2s, box-shadow .2s;
    }
    .order-item:hover { transform: translateX(3px); box-shadow: var(--shadow-md); }
    .order-info { display: flex; align-items: center; gap: .95rem; flex: 1; min-width: 0; }
    .order-info .emoji-box { width: 48px; height: 48px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
    .order-details h4 { margin: 0 0 .15rem; font-size: .96rem; font-weight: 700; color: var(--ink); }
    .order-details p { margin: 0; font-size: .82rem; color: var(--muted); }
    .order-price { color: var(--ink); font-weight: 800; font-size: .98rem; }
    .btn-outline {
        border: 1.5px solid var(--line); padding: .5rem 1rem; border-radius: 10px;
        color: var(--ink-2); text-decoration: none; font-size: .84rem; font-weight: 700; background: #fff; transition: all .2s;
    }
    .btn-outline:hover { border-color: var(--primary-2); color: var(--primary); }

    @media (max-width: 1080px) { .stats-row { grid-template-columns: repeat(2, 1fr); } .favorites-row { grid-template-columns: 1fr; } .hero-text { max-width: 80%; } }
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
            <div class="stat-label">Total Orders</div>
            <div class="stat-value countup" data-count="12">0</div>
        </div>
        <div class="stat-card reveal" data-delay="70" style="--tint: rgba(244,63,94,.1); --tint-ink: #E11D48;">
            <div class="stat-ico">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </div>
            <div class="stat-label">Favorites</div>
            <div class="stat-value countup" data-count="5">0</div>
        </div>
        <div class="stat-card reveal" data-delay="140" style="--tint: rgba(37,99,235,.1); --tint-ink: #2563EB;">
            <div class="stat-ico">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            </div>
            <div class="stat-label">Reviews</div>
            <div class="stat-value countup" data-count="3">0</div>
        </div>
        <div class="stat-card reveal" data-delay="210" style="--tint: rgba(124,58,237,.1); --tint-ink: #7C3AED;">
            <div class="stat-ico">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div class="stat-label">Upcoming Pickup</div>
            <div class="stat-value countup" data-count="2">0</div>
        </div>
    </div>

    <!-- HERO -->
    <div class="hero-banner reveal">
        <img class="hero-bg" src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=1400&q=80" alt="Fresh produce">
        <div class="hero-text">
            <span class="hero-eyebrow">🌱 Farm fresh daily</span>
            <h2>Fresh &amp; Local Produce</h2>
            <p>Directly from farmers near you.</p>
            <a href="browse_markets.php" class="btn-white">Browse Markets
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>

    <!-- FAVORITE FARMERS -->
    <div class="section-header reveal">
        <h3>Favorite Farmers</h3>
        <a href="favorites.php">View All
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>
    <div class="favorites-row">
        <?php
        $fav_farmers = [
            ['id' => 1, 'name' => 'Ali Khan Farms',   'ava' => 'photo-1595078475343-1c03ade2f0f8', 'rating' => '4.8', 'reviews' => 16, 'market' => 'Gulshan Market'],
            ['id' => 2, 'name' => 'Sara Ahmed Farms', 'ava' => 'photo-1621939514649-280e2ee25f60', 'rating' => '4.7', 'reviews' => 42, 'market' => 'Clifton Market'],
            ['id' => 3, 'name' => 'Usman Farm',       'ava' => 'photo-1500648767791-00dcc994a43e', 'rating' => '4.6', 'reviews' => 28, 'market' => 'Saddar Market'],
        ];
        foreach ($fav_farmers as $i => $f): ?>
        <div class="fav-card reveal" data-delay="<?php echo $i * 80; ?>">
            <div class="fav-ava">
                <img src="https://images.unsplash.com/<?php echo $f['ava']; ?>?w=200&q=80" alt="<?php echo htmlspecialchars($f['name']); ?>">
                <span class="verified"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></span>
            </div>
            <h4><?php echo htmlspecialchars($f['name']); ?></h4>
            <p class="fav-loc">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <?php echo htmlspecialchars($f['market']); ?>
            </p>
            <span class="fav-badge">&#9733; <?php echo $f['rating']; ?> &middot; <?php echo $f['reviews']; ?> reviews</span>
            <a href="farmer_profile.php?id=<?php echo $f['id']; ?>" class="fav-btn">View Profile</a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- RECENT ORDERS -->
    <div class="section-header reveal">
        <h3>Recent Orders</h3>
        <a href="orders.php">View All
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
    </div>
    <div class="order-list">
        <div class="order-item reveal">
            <div class="order-info">
                <div class="emoji-box" style="background:#fee2e2;">&#127813;</div>
                <div class="order-details">
                    <h4>Organic Tomatoes</h4>
                    <p>2 kg &middot; Apr 10, 2026</p>
                </div>
            </div>
            <div class="order-price">Rs. 500</div>
            <span class="status-pill pill-green">Delivered</span>
            <a href="orders.php" class="btn-outline">View Details</a>
        </div>
        <div class="order-item reveal" data-delay="80">
            <div class="order-info">
                <div class="emoji-box" style="background:#dcfce7;">&#129388;</div>
                <div class="order-details">
                    <h4>Fresh Spinach</h4>
                    <p>1 bunch &middot; Apr 10, 2026</p>
                </div>
            </div>
            <div class="order-price">Rs. 150</div>
            <span class="status-pill pill-orange">Pending</span>
            <a href="orders.php" class="btn-outline">View Details</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
