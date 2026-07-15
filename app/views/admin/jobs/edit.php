<div class="page-header">
    <h1 class="page-title" style="color:#fff">Edit Job</h1>
    <a href="/admin/jobs/view/<?= $job->id ?>" class="btn" style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.1)"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-input" value="<?= e($job->title) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= $cat->id == $job->category_id ? 'selected' : '' ?>><?= e($cat->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" style="min-height:150px"><?= e($job->description) ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Reward ($)</label>
                    <input type="number" name="reward" class="form-input" step="0.01" value="<?= $job->reward ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Available Slots</label>
                    <input type="number" name="available_slots" class="form-input" value="<?= $job->available_slots ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (JOB_STATUSES as $s): ?>
                        <option value="<?= $s ?>" <?= $job->status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Job</button>
        </form>
    </div>
</div>
