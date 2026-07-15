<div class="page-header">
    <h1 class="page-title">My Wallet</h1>
    <a href="/buyer/deposit" class="btn btn-primary"><i class="fas fa-plus"></i> Deposit Funds</a>
</div>

<div class="wallet-overview">
    <div class="wallet-card">
        <p style="color:rgba(255,255,255,.7);font-size:.8125rem">Available Balance</p>
        <div class="balance"><?= format_currency($wallet->balance) ?></div>
    </div>
    <div class="stat-card">
        <div style="display:flex;justify-content:space-between;width:100%">
            <div>
                <div class="stat-value"><?= format_currency($wallet->total_deposited) ?></div>
                <div class="stat-label">Total Deposited</div>
            </div>
            <i class="fas fa-arrow-down" style="color:#10b981;font-size:1.5rem"></i>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;justify-content:space-between;width:100%">
            <div>
                <div class="stat-value"><?= format_currency($wallet->total_earned) ?></div>
                <div class="stat-label">Total Spent</div>
            </div>
            <i class="fas fa-arrow-up" style="color:#ef4444;font-size:1.5rem"></i>
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
                    <td style="white-space:nowrap"><?= format_date($t->created_at) ?></td>
                    <td><span class="badge" style="background:#eef2ff;color:#6366f1"><?= ucfirst($t->type) ?></span></td>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($t->description) ?></td>
                    <td style="font-weight:600;color:<?= $t->amount >= 0 ? '#10b981' : '#ef4444' ?>"><?= $t->amount >= 0 ? '+' : '' ?><?= format_currency($t->amount) ?></td>
                    <td><?= get_status_badge($t->status) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
