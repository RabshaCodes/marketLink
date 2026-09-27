<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Browse Markets - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'markets';
include __DIR__ . '/includes/dashboard_layout.php';

$markets = [
    ['id' => 1, 'name' => 'Gulshan Farmers Market', 'rating' => '4.8', 'reviews' => 235, 'farmers' => 6, 'area' => 'Gulshan, Karachi', 'days' => 'Mon, Wed, Sat', 'hours' => '7:00 AM - 2:00 PM', 'img' => 'photo-1488459716781-31db52582fe9'],
    ['id' => 2, 'name' => 'Orangi Market', 'rating' => '4.6', 'reviews' => 142, 'farmers' => 4, 'area' => 'Orangi, Karachi', 'days' => 'Tue, Thu, Sun', 'hours' => '8:00 AM - 3:00 PM', 'img' => 'photo-1542838132-92c53300491e'],
    ['id' => 3, 'name' => 'Malir Market', 'rating' => '4.9', 'reviews' => 312, 'farmers' => 8, 'area' => 'Malir, Karachi', 'days' => 'Mon, Thu, Sat', 'hours' => '6:00 AM - 1:00 PM', 'img' => 'photo-1519999482648-25049ddd37b1'],
    ['id' => 4, 'name' => 'Clifton Market', 'rating' => '4.7', 'reviews' => 189, 'farmers' => 5, 'area' => 'Clifton, Karachi', 'days' => 'Sat, Sun, Mon', 'hours' => '9:00 AM - 5:00 PM', 'img' => 'photo-1533900298318-6b8da08a523e'],
    ['id' => 5, 'name' => 'North Karachi Market', 'rating' => '4.5', 'reviews' => 98, 'farmers' => 3, 'area' => 'North Karachi, Karachi', 'days' => 'Tue, Thu, Sat', 'hours' => '7:00 AM - 1:00 PM', 'img' => 'photo-1490287407262-2410138891a1'],
    ['id' => 6, 'name' => 'Saddar Market', 'rating' => '4.4', 'reviews' => 76, 'farmers' => 4, 'area' => 'Saddar, Karachi', 'days' => 'Mon, Wed, Fri', 'hours' => '8:00 AM - 4:00 PM', 'img' => 'photo-1604721769562-1eb2181f408f'],
];
?>
<style>
    /* Pull the entire page content up */
    .page-body {
        padding-top: 0 !important;
    }

    .page-header {
        margin-bottom: .4rem;
    }
    .page-header h1 {
        margin: 0 0 .1rem 0;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--ink);
    }
    .page-header p {
        margin: 0;
        color: var(--muted);
        font-size: .85rem;
    }

    /* FILTER BAR */
    .filter-bar {
        display: flex; gap: .6rem; margin-bottom: .4rem; align-items: center; flex-wrap: wrap;
        background: var(--surface); border: 1px solid var(--line); border-radius: var(--r-md);
        padding: .5rem .75rem; box-shadow: var(--shadow-sm);
    }
    .filter-select {
        padding: .6rem .9rem; border: 1.5px solid var(--line); border-radius: 10px; background: #fff;
        color: var(--ink); flex: 1; min-width: 180px; font-size: .9rem; font-weight: 600;
        display: flex; align-items: center; gap: .5rem; transition: border-color .2s;
    }
    .filter-select:focus-within { border-color: #2E7D32; }
    .filter-select svg { color: #2E7D32; flex-shrink: 0; }
    .filter-select select { border: none; outline: none; background: transparent; width: 100%; cursor: pointer; font-family: inherit; font-weight: 600; color: var(--ink); }
    .btn-search {
        background: #2E7D32; color: #fff; border: none;
        padding: .65rem 1.6rem; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: inherit; font-size: .9rem;
        box-shadow: 0 8px 18px -8px rgba(46, 125, 50, .5); transition: transform .2s, filter .2s;
    }
    .btn-search:hover { transform: translateY(-2px); filter: brightness(1.1); }

    .section-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: .4rem; }
    .section-title h3 { margin: 0; font-size: 1.2rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .sort-by { color: var(--muted); font-size: .85rem; font-weight: 600; }
    .sort-by select { border: none; background: transparent; font-weight: 700; color: var(--ink); cursor: pointer; outline: none; font-family: inherit; }

    /* MARKETS GRID */
    .markets-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; }
    .market-card {
        background: var(--surface); border-radius: var(--r-md); overflow: hidden;
        box-shadow: var(--shadow-sm); border: 1px solid var(--line);
        display: flex; flex-direction: column;
        transition: transform .28s, box-shadow .28s;
    }
    .market-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .market-img-container { position: relative; height: 145px; overflow: hidden; }
    .market-img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
    .market-card:hover .market-img-container img { transform: scale(1.08); }
    .market-img-container::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 45%, rgba(6,64,43,.5)); }
    .market-badge {
        position: absolute; top: 10px; right: 10px; z-index: 2;
        background: rgba(255,255,255,.92); backdrop-filter: blur(6px);
        padding: .25rem .5rem; border-radius: 999px; font-size: .72rem; font-weight: 800; color: #2E7D32;
        display: flex; align-items: center; gap: .25rem; box-shadow: 0 3px 8px -2px rgba(0,0,0,.2);
    }
    .market-open {
        position: absolute; top: 10px; left: 10px; z-index: 2;
        background: rgba(46, 125, 50, .92); color: #fff; padding: .25rem .5rem; border-radius: 999px;
        font-size: .68rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase;
        display: flex; align-items: center; gap: .3rem;
    }
    .market-open .dot { width: 6px; height: 6px; border-radius: 50%; background: #A7F3D0; box-shadow: 0 0 0 0 rgba(167, 243, 208, 0.7); animation: ml-ping 1.8s infinite; }
    @keyframes ml-ping { 0% { box-shadow: 0 0 0 0 rgba(167, 243, 208, .7); } 70% { box-shadow: 0 0 0 6px rgba(167, 243, 208, 0); } 100% { box-shadow: 0 0 0 0 rgba(167, 243, 208, 0); } }
    .market-info { padding: 1rem 1.15rem 1.15rem; display: flex; flex-direction: column; flex: 1; }
    .market-info h4 { margin: 0 0 .25rem; font-size: 1.08rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .market-rating { font-size: .82rem; color: #B45309; font-weight: 700; margin-bottom: .75rem; display: flex; align-items: center; gap: .3rem; }
    .market-rating .star { color: #F59E0B; }
    .market-rating span { color: var(--muted); font-weight: 500; }
    .market-detail-row { display: flex; align-items: center; gap: .5rem; margin-bottom: .4rem; font-size: .82rem; color: var(--muted); font-weight: 500; }
    .market-detail-row svg { color: #2E7D32; flex-shrink: 0; }
    .btn-view {
        display: flex; align-items: center; justify-content: center; gap: .5rem; width: 100%;
        background: #2E7D32; color: #fff;
        padding: .65rem; border-radius: 10px; text-decoration: none; font-weight: 700; margin-top: auto; font-size: .88rem;
        box-shadow: 0 6px 14px -5px rgba(46, 125, 50, .5); transition: filter .2s, gap .2s;
    }
    .btn-view:hover { filter: brightness(1.1); gap: .75rem; }
</style>

<div class="page-body">
    <div class="page-header reveal">
        <h1>Browse Markets</h1>
        <p>Discover local farmers markets near you and find fresh, seasonal produce.</p>
    </div>

    <!-- FILTERS -->
    <div class="filter-bar reveal">
        <div class="filter-select">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <select><option>Karachi</option><option>Lahore</option><option>Islamabad</option></select>
        </div>
        <div class="filter-select">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <select><option>All Days</option><option>Weekends Only</option><option>Weekdays</option></select>
        </div>
        <button class="btn-search">Search</button>
    </div>

    <div class="section-title reveal">
        <h3>Nearby Markets</h3>
        <div class="sort-by">Sort by:
            <select><option>Nearest</option><option>Highest Rated</option><option>Most Farmers</option></select>
        </div>
    </div>

    <div class="markets-grid">
        <?php foreach ($markets as $i => $m): ?>
        <div class="market-card reveal" data-delay="<?php echo ($i % 3) * 90; ?>">
            <div class="market-img-container">
                <span class="market-open"><span class="dot"></span> Open</span>
                <img src="https://images.unsplash.com/<?php echo $m['img']; ?>?w=500&q=80" alt="<?php echo htmlspecialchars($m['name']); ?>">
                <div class="market-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path></svg>
                    <?php echo $m['farmers']; ?> farmers
                </div>
            </div>
            <div class="market-info">
                <h4><?php echo htmlspecialchars($m['name']); ?></h4>
                <div class="market-rating"><span class="star">&#9733;</span> <?php echo $m['rating']; ?> <span>(<?php echo $m['reviews']; ?> Reviews)</span></div>
                <div class="market-detail-row">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path></svg>
                    <span><?php echo htmlspecialchars($m['area']); ?></span>
                </div>
                <div class="market-detail-row">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line></svg>
                    <span><?php echo htmlspecialchars($m['days']); ?></span>
                </div>
                <div class="market-detail-row" style="margin-bottom: 1.3rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span><?php echo htmlspecialchars($m['hours']); ?></span>
                </div>
                <a href="market_profile.php?id=<?php echo $m['id']; ?>" class="btn-view">View Market
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
