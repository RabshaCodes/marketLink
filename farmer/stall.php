<?php 
session_start();
$page_title = 'Green Valley Farm - MarketLink';
$extra_css = '
<style>
    .stall-header { background-color: var(--white); border-radius: 12px; padding: 2rem; margin-bottom: 2rem; box-shadow: var(--shadow-sm); display: flex; gap: 2rem; align-items: flex-start; }
    .stall-img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-light); }
    .filter-section { background: var(--white); padding: 1.5rem; border-radius: 12px; box-shadow: var(--shadow-sm); margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center; }
    .cart-widget { position: fixed; bottom: 2rem; right: 2rem; background: var(--primary-color); color: white; padding: 1rem 2rem; border-radius: 50px; box-shadow: var(--shadow-lg); display: flex; align-items: center; gap: 1rem; cursor: pointer; z-index: 1000; }
</style>
';
include '../includes/header.php'; 
?>

    <div class="section">
        <div class="stall-header">
            <img src="https://images.unsplash.com/photo-1595858603606-21820684f884?q=80&w=2070&auto=format&fit=crop" alt="Green Valley Farm" class="stall-img">
            <div>
                <h1 style="margin-bottom: 0.5rem;">Green Valley Farm</h1>
                <p class="text-light mb-1">Organic Vegetables & Fruits | Located at Downtown Farmers Market</p>
                <div class="flex gap-1 mb-1">
                    <span class="badge badge-success">Accepting Pre-orders</span>
                    <span class="badge badge-warning">★ 4.8 (124 Reviews)</span>
                </div>
                <button class="btn btn-outline" style="padding: 0.4rem 1rem;">♥ Save as Favorite</button>
            </div>
        </div>

        <div class="filter-section">
            <input type="text" class="form-control" placeholder="Search products...">
            <select class="form-control" style="width: 200px;">
                <option value="">All Categories</option>
                <option value="vegetables">Vegetables</option>
                <option value="fruits">Fruits</option>
            </select>
        </div>

        <h2>Available This Week</h2>
        <div class="grid mt-2">
            <div class="card">
                <img src="https://images.unsplash.com/photo-1596704017254-9b121068fb31?q=80&w=2070&auto=format&fit=crop" alt="Heirloom Tomatoes" class="card-img" style="height: 150px;">
                <div class="card-body">
                    <h3 class="card-title"><a href="product.php">Organic Heirloom Tomatoes</a></h3>
                    <p class="card-text text-sm">Rich, flavorful tomatoes perfect for salads.</p>
                    <div class="flex justify-between align-center">
                        <span style="font-weight: 700; color: var(--primary-color);">$4.50 / lb</span>
                        <button class="btn btn-primary" style="padding: 0.4rem 1rem;">Add to Cart</button>
                    </div>
                </div>
            </div>
            <!-- Additional items can be populated from database here -->
        </div>
    </div>

    <div class="cart-widget">
        <span>🛒 2 Items | $9.50</span>
        <a href="cart.php" style="font-weight: bold; margin-left: 1rem; border-left: 1px solid white; padding-left: 1rem;">Checkout</a>
    </div>

<?php include '../includes/footer.php'; ?>
