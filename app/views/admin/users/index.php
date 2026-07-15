<div class="page-header">
    <h1 class="page-title" style="color:#fff">Manage Users</h1>
</div>

<div style="display:flex;gap:.5rem;margin-bottom:1rem;flex-wrap:wrap">
    <a href="/admin/users" class="btn <?= !$currentRole ? 'btn-primary' : '' ?>" style="<?= !$currentRole ? '' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">All</a>
    <a href="/admin/users?role=buyer" class="btn <?= $currentRole === 'buyer' ? 'btn-primary' : '' ?>" style="<?= $currentRole === 'buyer' ? '' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Buyers</a>
    <a href="/admin/users?role=worker" class="btn <?= $currentRole === 'worker' ? 'btn-primary' : '' ?>" style="<?= $currentRole === 'worker' ? '' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Workers</a>
</div>

<div class="card">
    <div class="card-body" style="background:transparent">
        <form method="GET" action="/admin/users" style="display:flex;gap:.5rem">
            <input type="text" name="search" class="form-input" placeholder="Search by username or email..." value="<?= e($search) ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Wallet</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <img src="<?= get_avatar($user, 32) ?>" alt="" class="avatar avatar-sm">
                            <div>
                                <span style="font-weight:600;font-size:.875rem"><?= e($user->full_name ?: $user->username) ?></span>
                                <p style="font-size:.6875rem;color:#94a3b8">@<?= e($user->username) ?></p>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.8125rem"><?= e($user->email) ?></td>
                    <td><span class="badge" style="background:#eef2ff;color:#6366f1"><?= ucfirst($user->role) ?></span></td>
                    <td><?= get_status_badge($user->status) ?></td>
                    <td style="font-weight:600;color:#6366f1"><?= format_currency($user->wallet_balance) ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= format_date($user->created_at, 'M d, Y') ?></td>
                    <td>
                        <div style="display:flex;gap:.25rem">
                            <a href="/admin/users/view/<?= $user->id ?>" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                            <?php if ($user->role !== 'admin'): ?>
                                <?php if ($user->status === 'active'): ?>
                                    <a href="/admin/users/suspend/<?= $user->id ?>" class="btn btn-warning btn-sm" onclick="return confirm('Suspend this user?')"><i class="fas fa-pause"></i></a>
                                <?php elseif ($user->status === 'suspended'): ?>
                                    <a href="/admin/users/activate/<?= $user->id ?>" class="btn btn-success btn-sm"><i class="fas fa-play"></i></a>
                                <?php endif; ?>
                                <a href="/admin/users/ban/<?= $user->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Ban this user permanently?')"><i class="fas fa-ban"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
