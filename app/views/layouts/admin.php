<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin') ?> | SpecialGig Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/assets/css/app.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
    <div style="display:flex">
        <aside class="sidebar" id="adminSidebar">
            <div class="sidebar-logo">
                <i class="fas fa-bolt" style="color:#818cf8;font-size:1.5rem"></i>
                <div>
                    <h2>SpecialGig</h2>
                    <p style="font-size:.6875rem;color:rgba(255,255,255,.35);margin-top:-.125rem">Admin Panel</p>
                </div>
                <button class="sidebar-toggle" onclick="toggleSidebar()" style="color:#fff;font-size:1.25rem;margin-left:auto">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="sidebar-label">Main</div>
                <a href="/admin/dashboard" class="sidebar-item <?= str_contains($title ?? '', 'Dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie icon"></i> Dashboard
                </a>
                <div class="sidebar-label">Users</div>
                <a href="/admin/users?role=buyer" class="sidebar-item"><i class="fas fa-user-tie icon"></i> Buyers</a>
                <a href="/admin/users?role=worker" class="sidebar-item"><i class="fas fa-user-cog icon"></i> Workers</a>
                <a href="/admin/users" class="sidebar-item"><i class="fas fa-users icon"></i> All Users</a>
                <div class="sidebar-label">Jobs</div>
                <a href="/admin/jobs?status=pending" class="sidebar-item"><i class="fas fa-clock icon"></i> Pending Review</a>
                <a href="/admin/jobs" class="sidebar-item"><i class="fas fa-briefcase icon"></i> All Jobs</a>
                <div class="sidebar-label">Finance</div>
                <a href="/admin/wallet/deposits?status=pending" class="sidebar-item"><i class="fas fa-arrow-down icon"></i> Deposits</a>
                <a href="/admin/wallet/withdrawals?status=pending" class="sidebar-item"><i class="fas fa-arrow-up icon"></i> Withdrawals</a>
                <div class="sidebar-label">Disputes</div>
                <a href="/admin/disputes" class="sidebar-item"><i class="fas fa-gavel icon"></i> Disputes</a>
                <div class="sidebar-label">Content</div>
                <a href="/admin/cms" class="sidebar-item"><i class="fas fa-file-alt icon"></i> CMS Pages</a>
                <a href="/admin/cms/faqs" class="sidebar-item"><i class="fas fa-question-circle icon"></i> FAQs</a>
                <a href="/admin/cms/announcements" class="sidebar-item"><i class="fas fa-bullhorn icon"></i> Announcements</a>
                <a href="/admin/cms/contacts" class="sidebar-item"><i class="fas fa-envelope icon"></i> Messages</a>
                <div class="sidebar-label">Management</div>
                <a href="/admin/settings" class="sidebar-item"><i class="fas fa-cog icon"></i> Settings</a>
                <a href="/admin/settings/categories" class="sidebar-item"><i class="fas fa-tags icon"></i> Categories</a>
                <a href="/admin/settings/badges" class="sidebar-item"><i class="fas fa-medal icon"></i> Badges</a>
                <a href="/admin/settings/levels" class="sidebar-item"><i class="fas fa-level-up-alt icon"></i> Levels</a>
                <a href="/admin/settings/coupons" class="sidebar-item"><i class="fas fa-percent icon"></i> Coupons</a>
                <div class="sidebar-label">Analytics</div>
                <a href="/admin/reports" class="sidebar-item"><i class="fas fa-chart-bar icon"></i> Reports</a>
                <a href="/admin/logs" class="sidebar-item"><i class="fas fa-history icon"></i> Activity Logs</a>
                <div class="sidebar-label">Account</div>
                <a href="/" class="sidebar-item" style="color:rgba(255,255,255,.4)"><i class="fas fa-external-link-alt icon"></i> View Site</a>
                <a href="/logout" class="sidebar-item" style="color:#f87171"><i class="fas fa-sign-out-alt icon"></i> Logout</a>
            </nav>
        </aside>
        <div class="main-content">
            <header class="navbar">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div style="flex:1"></div>
                <div style="display:flex;align-items:center;gap:1rem">
                    <a href="/notifications" style="color:#64748b;font-size:1.125rem;position:relative">
                        <i class="far fa-bell"></i>
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm" style="display:flex;align-items:center;gap:.5rem">
                            <span><?= e(Auth::user()->username ?? 'Admin') ?></span>
                            <i class="fas fa-chevron-down" style="font-size:.625rem"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="/admin/dashboard" class="dropdown-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            <a href="/" class="dropdown-item"><i class="fas fa-external-link-alt"></i> View Site</a>
                            <div class="dropdown-divider"></div>
                            <a href="/logout" class="dropdown-item" style="color:#ef4444"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </header>
            <div style="padding:1.5rem 2rem">
                <?= $viewContent ?? '' ?>
            </div>
        </div>
    </div>
    <?php flash_messages(); ?>
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <script src="/public/assets/js/app.js"></script>
</body>
</html>
