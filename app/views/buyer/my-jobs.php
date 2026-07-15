<div class="page-header">
    <div>
        <h1 class="page-title">My Jobs</h1>
        <p style="color:#64748b;font-size:.875rem"><?= $total ?> total jobs</p>
    </div>
    <a href="/buyer/create-job" class="btn btn-primary"><i class="fas fa-plus"></i> Create Job</a>
</div>

<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;flex-wrap:wrap">
    <a href="/buyer/my-jobs" class="btn <?= !$currentStatus ? 'btn-primary' : 'btn-secondary' ?> btn-sm">All</a>
    <a href="/buyer/my-jobs?status=active" class="btn <?= $currentStatus === 'active' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Active</a>
    <a href="/buyer/my-jobs?status=pending" class="btn <?= $currentStatus === 'pending' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Pending</a>
    <a href="/buyer/my-jobs?status=paused" class="btn <?= $currentStatus === 'paused' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Paused</a>
    <a href="/buyer/my-jobs?status=completed" class="btn <?= $currentStatus === 'completed' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Completed</a>
</div>

<?php if (empty($jobs)): ?>
<div class="card" style="padding:3rem;text-align:center">
    <i class="fas fa-briefcase" style="font-size:3rem;color:#cbd5e1;margin-bottom:1rem"></i>
    <h3 style="font-size:1.125rem;margin-bottom:.5rem">No jobs found</h3>
    <p style="color:#64748b;font-size:.875rem;margin-bottom:1.5rem">Create your first job to start hiring workers.</p>
    <a href="/buyer/create-job" class="btn btn-primary">Create a Job</a>
</div>
<?php else: ?>
<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Job</th>
                    <th>Category</th>
                    <th>Budget</th>
                    <th>Slots</th>
                    <th>Applications</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job): ?>
                <tr>
                    <td>
                        <a href="/jobs/<?= e($job->slug) ?>" style="font-weight:600;color:#0f172a;text-decoration:none"><?= e($job->title) ?></a>
                        <p style="font-size:.75rem;color:#94a3b8">Created <?= time_ago($job->created_at) ?></p>
                    </td>
                    <td><span style="font-size:.8125rem;color:#64748b"><?= e($job->category_name) ?></span></td>
                    <td style="font-weight:600;color:#6366f1"><?= format_currency($job->reward) ?></td>
                    <td><span style="font-size:.8125rem"><?= $job->filled_slots ?>/<?= $job->available_slots ?></span></td>
                    <td>
                        <span style="font-size:.8125rem"><?= $job->total_applications ?></span>
                        <?php if ($job->pending_review > 0): ?>
                            <span class="badge" style="background:#fef3c7;color:#f59e0b;margin-left:.25rem"><?= $job->pending_review ?> new</span>
                        <?php endif; ?>
                    </td>
                    <td><?= get_status_badge($job->status) ?></td>
                    <td>
                        <div style="display:flex;gap:.375rem">
                            <?php if (in_array($job->status, ['pending', 'active', 'paused'])): ?>
                                <a href="/buyer/edit-job/<?= $job->id ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                            <?php endif; ?>
                            <?php if ($job->status === 'active'): ?>
                                <a href="/buyer/pause-job/<?= $job->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-pause"></i></a>
                            <?php endif; ?>
                            <?php if ($job->status === 'paused'): ?>
                                <a href="/buyer/resume-job/<?= $job->id ?>" class="btn btn-success btn-sm"><i class="fas fa-play"></i></a>
                            <?php endif; ?>
                            <?php if (in_array($job->status, ['pending', 'paused'])): ?>
                                <a href="/buyer/delete-job/<?= $job->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this job? Budget will be refunded.')"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($lastPage > 1): ?>
<div style="display:flex;justify-content:center;gap:.5rem;margin-top:1.5rem">
    <?php for ($i = 1; $i <= $lastPage; $i++): ?>
        <a href="/buyer/my-jobs?page=<?= $i ?><?= $currentStatus ? '&status=' . $currentStatus : '' ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-secondary' ?> btn-sm"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>
<?php endif; ?>
