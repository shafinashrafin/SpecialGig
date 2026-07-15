<div class="page-header">
    <h1 class="page-title">Review Proof</h1>
    <a href="/buyer/applications" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
    <div class="card">
        <div class="card-header">
            <h3 style="font-size:1rem">Submitted Proof</h3>
        </div>
        <div class="card-body">
            <div style="background:#f8fafc;border-radius:.75rem;padding:1.25rem;margin-bottom:1rem">
                <p style="font-weight:600;font-size:.875rem;margin-bottom:.5rem">Proof Details</p>
                <p style="font-size:.875rem;color:#475569;white-space:pre-wrap"><?= e($application->proof ?: 'No text proof provided') ?></p>
            </div>

            <?php if ($application->proof_files): ?>
            <div style="margin-bottom:1rem">
                <p style="font-weight:600;font-size:.875rem;margin-bottom:.5rem">Attached Files</p>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem">
                    <?php foreach (explode(',', $application->proof_files) as $file): ?>
                    <a href="/public/<?= e($file) ?>" target="_blank" class="btn btn-secondary btn-sm">
                        <i class="fas fa-file"></i> View File
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($application->worker_notes): ?>
            <div style="background:#eef2ff;border-radius:.75rem;padding:1rem;margin-bottom:1rem">
                <p style="font-weight:600;font-size:.8125rem;color:#6366f1;margin-bottom:.25rem">Worker Notes</p>
                <p style="font-size:.875rem;color:#475569"><?= e($application->worker_notes) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-header"><h3 style="font-size:1rem">Worker Info</h3></div>
            <div class="card-body" style="text-align:center">
                <img src="<?= get_avatar($worker, 96) ?>" alt="" style="width:5rem;height:5rem;border-radius:50%;margin:0 auto .75rem">
                <h4 style="font-size:1rem"><?= e($worker->full_name ?: $worker->username) ?></h4>
                <?php if ($worker->country): ?>
                    <p style="font-size:.8125rem;color:#64748b"><i class="fas fa-map-marker-alt"></i> <?= e($worker->country) ?></p>
                <?php endif; ?>
                <div class="stars" style="justify-content:center;margin:.5rem 0"><?= get_stars(round(Review::averageRating($worker->id))) ?></div>
                <p style="font-size:.8125rem;color:#64748b"><?= Review::ratingCount($worker->id) ?> reviews</p>
            </div>
        </div>

        <div class="card" style="margin-top:1rem">
            <div class="card-header"><h3 style="font-size:1rem">Actions</h3></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:.75rem">
                <form method="POST" action="/buyer/approve-proof/<?= $application->id ?>">
                    <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Approve this proof and release payment?')">
                        <i class="fas fa-check"></i> Approve & Release Payment
                    </button>
                </form>
                <form method="POST" action="/buyer/reject-proof/<?= $application->id ?>">
                    <div class="form-group" style="margin-bottom:.5rem">
                        <textarea name="rejection_reason" class="form-textarea" placeholder="Reason for rejection..." style="min-height:80px" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Reject this proof?')">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
