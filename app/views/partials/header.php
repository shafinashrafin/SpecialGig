<header style="background:rgba(15,23,42,.9);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,.06);position:sticky;top:0;z-index:50">
    <div class="container">
        <nav style="display:flex;align-items:center;justify-content:space-between;height:4rem">
            <div style="display:flex;align-items:center;gap:2.5rem">
                <a href="/" style="display:flex;align-items:center;gap:.5rem;text-decoration:none">
                    <span style="font-size:1.5rem;font-weight:800;color:#fff">Special<span style="color:#818cf8">Gig</span></span>
                </a>
                <div style="display:flex;align-items:center;gap:.25rem" class="nav-links">
                    <a href="/" class="nav-link <?= get_route() === '' ? 'active' : '' ?>">Home</a>
                    <a href="/jobs/browse" class="nav-link">Browse Jobs</a>
                    <a href="/categories" class="nav-link">Categories</a>
                    <a href="/pricing" class="nav-link">Pricing</a>
                    <a href="/how-it-works" class="nav-link">How It Works</a>
                    <a href="/faq" class="nav-link">FAQ</a>
                    <a href="/contact" class="nav-link">Contact</a>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:.75rem">
                <?php if (Auth::check()): ?>
                    <div class="dropdown">
                        <button class="btn" style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1)">
                            <i class="far fa-bell"></i>
                        </button>
                    </div>
                    <div class="dropdown">
                        <button class="btn" style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:.5rem">
                            <img src="<?= get_avatar(Auth::user()) ?>" alt="" class="avatar avatar-sm">
                            <span><?= e(Auth::user()->username) ?></span>
                            <i class="fas fa-chevron-down" style="font-size:.625rem;opacity:.5"></i>
                        </button>
                        <div class="dropdown-menu">
                            <?php if (Auth::isAdmin()): ?>
                                <a href="/admin/dashboard" class="dropdown-item"><i class="fas fa-tachometer-alt"></i> Admin Panel</a>
                            <?php elseif (Auth::isBuyer()): ?>
                                <a href="/buyer/dashboard" class="dropdown-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                                <a href="/buyer/create-job" class="dropdown-item"><i class="fas fa-plus-circle"></i> Post a Job</a>
                                <a href="/buyer/wallet" class="dropdown-item"><i class="fas fa-wallet"></i> Wallet</a>
                            <?php elseif (Auth::isWorker()): ?>
                                <a href="/worker/dashboard" class="dropdown-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                                <a href="/worker/browse" class="dropdown-item"><i class="fas fa-search"></i> Browse Jobs</a>
                                <a href="/worker/wallet" class="dropdown-item"><i class="fas fa-wallet"></i> Wallet</a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="/<?= Auth::role() ?>/settings" class="dropdown-item"><i class="fas fa-cog"></i> Settings</a>
                            <a href="/logout" class="dropdown-item" style="color:#ef4444"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                    <?php if (Auth::isBuyer()): ?>
                        <a href="/buyer/create-job" class="btn btn-primary">Post a Job</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/login" class="btn" style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1)">Login</a>
                    <a href="/register" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
<style>
    .nav-link { padding:.5rem .75rem; border-radius:.5rem; font-size:.875rem; font-weight:500; color:rgba(255,255,255,.65); text-decoration:none; transition:all .2s }
    .nav-link:hover { background:rgba(255,255,255,.08); color:#fff }
    .nav-link.active { color:#818cf8 }
    @media(max-width:768px){ .nav-links { display:none } }
</style>
