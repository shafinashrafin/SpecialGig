<div class="page-header">
    <h1 class="page-title" style="color:#fff">Disputes</h1>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead><tr><th>ID</th><th>Job</th><th>Buyer</th><th>Worker</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($disputes as $d): ?>
                <tr>
                    <td>#<?= $d->id ?></td>
                    <td style="font-weight:600;font-size:.875rem"><?= e(truncate($d->job_title, 30)) ?></td>
                    <td style="font-size:.8125rem"><?= e($d->buyer_username) ?></td>
                    <td style="font-size:.8125rem"><?= e($d->worker_username) ?></td>
                    <td><?= get_status_badge($d->status) ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($d->created_at) ?></td>
                    <td><a href="/admin/disputes/view/<?= $d->id ?>" class="btn btn-secondary btn-sm">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
