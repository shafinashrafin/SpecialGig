<div class="page-header">
    <h1 class="page-title">My Tasks</h1>
</div>

<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;flex-wrap:wrap">
    <a href="/worker/my-tasks" class="btn <?= !$currentStatus ? 'btn-primary' : 'btn-secondary' ?> btn-sm">All</a>
    <a href="/worker/my-tasks?status=accepted" class="btn <?= $currentStatus === 'accepted' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">In Progress</a>
    <a href="/worker/my-tasks?status=submitted" class="btn <?= $currentStatus === 'submitted' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Pending Review</a>
    <a href="/worker/my-tasks?status=approved" class="btn <?= $currentStatus === 'approved' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Completed</a>
    <a href="/worker/my-tasks?status=rejected" class="btn <?= $currentStatus === 'rejected' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Rejected</a>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Job</th>
                    <th>Buyer</th>
                    <th>Reward</th>
                    <th>Accepted</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                <tr><td colspan="6" style="text-align:center;padding:2rem;color:#94a3b8">No tasks found</td></tr>
                <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                <tr>
                    <td>
                        <a href="/jobs/<?= e($task->job_slug) ?>" style="font-weight:600;color:#0f172a;text-decoration:none;font-size:.875rem"><?= e($task->job_title) ?></a>
                        <p style="font-size:.75rem;color:#94a3b8"><?= e($task->category_name) ?></p>
                    </td>
                    <td style="font-size:.875rem;color:#475569"><?= e($task->buyer_name ?: $task->buyer_username) ?></td>
                    <td style="font-weight:600;color:#6366f1"><?= format_currency($task->reward) ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($task->created_at) ?></td>
                    <td><?= get_status_badge($task->status) ?></td>
                    <td>
                        <?php if ($task->status === 'accepted'): ?>
                            <a href="/worker/submit-proof/<?= $task->id ?>" class="btn btn-primary btn-sm">Submit Proof</a>
                        <?php elseif ($task->status === 'submitted'): ?>
                            <span style="font-size:.8125rem;color:#f59e0b">Waiting for approval</span>
                        <?php elseif ($task->status === 'approved'): ?>
                            <span style="font-size:.8125rem;color:#10b981">Completed</span>
                        <?php elseif ($task->status === 'rejected'): ?>
                            <span style="font-size:.8125rem;color:#ef4444;cursor:pointer" onclick="alert('<?= e($task->buyer_notes ?: 'No feedback provided') ?>')">View Feedback</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
