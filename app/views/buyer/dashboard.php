<div class="page-header">
    <div>
        <h1 class="page-title">Buyer Dashboard</h1>
        <p style="color:#64748b;font-size:.875rem">Manage your jobs and track performance</p>
    </div>
    <a href="/buyer/create-job" class="btn btn-primary"><i class="fas fa-plus"></i> Create New Job</a>
</div>

<!-- Wallet Overview -->
<div class="wallet-overlay" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;margin-bottom:2rem">
    <div class="wallet-card">
        <p style="color:rgba(255,255,255,.7);font-size:.8125rem;font-weight:500">Wallet Balance</p>
        <div class="balance"><?= format_currency($wallet->balance) ?></div>
        <p style="color:rgba(255,255,255,.5);font-size:.75rem;margin-top:.25rem">Available for new jobs</p>
        <a href="/buyer/deposit" class="btn" style="background:rgba(255,255,255,.15);color:#fff;margin-top:1rem;width:100%">Deposit Funds</a>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef2ff;color:#6366f1"><i class="fas fa-play-circle"></i></div>
        <div>
            <div class="stat-value"><?= $stats['active_jobs'] ?></div>
            <div class="stat-label">Active Jobs</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#f59e0b"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value"><?= $stats['pending_jobs'] ?></div>
            <div class="stat-label">Pending Review</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5;color:#10b981"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-value"><?= $stats['completed_jobs'] ?></div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#3b82f6"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="stat-value"><?= format_currency($stats['total_spent']) ?></div>
            <div class="stat-label">Total Spent</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3;color:#ec4899"><i class="fas fa-clipboard-list"></i></div>
        <div>
            <div class="stat-value"><?= $stats['pending_reviews'] ?></div>
            <div class="stat-label">Pending Review</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
    <div>
        <div class="card">
            <div class="card-header">
                <h3 style="font-size:1rem">Recent Jobs</h3>
                <a href="/buyer/my-jobs" style="font-size:.8125rem;color:#6366f1;text-decoration:none">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($recentJobs)): ?>
                    <p style="text-align:center;color:#94a3b8;padding:2rem 0">No jobs yet. <a href="/buyer/create-job" style="color:#6366f1">Create your first job</a></p>
                <?php else: ?>
                    <?php foreach ($recentJobs as $job): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid #f1f5f9">
                        <div>
                            <a href="/jobs/<?= e($job->slug) ?>" style="font-weight:600;font-size:.875rem;color:#0f172a;text-decoration:none"><?= e($job->title) ?></a>
                            <p style="font-size:.75rem;color:#94a3b8"><?= e($job->category_name) ?> · <?= $job->applications ?> applications</p>
                        </div>
                        <div style="text-align:right">
                            <p style="font-weight:700;font-size:.875rem;color:#6366f1"><?= format_currency($job->reward) ?></p>
                            <?= get_status_badge($job->status) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 style="font-size:1rem">Recent Activity</h3>
            </div>
            <div class="card-body">
                <?php if (empty($recentActivity)): ?>
                    <p style="text-align:center;color:#94a3b8;padding:2rem 0">No recent activity</p>
                <?php else: ?>
                    <?php foreach ($recentActivity as $notif): ?>
                    <div style="display:flex;gap:.75rem;padding:.625rem 0;border-bottom:1px solid #f1f5f9">
                        <div style="width:2rem;height:2rem;border-radius:.5rem;background:#eef2ff;display:flex;align-items:center;justify-content:center;color:#6366f1;font-size:.75rem;flex-shrink:0">
                            <i class="fas fa-<?= $notif->type === 'success' ? 'check' : ($notif->type === 'error' ? 'times' : 'info') ?>"></i>
                        </div>
                        <div>
                            <p style="font-size:.8125rem;font-weight:600;color:#0f172a"><?= e($notif->title) ?></p>
                            <p style="font-size:.75rem;color:#64748b"><?= e($notif->message) ?></p>
                            <p style="font-size:.6875rem;color:#94a3b8;margin-top:.125rem"><?= time_ago($notif->created_at) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
