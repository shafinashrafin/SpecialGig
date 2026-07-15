<div class="page-header">
    <h1 class="page-title" style="color:#fff">Manage Jobs</h1>
</div>

<div style="display:flex;gap:.5rem;margin-bottom:1rem;flex-wrap:wrap">
    <a href="/admin/jobs" class="btn" style="<?= !$currentStatus ? 'background:#6366f1;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">All</a>
    <a href="/admin/jobs?status=pending" class="btn" style="<?= $currentStatus === 'pending' ? 'background:#6366f1;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Pending</a>
    <a href="/admin/jobs?status=active" class="btn" style="<?= $currentStatus === 'active' ? 'background:#6366f1;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Active</a>
    <a href="/admin/jobs?status=completed" class="btn" style="<?= $currentStatus === 'completed' ? 'background:#6366f1;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Completed</a>
    <a href="/admin/jobs?status=rejected" class="btn" style="<?= $currentStatus === 'rejected' ? 'background:#6366f1;color:#fff' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>">Rejected</a>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Buyer</th>
                    <th>Category</th>
                    <th>Budget</th>
                    <th>Slots</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                <tr>
                    <td style="font-weight:600;font-size:.8125rem"><?= e(truncate($job->title, 40)) ?></td>
                    <td style="font-size:.8125rem"><?= e($job->username) ?></td>
                    <td style="font-size:.8125rem"><?= e($job->category_name ?? 'N/A') ?></td>
                    <td style="font-weight:600;color:#6366f1"><?= format_currency($job->reward) ?></td>
                    <td style="font-size:.8125rem"><?= $job->filled_slots ?>/<?= $job->available_slots ?></td>
                    <td><?= get_status_badge($job->status) ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($job->created_at) ?></td>
                    <td>
                        <div style="display:flex;gap:.25rem">
                            <a href="/admin/jobs/view/<?= $job->id ?>" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="/admin/jobs/edit/<?= $job->id ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                            <?php if ($job->status === 'pending'): ?>
                                <a href="/admin/jobs/approve/<?= $job->id ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve?')"><i class="fas fa-check"></i></a>
                                <a href="/admin/jobs/reject/<?= $job->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject?')"><i class="fas fa-times"></i></a>
                            <?php endif; ?>
                            <a href="/admin/jobs/delete/<?= $job->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Permanently delete?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
