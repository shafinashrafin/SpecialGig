<div class="page-header">
    <div>
        <h1 class="page-title">Worker Dashboard</h1>
        <p style="color:#64748b;font-size:.875rem">Welcome back! Here's your overview.</p>
    </div>
    <a href="/worker/browse" class="btn btn-primary"><i class="fas fa-search"></i> Browse Jobs</a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef2ff;color:#6366f1"><i class="fas fa-search"></i></div>
        <div>
            <div class="stat-value"><?= $stats['available_jobs'] ?></div>
            <div class="stat-label">Available Jobs</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#3b82f6"><i class="fas fa-tasks"></i></div>
        <div>
            <div class="stat-value"><?= $stats['accepted_jobs'] ?></div>
            <div class="stat-label">Accepted</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#f59e0b"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value"><?= $stats['pending_approval'] ?></div>
            <div class="stat-label">Pending Approval</div>
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
        <div class="stat-icon" style="background:#ede9fe;color:#8b5cf6"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="stat-value"><?= format_currency($stats['earnings']) ?></div>
            <div class="stat-label">Total Earnings</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fce7f3;color:#ec4899"><i class="fas fa-gift"></i></div>
        <div>
            <div class="stat-value"><?= format_currency($stats['referral_income']) ?></div>
            <div class="stat-label">Referral Income</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div class="card">
        <div class="card-header">
            <h3 style="font-size:1rem">Recent Tasks</h3>
            <a href="/worker/my-tasks" style="font-size:.8125rem;color:#6366f1;text-decoration:none">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentTasks)): ?>
                <p style="text-align:center;color:#94a3b8;padding:1.5rem 0">No tasks yet. <a href="/worker/browse" style="color:#6366f1">Browse available jobs</a></p>
            <?php else: ?>
                <?php foreach ($recentTasks as $task): ?>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid #f1f5f9">
                    <div>
                        <a href="/jobs/<?= e($task->job_slug) ?>" style="font-weight:600;font-size:.875rem;color:#0f172a;text-decoration:none"><?= e($task->job_title) ?></a>
                        <p style="font-size:.75rem;color:#94a3b8">Reward: <?= format_currency($task->reward) ?> · <?= time_ago($task->created_at) ?></p>
                    </div>
                    <?= get_status_badge($task->status) ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div>
        <div class="card" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none">
            <div class="card-body" style="text-align:center">
                <div style="font-size:3rem;font-weight:800">#<?= $ranking ?></div>
                <p style="color:rgba(255,255,255,.7);font-size:.875rem">Your Leaderboard Rank</p>
                <div style="margin-top:.75rem;display:flex;justify-content:center">
                    <div style="display:flex;align-items:center;gap:.25rem">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="<?= $i < round($avgRating) ? '#fbbf24' : 'rgba(255,255,255,.2)' ?>">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        <?php endfor; ?>
                    </div>
                </div>
                <p style="color:rgba(255,255,255,.6);font-size:.75rem;margin-top:.25rem"><?= $avgRating ?> average rating</p>
            </div>
        </div>
        <?php if (!empty($badges)): ?>
        <div class="card" style="margin-top:1rem">
            <div class="card-header"><h3 style="font-size:1rem">Badges</h3></div>
            <div class="card-body">
                <div style="display:flex;flex-wrap:wrap;gap:.5rem">
                    <?php foreach ($badges as $badge): ?>
                    <span class="badge" style="background:#eef2ff;color:#6366f1"><?= e($badge->name) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 style="font-size:1rem">Earnings Overview</h3>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;text-align:center">
            <div>
                <p style="font-size:1.5rem;font-weight:800;color:#0f172a"><?= format_currency($wallet->balance) ?></p>
                <p style="font-size:.8125rem;color:#64748b">Available Balance</p>
            </div>
            <div>
                <p style="font-size:1.5rem;font-weight:800;color:#f59e0b"><?= format_currency($wallet->pending_balance) ?></p>
                <p style="font-size:.8125rem;color:#64748b">Pending Balance</p>
            </div>
            <div>
                <p style="font-size:1.5rem;font-weight:800;color:#10b981"><?= format_currency($wallet->total_earned) ?></p>
                <p style="font-size:.8125rem;color:#64748b">Total Earned</p>
            </div>
            <div>
                <p style="font-size:1.5rem;font-weight:800;color:#8b5cf6"><?= format_currency($wallet->referral_earnings) ?></p>
                <p style="font-size:.8125rem;color:#64748b">Referral Earnings</p>
            </div>
        </div>
        <div style="display:flex;gap:.75rem;justify-content:center;margin-top:1rem">
            <a href="/worker/wallet" class="btn btn-outline btn-sm">View Wallet</a>
            <a href="/worker/withdraw" class="btn btn-primary btn-sm">Withdraw</a>
        </div>
    </div>
</div>
