<div class="page-header">
    <h1 class="page-title">Withdraw Funds</h1>
    <a href="/worker/wallet" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Wallet</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
    <div class="card">
        <div class="card-body">
            <div style="background:#eef2ff;border-radius:.75rem;padding:1rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:.75rem;font-size:.875rem">
                <i class="fas fa-info-circle" style="color:#6366f1"></i>
                Available balance: <strong><?= format_currency($wallet->balance) ?></strong>
            </div>
            <form method="POST" action="/worker/withdraw">
                <div class="form-group">
                    <label class="form-label">Amount ($)</label>
                    <input type="number" name="amount" class="form-input" step="0.01" min="<?= get_setting('min_withdrawal', 5) ?>" max="<?= min($wallet->balance, get_setting('max_withdrawal', 10000)) ?>" placeholder="0.00" required>
                    <p style="font-size:.75rem;color:#94a3b8;margin-top:.25rem">Min: $<?= get_setting('min_withdrawal', 5) ?> · Max: $<?= get_setting('max_withdrawal', 10000) ?></p>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="">Select method</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="paypal">PayPal</option>
                        <option value="stripe">Stripe</option>
                        <option value="crypto_usdt">Cryptocurrency (USDT)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Payment Details</label>
                    <textarea name="payment_details" class="form-textarea" placeholder="Enter your account details (email, wallet address, account number, etc.)" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Request Withdrawal</button>
            </form>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-header"><h3 style="font-size:1rem">Recent Withdrawals</h3></div>
            <div class="card-body">
                <?php if (empty($withdrawals)): ?>
                <p style="text-align:center;color:#94a3b8;font-size:.875rem;padding:1rem">No withdrawals yet</p>
                <?php else: ?>
                <?php foreach ($withdrawals as $w): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:.625rem 0;border-bottom:1px solid #f1f5f9">
                    <div>
                        <p style="font-weight:600;font-size:.875rem;color:#0f172a"><?= format_currency($w->amount) ?></p>
                        <p style="font-size:.75rem;color:#94a3b8"><?= e($w->payment_method) ?> · <?= time_ago($w->created_at) ?></p>
                    </div>
                    <?= get_status_badge($w->status) ?>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="card" style="margin-top:1rem">
            <div class="card-header"><h3 style="font-size:1rem">Withdrawal Info</h3></div>
            <div class="card-body" style="font-size:.8125rem;color:#64748b;line-height:1.7">
                <p style="margin-bottom:.5rem">✓ Withdrawals are processed within 24-48 hours</p>
                <p style="margin-bottom:.5rem">✓ Minimum withdrawal: $<?= get_setting('min_withdrawal', 5) ?></p>
                <p style="margin-bottom:.5rem">✓ Maximum withdrawal: $<?= get_setting('max_withdrawal', 10000) ?></p>
                <p>✓ Provide accurate payment details</p>
            </div>
        </div>
    </div>
</div>
