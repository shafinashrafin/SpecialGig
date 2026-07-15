<div class="page-header">
    <h1 class="page-title">Submit Proof</h1>
    <a href="/worker/my-tasks" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
    <div class="card">
        <div class="card-header">
            <h3 style="font-size:1rem"><?= e($application->job_title) ?></h3>
        </div>
        <div class="card-body">
            <div style="background:#f8fafc;border-radius:.75rem;padding:1.25rem;margin-bottom:1.5rem">
                <p style="font-weight:600;font-size:.875rem;margin-bottom:.5rem">Instructions</p>
                <p style="font-size:.875rem;color:#475569;white-space:pre-wrap"><?= e($application->instructions ?: 'Follow the job description to complete this task.') ?></p>
            </div>

            <div style="background:#eef2ff;border-radius:.75rem;padding:1.25rem;margin-bottom:1.5rem">
                <p style="font-weight:600;font-size:.875rem;color:#6366f1;margin-bottom:.5rem">Proof Requirements</p>
                <p style="font-size:.875rem;color:#475569;white-space:pre-wrap"><?= e($application->proof_requirements ?: 'Provide a screenshot or link as proof of completion.') ?></p>
            </div>

            <form method="POST" action="/worker/submit-proof/<?= $application->id ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Proof Details</label>
                    <textarea name="proof" class="form-textarea" placeholder="Describe what you did to complete the task and provide any relevant links or details..." required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Proof Files (Screenshots)</label>
                    <input type="file" name="proof_files[]" multiple class="form-input" accept="image/*,.pdf">
                    <p style="font-size:.75rem;color:#94a3b8;margin-top:.25rem">Upload screenshots or documents as proof</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Additional Notes</label>
                    <textarea name="notes" class="form-textarea" placeholder="Any additional information for the buyer..." style="min-height:80px"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Submit Proof</button>
            </form>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-header"><h3 style="font-size:1rem">Task Info</h3></div>
            <div class="card-body" style="font-size:.875rem;color:#64748b">
                <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9">
                    <span>Reward</span>
                    <span style="font-weight:700;color:#6366f1"><?= format_currency($application->reward) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9">
                    <span>Time Limit</span>
                    <span><?= $application->completion_time_limit ? $application->completion_time_limit . ' hours' : 'No limit' ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f1f5f9">
                    <span>Status</span>
                    <?= get_status_badge($application->status) ?>
                </div>
            </div>
        </div>
        <div class="card" style="margin-top:1rem">
            <div class="card-header"><h3 style="font-size:1rem">Tips</h3></div>
            <div class="card-body" style="font-size:.8125rem;color:#64748b;line-height:1.7">
                <p style="margin-bottom:.5rem">✓ Read the instructions carefully</p>
                <p style="margin-bottom:.5rem">✓ Provide clear proof of completion</p>
                <p style="margin-bottom:.5rem">✓ Add screenshots when possible</p>
                <p>✓ Write a note explaining what you did</p>
            </div>
        </div>
    </div>
</div>
