<div class="page-header">
    <h1 class="page-title" style="color:#fff">Job Details</h1>
    <div style="display:flex;gap:.5rem">
        <a href="/admin/jobs" class="btn" style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1)"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="/admin/jobs/edit/<?= $job->id ?>" class="btn btn-primary">Edit Job</a>
    </div>
</div>

<div class="card" style="margin-bottom:1.5rem">
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div>
                <p style="font-size:.75rem;color:#94a3b8;text-transform:uppercase;font-weight:700;letter-spacing:.05em">Title</p>
                <p style="font-weight:600"><?= e($job->title) ?></p>
            </div>
            <div>
                <p style="font-size:.75rem;color:#94a3b8;text-transform:uppercase;font-weight:700;letter-spacing:.05em">Buyer</p>
                <p style="font-weight:600"><?= e($job->full_name ?: $job->username) ?></p>
            </div>
            <div>
                <p style="font-size:.75rem;color:#94a3b8;text-transform:uppercase;font-weight:700;letter-spacing:.05em">Category</p>
                <p><?= e($job->category_name ?? 'N/A') ?></p>
            </div>
            <div>
                <p style="font-size:.75rem;color:#94a3b8;text-transform:uppercase;font-weight:700;letter-spacing:.05em">Status</p>
                <?= get_status_badge($job->status) ?>
            </div>
            <div>
                <p style="font-size:.75rem;color:#94a3b8;text-transform:uppercase;font-weight:700;letter-spacing:.05em">Reward</p>
                <p style="font-size:1.25rem;font-weight:700;color:#6366f1"><?= format_currency($job->reward) ?></p>
            </div>
            <div>
                <p style="font-size:.75rem;color:#94a3b8;text-transform:uppercase;font-weight:700;letter-spacing:.05em">Budget</p>
                <p style="font-weight:600"><?= format_currency($job->total_budget) ?></p>
            </div>
            <div>
                <p style="font-size:.75rem;color:#94a3b8;text-transform:uppercase;font-weight:700;letter-spacing:.05em">Slots</p>
                <p><?= $job->filled_slots ?> / <?= $job->available_slots ?> filled</p>
            </div>
            <div>
                <p style="font-size:.75rem;color:#94a3b8;text-transform:uppercase;font-weight:700;letter-spacing:.05em">Created</p>
                <p><?= format_date($job->created_at) ?></p>
            </div>
        </div>
        <?php if ($job->rejection_reason): ?>
        <div class="alert alert-error" style="margin-top:1rem">
            <strong>Rejection Reason:</strong> <?= e($job->rejection_reason) ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Description</h3></div>
        <div class="card-body" style="font-size:.875rem;color:#475569;line-height:1.7;white-space:pre-wrap"><?= e($job->description) ?></div>
    </div>
    <?php if ($job->instructions): ?>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Instructions</h3></div>
        <div class="card-body" style="font-size:.875rem;color:#475569;line-height:1.7;white-space:pre-wrap"><?= e($job->instructions) ?></div>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header"><h3 style="font-size:1rem">Applications (<?= count($applications) ?>)</h3></div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Worker</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($applications)): ?>
                <tr><td colspan="4" style="text-align:center;padding:1.5rem;color:#94a3b8">No applications</td></tr>
                <?php else: ?>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <img src="<?= get_avatar($app, 28) ?>" alt="" class="avatar avatar-sm">
                            <span><?= e($app->full_name ?: $app->username) ?></span>
                        </div>
                    </td>
                    <td><?= get_status_badge($app->status) ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($app->created_at) ?></td>
                    <td><?php if ($app->proof): ?><button class="btn btn-secondary btn-sm" onclick="alert('<?= e($app->proof) ?>')">View Proof</button><?php else: ?><span style="color:#94a3b8">N/A</span><?php endif; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($job->status === 'pending'): ?>
<div style="display:flex;gap:.75rem;margin-top:1.5rem;justify-content:center">
    <a href="/admin/jobs/approve/<?= $job->id ?>" class="btn btn-success btn-lg" onclick="return confirm('Approve this job?')"><i class="fas fa-check"></i> Approve Job</a>
    <a href="/admin/jobs/reject/<?= $job->id ?>" class="btn btn-danger btn-lg" onclick="return confirm('Reject this job?')"><i class="fas fa-times"></i> Reject Job</a>
</div>
<?php endif; ?>
