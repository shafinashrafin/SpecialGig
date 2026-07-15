<div class="page-header">
    <h1 class="page-title" style="color:#fff">Withdrawal Management</h1>
</div>

<div style="display:flex;gap:.5rem;margin-bottom:1rem;flex-wrap:wrap">
    <a href="/admin/wallet/withdrawals" class="btn" style="<?= !$currentStatus ? 'background:#6366f1;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">All</a>
    <a href="/admin/wallet/withdrawals?status=pending" class="btn" style="<?= $currentStatus === 'pending' ? 'background:#f59e0b;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Pending</a>
    <a href="/admin/wallet/withdrawals?status=completed" class="btn" style="<?= $currentStatus === 'completed' ? 'background:#10b981;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Completed</a>
    <a href="/admin/wallet/withdrawals?status=failed" class="btn" style="<?= $currentStatus === 'failed' ? 'background:#ef4444;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Failed</a>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Details</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($withdrawals as $w): ?>
                <tr>
                    <td><span style="font-weight:600;font-size:.875rem"><?= e($w->full_name ?: $w->username) ?></span></td>
                    <td style="font-weight:600;color:#ef4444"><?= format_currency($w->amount) ?></td>
                    <td><span class="badge" style="background:#eef2ff;color:#6366f1"><?= e($w->payment_method) ?></span></td>
                    <td style="font-size:.8125rem;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= e($w->payment_details) ?>"><?= e(truncate($w->payment_details ?? '', 30)) ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($w->created_at) ?></td>
                    <td><?= get_status_badge($w->status) ?></td>
                    <td>
                        <?php if ($w->status === 'pending'): ?>
                        <div style="display:flex;gap:.25rem">
                            <a href="/admin/wallet/approve-withdrawal/<?= $w->id ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve withdrawal of <?= format_currency($w->amount) ?>?')"><i class="fas fa-check"></i></a>
                            <a href="/admin/wallet/reject-withdrawal/<?= $w->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject this withdrawal?')"><i class="fas fa-times"></i></a>
                        </div>
                        <?php else: ?>
                        <span style="color:#94a3b8;font-size:.8125rem"><?= $w->admin_note ? e($w->admin_note) : '—' ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
