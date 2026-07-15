<div class="page-header">
    <h1 class="page-title" style="color:#fff">User Levels</h1>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Create Level</h3></div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Min Earnings ($)</label>
                    <input type="number" name="min_earnings" class="form-input" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Max Earnings ($)</label>
                    <input type="number" name="max_earnings" class="form-input" step="0.01" placeholder="Leave empty for unlimited">
                </div>
                <div class="form-group">
                    <label class="form-label">Benefits</label>
                    <textarea name="benefits" class="form-textarea" placeholder="Describe the benefits of this level"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Level</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">All Levels</h3></div>
        <div class="card-body">
            <?php foreach ($levels as $l): ?>
            <div style="display:flex;align-items:center;gap:.75rem;padding:.625rem 0;border-bottom:1px solid #f1f5f9">
                <div style="width:2.5rem;height:2.5rem;border-radius:.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.75rem"><?= substr($l->name, 0, 1) ?></div>
                <div style="flex:1">
                    <p style="font-weight:600;font-size:.875rem"><?= e($l->name) ?></p>
                    <p style="font-size:.75rem;color:#94a3b8">Earnings: $<?= number_format($l->min_earnings, 0) ?> - <?= $l->max_earnings ? '$' . number_format($l->max_earnings, 0) : 'Unlimited' ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
