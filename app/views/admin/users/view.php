<div class="page-header">
    <h1 class="page-title" style="color:#fff">User Details</h1>
    <a href="/admin/users" class="btn" style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1)"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div class="card">
        <div class="card-body" style="display:flex;align-items:center;gap:1.5rem">
            <img src="<?= get_avatar($user, 80) ?>" alt="" style="width:5rem;height:5rem;border-radius:50%">
            <div>
                <h3 style="font-size:1.25rem"><?= e($user->full_name ?: $user->username) ?></h3>
                <p style="color:#64748b">@<?= e($user->username) ?> · <?= e($user->email) ?></p>
                <div style="display:flex;gap:.5rem;margin-top:.5rem">
                    <span class="badge" style="background:#eef2ff;color:#6366f1"><?= ucfirst($user->role) ?></span>
                    <?= get_status_badge($user->status) ?>
                    <?php if ($user->email_verified_at): ?>
                    <span class="badge" style="background:#d1fae5;color:#065f46">Verified</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div><p style="font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase">Phone</p><p style="font-size:.875rem"><?= e($user->phone ?: '—') ?></p></div>
                <div><p style="font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase">Country</p><p style="font-size:.875rem"><?= e($user->country ?: '—') ?></p></div>
                <div><p style="font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase">Joined</p><p style="font-size:.875rem"><?= format_date($user->created_at) ?></p></div>
                <div><p style="font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase">Referral Code</p><p style="font-size:.875rem;font-family:monospace"><?= e($user->referral_code ?: '—') ?></p></div>
            </div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Wallet</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;text-align:center">
                <div style="padding:1rem;background:#f8fafc;border-radius:.75rem">
                    <p style="font-size:1.25rem;font-weight:800;color:#6366f1"><?= format_currency($wallet->balance) ?></p>
                    <p style="font-size:.75rem;color:#64748b">Balance</p>
                </div>
                <div style="padding:1rem;background:#f8fafc;border-radius:.75rem">
                    <p style="font-size:1.25rem;font-weight:800;color:#f59e0b"><?= format_currency($wallet->pending_balance) ?></p>
                    <p style="font-size:.75rem;color:#64748b">Pending</p>
                </div>
                <div style="padding:1rem;background:#f8fafc;border-radius:.75rem">
                    <p style="font-size:1.25rem;font-weight:800;color:#10b981"><?= format_currency($wallet->total_earned) ?></p>
                    <p style="font-size:.75rem;color:#64748b">Total Earned</p>
                </div>
                <div style="padding:1rem;background:#f8fafc;border-radius:.75rem">
                    <p style="font-size:1.25rem;font-weight:800;color:#3b82f6"><?= format_currency($wallet->total_deposited) ?></p>
                    <p style="font-size:.75rem;color:#64748b">Total Deposited</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Login History</h3></div>
        <div class="card-body">
            <?php foreach ($loginHistory as $lh): ?>
            <div style="display:flex;justify-content:space-between;padding:.375rem 0;border-bottom:1px solid #f1f5f9;font-size:.75rem">
                <span style="color:#64748b"><?= time_ago($lh->created_at) ?></span>
                <span style="font-family:monospace;color:#94a3b8"><?= e($lh->ip_address) ?></span>
                <span class="badge" style="background:<?= $lh->status === 'success' ? '#d1fae5' : '#fee2e2' ?>;color:<?= $lh->status === 'success' ? '#065f46' : '#991b1b' ?>"><?= $lh->status ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
    <div class="card-header"><h3 style="font-size:1rem">Jobs</h3></div>
    <div class="table-container">
        <table>
            <thead><tr><th>Title</th><th>Reward</th><th>Status</th><th>Created</th></tr></thead>
            <tbody>
                <?php if (empty($jobs)): ?>
                <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:1rem">No jobs</td></tr>
                <?php else: ?>
                <?php foreach ($jobs as $j): ?>
                <tr><td style="font-weight:600;font-size:.875rem"><?= e($j->title) ?></td><td><?= format_currency($j->reward) ?></td><td><?= get_status_badge($j->status) ?></td><td style="font-size:.8125rem;color:#64748b"><?= time_ago($j->created_at) ?></td></tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex;gap:.5rem;justify-content:center">
    <?php if ($user->role !== 'admin'): ?>
        <?php if ($user->status === 'active'): ?>
            <a href="/admin/users/suspend/<?= $user->id ?>" class="btn btn-warning" onclick="return confirm('Suspend this user?')"><i class="fas fa-pause"></i> Suspend</a>
        <?php elseif ($user->status === 'suspended'): ?>
            <a href="/admin/users/activate/<?= $user->id ?>" class="btn btn-success"><i class="fas fa-play"></i> Activate</a>
        <?php endif; ?>
        <a href="/admin/users/ban/<?= $user->id ?>" class="btn btn-danger" onclick="return confirm('Ban this user permanently?')"><i class="fas fa-ban"></i> Ban</a>
    <?php endif; ?>
</div>
