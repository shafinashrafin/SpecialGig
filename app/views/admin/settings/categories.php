<div class="page-header">
    <h1 class="page-title" style="color:#fff">Categories</h1>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Add Category</h3></div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Icon (Font Awesome)</label>
                    <input type="text" name="icon" class="form-input" placeholder="e.g. facebook, star, globe">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">All Categories</h3></div>
        <div class="table-container">
            <table>
                <thead><tr><th>Name</th><th>Slug</th><th>Order</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td style="font-weight:600"><?= e($cat->name) ?></td>
                        <td style="font-size:.8125rem;color:#64748b"><?= e($cat->slug) ?></td>
                        <td><?= $cat->order_column ?></td>
                        <td><a href="/admin/settings/delete-category/<?= $cat->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
