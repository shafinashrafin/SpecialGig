<footer class="site-footer">
    <div class="container">
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr;gap:2rem">
            <div>
                <h4 style="font-size:1.25rem;margin-bottom:1rem">Special<span style="color:#818cf8">Gig</span></h4>
                <p style="font-size:.875rem;color:rgba(255,255,255,.5);line-height:1.7;margin-bottom:1rem">Premium micro job marketplace connecting talented workers with businesses worldwide. Complete tasks, earn rewards, and grow your career.</p>
                <div style="display:flex;gap:.75rem">
                    <a href="#" style="width:2.5rem;height:2.5rem;border-radius:.5rem;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.6);text-decoration:none"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="width:2.5rem;height:2.5rem;border-radius:.5rem;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.6);text-decoration:none"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="width:2.5rem;height:2.5rem;border-radius:.5rem;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.6);text-decoration:none"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="width:2.5rem;height:2.5rem;border-radius:.5rem;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.6);text-decoration:none"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div>
                <h4>For Buyers</h4>
                <a href="/how-it-works">How It Works</a>
                <a href="/jobs/browse">Browse Jobs</a>
                <a href="/pricing">Pricing</a>
                <a href="/register">Create Account</a>
                <a href="/contact">Contact</a>
            </div>
            <div>
                <h4>For Workers</h4>
                <a href="/how-it-works">How It Works</a>
                <a href="/jobs/browse">Find Work</a>
                <a href="/worker/leaderboard">Leaderboard</a>
                <a href="/faq">FAQ</a>
                <a href="/register">Join Now</a>
            </div>
            <div>
                <h4>Company</h4>
                <a href="/page/about">About Us</a>
                <a href="/blog">Blog</a>
                <a href="/page/privacy-policy">Privacy Policy</a>
                <a href="/page/terms-of-service">Terms of Service</a>
                <a href="/page/refund-policy">Refund Policy</a>
            </div>
            <div>
                <h4>Support</h4>
                <a href="/faq">FAQ</a>
                <a href="/contact">Contact Us</a>
                <a href="/support">Support Center</a>
                <p style="font-size:.875rem;color:rgba(255,255,255,.5);margin-top:1rem">
                    <i class="fas fa-envelope" style="margin-right:.5rem"></i> <?= e(get_setting('site_email', 'support@specialgig.com')) ?>
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <span><?= e(get_setting('footer_text', '© 2026 SpecialGig. All rights reserved.')) ?></span>
            <div style="display:flex;gap:1rem">
                <span><i class="fab fa-cc-visa" style="font-size:1.5rem;opacity:.5"></i></span>
                <span><i class="fab fa-cc-mastercard" style="font-size:1.5rem;opacity:.5"></i></span>
                <span><i class="fab fa-cc-paypal" style="font-size:1.5rem;opacity:.5"></i></span>
                <span><i class="fab fa-cc-stripe" style="font-size:1.5rem;opacity:.5"></i></span>
            </div>
        </div>
    </div>
</footer>
