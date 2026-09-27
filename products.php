<?php
session_start();
$page_title = 'Products - MarketLink';
include 'includes/header.php';
?>
<style>
    body {
        background-color: #f3f6f4;
    }

    .products-hero {
        background: linear-gradient(135deg, #10523e 0%, #6eb793 100%);
        padding: 4rem 2rem;
        text-align: center;
        color: white;
        border-radius: 0 0 30px 30px;
        margin-bottom: 3rem;
    }

    .products-hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .products-hero p {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .search-bar-container {
        max-width: 600px;
        margin: 0 auto;
        display: flex;
        background: white;
        border-radius: 50px;
        padding: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .search-bar-container input {
        flex: 1;
        border: none;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
        border-radius: 50px;
        outline: none;
        font-family: inherit;
    }

    .search-bar-container button {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 0 1.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .search-bar-container button:hover {
        background: var(--primary-dark);
    }

    .category-pills {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        padding: 0 1rem;
    }

    .category-pill {
        background: white;
        border: 1px solid var(--border-color);
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        color: var(--text-dark);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: var(--shadow-sm);
    }

    .category-pill.active,
    .category-pill:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        max-width: 1300px;
        margin: 0 auto 4rem;
        padding: 0 2rem;
    }

    .prod-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: all 0.3s;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .prod-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: #60ad5e;
    }

    .prod-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: white;
        color: var(--text-dark);
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 10;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: all 0.3s;
        cursor: default;
    }

    .prod-badge:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .prod-heart {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.2rem;
        color: #9ca3af;
        cursor: pointer;
        z-index: 10;
        transition: color 0.3s, transform 0.2s;
        background: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 0;
        line-height: 1;
    }

    .prod-heart svg {
        transition: fill 0.3s, color 0.3s;
    }

    .prod-heart:hover {
        color: #ef4444;
        transform: scale(1.1);
    }

    .prod-heart:hover svg {
        fill: #ef4444;
    }

    .prod-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .prod-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .prod-vendor {
        font-size: 0.8rem;
        color: var(--text-light);
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .prod-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .prod-price {
        font-size: 1.4rem;
        color: var(--primary-color);
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .prod-price span {
        font-size: 0.9rem;
        color: var(--text-light);
        font-weight: 500;
    }

    .prod-footer {
        margin-top: auto;
        display: flex;
        gap: 1rem;
    }

    .btn-cart {
        flex: 1;
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-cart:hover {
        background: var(--primary-dark);
    }

    @media (max-width: 768px) {
        .products-hero {
            margin: 1rem;
            padding: 2rem 1rem;
            border-radius: 16px;
        }

        .products-hero h1 {
            font-size: 1.8rem;
        }

        .products-hero p {
            font-size: 0.95rem;
        }

        .search-bar-container {
            padding: 0.3rem;
        }

        .search-bar-container input {
            padding: 0.8rem 1rem;
            width: 100%;
        }

        .products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem;
            padding: 0 1rem;
        }

        .prod-card {
            min-width: 0; /* allows card to shrink below content min-size */
        }

        .prod-img {
            height: 120px;
        }

        .prod-content {
            padding: 0.6rem;
        }

        .prod-title {
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
            line-height: 1.2;
            word-wrap: break-word;
        }

        .prod-vendor {
            font-size: 0.65rem;
            margin-bottom: 0.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .prod-price {
            font-size: 1rem;
            margin-bottom: 0.6rem;
        }

        .prod-price span {
            font-size: 0.75rem;
        }

        .btn-cart {
            padding: 0.5rem;
            font-size: 0.75rem;
            gap: 0.25rem;
            white-space: nowrap;
        }

        .btn-cart svg {
            width: 14px;
            height: 14px;
        }

        .prod-badge {
            top: 0.5rem;
            left: 0.5rem;
            padding: 0.2rem 0.4rem;
            font-size: 0.6rem;
        }

        .prod-heart {
            top: 0.5rem;
            right: 0.5rem;
            width: 24px;
            height: 24px;
        }
        
        .prod-heart svg {
            width: 12px;
            height: 12px;
        }

        .category-pill {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .products-hero h1 {
            font-size: 1.5rem;
        }
        
        .products-grid {
            gap: 0.6rem;
            padding: 0 0.6rem;
        }
        
        .btn-cart {
            font-size: 0.7rem;
            padding: 0.4rem;
        }
    }
</style>

<div class="products-hero">
    <h1>Fresh Local Produce</h1>
    <p>Browse farm-fresh fruits, vegetables, and goods directly from local farmers.</p>
    <div class="search-bar-container">
        <input type="text" placeholder="Search for tomatoes, honey, milk...">
        <button type="button">Search</button>
    </div>
</div>

<div class="category-pills">
    <div class="category-pill active">All Produce</div>
    <div class="category-pill">Vegetables</div>
    <div class="category-pill">Fruits</div>
    <div class="category-pill">Dairy & Eggs</div>
    <div class="category-pill">Meat & Poultry</div>
    <div class="category-pill">Bakery</div>
</div>

<div class="products-grid">
    <!-- Item 1 -->
    <div class="prod-card">
        <div class="prod-badge">Organic</div>
        <button class="prod-heart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                </path>
            </svg>
        </button>
        <img src="https://images.unsplash.com/photo-1592924357228-91a4daadcfea?q=80&w=2000&auto=format&fit=crop"
            class="prod-img" alt="Tomatoes">
        <div class="prod-content">
            <div class="prod-vendor">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Ali's Farm
            </div>
            <h3 class="prod-title">Fresh Organic Tomatoes</h3>
            <div class="prod-price">Rs. 150 <span>/ kg</span></div>
            <div class="prod-footer">
                <button class="btn-cart">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Item 2 -->
    <div class="prod-card">
        <div class="prod-badge">Fresh</div>
        <button class="prod-heart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                </path>
            </svg>
        </button>
        <img src="https://images.unsplash.com/photo-1587049352847-81a56d773c1c?q=80&w=2000&auto=format&fit=crop"
            class="prod-img" alt="Carrots">
        <div class="prod-content">
            <div class="prod-vendor">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Green Valley Farm
            </div>
            <h3 class="prod-title">Crunchy Carrots</h3>
            <div class="prod-price">Rs. 80 <span>/ kg</span></div>
            <div class="prod-footer">
                <button class="btn-cart">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Item 3 -->
    <div class="prod-card">
        <div class="prod-badge">Farm Fresh</div>
        <button class="prod-heart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                </path>
            </svg>
        </button>
        <img src="https://images.unsplash.com/photo-1628088062854-d1870b4553da?q=80&w=2000&auto=format&fit=crop"
            class="prod-img" alt="Spinach">
        <div class="prod-content">
            <div class="prod-vendor">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Malir Fresh Hub
            </div>
            <h3 class="prod-title">Green Spinach (Palak)</h3>
            <div class="prod-price">Rs. 50 <span>/ bunch</span></div>
            <div class="prod-footer">
                <button class="btn-cart">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Item 4 -->
    <div class="prod-card">
        <div class="prod-badge">Fresh</div>
        <button class="prod-heart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                </path>
            </svg>
        </button>
        <img src="https://images.unsplash.com/photo-1550583724-b2692b85b150?q=80&w=2000&auto=format&fit=crop"
            class="prod-img" alt="Milk">
        <div class="prod-content">
            <div class="prod-vendor">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Tariq Road Sunday Market
            </div>
            <h3 class="prod-title">Raw Cow Milk</h3>
            <div class="prod-price">Rs. 220 <span>/ liter</span></div>
            <div class="prod-footer">
                <button class="btn-cart">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Item 5 -->
    <div class="prod-card">
        <div class="prod-badge">Best Seller</div>
        <button class="prod-heart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                </path>
            </svg>
        </button>
        <img src="https://images.unsplash.com/photo-1582979512210-99b6a53386f9?q=80&w=2000&auto=format&fit=crop"
            class="prod-img" alt="Apples">
        <div class="prod-content">
            <div class="prod-vendor">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Fresh Harvest
            </div>
            <h3 class="prod-title">Golden Apples</h3>
            <div class="prod-price">Rs. 300 <span>/ kg</span></div>
            <div class="prod-footer">
                <button class="btn-cart">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Item 6 -->
    <div class="prod-card">
        <div class="prod-badge">Fresh</div>
        <button class="prod-heart">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                </path>
            </svg>
        </button>
        <img src="https://images.unsplash.com/photo-1579621970588-a35d0e7ab9b6?q=80&w=2000&auto=format&fit=crop"
            class="prod-img" alt="Bread">
        <div class="prod-content">
            <div class="prod-vendor">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Gulshan Organic Market
            </div>
            <h3 class="prod-title">Sourdough Bread</h3>
            <div class="prod-price">Rs. 450 <span>/ loaf</span></div>
            <div class="prod-footer">
                <button class="btn-cart">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>