<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$page_title = 'Profile - MarketLink';
include '../includes/header.php';
require_once '../includes/database.php';

$dash_active = 'profile';
include __DIR__ . '/includes/dashboard_layout.php';

// Try to load live user data, fall back to session/demo values
$profile = [
    'name'  => $fullName,
    'email' => $userMail,
    'phone' => '+92 300 1234567',
    'city'  => 'Karachi, Pakistan',
    'address' => 'Gulshan, Karachi',
];
try {
    if (isset($pdo) && !empty($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        if ($row = $stmt->fetch()) {
            $profile['name']  = trim($row['first_name'] . ' ' . $row['last_name']);
            $profile['email'] = $row['email'];
        }
    }
} catch (Throwable $e) { /* keep demo values */ }
?>
<style>
    .profile-card { background: var(--surface); border-radius: var(--r-lg); border: 1px solid var(--line); box-shadow: var(--shadow-md); padding: 0 2rem 2rem; overflow: hidden; }
    .profile-banner {
        height: 108px; margin: 0 -2rem 3.1rem;
        background:
            radial-gradient(420px 160px at 88% 130%, rgba(134,239,172,.4), transparent 60%),
            linear-gradient(120deg, #0A5537, #15803D 70%);
        position: relative;
    }
    .profile-top { display: flex; justify-content: space-between; align-items: flex-end; margin-top: -60px; margin-bottom: 1.9rem; gap: 1rem; flex-wrap: wrap; }
    .profile-identity { display: flex; align-items: center; gap: 1.15rem; }
    .avatar-wrap { position: relative; }
    .profile-identity img { width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: var(--shadow-md); background: #fff; }
    .verified-badge {
        position: absolute; right: 1px; bottom: 4px; width: 27px; height: 27px; border-radius: 50%;
        background: linear-gradient(135deg, #22C55E, #15803D); border: 2.5px solid #fff;
        display: flex; align-items: center; justify-content: center; color: #fff;
        box-shadow: 0 4px 10px -2px rgba(21,128,61,.6);
    }
    .profile-identity h2 { margin: 0 0 .25rem; font-size: 1.4rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .profile-identity .role { display: inline-flex; align-items: center; gap: .35rem; color: var(--primary); font-weight: 800; font-size: .76rem; text-transform: uppercase; letter-spacing: .06em; }

    .btn-edit {
        display: inline-flex; align-items: center; gap: 0.4rem;
        border: 1.5px solid #CBE7D5; color: var(--primary); background: #fff;
        padding: 0.6rem 1.15rem; border-radius: 12px; text-decoration: none; font-size: 0.86rem; font-weight: 700;
        transition: all .2s;
    }
    .btn-edit:hover { border-color: var(--primary-2); background: #F0FDF4; transform: translateY(-1px); box-shadow: var(--shadow-sm); }

    .field-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.1rem; }
    .field-group { margin: 0; }
    .field-group.full { grid-column: 1 / -1; }
    .field-group label { display: block; font-size: 0.76rem; color: var(--muted); margin-bottom: 0.45rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; }
    .field-group .field-value {
        background: #F6FAF7; border: 1px solid var(--line); border-radius: var(--r-md);
        padding: 0.85rem 1rem; font-size: 0.94rem; color: var(--ink); font-weight: 600;
        display: flex; align-items: center; gap: 0.6rem; transition: border-color .2s, background .2s;
    }
    .field-group .field-value:hover { border-color: #CBE7D5; background: #fff; }
    .field-group .field-value svg { color: var(--primary); flex-shrink: 0; }

    .addresses-section { margin-top: 2rem; border-top: 1px solid var(--line); padding-top: 1.75rem; }
    .addresses-section h3 { margin: 0 0 1rem; font-size: 1.15rem; font-weight: 800; color: var(--ink); letter-spacing: -.02em; }
    .address-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; background: #F6FAF7; border: 1px solid var(--line); border-radius: var(--r-md); padding: 1rem 1.2rem; margin-bottom: 0.75rem; transition: transform .2s, box-shadow .2s; }
    .address-row:hover { transform: translateX(4px); box-shadow: var(--shadow-sm); }
    .ar-left { display: flex; align-items: center; gap: 0.9rem; }
    .ar-ico { width: 40px; height: 40px; border-radius: 12px; background: #DCF6E6; color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .address-row .ar-type { font-weight: 800; color: var(--ink); font-size: 0.92rem; margin-bottom: 0.1rem; display: flex; align-items: center; gap: 0.5rem; }
    .address-row .ar-value { color: var(--muted); font-size: 0.85rem; }
    .ar-tag { font-size: .64rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; background: #DCF6E6; color: #065F46; padding: .1rem .45rem; border-radius: 999px; }
    .btn-manage { background: linear-gradient(135deg,#1D9A50,#15803D); color: #fff; border: none; padding: 0.6rem 1.3rem; border-radius: 12px; font-weight: 700; cursor: pointer; font-family: inherit; font-size: 0.86rem; text-decoration: none; box-shadow: 0 8px 18px -8px rgba(21,128,61,.6); transition: filter .2s, transform .2s; }
    .btn-manage:hover { filter: brightness(1.06); transform: translateY(-1px); }
    @media (max-width: 620px) { .field-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-body" style="max-width: 760px;">
    <div class="page-header reveal" style="margin-bottom:1.25rem;">
        <h1 style="margin:0;">Profile</h1>
        <p>Manage your personal information and delivery addresses.</p>
    </div>

    <div class="profile-card reveal">
        <div class="profile-banner"></div>
        <div class="profile-top">
            <div class="profile-identity">
                <div class="avatar-wrap">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($profile['name']); ?>&background=15803D&color=fff&size=192&bold=true" alt="Profile photo">
                    <span class="verified-badge" title="Verified account">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                </div>
                <div>
                    <h2><?php echo htmlspecialchars($profile['name']); ?></h2>
                    <div class="role">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Customer
                    </div>
                </div>
            </div>
            <a href="#" class="btn-edit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Profile
            </a>
        </div>

        <div class="field-grid">
            <div class="field-group full">
                <label>Email</label>
                <div class="field-value">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <?php echo htmlspecialchars($profile['email']); ?>
                </div>
            </div>
            <div class="field-group">
                <label>Phone</label>
                <div class="field-value">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <?php echo htmlspecialchars($profile['phone']); ?>
                </div>
            </div>
            <div class="field-group">
                <label>Location</label>
                <div class="field-value">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <?php echo htmlspecialchars($profile['city']); ?>
                </div>
            </div>
        </div>

        <div class="addresses-section">
            <h3>My Addresses</h3>
            <div class="address-row">
                <div class="ar-left">
                    <span class="ar-ico">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    </span>
                    <div>
                        <div class="ar-type">Home <span class="ar-tag">Default</span></div>
                        <div class="ar-value"><?php echo htmlspecialchars($profile['address']); ?></div>
                    </div>
                </div>
                <a href="#" class="btn-manage">Manage</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/dashboard_layout_close.php'; ?>
<?php include '../includes/footer.php'; ?>
