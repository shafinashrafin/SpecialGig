<div class="page-header">
    <h1 class="page-title" style="color:#fff">Activity Logs</h1>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead><tr><th>Time</th><th>User</th><th>Action</th><th>Details</th><th>IP Address</th></tr></thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td style="white-space:nowrap;font-size:.75rem;color:#64748b"><?= time_ago($log->created_at) ?></td>
                    <td style="font-size:.8125rem"><?= e($log->username ?? 'System') ?></td>
                    <td><span class="badge" style="background:#eef2ff;color:#6366f1"><?= e($log->action) ?></span></td>
                    <td style="font-size:.8125rem;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($log->details ?? '') ?></td>
                    <td style="font-size:.75rem;color:#94a3b8;font-family:monospace"><?= e($log->ip_address ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
