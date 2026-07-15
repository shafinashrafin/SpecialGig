<div class="page-header">
    <h1 class="page-title" style="color:#fff">Badges</h1>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Create Badge</h3></div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Criteria</label>
                    <textarea name="criteria" class="form-textarea" placeholder="e.g. Complete 10 jobs"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Badge</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">All Badges</h3></div>
        <div class="card-body">
            <?php foreach ($badges as $b): ?>
            <div style="display:flex;align-items:center;gap:.75rem;padding:.625rem 0;border-bottom:1px solid #f1f5f9">
                <div style="width:2.5rem;height:2.5rem;border-radius:.5rem;background:#eef2ff;display:flex;align-items:center;justify-content:center;color:#6366f1;font-size:1rem"><i class="fas fa-medal"></i></div>
                <div style="flex:1">
                    <p style="font-weight:600;font-size:.875rem"><?= e($b->name) ?></p>
                    <p style="font-size:.75rem;color:#94a3b8"><?= e($b->description) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
