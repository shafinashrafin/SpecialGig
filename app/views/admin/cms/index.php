<div class="page-header">
    <h1 class="page-title" style="color:#fff">CMS Pages</h1>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead><tr><th>Page</th><th>Slug</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                <tr>
                    <td style="font-weight:600"><?= e($p->title) ?></td>
                    <td style="font-size:.8125rem;color:#64748b">/<?= e($p->slug) ?></td>
                    <td><?= get_status_badge($p->status) ?></td>
                    <td style="font-size:.8125rem;color:#64748b"><?= time_ago($p->updated_at) ?></td>
                    <td><a href="/admin/cms/edit/<?= $p->id ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i> Edit</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
