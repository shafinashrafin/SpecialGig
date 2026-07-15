<div style="min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;padding:2rem 1rem">
    <div style="width:100%;max-width:520px;background:#fff;border-radius:1.5rem;border:1px solid #e2e8f0;box-shadow:0 25px 80px rgba(0,0,0,.08);padding:2.5rem">
        <div style="text-align:center;margin-bottom:2rem">
            <a href="/" style="font-size:1.75rem;font-weight:800;color:#0f172a;text-decoration:none">Special<span style="color:#6366f1">Gig</span></a>
            <h2 style="margin-top:1.5rem;font-size:1.5rem">Create Account</h2>
            <p style="color:#64748b;font-size:.875rem;margin-top:.25rem">Join as a Buyer or Worker</p>
        </div>
        <form method="POST" action="/register">
            <div style="display:flex;gap:1rem;margin-bottom:1.25rem">
                <label style="flex:1;padding:.75rem;border:2px solid #e2e8f0;border-radius:.75rem;display:flex;align-items:center;gap:.5rem;cursor:pointer;transition:all .2s;font-size:.875rem;font-weight:500" onclick="document.querySelectorAll('.role-option').forEach(e=>e.style.borderColor='#e2e8f0');this.style.borderColor='#6366f1';document.getElementById('role').value='buyer'">
                    <input type="radio" name="role" value="buyer" class="role-radio" checked style="accent-color:#6366f1"> <i class="fas fa-user-tie" style="color:#6366f1"></i> I'm a Buyer
                </label>
                <label style="flex:1;padding:.75rem;border:2px solid #e2e8f0;border-radius:.75rem;display:flex;align-items:center;gap:.5rem;cursor:pointer;transition:all .2s;font-size:.875rem;font-weight:500" onclick="document.querySelectorAll('.role-option').forEach(e=>e.style.borderColor='#e2e8f0');this.style.borderColor='#6366f1';document.getElementById('role').value='worker'">
                    <input type="radio" name="role" value="worker" class="role-radio" style="accent-color:#6366f1"> <i class="fas fa-user-cog" style="color:#6366f1"></i> I'm a Worker
                </label>
            </div>
            <input type="hidden" name="role" id="role" value="buyer">
            <?php if ($ref): ?>
                <input type="hidden" name="ref" value="<?= e($ref) ?>">
            <?php endif; ?>
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" placeholder="Choose a username" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" placeholder="you@example.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="At least 6 characters" required minlength="6">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm your password" required>
            </div>
            <label style="display:flex;align-items:flex-start;gap:.5rem;font-size:.8125rem;color:#64748b;cursor:pointer;margin-bottom:1.5rem">
                <input type="checkbox" name="agree" class="form-checkbox" required style="margin-top:.125rem">
                I agree to the <a href="/page/terms-of-service" style="color:#6366f1;text-decoration:none">Terms of Service</a> and <a href="/page/privacy-policy" style="color:#6366f1;text-decoration:none">Privacy Policy</a>
            </label>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Create Account</button>
        </form>
        <p style="text-align:center;margin-top:1.5rem;font-size:.875rem;color:#64748b">
            Already have an account? <a href="/login" style="color:#6366f1;font-weight:600;text-decoration:none">Sign In</a>
        </p>
    </div>
</div>
