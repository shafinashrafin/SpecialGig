<div class="page-header">
    <h1 class="page-title">Applications</h1>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Worker</th>
                    <th>Job</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($applications)): ?>
                <tr><td colspan="5" style="text-align:center;padding:2rem;color:#94a3b8">No applications yet</td></tr>
                <?php else: ?>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <img src="<?= get_avatar($app) ?>" alt="" class="avatar avatar-sm">
                            <span style="font-weight:600;font-size:.875rem"><?= e($app->full_name ?: $app->username) ?></span>
                        </div>
                    </td>
                    <td>
                        <a href="/jobs/<?= e($app->job_slug) ?>" style="font-weight:500;color:#0f172a;text-decoration:none;font-size:.875rem"><?= e($app->job_title) ?></a>
                        <p style="font-size:.75rem;color:#94a3b8">Reward: <?= format_currency($app->reward) ?></p>
                    </td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($app->created_at) ?></td>
                    <td><?= get_status_badge($app->status) ?></td>
                    <td>
                        <?php if ($app->status === 'submitted'): ?>
                            <a href="/buyer/review-proof/<?= $app->id ?>" class="btn btn-primary btn-sm">Review Proof</a>
                        <?php elseif ($app->status === 'approved'): ?>
                            <a href="/buyer/rate/<?= $app->id ?>" class="btn btn-secondary btn-sm">Rate Worker</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
