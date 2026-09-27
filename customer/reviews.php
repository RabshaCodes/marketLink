<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Product Reviews - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'reviews';
include __DIR__ . '/includes/dashboard_layout.php';

$reviews = [
    ['name' => 'Sara Khan',  'stars' => 5, 'when' => '2 days ago', 'text' => 'Very fresh and quality tomatoes. Highly recommend!',      'ava' => 'Sara+Khan'],
    ['name' => 'Ahmed Raza', 'stars' => 4, 'when' => '5 days ago', 'text' => 'Good quality and reasonable price. Will buy again.',       'ava' => 'Ahmed+Raza'],
    ['name' => 'Fatima Ali', 'stars' => 3, 'when' => '1 week ago', 'text' => 'Fresh but some were a bit soft.',                          'ava' => 'Fatima+Ali'],
];
?>
<style>
    /* SUMMARY CARD */
    .review-summary-card {
        background: var(--surface); border-radius: var(--r-lg); padding: 1.9rem;
        box-shadow: var(--shadow-md); border: 1px solid var(--line); margin-bottom: 2rem;
        position: relative; overflow: hidden;
    }
    .review-summary-card::before {
        content: ''; position: absolute; top: -60px; right: -40px; width: 180px; height: 180px; border-radius: 50%;
        background: radial-gradient(circle, rgba(34,197,94,.1), transparent 70%); pointer-events: none;
    }
    .product-review-header {
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid var(--line); padding-bottom: 1.5rem; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;
        position: relative; z-index: 1;
    }
    .product-review-info { display: flex; gap: 1.05rem; align-items: center; }
    .product-review-info img { width: 68px; height: 68px; border-radius: 16px; object-fit: cover; box-shadow: 0 0 0 3px #F1F7F2, var(--shadow-sm); }
    .product-review-info h2 { margin: 0 0 .3rem; font-size: 1.3rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .product-review-info .price { margin: 0 0 .45rem; color: var(--sb-1); font-size: .9rem; font-weight: 800; }
    .product-review-info .rating { display: inline-flex; align-items: center; gap: .35rem; color: #B45309; font-weight: 800; font-size: .88rem; background: #FFF7E6; padding: .22rem .65rem; border-radius: 999px; }
    .product-review-info .rating .star { color: #F59E0B; }
    .product-review-info .rating span { color: var(--muted); font-weight: 600; }

    /* RATING BARS */
    .rating-bars { display: flex; flex-direction: column; gap: .6rem; position: relative; z-index: 1; }
    .rating-bar-row { display: flex; align-items: center; gap: 1rem; font-size: .85rem; color: var(--ink-2); font-weight: 700; }
    .rating-bar-row span:first-child { width: 34px; display: flex; align-items: center; gap: .28rem; }
    .rating-bar-row span:first-child svg { color: #F59E0B; }
    .bar-bg { flex: 1; height: 9px; background: #EAF2EC; border-radius: 999px; overflow: hidden; }
    .bar-fill { height: 100%; background: linear-gradient(90deg, #FBBF24, #F59E0B); border-radius: 999px; }
    .rating-bar-row span:last-child { width: 42px; text-align: right; color: var(--muted); font-weight: 700; }

    /* REVIEWS LIST */
    .review-list { display: flex; flex-direction: column; gap: 1.1rem; }
    .review-item {
        background: var(--surface); border: 1px solid var(--line); border-radius: var(--r-md);
        box-shadow: var(--shadow-sm); padding: 1.3rem 1.4rem; display: flex; gap: 1rem;
        transition: transform .2s, box-shadow .2s;
    }
    .review-item:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .review-item > img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; flex-shrink: 0; box-shadow: 0 0 0 3px #F1F7F2; }
    .review-content { flex: 1; min-width: 0; }
    .review-content .rc-top { display: flex; justify-content: space-between; align-items: baseline; gap: .6rem; flex-wrap: wrap; }
    .review-content h4 { margin: 0; font-size: 1rem; font-weight: 800; color: var(--ink); }
    .review-content .meta { color: var(--muted); font-size: .8rem; font-weight: 600; }
    .review-content .stars { color: #F59E0B; margin: .35rem 0 .55rem; letter-spacing: 2px; font-size: .95rem; }
    .review-content .stars .off { color: #DDE6DF; }
    .review-content p { margin: 0; color: var(--ink-2); line-height: 1.6; font-size: .93rem; }

    /* CUSTOM THEME BUTTON */
    .btn-theme-solid {
        display: inline-flex; align-items: center; justify-content: center; gap: .45rem;
        background: linear-gradient(135deg, var(--sb-2), var(--sb-1));
        color: #fff;
        padding: .68rem 1.45rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700; font-size: .88rem; font-family: inherit;
        border: none; cursor: pointer;
        box-shadow: 0 10px 22px -8px rgba(6, 64, 43, .5);
        transition: transform .2s, box-shadow .2s, filter .2s;
    }
    .btn-theme-solid:hover { transform: translateY(-2px); filter: brightness(1.1); box-shadow: 0 14px 28px -8px rgba(6, 64, 43, .55); }
    .btn-theme-solid:active { transform: translateY(0); }
</style>

<div class="page-body">
    <div class="page-header reveal">
        <h1>Product Reviews</h1>
        <p>See what other customers are saying.</p>
    </div>

    <!-- REVIEW SUMMARY CARD -->
    <div class="review-summary-card reveal">
        <div class="product-review-header">
            <div class="product-review-info">
                <img src="https://images.unsplash.com/photo-1592924357228-1be254705e89?w=200&q=80" alt="Organic Tomatoes">
                <div>
                    <h2>Organic Tomatoes</h2>
                    <p class="price">Rs. 250 / kg</p>
                    <div class="rating"><span class="star">&#9733;</span> 4.8 <span>(56 reviews)</span></div>
                </div>
            </div>
            <a href="#" class="btn-theme-solid">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"></path></svg>
                Write a Review
            </a>
        </div>

        <!-- RATING BARS -->
        <div class="rating-bars">
            <?php
            $bars = [5 => 78, 4 => 15, 3 => 5, 2 => 2, 1 => 0];
            foreach ($bars as $star => $pct): ?>
            <div class="rating-bar-row">
                <span><?php echo $star; ?> <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></span>
                <div class="bar-bg"><div class="bar-fill" style="width: <?php echo $pct; ?>%;"></div></div>
                <span><?php echo $pct; ?>%</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- REVIEWS LIST -->
    <div class="review-list">
        <?php foreach ($reviews as $i => $r): ?>
        <div class="review-item reveal" data-delay="<?php echo $i * 90; ?>">
            <img src="https://ui-avatars.com/api/?name=<?php echo $r['ava']; ?>&background=DCF6E6&color=15803D&bold=true" alt="<?php echo htmlspecialchars($r['name']); ?>">
            <div class="review-content">
                <div class="rc-top">
                    <h4><?php echo htmlspecialchars($r['name']); ?></h4>
                    <span class="meta"><?php echo htmlspecialchars($r['when']); ?></span>
                </div>
                <div class="stars"><?php echo str_repeat('&#9733;', $r['stars']); ?><span class="off"><?php echo str_repeat('&#9733;', 5 - $r['stars']); ?></span></div>
                <p><?php echo htmlspecialchars($r['text']); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
