<div class="page-header">
    <h1 class="page-title" style="color:#fff">Announcements</h1>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Create Announcement</h3></div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-textarea" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="info">Info</option>
                        <option value="success">Success</option>
                        <option value="warning">Warning</option>
                        <option value="error">Error</option>
                    </select>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="starts_at" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expires_at" class="form-input">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">All Announcements</h3></div>
        <div class="card-body">
            <?php foreach ($announcements as $a): ?>
            <div style="padding:.75rem 0;border-bottom:1px solid #f1f5f9">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <div style="display:flex;align-items:center;gap:.5rem">
                        <span class="badge" style="background:<?= $a->type === 'error' ? '#fee2e2' : ($a->type === 'warning' ? '#fef3c7' : ($a->type === 'success' ? '#d1fae5' : '#dbeafe')) ?>;color:<?= $a->type === 'error' ? '#991b1b' : ($a->type === 'warning' ? '#92400e' : ($a->type === 'success' ? '#065f46' : '#1e40af')) ?>"><?= ucfirst($a->type) ?></span>
                        <span style="font-weight:600;font-size:.875rem"><?= e($a->title) ?></span>
                    </div>
                    <?= get_status_badge($a->status) ?>
                </div>
                <p style="font-size:.8125rem;color:#64748b;margin-top:.25rem"><?= e(truncate($a->message, 100)) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
