<?php 
session_start();
$page_title = 'MarketLink - Fresh Local Produce Directly from Farmers';
include 'includes/header.php'; 
?>

    <section class="hero-redesign">
        <div class="doodles">
            <div class="doodle doodle-leaf-1"></div>
            <div class="doodle doodle-leaf-2"></div>
            <div class="doodle doodle-carrot"></div>
            <div class="doodle doodle-apple"></div>
            <div class="doodle doodle-sparkle-1"></div>
            <div class="doodle doodle-sparkle-2"></div>
            <div class="doodle doodle-leaf-3"></div>
        </div>
        <div class="hero-content-wrapper">
            <div class="hero-left">
                <div class="pill-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 12c-3-3-6-3-6-3s0 3 3 6c3 3 6 3 6 3s0-3-3-6z"/></svg>
                    Fresh • Local • Sustainable
                </div>
                
                <h1>Fresh Local Produce<br>Directly from <span class="highlight-text">Farmers</span></h1>
                
                <p class="hero-desc">Discover nearby farmers markets, pre-order organic harvests directly, and support local farm families.</p>
                
                <div class="search-box">
                    <div class="search-input-wrapper">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <input type="text" placeholder="Search markets, products or farmers...">
                    </div>
                    <button class="btn btn-primary"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> Search Nearby</button>
                </div>
                
                <div class="info-cards">
                    <div class="info-card">
                        <div class="info-icon">♥</div>
                        <div>
                            <h4>Support Local</h4>
                            <p>Farmers & Community</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">🍏</div>
                        <div>
                            <h4>Fresh & Healthy</h4>
                            <p>Organic Produce</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">📍</div>
                        <div>
                            <h4>Find Nearby</h4>
                            <p>Markets & Stalls</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">🏡</div>
                        <div>
                            <h4>Eco Friendly</h4>
                            <p>Green Agriculture</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="hero-right">
                <div class="image-wrapper">
                    <img src="https://images.unsplash.com/photo-1595858603606-21820684f884?q=80&w=2070&auto=format&fit=crop" alt="Smiling Local Farmer">
                </div>
                <div class="overlay-card">
                    <h3>Support Local<br>Farmers</h3>
                    <p>♥ Fresh Daily ♥</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="background-color: #ffffff;">
        <div class="flex justify-between align-center mb-2" style="border-bottom: 2px solid var(--border-color); padding-bottom: 1rem;">
            <div>
                <div class="pill-badge" style="margin-bottom: 0.5rem; display: inline-flex; border: 1px solid #79c57d; font-size: 0.7rem; box-shadow: none;">DIRECT FROM FARMS</div>
                <h2 style="font-size: 2.5rem; color: #1a361e; font-weight: 700;">Pre-Order Fresh Harvest</h2>
                <p style="color: #6b7280; font-size: 1.1rem; margin-top: 0.5rem;">Reserve organic produce online and collect fresh at your nearest market</p>
            </div>
            <a href="products.php" style="color: var(--primary-color); font-weight: 600; font-size: 1.1rem;">View All Products ></a>
        </div>

        <div class="grid mt-2" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <!-- Product 1 -->
            <div class="product-card">
                <div class="product-img-wrapper">
                    <span class="product-badge">Fresh Daily</span>
                    <img src="https://images.unsplash.com/photo-1592924357228-91a4daadcfea?q=80&w=2022&auto=format&fit=crop" alt="Organic Tomatoes">
                </div>
                <div class="product-info">
                    <span class="product-farm">ALI'S FARM</span>
                    <h3 class="product-title">Organic Tomatoes</h3>
                    <p class="product-price">PKR 2.5 <span>/ kg</span></p>
                    <button class="btn btn-outline product-btn">+ Add to Cart</button>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="product-card">
                <div class="product-img-wrapper">
                    <span class="product-badge">Fresh Daily</span>
                    <img src="https://images.unsplash.com/photo-1576045057995-568f588f82fb?q=80&w=2080&auto=format&fit=crop" alt="Fresh Spinach">
                </div>
                <div class="product-info">
                    <span class="product-farm">ALI'S FARM</span>
                    <h3 class="product-title">Fresh Spinach</h3>
                    <p class="product-price">PKR 1.5 <span>/ bunch</span></p>
                    <button class="btn btn-outline product-btn">+ Add to Cart</button>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="product-card">
                <div class="product-img-wrapper">
                    <span class="product-badge">Fresh Daily</span>
                    <img src="https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?q=80&w=1974&auto=format&fit=crop" alt="Carrots">
                </div>
                <div class="product-info">
                    <span class="product-farm">ALI'S FARM</span>
                    <h3 class="product-title">Carrots</h3>
                    <p class="product-price">PKR 1.2 <span>/ kg</span></p>
                    <button class="btn btn-outline product-btn">+ Add to Cart</button>
                </div>
            </div>

            <!-- Product 4 -->
            <div class="product-card">
                <div class="product-img-wrapper">
                    <span class="product-badge">Fresh Daily</span>
                    <img src="https://images.unsplash.com/photo-1464965911861-746a04b4bca6?q=80&w=2070&auto=format&fit=crop" alt="Strawberries">
                </div>
                <div class="product-info">
                    <span class="product-farm">ALI'S FARM</span>
                    <h3 class="product-title">Strawberries</h3>
                    <p class="product-price">PKR 3 <span>/ box</span></p>
                    <button class="btn btn-outline product-btn">+ Add to Cart</button>
                </div>
            </div>
        </div>

        <div class="text-center mt-2" style="padding-top: 2rem;">
            <a href="products.php" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.1rem; border-radius: 8px;">View Full Catalog →</a>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
