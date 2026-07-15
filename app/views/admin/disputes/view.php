<div class="page-header">
    <h1 class="page-title" style="color:#fff">Dispute #<?= $dispute->id ?></h1>
    <a href="/admin/disputes" class="btn" style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1)"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="margin-bottom:1.5rem">
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div><p style="font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase">Job</p><p style="font-weight:600"><?= e($dispute->job_title) ?></p></div>
            <div><p style="font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase">Status</p><?= get_status_badge($dispute->status) ?></div>
            <div><p style="font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase">Reason</p><p style="font-size:.875rem"><?= e($dispute->reason) ?></p></div>
            <div><p style="font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase">Created</p><p style="font-size:.875rem"><?= format_date($dispute->created_at) ?></p></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 style="font-size:1rem">Resolution</h3></div>
    <div class="card-body">
        <?php if (in_array($dispute->status, ['resolved', 'closed'])): ?>
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.75rem;padding:1rem">
                <p style="font-weight:600;color:#065f46">Resolution</p>
                <p style="color:#475569;margin-top:.25rem"><?= e($dispute->resolution) ?></p>
            </div>
        <?php else: ?>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Resolution</label>
                <textarea name="resolution" class="form-textarea" required placeholder="Describe the resolution of this dispute..." style="min-height:120px"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Resolve Dispute</button>
        </form>
        <?php endif; ?>
    </div>
</div>
