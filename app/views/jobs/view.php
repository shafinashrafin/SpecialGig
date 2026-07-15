<article>
    <div class="job-detail-header">
        <div class="container">
            <p style="color:rgba(255,255,255,.5);font-size:.8125rem;margin-bottom:.5rem">
                <a href="/jobs/browse" style="color:rgba(255,255,255,.5);text-decoration:none">Browse Jobs</a> / <span style="color:rgba(255,255,255,.6)"><?= e($job->category_name) ?></span>
            </p>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem">
                <div>
                    <h1><?= e($job->title) ?></h1>
                    <div class="meta" style="display:flex;gap:1rem;flex-wrap:wrap;margin-top:.75rem">
                        <span><i class="far fa-clock"></i> Posted <?= time_ago($job->created_at) ?></span>
                        <span><i class="fas fa-users"></i> <?= $job->available_slots - $job->filled_slots ?> slots remaining</span>
                        <span><i class="fas fa-globe"></i> <?= $job->country_restriction ?: 'Worldwide' ?></span>
                        <span class="badge <?= get_difficulty_color($job->difficulty) ?>"><?= ucfirst($job->difficulty) ?></span>
                    </div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:2.5rem;font-weight:800;color:#818cf8"><?= format_currency($job->reward) ?></div>
                    <p style="color:rgba(255,255,255,.5);font-size:.875rem">per task · Budget: <?= format_currency($job->total_budget) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top:2rem">
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem">
            <div>
                <div class="card" style="margin-bottom:1.5rem">
                    <div class="card-header"><h3 style="font-size:1rem">Description</h3></div>
                    <div class="card-body" style="font-size:.875rem;color:#475569;line-height:1.7;white-space:pre-wrap">
                        <?= e($job->description) ?>
                    </div>
                </div>

                <?php if ($job->instructions): ?>
                <div class="card" style="margin-bottom:1.5rem">
                    <div class="card-header"><h3 style="font-size:1rem">Instructions</h3></div>
                    <div class="card-body" style="font-size:.875rem;color:#475569;line-height:1.7;white-space:pre-wrap">
                        <?= e($job->instructions) ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($job->proof_requirements): ?>
                <div class="card" style="margin-bottom:1.5rem">
                    <div class="card-header"><h3 style="font-size:1rem">Proof Requirements</h3></div>
                    <div class="card-body" style="font-size:.875rem;color:#475569;line-height:1.7;white-space:pre-wrap">
                        <?= e($job->proof_requirements) ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($files)): ?>
                <div class="card" style="margin-bottom:1.5rem">
                    <div class="card-header"><h3 style="font-size:1rem">Attachments</h3></div>
                    <div class="card-body">
                        <?php foreach ($files as $file): ?>
                        <a href="/public/<?= e($file->file_path) ?>" target="_blank" class="btn btn-secondary btn-sm"><i class="fas fa-download"></i> Download File</a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($reviews)): ?>
                <div class="card">
                    <div class="card-header"><h3 style="font-size:1rem">Reviews</h3></div>
                    <div class="card-body">
                        <?php foreach ($reviews as $review): ?>
                        <div style="display:flex;gap:.75rem;padding:.75rem 0;border-bottom:1px solid #f1f5f9">
                            <img src="<?= get_avatar($review, 40) ?>" alt="" class="avatar">
                            <div>
                                <div style="display:flex;align-items:center;gap:.5rem">
                                    <span style="font-weight:600;font-size:.875rem"><?= e($review->full_name ?: $review->username) ?></span>
                                    <div class="stars"><?= get_stars($review->rating) ?></div>
                                </div>
                                <?php if ($review->review): ?>
                                <p style="font-size:.8125rem;color:#64748b;margin-top:.25rem"><?= e($review->review) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div>
                <div class="card" style="position:sticky;top:6rem">
                    <div class="card-body" style="text-align:center">
                        <div style="font-size:2.5rem;font-weight:800;color:#6366f1;margin-bottom:.5rem"><?= format_currency($job->reward) ?></div>
                        <p style="color:#64748b;font-size:.875rem;margin-bottom:1rem">Per task · <?= $job->available_slots - $job->filled_slots ?> slots left</p>

                        <?php if (Auth::isWorker()): ?>
                            <?php
                            $accepted = Database::fetch("SELECT id FROM job_applications WHERE job_id = :jid AND worker_id = :wid", ['jid' => $job->id, 'wid' => Auth::id()]);
                            ?>
                            <?php if ($accepted): ?>
                                <div class="alert alert-success">You have accepted this job!</div>
                                <a href="/worker/submit-proof/<?= $accepted->id ?>" class="btn btn-primary btn-block">Submit Proof</a>
                            <?php elseif ($job->status === 'active' && $job->available_slots > $job->filled_slots): ?>
                                <a href="/jobs/accept/<?= $job->id ?>" class="btn btn-primary btn-block btn-lg" onclick="return confirm('Accept this job?')">Accept Job</a>
                            <?php else: ?>
                                <div class="alert alert-warning">This job is no longer available</div>
                            <?php endif; ?>
                        <?php elseif (Auth::isBuyer()): ?>
                            <div class="alert alert-info">This is your job. <a href="/buyer/applications" style="color:#6366f1">View applications</a></div>
                        <?php else: ?>
                            <a href="/register?role=worker" class="btn btn-primary btn-block btn-lg">Register to Apply</a>
                            <p style="font-size:.75rem;color:#94a3b8;margin-top:.5rem">Already have an account? <a href="/login" style="color:#6366f1">Login</a></p>
                        <?php endif; ?>
                    </div>

                    <div style="padding:1rem 1.5rem;border-top:1px solid #f1f5f9">
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem">
                            <img src="<?= get_avatar($job, 48) ?>" alt="" class="avatar">
                            <div>
                                <p style="font-weight:600;font-size:.875rem;color:#0f172a"><?= e($job->full_name ?: $job->username) ?></p>
                                <div style="display:flex;align-items:center;gap:.25rem">
                                    <div class="stars"><?= get_stars(round($buyerRating)) ?></div>
                                    <span style="font-size:.75rem;color:#94a3b8">(<?= $buyerJobs->total ?? 0 ?> jobs)</span>
                                </div>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:.5rem;font-size:.8125rem;color:#64748b">
                            <div style="display:flex;justify-content:space-between"><span>Approval</span><span><?= $job->is_manual_approval ? 'Manual' : 'Auto' ?></span></div>
                            <?php if ($job->completion_time_limit): ?>
                            <div style="display:flex;justify-content:space-between"><span>Time Limit</span><span><?= $job->completion_time_limit ?> hours</span></div>
                            <?php endif; ?>
                            <?php if ($job->device_restriction): ?>
                            <div style="display:flex;justify-content:space-between"><span>Device</span><span><?= ucfirst($job->device_restriction) ?></span></div>
                            <?php endif; ?>
                            <?php if ($job->browser_restriction): ?>
                            <div style="display:flex;justify-content:space-between"><span>Browser</span><span><?= ucfirst($job->browser_restriction) ?></span></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
