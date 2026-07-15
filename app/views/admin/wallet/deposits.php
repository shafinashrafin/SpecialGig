<div class="page-header">
    <h1 class="page-title" style="color:#fff">Deposit Management</h1>
</div>

<div style="display:flex;gap:.5rem;margin-bottom:1rem;flex-wrap:wrap">
    <a href="/admin/wallet/deposits" class="btn" style="<?= !$currentStatus ? 'background:#6366f1;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">All</a>
    <a href="/admin/wallet/deposits?status=pending" class="btn" style="<?= $currentStatus === 'pending' ? 'background:#f59e0b;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Pending</a>
    <a href="/admin/wallet/deposits?status=completed" class="btn" style="<?= $currentStatus === 'completed' ? 'background:#10b981;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Completed</a>
    <a href="/admin/wallet/deposits?status=failed" class="btn" style="<?= $currentStatus === 'failed' ? 'background:#ef4444;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Failed</a>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deposits as $d): ?>
                <tr>
                    <td>
                        <span style="font-weight:600;font-size:.875rem"><?= e($d->full_name ?: $d->username) ?></span>
                    </td>
                    <td style="font-weight:600;color:#10b981"><?= format_currency($d->amount) ?></td>
                    <td><span class="badge" style="background:#eef2ff;color:#6366f1"><?= e($d->payment_method) ?></span></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= e($d->reference) ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($d->created_at) ?></td>
                    <td><?= get_status_badge($d->status) ?></td>
                    <td>
                        <?php if ($d->status === 'pending'): ?>
                        <div style="display:flex;gap:.25rem">
                            <a href="/admin/wallet/approve-deposit/<?= $d->id ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve deposit of <?= format_currency($d->amount) ?>?')"><i class="fas fa-check"></i></a>
                            <a href="/admin/wallet/reject-deposit/<?= $d->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject this deposit?')"><i class="fas fa-times"></i></a>
                        </div>
                        <?php else: ?>
                        <span style="color:#94a3b8;font-size:.8125rem"><?= $d->admin_note ? e($d->admin_note) : '—' ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
