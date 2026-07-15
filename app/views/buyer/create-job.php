<div class="page-header">
    <div>
        <h1 class="page-title">Create New Job</h1>
        <p style="color:#64748b;font-size:.875rem">Post a new micro job for workers</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
    <div class="card">
        <div class="card-body">
            <div style="background:#fef3c7;border-radius:.75rem;padding:1rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:.75rem;font-size:.875rem;border:1px solid #fde68a">
                <i class="fas fa-info-circle" style="color:#f59e0b"></i>
                <span>Your balance: <strong><?= format_currency($wallet->balance) ?></strong>. The total budget will be deducted from your wallet.</span>
            </div>
            <form method="POST" action="/buyer/create-job" enctype="multipart/form-data">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem">
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Job Title <span class="required">*</span></label>
                        <input type="text" name="title" class="form-input" placeholder="e.g. Like my Facebook page" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category <span class="required">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>"><?= e($cat->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Difficulty</label>
                        <select name="difficulty" class="form-select">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Description <span class="required">*</span></label>
                        <textarea name="description" class="form-textarea" placeholder="Describe what the worker needs to do" required></textarea>
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Step-by-Step Instructions</label>
                        <textarea name="instructions" class="form-textarea" placeholder="Provide detailed instructions for the worker"></textarea>
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Proof Requirements</label>
                        <textarea name="proof_requirements" class="form-textarea" placeholder="What proof should the worker submit? (e.g. screenshot, link)"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reward Per Task ($) <span class="required">*</span></label>
                        <input type="number" name="reward" class="form-input" step="0.01" min="0.01" placeholder="0.50" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Available Slots <span class="required">*</span></label>
                        <input type="number" name="available_slots" class="form-input" min="1" value="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country Restriction</label>
                        <input type="text" name="country_restriction" class="form-input" placeholder="e.g. United States">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Device Restriction</label>
                        <select name="device_restriction" class="form-select">
                            <option value="">Any device</option>
                            <option value="mobile">Mobile</option>
                            <option value="desktop">Desktop</option>
                            <option value="tablet">Tablet</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Browser Restriction</label>
                        <select name="browser_restriction" class="form-select">
                            <option value="">Any browser</option>
                            <option value="chrome">Chrome</option>
                            <option value="firefox">Firefox</option>
                            <option value="safari">Safari</option>
                            <option value="edge">Edge</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Completion Time Limit (hours)</label>
                        <input type="number" name="completion_time_limit" class="form-input" min="0" placeholder="24">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Approval Time Limit (hours)</label>
                        <input type="number" name="approval_time_limit" class="form-input" min="0" placeholder="48">
                    </div>
                </div>

                <div style="margin:1.5rem 0;padding:1.25rem;background:#f8fafc;border-radius:.75rem;border:1px solid #e2e8f0">
                    <p style="font-weight:600;font-size:.875rem;margin-bottom:1rem">Job Settings</p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;cursor:pointer">
                            <input type="checkbox" name="is_manual_approval" class="form-checkbox" checked> Manual Approval
                        </label>
                        <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;cursor:pointer">
                            <input type="checkbox" name="is_hidden" class="form-checkbox"> Hidden Job
                        </label>
                        <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;cursor:pointer">
                            <input type="checkbox" name="is_featured" class="form-checkbox"> Featured Job
                        </label>
                        <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;cursor:pointer">
                            <input type="checkbox" name="is_urgent" class="form-checkbox"> Urgent Job
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Attachments</label>
                    <input type="file" name="attachments[]" multiple class="form-input" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                    <p style="font-size:.75rem;color:#94a3b8;margin-top:.25rem">Allowed: JPG, PNG, PDF, DOC. Max 5MB each.</p>
                </div>

                <div style="display:flex;gap:.75rem;margin-top:1.5rem">
                    <button type="submit" class="btn btn-primary btn-lg">Create Job</button>
                    <a href="/buyer/my-jobs" class="btn btn-secondary btn-lg">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-header"><h3 style="font-size:1rem">Job Tips</h3></div>
            <div class="card-body" style="font-size:.8125rem;color:#64748b;line-height:1.7">
                <p style="margin-bottom:.75rem"><strong style="color:#0f172a">Clear Instructions</strong><br>Workers perform better with clear, step-by-step instructions.</p>
                <p style="margin-bottom:.75rem"><strong style="color:#0f172a">Fair Reward</strong><br>Set a reward that matches the effort required for the task.</p>
                <p style="margin-bottom:.75rem"><strong style="color:#0f172a">Proof Requirements</strong><br>Specify exactly what proof workers need to submit.</p>
                <p><strong style="color:#0f172a">Quick Review</strong><br>Review submitted proofs promptly to keep workers engaged.</p>
            </div>
        </div>
    </div>
</div>
