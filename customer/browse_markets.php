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
    /* FILTER BAR */
    .filter-bar {
        display: flex; gap: .9rem; margin-bottom: 2.2rem; align-items: center; flex-wrap: wrap;
        background: var(--surface); border: 1px solid var(--line); border-radius: var(--r-lg);
        padding: 1rem 1.15rem; box-shadow: var(--shadow-sm);
    }
    .filter-select {
        padding: .65rem .95rem; border: 1.5px solid var(--line); border-radius: 12px; background: #fff;
        color: var(--ink); flex: 1; min-width: 180px; max-width: 260px; font-size: .9rem; font-weight: 600;
        display: flex; align-items: center; gap: .5rem; transition: border-color .2s;
    }
    .filter-select:focus-within { border-color: var(--primary-2); }
    .filter-select svg { color: var(--primary); flex-shrink: 0; }
    .filter-select select { border: none; outline: none; background: transparent; width: 100%; cursor: pointer; font-family: inherit; font-weight: 600; color: var(--ink); }
    .btn-search {
        background: linear-gradient(135deg, #1D9A50, #15803D); color: #fff; border: none;
        padding: .72rem 1.9rem; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: inherit; font-size: .9rem;
        box-shadow: 0 10px 22px -8px rgba(21,128,61,.55); transition: transform .2s, filter .2s;
    }
    .btn-search:hover { transform: translateY(-2px); filter: brightness(1.05); }

    .section-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .section-title h3 { margin: 0; font-size: 1.28rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .sort-by { color: var(--muted); font-size: .88rem; font-weight: 600; }
    .sort-by select { border: none; background: transparent; font-weight: 700; color: var(--ink); cursor: pointer; outline: none; font-family: inherit; }

    /* MARKETS GRID */
    .markets-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 1.6rem; }
    .market-card {
        background: var(--surface); border-radius: var(--r-lg); overflow: hidden;
        box-shadow: var(--shadow-sm); border: 1px solid var(--line);
        display: flex; flex-direction: column;
        transition: transform .28s, box-shadow .28s;
    }
    .market-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-md); }
    .market-img-container { position: relative; height: 165px; overflow: hidden; }
    .market-img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
    .market-card:hover .market-img-container img { transform: scale(1.08); }
    .market-img-container::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 45%, rgba(6,64,43,.5)); }
    .market-badge {
        position: absolute; top: 12px; right: 12px; z-index: 2;
        background: rgba(255,255,255,.92); backdrop-filter: blur(6px);
        padding: .3rem .6rem; border-radius: 999px; font-size: .74rem; font-weight: 800; color: var(--primary);
        display: flex; align-items: center; gap: .3rem; box-shadow: 0 4px 10px -3px rgba(0,0,0,.25);
    }
    .market-open {
        position: absolute; top: 12px; left: 12px; z-index: 2;
        background: rgba(21,128,61,.92); color: #fff; padding: .28rem .6rem; border-radius: 999px;
        font-size: .68rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase;
        display: flex; align-items: center; gap: .32rem;
    }
    .market-open .dot { width: 6px; height: 6px; border-radius: 50%; background: #86EFAC; box-shadow: 0 0 0 0 rgba(134,239,172,.7); animation: ml-ping 1.8s infinite; }
    @keyframes ml-ping { 0% { box-shadow: 0 0 0 0 rgba(134,239,172,.7); } 70% { box-shadow: 0 0 0 7px rgba(134,239,172,0); } 100% { box-shadow: 0 0 0 0 rgba(134,239,172,0); } }
    .market-info { padding: 1.15rem 1.3rem 1.3rem; display: flex; flex-direction: column; flex: 1; }
    .market-info h4 { margin: 0 0 .35rem; font-size: 1.12rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .market-rating { font-size: .85rem; color: #B45309; font-weight: 700; margin-bottom: .85rem; display: flex; align-items: center; gap: .35rem; }
    .market-rating .star { color: #F59E0B; }
    .market-rating span { color: var(--muted); font-weight: 500; }
    .market-detail-row { display: flex; align-items: center; gap: .55rem; margin-bottom: .5rem; font-size: .85rem; color: var(--muted); font-weight: 500; }
    .market-detail-row svg { color: var(--primary); flex-shrink: 0; }
    .btn-view {
        display: flex; align-items: center; justify-content: center; gap: .5rem; width: 100%;
        background: linear-gradient(135deg, #1D9A50, #15803D); color: #fff;
        padding: .78rem; border-radius: 12px; text-decoration: none; font-weight: 700; margin-top: auto; font-size: .9rem;
        box-shadow: 0 8px 18px -8px rgba(21,128,61,.6); transition: filter .2s, gap .2s;
    }
    .btn-view:hover { filter: brightness(1.06); gap: .75rem; }
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
