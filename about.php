<?php 
session_start();
$page_title = 'About Us - MarketLink';
include 'includes/header.php'; 
?>
<style>
    body { background-color: #f3f6f4; }
    
    .about-section {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    /* Hero Banner */
    .about-hero {
        position: relative;
        background: url('assets/images/about-bg.avif') center right / cover no-repeat;
        border-radius: 16px;
        padding: 4rem 3rem;
        color: white;
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .about-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, #10523e 0%, rgba(16, 82, 62, 0.6) 50%, transparent 100%);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        mask-image: linear-gradient(to right, black 30%, transparent 80%);
        -webkit-mask-image: linear-gradient(to right, black 30%, transparent 80%);
        z-index: 1;
    }
    .about-hero > * {
        position: relative;
        z-index: 2;
    }
    .about-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }
    .about-hero p {
        font-size: 1.1rem;
        max-width: 600px;
        opacity: 0.9;
        line-height: 1.6;
    }

    /* Grid Layout */
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Cards */
    .about-card {
        background: var(--white);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }
    .about-card h2 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--text-dark);
        font-weight: 700;
    }
    .about-card p {
        color: var(--text-light);
        margin-bottom: 1rem;
        line-height: 1.7;
    }
    .about-card p strong {
        color: var(--text-dark);
    }

    /* How It Works Steps */
    .step-item {
        background: #f0fdf4;
        border-radius: 12px;
        padding: 1.2rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        margin-bottom: 1rem;
        border: 1px solid #dcfce7;
    }
    .step-item:last-child {
        margin-bottom: 0;
    }
    .step-icon {
        width: 40px;
        height: 40px;
        background: #dcfce7;
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .step-content h3 {
        font-size: 1rem;
        margin-bottom: 0.2rem;
        color: var(--text-dark);
        font-weight: 600;
    }
    .step-content p {
        margin: 0;
        font-size: 0.85rem;
        color: var(--text-light);
    }

    /* CTA Section */
    .about-cta {
        background: var(--white);
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        margin-bottom: 4rem;
    }
    .about-cta h2 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
        font-weight: 700;
    }
    .about-cta p {
        color: var(--text-light);
        margin-bottom: 2rem;
    }
    .cta-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
    .btn-outline-gray {
        background: #f3f4f6;
        color: var(--text-dark);
        border: 1px solid #e5e7eb;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-block;
    }
    .btn-outline-gray:hover {
        background: #e5e7eb;
    }
    
    .btn-primary {
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        display: inline-block;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .about-grid {
            grid-template-columns: 1fr;
        }
        .about-hero {
            padding: 3rem 2rem;
        }
        .about-hero h1 {
            font-size: 2rem;
        }
    }
    @media (max-width: 600px) {
        .cta-buttons {
            flex-direction: column;
        }
        .about-hero {
            padding: 2rem 1.5rem;
        }
        .about-section {
            padding: 0 1rem;
        }
    }
</style>

<div class="about-section">
    <!-- Hero Banner -->
    <div class="about-hero">
        <h1>About MarketLink</h1>
        <p>Bridging the gap between local farmers and conscious consumers — one fresh pickup at a time.</p>
    </div>

    <!-- Main Grid -->
    <div class="about-grid">
        
        <!-- Mission Card -->
        <div class="about-card">
            <h2>Our Mission</h2>
            <p>MarketLink connects local farmers-market vendors with nearby customers. Farmers publish their weekly fresh stock, customers pre-order for convenient pickup, discover markets on an interactive map, and leave verified reviews — all without any intermediary markup. Payment is settled in-person at pickup.</p>
            <p style="margin-top: 1.5rem;">We believe in <strong>fresh food, fair prices, and stronger communities.</strong></p>
        </div>

        <!-- How It Works Card -->
        <div class="about-card">
            <h2>How It Works</h2>
            
            <div class="step-item">
                <div class="step-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                </div>
                <div class="step-content">
                    <h3>1. Find a Market</h3>
                    <p>Discover nearby farmers markets on our interactive map</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>
                <div class="step-content">
                    <h3>2. Pre-Order Fresh Produce</h3>
                    <p>Reserve farm-fresh items online before market day</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>
                <div class="step-content">
                    <h3>3. Pick Up & Pay Locally</h3>
                    <p>Collect your order at the stall and pay in person</p>
                </div>
            </div>

        </div>
    </div>

    <!-- CTA Section -->
    <div class="about-cta">
        <h2>Ready to get started?</h2>
        <p>Join hundreds of local farmers and conscious buyers in Karachi.</p>
        <div class="cta-buttons">
            <a href="register.php" class="btn btn-primary">Create Account</a>
            <a href="markets.php" class="btn-outline-gray">Browse Markets</a>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
