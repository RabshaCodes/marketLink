<?php 
session_start();
$page_title = 'Contact Us - MarketLink';
include 'includes/header.php'; 
?>
<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    body { background-color: #f3f6f4; }
    
    .contact-section {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    /* Hero Banner */
    .contact-hero {
        position: relative;
        background: url('assets/images/contact-bg.jpg') center right / cover no-repeat;
        border-radius: 16px;
        padding: 4rem 3rem;
        color: white;
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .contact-hero::before {
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
    .contact-hero > * {
        position: relative;
        z-index: 2;
    }
    .contact-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }
    .contact-hero p {
        font-size: 1.1rem;
        max-width: 600px;
        opacity: 0.9;
        line-height: 1.6;
    }

    /* Main Grid: Left (Info+Form) / Right (Map) */
    .contact-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 4rem;
    }

    /* Left Side */
    .contact-left {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Info Cards */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .info-card {
        background: var(--white);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--shadow-sm);
    }
    .info-icon {
        width: 40px;
        height: 40px;
        background: #f0fdf4;
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .info-content h3 {
        font-size: 0.9rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-dark);
    }
    .info-content p {
        font-size: 0.85rem;
        color: var(--text-light);
        margin: 0;
    }

    /* Form Card */
    .form-card {
        background: var(--white);
        border-radius: 16px;
        padding: 2.5rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }
    .form-card h2 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--text-dark);
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-dark);
    }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-family: inherit;
        background: #f9fafb;
    }
    .form-group textarea {
        resize: vertical;
        height: 120px;
    }
    .form-card .btn {
        padding: 0.8rem 2rem;
        border-radius: 8px;
        font-weight: 600;
    }

    /* Right Side (Map) */
    .map-card {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        height: 100%;
        min-height: 400px;
    }
    #contactMap {
        width: 100%;
        height: 100%;
    }

    /* Responsive */
    @media (max-width: 950px) {
        .contact-layout {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .contact-left {
            gap: 1.5rem;
        }
        .map-card {
            min-height: 350px;
        }
        .form-card {
            padding: 1.5rem;
        }
    }
    @media (max-width: 600px) {
        .contact-hero {
            padding: 2rem 1.2rem;
            border-radius: 12px;
        }
        .contact-hero h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        .contact-hero p {
            font-size: 1rem;
        }
        .form-row {
            grid-template-columns: 1fr;
        }
        .info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
        }
        .info-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 1rem;
        }
        .info-content h3 {
            font-size: 0.85rem;
        }
        .info-content p {
            font-size: 0.75rem;
        }
        .contact-section {
            padding: 0 1rem;
            margin: 1.5rem auto;
        }
    }
</style>

<div class="contact-section">
    <!-- Hero Banner -->
    <div class="contact-hero">
        <h1>Contact MarketLink</h1>
        <p>We'd love to hear from you — farmers, customers, and community partners alike.</p>
    </div>

    <!-- Main Layout -->
    <div class="contact-layout">
        
        <!-- Left Side: Info & Form -->
        <div class="contact-left">
            
            <!-- Info Grid -->
            <div class="info-grid">
                <!-- Location -->
                <div class="info-card">
                    <div class="info-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <div class="info-content">
                        <h3>Location</h3>
                        <p>Clifton, Karachi</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="info-card">
                    <div class="info-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <div class="info-content">
                        <h3>Email</h3>
                        <p>team@marketlink.pk</p>
                    </div>
                </div>

                <!-- Phone -->
                <div class="info-card">
                    <div class="info-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </div>
                    <div class="info-content">
                        <h3>Phone</h3>
                        <p>0300-0000000</p>
                    </div>
                </div>

                <!-- Support Hours -->
                <div class="info-card">
                    <div class="info-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="info-content">
                        <h3>Support Hours</h3>
                        <p>Sat-Sun : 7 AM - 2 PM</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="form-card">
                <h2>Send us a Message</h2>
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Message sent successfully!');">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Your Name</label>
                            <input type="text" placeholder="e.g. Ayesha Khan" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" placeholder="your@email.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea placeholder="How can we help you?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>

        </div>

        <!-- Right Side: Map -->
        <div class="map-card">
            <div id="contactMap"></div>
        </div>

    </div>
</div>

<script>
    // Initialize map focused on Clifton, Karachi (MarketLink HQ placeholder)
    var map = L.map('contactMap').setView([24.8138, 67.0311], 14); 

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([24.8138, 67.0311])
        .bindPopup('<div style="text-align:center;"><strong>MarketLink HQ</strong><br>Clifton, Karachi</div>')
        .addTo(map);
        
    marker.openPopup();
</script>

<?php include 'includes/footer.php'; ?>
