<div style="min-height:calc(100vh - 200px);display:flex;align-items:center;justify-content:center;padding:2rem 1rem">
    <div style="width:100%;max-width:440px;background:#fff;border-radius:1.5rem;border:1px solid #e2e8f0;box-shadow:0 25px 80px rgba(0,0,0,.08);padding:2.5rem">
        <div style="text-align:center;margin-bottom:2rem">
            <a href="/" style="font-size:1.75rem;font-weight:800;color:#0f172a;text-decoration:none">Special<span style="color:#6366f1">Gig</span></a>
            <h2 style="margin-top:1.5rem;font-size:1.5rem">Welcome Back</h2>
            <p style="color:#64748b;font-size:.875rem;margin-top:.25rem">Sign in to your account</p>
        </div>
        <form method="POST" action="/login">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" placeholder="you@example.com" required value="<?= e(old('email')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Enter your password" required>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
                <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:#64748b;cursor:pointer">
                    <input type="checkbox" name="remember" class="form-checkbox"> Remember me
                </label>
                <a href="/forgot-password" style="font-size:.875rem;color:#6366f1;text-decoration:none;font-weight:600">Forgot password?</a>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Sign In</button>
        </form>
        <p style="text-align:center;margin-top:1.5rem;font-size:.875rem;color:#64748b">
            Don't have an account? <a href="/register" style="color:#6366f1;font-weight:600;text-decoration:none">Register</a>
        </p>
    </div>
</div>
