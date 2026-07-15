<div class="page-header">
    <h1 class="page-title" style="color:#fff">Contact Messages</h1>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($contacts as $c): ?>
                <tr>
                    <td style="font-weight:600"><?= e($c->name) ?></td>
                    <td style="font-size:.8125rem"><?= e($c->email) ?></td>
                    <td style="font-size:.8125rem"><?= e($c->subject ?: '—') ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($c->created_at) ?></td>
                    <td><?= $c->is_read ? '<span class="badge" style="background:#d1fae5;color:#065f46">Read</span>' : '<span class="badge" style="background:#fef3c7;color:#92400e">New</span>' ?></td>
                    <td>
                        <?php if (!$c->is_read): ?>
                        <a href="/admin/cms/read-contact/<?= $c->id ?>" class="btn btn-secondary btn-sm">Mark Read</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
