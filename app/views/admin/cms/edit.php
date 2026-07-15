<div class="page-header">
    <h1 class="page-title" style="color:#fff">Edit: <?= e($page->title) ?></h1>
    <a href="/admin/cms" class="btn" style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1)"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/cms/edit/<?= $page->id ?>">
            <div class="form-group">
                <label class="form-label">Page Title</label>
                <input type="text" name="title" class="form-input" value="<?= e($page->title) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Content (HTML)</label>
                <textarea name="content" class="form-textarea" style="min-height:400px;font-family:monospace"><?= e($page->content) ?></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-input" value="<?= e($page->meta_title ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= $page->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $page->status === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Meta Description</label>
                <textarea name="meta_description" class="form-textarea" style="min-height:80px"><?= e($page->meta_description ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
