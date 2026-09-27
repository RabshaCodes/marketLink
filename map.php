<?php 
session_start();
$page_title = 'Map - MarketLink';
include 'includes/header.php'; 
?>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    body { background-color: #f3f6f4; }
    
    .map-page-layout {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
        height: calc(100vh - 120px);
        min-height: 600px;
    }

    .farmers-sidebar {
        background: var(--white);
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .farmers-sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .farmers-sidebar-header h2 {
        font-size: 1.2rem;
        color: var(--text-dark);
        margin: 0;
    }

    .farmers-list {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .farmer-card {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: var(--transition);
        background: var(--white);
        width: 100%;
        height: 100px;
        box-sizing: border-box;
    }
    
    .farmer-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-sm);
    }

    .farmer-card.active {
        background-color: #eaf3e9;
        border-color: var(--primary-color);
    }

    .farmer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .farmer-info {
        flex: 1;
        min-width: 0;
    }
    
    .farmer-name {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-dark);
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .farmer-meta {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .farmer-rating {
        font-size: 0.8rem;
        color: #f59e0b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.2rem;
    }

    .farmer-card .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
        border-radius: 6px;
        white-space: nowrap;
    }

    .map-container {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        position: relative;
    }

    #map {
        width: 100%;
        height: 100%;
    }
    
    /* Custom Leaflet Popup Styles */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        padding: 0;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    .leaflet-popup-content {
        margin: 0;
        padding: 1.2rem;
        text-align: center;
    }
    .popup-name {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.3rem;
        color: var(--text-dark);
    }
    .popup-meta {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }
    .popup-btn {
        display: inline-block;
        background: var(--primary-color);
        color: white !important;
        padding: 0.5rem 1.2rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .popup-btn:hover {
        background: var(--primary-dark);
        color: white !important;
        transform: translateY(-2px);
    }

    /* Responsive for Tablet/Mobile */
    @media (max-width: 900px) {
        .map-page-layout {
            grid-template-columns: 1fr;
            grid-template-rows: auto 1fr;
            height: calc(100vh - 100px); /* Fill screen minus header */
            gap: 1rem;
        }
        
        .farmers-sidebar {
            /* Restrict height on mobile so map stays visible below it */
            max-height: 35vh; 
        }
    }

    @media (max-width: 480px) {
        .farmer-card {
            padding: 0.6rem;
            gap: 0.5rem;
        }
        .farmer-avatar {
            width: 40px;
            height: 40px;
        }
        .farmer-name {
            font-size: 0.9rem;
        }
        .farmer-card .btn {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
        }
    }
</style>

<div class="section pt-0" style="padding-top: 1.5rem;">
    <div class="map-page-layout">
        
        <!-- Left Sidebar: Farmers List -->
        <div class="farmers-sidebar">
            <div class="farmers-sidebar-header">
                <h2>Nearby Farmers</h2>
            </div>
            <div class="farmers-list">
                
                <!-- Farmer 1 -->
                <div class="farmer-card active" onclick="focusMarker(0)">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100&auto=format&fit=crop" alt="Ali's Farm" class="farmer-avatar">
                    <div class="farmer-info">
                        <div class="farmer-name">Ali's Farm</div>
                        <div class="farmer-meta">2.3 km • Clifton</div>
                        <div class="farmer-rating">★ 4.8</div>
                    </div>
                    <a href="stall.php" class="btn btn-primary" onclick="event.stopPropagation()">View Details</a>
                </div>

                <!-- Farmer 2 -->
                <div class="farmer-card" onclick="focusMarker(1)">
                    <img src="https://images.unsplash.com/photo-1574015974293-817f0ebebb74?q=80&w=100&auto=format&fit=crop" alt="Green Valley Farm" class="farmer-avatar">
                    <div class="farmer-info">
                        <div class="farmer-name">Green Valley Farm</div>
                        <div class="farmer-meta">4.1 km • DHA</div>
                        <div class="farmer-rating">★ 4.2</div>
                    </div>
                    <a href="stall.php" class="btn btn-primary" onclick="event.stopPropagation()">View Details</a>
                </div>

                <!-- Farmer 3 -->
                <div class="farmer-card" onclick="focusMarker(2)">
                    <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=100&auto=format&fit=crop" alt="Fresh Harvest" class="farmer-avatar">
                    <div class="farmer-info">
                        <div class="farmer-name">Fresh Harvest</div>
                        <div class="farmer-meta">5.0 km • Buffer Zone</div>
                        <div class="farmer-rating">★ 4.0</div>
                    </div>
                    <a href="stall.php" class="btn btn-primary" onclick="event.stopPropagation()">View Details</a>
                </div>
                
            </div>
        </div>

        <!-- Right: Map -->
        <div class="map-container">
            <div id="map"></div>
        </div>
        
    </div>
</div>

<script>
    // Initialize map focused on Karachi
    var map = L.map('map').setView([24.8138, 67.0311], 13); 

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Custom Leaf Icon for Map Markers
    var leafIcon = L.divIcon({
        className: 'custom-leaf-icon',
        html: '<div style="background: #2e7d32; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3); font-size: 16px;">🌿</div>',
        iconSize: [32, 32],
        iconAnchor: [16, 16],
        popupAnchor: [0, -16]
    });

    // Farmers Data
    var farmers = [
        { name: "Ali's Farm", meta: "2.3 km • Clifton", lat: 24.8138, lng: 67.0311 },
        { name: "Green Valley Farm", meta: "4.1 km • DHA", lat: 24.8050, lng: 67.0500 },
        { name: "Fresh Harvest", meta: "5.0 km • Buffer Zone", lat: 24.9500, lng: 67.0600 }
    ];

    var markers = [];

    // Add markers to map
    farmers.forEach(function(farmer, index) {
        var popupContent = `
            <div class="popup-name">${farmer.name}</div>
            <div class="popup-meta">${farmer.meta}</div>
            <a href="stall.php" class="popup-btn">View Details</a>
        `;
        
        var marker = L.marker([farmer.lat, farmer.lng], {icon: leafIcon})
            .bindPopup(popupContent, { closeButton: true, minWidth: 150 })
            .addTo(map);
            
        markers.push(marker);
        
        // Open the first popup by default
        if (index === 0) {
            marker.openPopup();
        }
        
        // Add click listener to marker to also highlight the sidebar card
        marker.on('click', function() {
            document.querySelectorAll('.farmer-card').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.farmer-card')[index].classList.add('active');
            // Scroll sidebar if needed
            var sidebar = document.querySelector('.farmers-list');
            var card = document.querySelectorAll('.farmer-card')[index];
            sidebar.scrollTop = card.offsetTop - sidebar.offsetTop;
        });
    });

    // Function to handle clicking on a sidebar card
    function focusMarker(index) {
        // Update active class
        document.querySelectorAll('.farmer-card').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.farmer-card')[index].classList.add('active');
        
        // Open popup and center map
        var marker = markers[index];
        map.setView(marker.getLatLng(), 14); // zoom in slightly when clicked
        marker.openPopup();
    }
</script>

<?php include 'includes/footer.php'; ?>
