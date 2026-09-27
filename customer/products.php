<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Products - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'markets';
include __DIR__ . '/includes/dashboard_layout.php';

$categories = ['All', 'Vegetables', 'Fruits', 'Herbs', 'Dairy'];
$active_cat = isset($_GET['category']) && in_array($_GET['category'], $categories) ? $_GET['category'] : 'All';

$products = [
    ['name' => 'Tomatoes',     'price' => '250', 'unit' => 'kg',    'cat' => 'Vegetables', 'img' => 'photo-1592924357228-1be254705e89', 'in_stock' => true],
    ['name' => 'Spinach',      'price' => '150', 'unit' => 'bunch', 'cat' => 'Vegetables', 'img' => 'photo-1576045057990-51b807586d5f', 'in_stock' => true],
    ['name' => 'Carrots',      'price' => '100', 'unit' => 'kg',    'cat' => 'Vegetables', 'img' => 'photo-1598170845058-32b9d0a6e70a', 'in_stock' => true],
    ['name' => 'Strawberries', 'price' => '300', 'unit' => 'box',   'cat' => 'Fruits',     'img' => 'photo-1464965398167-42c3f40863d7', 'in_stock' => true],
    ['name' => 'Onions',       'price' => '80',  'unit' => 'kg',    'cat' => 'Vegetables', 'img' => 'photo-1618512484249-7243d76f03d7', 'in_stock' => true],
    ['name' => 'Mangoes',      'price' => '200', 'unit' => 'kg',    'cat' => 'Fruits',     'img' => 'photo-1553279768-865429fa0078', 'in_stock' => false],
    ['name' => 'Fresh Mint',   'price' => '60',  'unit' => 'bunch', 'cat' => 'Herbs',      'img' => 'photo-1621233876133-a3190a7c2f4c', 'in_stock' => true],
    ['name' => 'Farm Eggs',    'price' => '280', 'unit' => 'dozen', 'cat' => 'Dairy',      'img' => 'photo-1518492104633-130d0cc84657', 'in_stock' => true],
];
$visible = $active_cat === 'All' ? $products : array_filter($products, fn($p) => $p['cat'] === $active_cat);
?>
<style>
    /* CATEGORY PILLS */
    .cat-row { display: flex; gap: .5rem; margin-bottom: 1.9rem; flex-wrap: wrap; }
    .cat-pill {
        padding: .55rem 1.3rem; border-radius: 999px; border: 1.5px solid var(--line);
        background: #fff; color: var(--muted); font-size: .88rem; font-weight: 700;
        text-decoration: none; cursor: pointer; font-family: inherit; transition: all .2s;
        display: inline-flex; align-items: center; gap: .4rem;
    }
    .cat-pill:hover { border-color: var(--primary-2); color: var(--primary); transform: translateY(-1px); }
    .cat-pill.active { background: linear-gradient(135deg,#1D9A50,#15803D); border-color: transparent; color: #fff; box-shadow: 0 8px 18px -8px rgba(21,128,61,.6); }

    /* PRODUCTS GRID */
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; }
    .prod-card { background: var(--surface); border-radius: var(--r-lg); border: 1px solid var(--line); box-shadow: var(--shadow-sm); overflow: hidden; transition: transform .25s, box-shadow .25s; }
    .prod-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-md); }
    .prod-card-img { position: relative; height: 160px; overflow: hidden; }
    .prod-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
    .prod-card:hover .prod-card-img img { transform: scale(1.08); }
    .prod-tag { position: absolute; left: 12px; top: 12px; background: rgba(255,255,255,.9); backdrop-filter: blur(4px); color: var(--primary); font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; padding: .25rem .6rem; border-radius: 999px; }
    .prod-heart {
        position: absolute; top: 10px; right: 10px; width: 32px; height: 32px;
        background: rgba(255,255,255,.92); backdrop-filter: blur(4px); border-radius: 50%;
        display: flex; align-items: center; justify-content: center; color: #DC2626; text-decoration: none;
        box-shadow: 0 3px 8px -2px rgba(0,0,0,.25); transition: transform .2s;
    }
    .prod-heart:hover { transform: scale(1.12); }
    .prod-card-body { padding: 1.05rem 1.15rem 1.2rem; display: flex; justify-content: space-between; align-items: center; gap: .75rem; }
    .prod-card-body h4 { margin: 0 0 .25rem; font-size: 1rem; font-weight: 800; color: var(--ink); }
    .prod-price { color: var(--primary); font-weight: 800; font-size: .95rem; margin: 0 0 .3rem; }
    .prod-stock { margin: 0; font-size: .78rem; color: var(--muted); font-weight: 600; display: inline-flex; align-items: center; gap: .3rem; }
    .prod-stock::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #22C55E; }
    .prod-stock.out { color: #DC2626; }
    .prod-stock.out::before { background: #DC2626; }
    .add-btn {
        width: 40px; height: 40px; flex-shrink: 0; border-radius: 12px; border: none;
        background: linear-gradient(135deg,#1D9A50,#15803D); color: #fff; font-size: 1.4rem; line-height: 1;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 18px -8px rgba(21,128,61,.6); transition: transform .2s, filter .2s;
    }
    .add-btn:hover { transform: translateY(-2px); filter: brightness(1.06); }
    .add-btn:disabled { background: #CBD5D1; cursor: not-allowed; box-shadow: none; transform: none; }
</style>

<div class="page-body">
    <div class="page-header reveal">
        <h1>All Products</h1>
        <p>Fresh and seasonal produce straight from our farms.</p>
    </div>

    <div class="cat-row reveal">
        <?php foreach ($categories as $c): ?>
            <a href="products.php<?php echo $c === 'All' ? '' : '?category=' . urlencode($c); ?>" class="cat-pill<?php echo $active_cat === $c ? ' active' : ''; ?>"><?php echo $c; ?></a>
        <?php endforeach; ?>
    </div>

    <div class="products-grid">
        <?php foreach ($visible as $i => $p): ?>
        <div class="prod-card reveal" data-delay="<?php echo ($i % 4) * 70; ?>">
            <div class="prod-card-img">
                <span class="prod-tag"><?php echo htmlspecialchars($p['cat']); ?></span>
                <img src="https://images.unsplash.com/<?php echo $p['img']; ?>?w=400&q=80" alt="<?php echo htmlspecialchars($p['name']); ?>">
                <a href="favorites.php" class="prod-heart" aria-label="Add to favorites">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </a>
            </div>
            <div class="prod-card-body">
                <div>
                    <h4><?php echo htmlspecialchars($p['name']); ?></h4>
                    <p class="prod-price">Rs. <?php echo $p['price']; ?>/<?php echo $p['unit']; ?></p>
                    <p class="prod-stock<?php echo $p['in_stock'] ? '' : ' out'; ?>"><?php echo $p['in_stock'] ? 'In Stock' : 'Out of Stock'; ?></p>
                </div>
                <button class="add-btn" <?php echo $p['in_stock'] ? '' : 'disabled'; ?> aria-label="Add <?php echo htmlspecialchars($p['name']); ?> to cart">+</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    document.querySelectorAll('.add-btn:not([disabled])').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.innerHTML = '&#10003;'; btn.style.fontSize = '1rem';
            setTimeout(() => { btn.textContent = '+'; btn.style.fontSize = '1.4rem'; }, 1200);
        });
    });
</script>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
