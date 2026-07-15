<div class="page-header">
    <h1 class="page-title">Referral Program</h1>
</div>

<div class="referral-box" style="margin-bottom:2rem">
    <h3 style="color:#fff;font-size:1.5rem;margin-bottom:.5rem">Invite Friends, Earn Rewards</h3>
    <p style="color:rgba(255,255,255,.7);margin-bottom:1rem">Share your referral link and earn <?= format_currency(get_setting('referral_bonus', 1)) ?> for each friend who joins!</p>
    <div style="margin:1.5rem 0">
        <p style="font-size:.8125rem;color:rgba(255,255,255,.6);margin-bottom:.5rem">Your Referral Code</p>
        <div class="referral-code" onclick="copyReferralCode()" id="referralCode">
            <span><?= e($referralCode) ?></span>
            <i class="fas fa-copy" style="font-size:.875rem;opacity:.7"></i>
        </div>
        <p style="font-size:.75rem;color:rgba(255,255,255,.5);margin-top:.5rem">Click to copy your referral code</p>
    </div>
    <div style="display:flex;justify-content:center;gap:.5rem;flex-wrap:wrap">
        <?php
        $referralUrl = url('/register?ref=' . $referralCode);
        $encodedUrl = urlencode($referralUrl);
        $shareText = urlencode('Join SpecialGig and start earning! Use my referral code: ' . $referralCode);
        ?>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" target="_blank" class="btn" style="background:#1877f2;color:#fff"><i class="fab fa-facebook-f"></i> Share</a>
        <a href="https://twitter.com/intent/tweet?text=<?= $shareText ?>&url=<?= $encodedUrl ?>" target="_blank" class="btn" style="background:#1da1f2;color:#fff"><i class="fab fa-twitter"></i> Tweet</a>
        <a href="https://wa.me/?text=<?= $shareText ?>%20<?= $encodedUrl ?>" target="_blank" class="btn" style="background:#25d366;color:#fff"><i class="fab fa-whatsapp"></i> WhatsApp</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1fae5;color:#10b981"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-value"><?= $referralCount->count ?? 0 ?></div>
            <div class="stat-label">Total Referrals</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#ede9fe;color:#8b5cf6"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="stat-value"><?= format_currency($referralCount->total_reward ?? 0) ?></div>
            <div class="stat-label">Referral Earnings</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#f59e0b"><i class="fas fa-trophy"></i></div>
        <div>
            <div class="stat-value"><?= format_currency($wallet->referral_earnings) ?></div>
            <div class="stat-label">Total Paid Out</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Your Referrals</h3></div>
        <div class="card-body">
            <?php if (empty($referrals)): ?>
            <p style="text-align:center;color:#94a3b8;padding:1.5rem 0">No referrals yet. Share your code!</p>
            <?php else: ?>
            <?php foreach ($referrals as $ref): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:.625rem 0;border-bottom:1px solid #f1f5f9">
                <div style="display:flex;align-items:center;gap:.5rem">
                    <img src="<?= get_avatar($ref, 32) ?>" alt="" class="avatar avatar-sm">
                    <div>
                        <p style="font-weight:600;font-size:.8125rem;color:#0f172a"><?= e($ref->full_name ?: $ref->username) ?></p>
                        <p style="font-size:.6875rem;color:#94a3b8">Joined <?= time_ago($ref->joined_date) ?></p>
                    </div>
                </div>
                <span style="font-weight:600;color:#6366f1;font-size:.875rem">+<?= format_currency($ref->reward) ?></span>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Top Referrers</h3></div>
        <div class="card-body">
            <?php if (empty($topReferrers)): ?>
            <p style="text-align:center;color:#94a3b8;padding:1.5rem 0">Be the first to refer!</p>
            <?php else: ?>
            <?php foreach ($topReferrers as $i => $r): ?>
            <div style="display:flex;align-items:center;gap:.75rem;padding:.5rem 0;border-bottom:1px solid #f1f5f9">
                <span style="width:1.5rem;font-weight:800;font-size:.875rem;color:<?= $i === 0 ? '#f59e0b' : ($i === 1 ? '#94a3b8' : ($i === 2 ? '#b45309' : '#cbd5e1')) ?>">#<?= $i + 1 ?></span>
                <img src="<?= get_avatar($r, 28) ?>" alt="" class="avatar avatar-sm">
                <span style="flex:1;font-size:.8125rem;font-weight:600;color:#0f172a"><?= e($r->full_name ?: $r->username) ?></span>
                <span style="font-size:.8125rem;color:#6366f1;font-weight:600"><?= $r->referral_count ?> refs</span>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function copyReferralCode() {
    const code = document.getElementById('referralCode').querySelector('span').textContent;
    navigator.clipboard.writeText(code).then(() => {
        alert('Referral code copied!');
    });
}
</script>
