<div style="margin-bottom:2rem">
    <h1 style="font-size:1.75rem;margin-bottom:.5rem">Browse Jobs</h1>
    <p style="color:#64748b"><?= $total ?> jobs available</p>
</div>

<div class="card" style="margin-bottom:1.5rem">
    <div class="card-body">
        <form method="GET" action="/jobs/browse" style="display:grid;grid-template-columns:1fr 1fr auto;gap:.75rem">
            <input type="text" name="q" class="form-input" placeholder="Search jobs..." value="<?= e($query) ?>">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->id ?>" <?= $selectedCategory == $cat->id ? 'selected' : '' ?>><?= e($cat->name) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>
</div>

<?php if (empty($jobs)): ?>
<div class="card" style="padding:3rem;text-align:center">
    <i class="fas fa-inbox" style="font-size:3rem;color:#cbd5e1;margin-bottom:1rem"></i>
    <h3 style="font-size:1.125rem;margin-bottom:.5rem">No jobs found</h3>
    <p style="color:#64748b;font-size:.875rem">Try different search terms or browse all categories.</p>
</div>
<?php else: ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1rem">
    <?php foreach ($jobs as $job): ?>
    <div class="job-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:.75rem">
                <img src="<?= get_avatar($job) ?>" alt="" class="avatar">
                <div>
                    <p style="font-weight:600;font-size:.875rem;color:#0f172a"><?= e($job->full_name ?: $job->username) ?></p>
                    <p style="font-size:.75rem;color:#94a3b8"><?= e($job->category_name) ?></p>
                </div>
            </div>
            <?php if ($job->is_featured): ?>
                <span class="badge" style="background:#eef2ff;color:#6366f1;font-size:.6875rem">Featured</span>
            <?php endif; ?>
        </div>
        <h3 style="font-size:1rem;font-weight:700;color:#0f172a">
            <a href="/jobs/<?= e($job->slug) ?>" style="text-decoration:none;color:inherit"><?= e($job->title) ?></a>
        </h3>
        <div class="reward"><?= format_currency($job->reward) ?> <span style="font-size:.875rem;font-weight:500;color:#94a3b8">per task</span></div>
        <div class="job-meta">
            <span><i class="far fa-clock"></i> <?= $job->completion_time_limit ? $job->completion_time_limit . 'h' : 'Flexible' ?></span>
            <span><i class="fas fa-users"></i> <?= max(0, $job->available_slots - $job->filled_slots) ?> slots</span>
            <span class="badge <?= get_difficulty_color($job->difficulty) ?>"><?= ucfirst($job->difficulty) ?></span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:.75rem;border-top:1px solid #f1f5f9">
            <div class="stars"><?= get_stars(round($job->rating)) ?></div>
            <a href="/jobs/<?= e($job->slug) ?>" class="btn btn-primary btn-sm">View & Apply</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if ($lastPage > 1): ?>
<div style="display:flex;justify-content:center;gap:.5rem;margin-top:2rem">
    <?php for ($i = 1; $i <= $lastPage; $i++): ?>
        <a href="/jobs/browse?page=<?= $i ?><?= $query ? '&q=' . urlencode($query) : '' ?><?= $selectedCategory ? '&category=' . $selectedCategory : '' ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-secondary' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>
<?php endif; ?>
