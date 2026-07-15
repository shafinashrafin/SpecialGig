<div class="page-header">
    <h1 class="page-title" style="color:#fff">Manage FAQs</h1>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Add FAQ</h3></div>
        <div class="card-body">
            <form method="POST" action="/admin/cms/faqs">
                <div class="form-group">
                    <label class="form-label">Question</label>
                    <input type="text" name="question" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Answer</label>
                    <textarea name="answer" class="form-textarea" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-input" placeholder="e.g. General, Payments">
                </div>
                <button type="submit" class="btn btn-primary">Add FAQ</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Existing FAQs</h3></div>
        <div class="card-body">
            <?php foreach ($faqs as $faq): ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:.75rem 0;border-bottom:1px solid #f1f5f9">
                <div style="flex:1">
                    <p style="font-weight:600;font-size:.875rem"><?= e($faq->question) ?></p>
                    <p style="font-size:.75rem;color:#94a3b8"><?= e(truncate($faq->answer, 80)) ?></p>
                </div>
                <a href="/admin/cms/delete-faq/<?= $faq->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
