<div class="page-header">
    <h1 class="page-title" style="color:#fff">Admin Dashboard</h1>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
    <div class="admin-stat">
        <p style="color:rgba(255,255,255,.5);font-size:.8125rem;font-weight:500">Total Users</p>
        <div class="stat-number"><?= number_format($stats['total_users']) ?></div>
        <p style="color:rgba(255,255,255,.35);font-size:.75rem;margin-top:.25rem"><?= $stats['total_buyers'] ?> buyers · <?= $stats['total_workers'] ?> workers</p>
    </div>
    <div class="admin-stat">
        <p style="color:rgba(255,255,255,.5);font-size:.8125rem;font-weight:500">Total Jobs</p>
        <div class="stat-number"><?= number_format($stats['total_jobs']) ?></div>
        <p style="color:rgba(255,255,255,.35);font-size:.75rem;margin-top:.25rem"><?= $stats['active_jobs'] ?> active · <?= $stats['pending_jobs'] ?> pending</p>
    </div>
    <div class="admin-stat">
        <p style="color:rgba(255,255,255,.5);font-size:.8125rem;font-weight:500">Wallet Balance</p>
        <div class="stat-number"><?= format_currency($stats['wallet_balance']) ?></div>
        <p style="color:rgba(255,255,255,.35);font-size:.75rem;margin-top:.25rem"><?= format_currency($stats['pending_balance']) ?> pending</p>
    </div>
    <div class="admin-stat">
        <p style="color:rgba(255,255,255,.5);font-size:.8125rem;font-weight:500">Revenue</p>
        <div class="stat-number"><?= format_currency($stats['revenue']) ?></div>
        <p style="color:rgba(255,255,255,.35);font-size:.75rem;margin-top:.25rem">From commissions</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
    <div class="stat-card" style="background:#fff">
        <div class="stat-icon" style="background:#fef3c7;color:#f59e0b"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value"><?= $stats['pending_jobs'] ?></div><div class="stat-label">Pending Jobs</div></div>
    </div>
    <div class="stat-card" style="background:#fff">
        <div class="stat-icon" style="background:#fef3c7;color:#f59e0b"><i class="fas fa-arrow-down"></i></div>
        <div><div class="stat-value"><?= $stats['pending_deposits'] ?></div><div class="stat-label">Pending Deposits</div></div>
    </div>
    <div class="stat-card" style="background:#fff">
        <div class="stat-icon" style="background:#fce7f3;color:#ec4899"><i class="fas fa-arrow-up"></i></div>
        <div><div class="stat-value"><?= $stats['pending_withdrawals'] ?></div><div class="stat-label">Pending Withdrawals</div></div>
    </div>
    <div class="stat-card" style="background:#fff">
        <div class="stat-icon" style="background:#fee2e2;color:#ef4444"><i class="fas fa-gavel"></i></div>
        <div><div class="stat-value"><?= $stats['open_disputes'] ?></div><div class="stat-label">Open Disputes</div></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Pending Job Reviews</h3></div>
        <div class="card-body">
            <?php if (empty($pendingJobs)): ?>
                <p style="color:#94a3b8;font-size:.875rem;text-align:center;padding:1rem">No pending jobs</p>
            <?php else: ?>
                <?php foreach ($pendingJobs as $job): ?>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid #f1f5f9">
                    <div>
                        <a href="/admin/jobs/view/<?= $job->id ?>" style="font-weight:600;font-size:.875rem;color:#0f172a;text-decoration:none"><?= e($job->title) ?></a>
                        <p style="font-size:.75rem;color:#94a3b8">By <?= e($job->username) ?> · <?= e($job->category_name ?? '') ?></p>
                    </div>
                    <div style="display:flex;gap:.375rem">
                        <a href="/admin/jobs/approve/<?= $job->id ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this job?')"><i class="fas fa-check"></i></a>
                        <a href="/admin/jobs/reject/<?= $job->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject this job?')"><i class="fas fa-times"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <a href="/admin/jobs?status=pending" style="display:block;text-align:center;margin-top:.75rem;font-size:.875rem;color:#6366f1;text-decoration:none">View All Pending Jobs</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Recent Users</h3></div>
        <div class="card-body">
            <?php foreach ($recentUsers as $user): ?>
            <div style="display:flex;align-items:center;gap:.75rem;padding:.5rem 0;border-bottom:1px solid #f1f5f9">
                <img src="<?= get_avatar($user, 32) ?>" alt="" class="avatar avatar-sm">
                <div style="flex:1">
                    <p style="font-size:.8125rem;font-weight:600;color:#0f172a"><?= e($user->full_name ?: $user->username) ?></p>
                    <p style="font-size:.6875rem;color:#94a3b8"><?= ucfirst($user->role) ?> · <?= time_ago($user->created_at) ?></p>
                </div>
                <?= get_status_badge($user->status) ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 style="font-size:1rem">Recent Jobs</h3></div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Buyer</th>
                    <th>Budget</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentJobs as $job): ?>
                <tr>
                    <td style="font-weight:600;font-size:.8125rem"><?= e(truncate($job->title, 40)) ?></td>
                    <td style="font-size:.8125rem"><?= e($job->username ?? 'N/A') ?></td>
                    <td style="font-weight:600;color:#6366f1"><?= format_currency($job->reward) ?></td>
                    <td><?= get_status_badge($job->status) ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($job->created_at) ?></td>
                    <td><a href="/admin/jobs/view/<?= $job->id ?>" class="btn btn-secondary btn-sm">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
