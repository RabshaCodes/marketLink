<?php
session_start();
$page_title = 'Markets - MarketLink';
include 'includes/header.php';
?>
<style>
    body {
        background-color: #f3f6f4;
    }

    .markets-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
        padding-bottom: 4rem;
    }

    /* Sidebar Sticky */
    .filters-sidebar {
        background: var(--white);
        padding: 1rem;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        position: -webkit-sticky;
        position: sticky;
        top: 110px;
        /* offset for sticky header */
        border: 1px solid var(--border-color);
        align-self: start;
    }

    .filter-group {
        margin-bottom: 0.8rem;
    }

    .filter-group label {
        display: block;
        margin-bottom: 0.2rem;
        font-weight: 500;
        font-size: 0.8rem;
    }

    .filter-group select,
    .filter-group input[type="text"] {
        width: 100%;
        padding: 0.35rem 0.5rem;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-family: inherit;
        font-size: 0.8rem;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.2rem;
    }

    .checkbox-group label {
        font-size: 0.8rem;
    }

    .checkbox-group input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--primary-color);
    }

    /* Toggle Switch */
    .toggle-switch-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .toggle-switch {
        position: relative;
        width: 40px;
        height: 20px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: var(--primary-color);
    }

    input:checked+.slider:before {
        transform: translateX(20px);
    }

    /* Mobile Filter Toggle Button (hidden by default) */
    .mobile-filter-btn {
        display: none;
        width: 100%;
        background: var(--white);
        border: 1px solid var(--border-color);
        padding: 1rem;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-sm);
        cursor: pointer;
        color: var(--text-dark);
    }

    /* Markets List Header */
    .markets-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .view-toggle {
        display: flex;
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
    }

    .view-toggle button {
        background: none;
        border: none;
        padding: 0.4rem 1rem;
        cursor: pointer;
        font-weight: 500;
        font-family: inherit;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-toggle button.active {
        background: #eaf3e9;
        color: var(--primary-color);
    }

    /* Horizontal Market Card */
    .market-list-card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.2rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .market-list-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .market-img {
        width: 200px;
        height: 140px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .market-info-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .market-info-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .market-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.3rem;
    }

    .market-meta {
        font-size: 0.85rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.3rem;
    }

    .distance-badge {
        background: #eaf3e9;
        color: var(--primary-color);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .market-actions {
        display: flex;
        gap: 1rem;
        margin-top: auto;
        /* Push buttons to the bottom */
    }

    .market-actions .btn {
        flex: 1;
        padding: 0.6rem;
        border-radius: 6px;
    }

    .market-actions .btn-outline {
        transition: background-color 0.3s ease, border-color 0.3s ease !important;
    }

    .market-actions .btn-outline:hover {
        background-color: #EAF3E9 !important;
        border-color: #EAF3E9 !important;
    }

    /* Mobile Adjustments */
    @media (max-width: 900px) {
        .markets-layout {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .mobile-filter-btn {
            display: block;
        }

        .filters-sidebar {
            display: none;
            /* hidden until toggled */
            position: absolute;
            top: 150px;
            /* approximate position below header & toggle btn */
            left: 5%;
            width: 90%;
            z-index: 50;
            box-shadow: var(--shadow-lg);
        }

        .filters-sidebar.show {
            display: block;
        }

        .market-list-card {
            flex-direction: column;
        }

        .market-img {
            width: 100%;
            height: 200px;
        }

        .market-info-wrapper {
            gap: 1rem;
        }
    }
</style>

<div class="section pt-0" style="padding-top: 2rem;">

    <button class="mobile-filter-btn" id="mobileFilterToggle">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
        </svg>
        Show Filters
    </button>

    <div class="markets-layout">
        <!-- Left Sidebar: Filters -->
        <div class="sidebar-wrapper">
            <aside class="filters-sidebar" id="filtersSidebar">
                <h3 class="mb-1" style="font-size: 1.2rem; margin-top: 0;">Filters</h3>

                <div class="filter-group">
                    <label>Location</label>
                    <select>
                        <option>All Locations</option>
                        <option>Clifton, Karachi</option>
                        <option>DHA, Karachi</option>
                        <option>Buffer Zone, Karachi</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Market Type</label>
                    <div class="checkbox-group">
                        <input type="checkbox" checked id="type1"><label for="type1"
                            style="margin:0; font-weight:normal; color:#4b5563;">Farmers Market</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="type2"><label for="type2"
                            style="margin:0; font-weight:normal; color:#4b5563;">Organic Market</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="type3"><label for="type3"
                            style="margin:0; font-weight:normal; color:#4b5563;">Local Market</label>
                    </div>
                </div>

                <div class="filter-group">
                    <label>Categories</label>
                    <div class="checkbox-group">
                        <input type="checkbox" checked id="cat1"><label for="cat1"
                            style="margin:0; font-weight:normal; color:#4b5563;">Vegetables</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" checked id="cat2"><label for="cat2"
                            style="margin:0; font-weight:normal; color:#4b5563;">Fruits</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="cat3"><label for="cat3"
                            style="margin:0; font-weight:normal; color:#4b5563;">Dairy & Eggs</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="cat4"><label for="cat4"
                            style="margin:0; font-weight:normal; color:#4b5563;">Meat & Poultry</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="cat5"><label for="cat5"
                            style="margin:0; font-weight:normal; color:#4b5563;">Baked Goods</label>
                    </div>
                </div>

                <div class="filter-group toggle-switch-wrapper">
                    <label style="margin:0;">Open Now</label>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <button class="btn btn-primary"
                    style="width: 100%; border-radius: 6px; margin-top: 0.5rem; padding: 0.5rem;">Apply
                    Filters</button>
            </aside>
        </div>

        <!-- Right Content: Market List -->
        <main class="markets-list">
            <div class="markets-header">
                <h2 style="font-size: 1.8rem; color: #111827;">Nearby Markets</h2>
                <div class="view-toggle">
                    <button class="active">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                        List
                    </button>
                    <button>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"></polygon>
                            <line x1="9" y1="3" x2="9" y2="21"></line>
                            <line x1="15" y1="3" x2="15" y2="21"></line>
                        </svg>
                        Map
                    </button>
                </div>
            </div>

            <!-- Card 1 -->
            <div class="market-list-card">
                <img src="https://images.unsplash.com/photo-1519999482648-25049ddd37b1?q=80&w=2126&auto=format&fit=crop"
                    alt="Karachi Farmers Market" class="market-img">
                <div class="market-info-wrapper">
                    <div class="market-info-top">
                        <div>
                            <h3 class="market-title">Karachi Farmers Market</h3>
                            <div class="market-meta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Clifton, Karachi
                            </div>
                            <div class="market-meta" style="color: #059669;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Open • 08:00 AM - 2:00 PM
                            </div>
                        </div>
                        <div class="distance-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z">
                                </path>
                            </svg>
                            2.3 km
                        </div>
                    </div>
                    <div class="market-actions">
                        <button class="btn btn-primary" onclick="window.location.href='stall.php'">View Details</button>
                        <button class="btn btn-outline"
                            style="border-color: var(--border-color); color: var(--text-dark);">View on Map</button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="market-list-card">
                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1974&auto=format&fit=crop"
                    alt="Buffer Zone Market" class="market-img">
                <div class="market-info-wrapper">
                    <div class="market-info-top">
                        <div>
                            <h3 class="market-title">Buffer Zone Market</h3>
                            <div class="market-meta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Buffer Zone, Karachi
                            </div>
                            <div class="market-meta" style="color: #059669;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Open • 07:00 AM - 1:00 PM
                            </div>
                        </div>
                        <div class="distance-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z">
                                </path>
                            </svg>
                            5.1 km
                        </div>
                    </div>
                    <div class="market-actions">
                        <button class="btn btn-primary" onclick="window.location.href='stall.php'">View Details</button>
                        <button class="btn btn-outline"
                            style="border-color: var(--border-color); color: var(--text-dark);">View on Map</button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="market-list-card">
                <img src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?q=80&w=1913&auto=format&fit=crop"
                    alt="DHA Market" class="market-img">
                <div class="market-info-wrapper">
                    <div class="market-info-top">
                        <div>
                            <h3 class="market-title">DHA Market</h3>
                            <div class="market-meta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                DHA, Karachi
                            </div>
                            <div class="market-meta" style="color: #059669;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Open • 08:00 AM - 2:00 PM
                            </div>
                        </div>
                        <div class="distance-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z">
                                </path>
                            </svg>
                            7.8 km
                        </div>
                    </div>
                    <div class="market-actions">
                        <button class="btn btn-primary" onclick="window.location.href='stall.php'">View Details</button>
                        <button class="btn btn-outline"
                            style="border-color: var(--border-color); color: var(--text-dark);">View on Map</button>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="market-list-card">
                <img src="https://images.unsplash.com/photo-1543362906-acfc16c67564?q=80&w=1965&auto=format&fit=crop"
                    alt="Gulshan Organic Market" class="market-img">
                <div class="market-info-wrapper">
                    <div class="market-info-top">
                        <div>
                            <h3 class="market-title">Gulshan Organic Market</h3>
                            <div class="market-meta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Gulshan-e-Iqbal, Karachi
                            </div>
                            <div class="market-meta" style="color: #059669;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Open • 09:00 AM - 4:00 PM
                            </div>
                        </div>
                        <div class="distance-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z">
                                </path>
                            </svg>
                            9.4 km
                        </div>
                    </div>
                    <div class="market-actions">
                        <button class="btn btn-primary" onclick="window.location.href='stall.php'">View Details</button>
                        <button class="btn btn-outline"
                            style="border-color: var(--border-color); color: var(--text-dark);">View on Map</button>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="market-list-card">
                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1974&auto=format&fit=crop"
                    alt="Malir Fresh Hub" class="market-img">
                <div class="market-info-wrapper">
                    <div class="market-info-top">
                        <div>
                            <h3 class="market-title">Malir Fresh Hub</h3>
                            <div class="market-meta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Malir Cantt, Karachi
                            </div>
                            <div class="market-meta" style="color: #6b7280;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Closed • Opens at 06:00 AM
                            </div>
                        </div>
                        <div class="distance-badge" style="background: #f3f4f6; color: #4b5563;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z">
                                </path>
                            </svg>
                            14.2 km
                        </div>
                    </div>
                    <div class="market-actions">
                        <button class="btn btn-primary" onclick="window.location.href='stall.php'">View Details</button>
                        <button class="btn btn-outline"
                            style="border-color: var(--border-color); color: var(--text-dark);">View on Map</button>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="market-list-card">
                <img src="https://images.unsplash.com/photo-1519999482648-25049ddd37b1?q=80&w=2126&auto=format&fit=crop"
                    alt="Tariq Road Sunday Market" class="market-img">
                <div class="market-info-wrapper">
                    <div class="market-info-top">
                        <div>
                            <h3 class="market-title">Tariq Road Sunday Market</h3>
                            <div class="market-meta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                PECHS, Karachi
                            </div>
                            <div class="market-meta" style="color: #059669;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Open • 07:00 AM - 6:00 PM
                            </div>
                        </div>
                        <div class="distance-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z">
                                </path>
                            </svg>
                            6.8 km
                        </div>
                    </div>
                    <div class="market-actions">
                        <button class="btn btn-primary" onclick="window.location.href='stall.php'">View Details</button>
                        <button class="btn btn-outline"
                            style="border-color: var(--border-color); color: var(--text-dark);">View on Map</button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('mobileFilterToggle');
    const sidebar = document.getElementById('filtersSidebar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        if (sidebar.classList.contains('show')) {
            toggleBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Close Filters';
        } else {
            toggleBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg> Show Filters';
        }
    });

    // Close when clicking outside in mobile view
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 900) {
            if (!sidebar.contains(e.target) && e.target !== toggleBtn && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('show');
                toggleBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg> Show Filters';
            }
        }
    });
</script>
<?php include 'includes/footer.php'; ?>