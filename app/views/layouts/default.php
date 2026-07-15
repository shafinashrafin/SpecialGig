<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'SpecialGig') ?> | SpecialGig</title>
    <meta name="description" content="<?= e($meta_description ?? 'Premium Micro Job Marketplace') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/assets/css/app.css">
    <style>
        body { font-family: 'Inter', 'Hind Siliguri', sans-serif; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div style="display:flex;align-items:center;gap:1rem">
            <a href="/" style="font-size:1.25rem;font-weight:800;color:#0f172a;text-decoration:none"><?= e(get_setting('site_name', 'SpecialGig')) ?></a>
        </div>
        <div style="display:flex;align-items:center;gap:.75rem">
            <?php if (Auth::check()): ?>
                <a href="/notifications" class="btn btn-secondary btn-sm"><i class="far fa-bell"></i></a>
                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm" style="display:flex;align-items:center;gap:.5rem">
                        <span><?= e(Auth::user()->username ?? 'User') ?></span>
                        <i class="fas fa-chevron-down" style="font-size:.625rem"></i>
                    </button>
                    <div class="dropdown-menu">
                        <?php if (Auth::isAdmin()): ?>
                            <a href="/admin/dashboard" class="dropdown-item"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a>
                        <?php elseif (Auth::isBuyer()): ?>
                            <a href="/buyer/dashboard" class="dropdown-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            <a href="/buyer/create-job" class="dropdown-item"><i class="fas fa-plus-circle"></i> Create Job</a>
                            <a href="/buyer/my-jobs" class="dropdown-item"><i class="fas fa-briefcase"></i> My Jobs</a>
                            <a href="/buyer/wallet" class="dropdown-item"><i class="fas fa-wallet"></i> Wallet</a>
                        <?php elseif (Auth::isWorker()): ?>
                            <a href="/worker/dashboard" class="dropdown-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            <a href="/worker/browse" class="dropdown-item"><i class="fas fa-search"></i> Browse Jobs</a>
                            <a href="/worker/my-tasks" class="dropdown-item"><i class="fas fa-tasks"></i> My Tasks</a>
                            <a href="/worker/wallet" class="dropdown-item"><i class="fas fa-wallet"></i> Wallet</a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a href="/<?= Auth::role() ?>/settings" class="dropdown-item"><i class="fas fa-cog"></i> Settings</a>
                        <a href="/logout" class="dropdown-item" style="color:#ef4444"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/login" class="btn btn-secondary btn-sm">Login</a>
                <a href="/register" class="btn btn-primary btn-sm">Register</a>
            <?php endif; ?>
        </div>
    </nav>
    <main style="padding:2rem 0;min-height:calc(100vh - 64px)">
        <div class="container">
            <?= $viewContent ?? '' ?>
        </div>
    </main>
    <?php flash_messages(); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <script src="/public/assets/js/app.js"></script>
</body>
</html>
