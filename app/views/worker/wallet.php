<div class="page-header">
    <h1 class="page-title">My Wallet</h1>
    <a href="/worker/withdraw" class="btn btn-primary"><i class="fas fa-arrow-up"></i> Withdraw</a>
</div>

<div class="wallet-overview">
    <div class="wallet-card">
        <p style="color:rgba(255,255,255,.7);font-size:.8125rem">Available Balance</p>
        <div class="balance"><?= format_currency($wallet->balance) ?></div>
        <a href="/worker/withdraw" style="display:inline-block;margin-top:1rem;color:#fff;font-size:.875rem;text-decoration:underline;opacity:.8">Withdraw funds</a>
    </div>
    <div class="stat-card">
        <div style="display:flex;justify-content:space-between;width:100%">
            <div>
                <div class="stat-value"><?= format_currency($wallet->pending_balance) ?></div>
                <div class="stat-label">Pending Balance</div>
            </div>
            <i class="fas fa-clock" style="color:#f59e0b;font-size:1.5rem"></i>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;justify-content:space-between;width:100%">
            <div>
                <div class="stat-value"><?= format_currency($wallet->total_earned) ?></div>
                <div class="stat-label">Total Earned</div>
            </div>
            <i class="fas fa-arrow-down" style="color:#10b981;font-size:1.5rem"></i>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;justify-content:space-between;width:100%">
            <div>
                <div class="stat-value"><?= format_currency($wallet->referral_earnings) ?></div>
                <div class="stat-label">Referral Earnings</div>
            </div>
            <i class="fas fa-gift" style="color:#8b5cf6;font-size:1.5rem"></i>
        </div>
    </div>
</div>

<div class="card" style="margin-top:1.5rem">
    <div class="card-header">
        <h3 style="font-size:1rem">Transaction History</h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                <tr><td colspan="5" style="text-align:center;padding:2rem;color:#94a3b8">No transactions yet</td></tr>
                <?php else: ?>
                <?php foreach ($transactions as $t): ?>
                <tr>
                    <td style="white-space:nowrap;font-size:.8125rem"><?= format_date($t->created_at) ?></td>
                    <td><span class="badge" style="background:#eef2ff;color:#6366f1"><?= ucfirst($t->type) ?></span></td>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.8125rem"><?= e($t->description) ?></td>
                    <td style="font-weight:600;color:<?= $t->amount >= 0 ? '#10b981' : '#ef4444' ?>"><?= $t->amount >= 0 ? '+' : '' ?><?= format_currency($t->amount) ?></td>
                    <td><?= get_status_badge($t->status) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
