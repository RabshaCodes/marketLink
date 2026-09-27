<?php
session_start();

$page_title = 'Login & Registration - MarketLink';
$extra_css = '
<style>
    /* MarketLink unified auth card â€” sliding toggle / hero / form track */
    .auth-section { 
        min-height: calc(100vh - 90px); 
        display: flex;
        flex-direction: column;
        padding: 1rem 1rem 6vh 1rem; /* Extra bottom padding pushes the centered content slightly upward */
    }

    .auth-card {
        width: 100%;
        max-width: 820px; /* Narrower Base width for Login */
        min-height: 520px; /* Minimum height to ensure it looks balanced and taller */
        margin: auto;
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        display: grid;
        grid-template-columns: 0.85fr 1.15fr;
        transition: max-width 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    }

    /* Expand the card for the Register form so it fits more comfortably */
    .auth-section.is-register .auth-card {
        max-width: 1024px;
    }

    /* Left brand / hero panel */
    .auth-hero {
        position: relative;
        background: linear-gradient(160deg, var(--primary-color), var(--primary-dark));
        color: #fff;
        padding: 2.75rem 2.25rem;
        overflow: hidden;
        display: flex;
        align-items: center;
    }
    .auth-hero::before {
        content: "";
        position: absolute;
        width: 220px; height: 220px;
        right: -70px; top: -70px;
        background: radial-gradient(circle, rgba(255,255,255,0.16), transparent 70%);
        border-radius: 50%;
    }
    .auth-hero::after {
        content: "";
        position: absolute;
        width: 160px; height: 160px;
        left: -60px; bottom: -60px;
        background: radial-gradient(circle, rgba(255,255,255,0.10), transparent 70%);
        border-radius: 50%;
    }
    .auth-hero-content {
        position: absolute;
        inset: 0;
        padding: 2.75rem 2.25rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.6rem;
        opacity: 0;
        transform: translateX(-24px);
        transition: opacity 0.45s ease, transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
        pointer-events: none;
    }
    .auth-hero-content.active { opacity: 1; transform: none; pointer-events: auto; }
    .auth-hero-badge {
        width: 46px; height: 46px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,0.16);
        border-radius: 12px;
        margin-bottom: 0.75rem;
    }
    .auth-hero-content h2 { font-size: 1.7rem; font-weight: 700; line-height: 1.25; }
    .auth-hero-content p { color: rgba(255,255,255,0.85); font-size: 1rem; max-width: 26ch; }

    /* Right form panel */
    .auth-main { padding: 1.25rem 2.5rem; }
    
    .auth-card .form-control {
        padding: 0.55rem 0.8rem;
        font-size: 0.9rem;
    }

    /* Remove browser autofill blue/yellow background */
    .auth-card .form-control:-webkit-autofill,
    .auth-card .form-control:-webkit-autofill:hover, 
    .auth-card .form-control:-webkit-autofill:focus, 
    .auth-card .form-control:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px white inset !important;
        -webkit-text-fill-color: var(--text-color) !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .auth-toggle {
        position: relative;
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: #eef1ee;
        border-radius: 12px;
        padding: 4px;
        margin-bottom: 0.8rem;
    }
    .auth-toggle-pill {
        position: absolute;
        top: 4px; bottom: 4px; left: 4px;
        width: calc(50% - 4px);
        background: var(--primary-color);
        border-radius: 9px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .auth-toggle.register .auth-toggle-pill { transform: translateX(100%); }
    .auth-toggle-btn {
        position: relative;
        z-index: 1;
        border: 0;
        background: transparent;
        padding: 0.5rem 1rem;
        font-family: inherit;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-light);
        cursor: pointer;
        border-radius: 9px;
        transition: color 0.3s ease;
    }
    .auth-toggle-btn.active { color: #fff; }

    /* Sliding form track (height animated via JS to fit active form) */
    .auth-slides {
        position: relative;
        overflow: hidden;
        transition: height 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .auth-slide {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        opacity: 0;
        transform: translateX(32px);
        pointer-events: none;
        transition: opacity 0.4s ease, transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .auth-slide.active {
        opacity: 1;
        transform: none;
        pointer-events: auto;
        position: relative;
    }
    .auth-submit { width: 100%; margin-top: 0.2rem; }
    .auth-hint { text-align: center; margin-top: 0.8rem; font-size: 0.85rem; color: var(--text-light); }
    .auth-hint a { color: var(--primary-color); font-weight: 600; }

    /* Two-column field grid to keep the register form compact (less scrolling) */
    .auth-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 1rem;
    }
    .auth-grid .form-group { margin-bottom: 0.6rem; }
    .auth-grid .span-2 { grid-column: 1 / -1; }
    .form-group label { font-size: 0.8rem; margin-bottom: 0.25rem; display: block; }

    /* Login Form specific spacing to fill the min-height gracefully */
    #login-form {
        padding-top: 2.5rem;
    }
    #login-form .form-group {
        margin-bottom: 2rem;
    }
    #login-form .auth-submit {
        margin-top: 1rem;
        padding: 0.8rem;
        font-size: 1.05rem;
    }
    #login-form .auth-hint {
        margin-top: 2.5rem;
    }

    /* Progressive farmer / stall disclosure */
    .farmer-toggle {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        width: 100%;
        margin-bottom: 0.8rem;
        padding: 0.6rem 0.8rem;
        border: 1.5px solid var(--border-color);
        border-radius: 12px;
        background: var(--bg-color);
        cursor: pointer;
        transition: border-color 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .farmer-toggle:hover { border-color: var(--primary-light); }
    .farmer-toggle.checked {
        border-color: var(--primary-color);
        background: #eef7ef;
        box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
    }
    .farmer-switch-input { position: absolute; opacity: 0; pointer-events: none; }
    .farmer-switch {
        flex-shrink: 0;
        width: 44px; height: 24px;
        margin-top: 2px;
        border-radius: 999px;
        background: #cbd5c9;
        position: relative;
        transition: background-color 0.3s ease;
    }
    .farmer-switch::after {
        content: "";
        position: absolute;
        top: 3px; left: 3px;
        width: 18px; height: 18px;
        background: #fff;
        border-radius: 50%;
        box-shadow: var(--shadow-sm);
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .farmer-toggle.checked .farmer-switch { background: var(--primary-color); }
    .farmer-toggle.checked .farmer-switch::after { transform: translateX(20px); }
    .farmer-switch-input:focus-visible + .farmer-switch {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }
    .farmer-toggle-text { display: flex; flex-direction: column; gap: 0.15rem; }
    .farmer-toggle-text strong { font-size: 0.98rem; font-weight: 600; color: var(--text-dark); }
    .farmer-toggle-text span { font-size: 0.85rem; line-height: 1.4; color: var(--text-light); }

    .farmer-panel {
        display: grid;
        grid-template-rows: 0fr;
        opacity: 0;
        visibility: hidden;
        transition: grid-template-rows 0.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.3s ease, visibility 0s linear 0.4s;
    }
    .farmer-panel.open {
        grid-template-rows: 1fr;
        opacity: 1;
        visibility: visible;
        transition: grid-template-rows 0.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.3s ease 0.05s, visibility 0s;
    }
    .farmer-panel-inner { overflow: hidden; min-height: 0; }

    @media (max-width: 820px) {
        .auth-card { grid-template-columns: 1fr; }
        .auth-hero { display: none; }
        .auth-main { padding: 2rem 1.5rem; }
    }

    @media (max-width: 480px) {
        .auth-grid { grid-template-columns: 1fr; }
        .auth-section { padding: 2rem 0.5rem; }
    }
</style>
';
include 'includes/header.php';
?>

<div class="section auth-section">
    <div class="auth-card">
        <!-- Brand / hero panel -->
        <aside class="auth-hero">
            <div class="auth-hero-content active" data-hero="login">
                <span class="auth-hero-badge">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                        <path d="M12 12c-3-3-6-3-6-3s0 3 3 6c3 3 6 3 6 3s0-3-3-6z" />
                    </svg>
                </span>
                <h2>Welcome back to MarketLink</h2>
                <p>Sign in to pre-order fresh harvests and support local farm families.</p>
            </div>
            <div class="auth-hero-content" data-hero="register">
                <span class="auth-hero-badge">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="8.5" cy="7" r="4" />
                        <line x1="20" y1="8" x2="20" y2="14" />
                        <line x1="23" y1="11" x2="17" y2="11" />
                    </svg>
                </span>
                <h2>Grow with our community</h2>
                <p>Create an account to shop, sell, or list your farm stall.</p>
            </div>
        </aside>

        <!-- Form panel -->
        <div class="auth-main">
            <div class="auth-toggle" id="authToggle" role="tablist">
                <span class="auth-toggle-pill" aria-hidden="true"></span>
                <button type="button" class="auth-toggle-btn active" data-view="login" role="tab"
                    aria-selected="true">Log In</button>
                <button type="button" class="auth-toggle-btn" data-view="register" role="tab"
                    aria-selected="false">Register</button>
            </div>

            <div class="auth-slides" id="authSlides">
                <?php if (isset($_SESSION['auth_error'])): ?>
                    <div
                        style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($_SESSION['auth_error']);
                        unset($_SESSION['auth_error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['auth_success'])): ?>
                    <div
                        style="background: #dcfce7; color: #15803d; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($_SESSION['auth_success']);
                        unset($_SESSION['auth_success']); ?>
                    </div>
                <?php endif; ?>
                <!-- Login Form -->
                <form id="login-form" class="auth-slide active" data-slide="login" action="includes/auth.php"
                    method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label for="login-email">Email Address</label>
                        <input type="email" name="email" id="login-email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" name="password" id="login-password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary auth-submit">Log In</button>
                    <p class="auth-hint">New to MarketLink? <a href="#" data-goto="register">Create an account</a></p>
                </form>

                <!-- Registration Form -->
                <form id="register-form" class="auth-slide" data-slide="register" action="includes/auth.php"
                    method="POST">
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" name="role" id="reg-role" value="customer">
                    <div class="auth-grid">
                        <div class="form-group">
                            <label for="reg-first">First Name</label>
                            <input type="text" name="first_name" id="reg-first" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="reg-last">Last Name</label>
                            <input type="text" name="last_name" id="reg-last" class="form-control" required>
                        </div>
                        <div class="form-group span-2">
                            <label for="reg-email">Email Address</label>
                            <input type="email" name="email" id="reg-email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="reg-password">Password</label>
                            <input type="password" name="password" id="reg-password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="reg-password-confirm">Confirm Password</label>
                            <input type="password" name="password_confirm" id="reg-password-confirm"
                                class="form-control" required>
                        </div>
                        <div class="form-group span-2">
                            <label for="reg-phone">Contact Number <span
                                    style="font-weight:400;color:var(--text-light);">(optional)</span></label>
                            <input type="tel" name="phone" id="reg-phone" class="form-control">
                        </div>

                        <!-- Progressive disclosure trigger -->
                        <div class="span-2">
                            <label class="farmer-toggle" id="farmer-toggle">
                                <input type="checkbox" id="farmer-switch" class="farmer-switch-input">
                                <span class="farmer-switch" aria-hidden="true"></span>
                                <span class="farmer-toggle-text">
                                    <strong>Registering as a Farmer or Stall Owner?</strong>
                                    <span>Enable this to list your fresh produce and manage your farm stall.</span>
                                </span>
                            </label>

                            <!-- Farmer / Stall fields (revealed progressively) -->
                            <div class="farmer-panel" id="farmer-panel">
                                <div class="farmer-panel-inner">
                                    <div class="auth-grid">
                                        <div class="form-group span-2">
                                            <label for="reg-stall">Farm / Stall Name</label>
                                            <input type="text" name="stall_name" id="reg-stall" class="form-control">
                                        </div>
                                        <div class="form-group span-2">
                                            <label for="reg-address">Farm / Stall Location</label>
                                            <input type="text" name="address" id="reg-address" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary auth-submit" id="reg-submit">Create Account</button>
                    <p class="auth-hint">Already have an account? <a href="#" data-goto="login">Log in</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const card = document.getElementById('authToggle');
        const slides = document.getElementById('authSlides');
        const section = document.querySelector('.auth-section');
        const toggleBtns = document.querySelectorAll('.auth-toggle-btn');
        const slideEls = document.querySelectorAll('.auth-slide');
        const heroEls = document.querySelectorAll('.auth-hero-content');

        // Progressive farmer / stall disclosure elements
        const farmerSwitch = document.getElementById('farmer-switch');
        const farmerToggle = document.getElementById('farmer-toggle');
        const farmerPanel = document.getElementById('farmer-panel');
        const roleInput = document.getElementById('reg-role');
        const regSubmit = document.getElementById('reg-submit');
        const stallName = document.getElementById('reg-stall');
        const stallLoc = document.getElementById('reg-address');

        function setFarmerMode(on) {
            farmerToggle.classList.toggle('checked', on);
            farmerPanel.classList.toggle('open', on);
            roleInput.value = on ? 'farmer' : 'customer';
            stallName.required = on;
            stallLoc.required = on;
            regSubmit.textContent = on ? 'Create Farmer Account' : 'Create Account';
            // Release the container so it follows the panel's own height animation
            slides.style.height = 'auto';
        }

        // Login / Register slide switch: animate height, then release to auto
        function selectView(view, animate) {
            const next = slides.querySelector('.auth-slide[data-slide="' + view + '"]');
            const current = slides.querySelector('.auth-slide.active');
            const willAnimate = animate !== false && next && next !== current;

            if (willAnimate) {
                slides.style.height = slides.offsetHeight + 'px';
                void slides.offsetHeight; // reflow to lock the start height
            }

            card.classList.toggle('register', view === 'register');
            section.classList.toggle('is-register', view === 'register');
            toggleBtns.forEach(b => {
                const on = b.dataset.view === view;
                b.classList.toggle('active', on);
                b.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            slideEls.forEach(s => s.classList.toggle('active', s.dataset.slide === view));
            heroEls.forEach(h => h.classList.toggle('active', h.dataset.hero === view));

            slides.style.height = willAnimate ? next.offsetHeight + 'px' : 'auto';
        }

        // After a slide height transition finishes, let content govern height again
        slides.addEventListener('transitionend', (e) => {
            if (e.target === slides && e.propertyName === 'height') slides.style.height = 'auto';
        });

        toggleBtns.forEach(b => b.addEventListener('click', () => selectView(b.dataset.view, true)));
        document.querySelectorAll('[data-goto]').forEach(a => {
            a.addEventListener('click', (e) => { e.preventDefault(); selectView(a.dataset.goto, true); });
        });
        farmerSwitch.addEventListener('change', () => setFarmerMode(farmerSwitch.checked));

        // Initial view (supports ?tab=register / ?tab=register-farmer deep links)
        const tab = new URLSearchParams(window.location.search).get('tab');
        if (tab === 'register' || tab === 'register-farmer') {
            selectView('register', false);
            if (tab === 'register-farmer') {
                farmerSwitch.checked = true;
                setFarmerMode(true);
            }
        }
    })();
</script>
<?php include 'includes/footer.php'; ?>