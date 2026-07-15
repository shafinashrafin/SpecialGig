<section style="padding:4rem 0">
    <div class="container" style="max-width:800px">
        <div style="text-align:center;margin-bottom:3rem">
            <h1 style="font-size:2.5rem;margin-bottom:.5rem">Simple Pricing</h1>
            <p style="color:#64748b">Transparent pricing with no hidden fees</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem">
            <div class="pricing-card">
                <h3 style="font-size:1.25rem;margin-bottom:.5rem">Free</h3>
                <div class="pricing-price">$0</div>
                <p class="pricing-period">Forever</p>
                <ul style="list-style:none;padding:1.5rem 0;text-align:left;font-size:.875rem;color:#64748b;line-height:2">
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Account Registration</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Browse Jobs</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Basic Support</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Email Notifications</li>
                </ul>
                <a href="/register" class="btn btn-outline btn-block">Get Started</a>
            </div>
            <div class="pricing-card featured">
                <div style="background:#6366f1;color:#fff;display:inline-block;padding:.25rem 1rem;border-radius:9999px;font-size:.75rem;font-weight:600;margin-bottom:1rem">Most Popular</div>
                <h3 style="font-size:1.25rem;margin-bottom:.5rem">Standard</h3>
                <div class="pricing-price"><?= $commission ?>%</div>
                <p class="pricing-period">Commission per task</p>
                <ul style="list-style:none;padding:1.5rem 0;text-align:left;font-size:.875rem;color:#64748b;line-height:2">
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> All Free Features</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Create Unlimited Jobs</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Accept Unlimited Tasks</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Priority Support</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Advanced Analytics</li>
                </ul>
                <a href="/register" class="btn btn-primary btn-block">Get Started</a>
            </div>
            <div class="pricing-card">
                <h3 style="font-size:1.25rem;margin-bottom:.5rem">Enterprise</h3>
                <div class="pricing-price">Custom</div>
                <p class="pricing-period">Contact us</p>
                <ul style="list-style:none;padding:1.5rem 0;text-align:left;font-size:.875rem;color:#64748b;line-height:2">
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> All Standard Features</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Dedicated Account Manager</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> Custom Integration</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> API Access</li>
                    <li><i class="fas fa-check" style="color:#10b981;margin-right:.5rem"></i> SLA Guarantee</li>
                </ul>
                <a href="/contact" class="btn btn-outline btn-block">Contact Us</a>
            </div>
        </div>
    </div>
</section>
