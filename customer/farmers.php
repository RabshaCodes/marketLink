<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Farmers - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'farmers';
include __DIR__ . '/includes/dashboard_layout.php';

$all_farmers = [
    ['id' => 1, 'name' => 'Ali Khan Farms',      'avatar' => 'photo-1595078475343-1c03ade2f0f8', 'rating' => '4.8', 'reviews' => 16, 'market' => 'Gulshan Market',     'cats' => 'Vegetables • Fruits • Herbs'],
    ['id' => 2, 'name' => 'Sara Ahmed Farms',    'avatar' => 'photo-1621939514649-280e2ee25f60', 'rating' => '4.7', 'reviews' => 42, 'market' => 'Clifton Market',     'cats' => 'Dairy • Eggs • Vegetables'],
    ['id' => 3, 'name' => 'Usman Farm',          'avatar' => 'photo-1500648767791-00dcc994a43e', 'rating' => '4.6', 'reviews' => 28, 'market' => 'Saddar Market',      'cats' => 'Fruits • Seasonal'],
    ['id' => 4, 'name' => 'Bilal Organic Grow',  'avatar' => 'photo-1507003211169-0a1dd7228f2d', 'rating' => '4.5', 'reviews' => 19, 'market' => 'Malir Market',       'cats' => 'Vegetables • Herbs'],
    ['id' => 5, 'name' => 'Karachi Green Farm',  'avatar' => 'photo-1519085360753-bf342679e516', 'rating' => '4.4', 'reviews' => 34, 'market' => 'Orangi Market',      'cats' => 'Fruits • Dairy'],
    ['id' => 6, 'name' => 'Malir Fresh Produce', 'avatar' => 'photo-1472099645785-818a24f532aa', 'rating' => '4.3', 'reviews' => 12, 'market' => 'North Karachi Market', 'cats' => 'Vegetables • Seasonal'],
];
?>
<style>
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
    .btn-search { background: linear-gradient(135deg,#1D9A50,#15803D); color: #fff; border: none; padding: .72rem 1.9rem; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: inherit; font-size: .9rem; box-shadow: 0 10px 22px -8px rgba(21,128,61,.55); transition: transform .2s, filter .2s; }
    .btn-search:hover { transform: translateY(-2px); filter: brightness(1.05); }

    .section-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .section-title h3 { margin: 0; font-size: 1.28rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .sort-by { color: var(--muted); font-size: .88rem; font-weight: 600; }
    .sort-by select { border: none; background: transparent; font-weight: 700; color: var(--ink); cursor: pointer; outline: none; font-family: inherit; }

    .farmers-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
    .farmer-card {
        position: relative; background: var(--surface); border-radius: var(--r-lg); border: 1px solid var(--line);
        box-shadow: var(--shadow-sm); padding: 2.6rem 1.5rem 1.5rem; text-align: center; overflow: hidden;
        transition: transform .25s, box-shadow .25s;
    }
    .farmer-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 66px; background: linear-gradient(135deg,#15803D,#22C55E); }
    .farmer-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-md); }
    .farmer-card img { width: 84px; height: 84px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 0 0 3px #DCF0E3, var(--shadow-sm); margin-bottom: .8rem; position: relative; z-index: 1; }
    .fc-rating-chip { position: absolute; top: .9rem; right: .9rem; z-index: 2; background: rgba(255,255,255,.92); color: #B45309; font-weight: 800; font-size: .76rem; padding: .25rem .55rem; border-radius: 999px; display: flex; align-items: center; gap: .25rem; box-shadow: 0 3px 8px -2px rgba(0,0,0,.2); }
    .fc-rating-chip .star { color: #F59E0B; }
    .farmer-card h4 { margin: 0 0 .35rem; font-size: 1.08rem; font-weight: 800; color: var(--ink); }
    .farmer-card .fc-loc { display: flex; align-items: center; justify-content: center; gap: .3rem; font-size: .84rem; color: var(--muted); font-weight: 600; margin-bottom: .5rem; }
    .farmer-card .fc-loc svg { color: var(--primary); }
    .farmer-card .fc-reviews { font-size: .78rem; color: var(--muted); margin-bottom: .55rem; }
    .farmer-card .fc-cats { font-size: .8rem; color: var(--primary); background: #EAF7EE; border-radius: 999px; padding: .35rem .8rem; display: inline-block; margin-bottom: 1.15rem; font-weight: 600; }
    .farmer-card .fav-btn { display: block; width: 100%; padding: .65rem; background: linear-gradient(135deg,#1D9A50,#15803D); color: #fff; text-decoration: none; border-radius: 12px; font-size: .9rem; font-weight: 700; box-shadow: 0 8px 18px -8px rgba(21,128,61,.6); transition: filter .2s, transform .2s; }
    .farmer-card .fav-btn:hover { filter: brightness(1.06); transform: translateY(-1px); }
</style>

<div class="page-body">
    <div class="page-header reveal">
        <h1>Local Farmers</h1>
        <p>Meet the farmers growing fresh, chemical-free produce near you.</p>
    </div>

    <div class="filter-bar reveal">
        <div class="filter-select">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <select><option>All Markets</option><option>Gulshan Market</option><option>Clifton Market</option><option>Saddar Market</option><option>Malir Market</option></select>
        </div>
        <div class="filter-select">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            <select><option>All Categories</option><option>Vegetables</option><option>Fruits</option><option>Dairy &amp; Eggs</option><option>Herbs</option></select>
        </div>
        <button class="btn-search">Search</button>
    </div>

    <div class="section-title reveal">
        <h3><?php echo count($all_farmers); ?> Farmers Available</h3>
        <div class="sort-by">Sort by:
            <select><option>Highest Rated</option><option>Most Reviews</option><option>Nearest</option></select>
        </div>
    </div>

    <div class="farmers-grid">
        <?php foreach ($all_farmers as $i => $f): ?>
        <div class="farmer-card reveal" data-delay="<?php echo ($i % 3) * 80; ?>">
            <span class="fc-rating-chip"><span class="star">&#9733;</span> <?php echo $f['rating']; ?></span>
            <img src="https://images.unsplash.com/<?php echo $f['avatar']; ?>?w=200&q=80" alt="<?php echo htmlspecialchars($f['name']); ?>">
            <h4><?php echo htmlspecialchars($f['name']); ?></h4>
            <div class="fc-loc">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <?php echo htmlspecialchars($f['market']); ?>
            </div>
            <div class="fc-reviews"><?php echo $f['reviews']; ?> reviews</div>
            <div class="fc-cats"><?php echo htmlspecialchars($f['cats']); ?></div>
            <a href="farmer_profile.php?id=<?php echo $f['id']; ?>" class="fav-btn">View Profile</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
