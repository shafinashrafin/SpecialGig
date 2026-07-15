<div class="page-header">
    <h1 class="page-title" style="color:#fff">Coupons</h1>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Create Coupon</h3></div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-input" placeholder="e.g. WELCOME10" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Discount Type</label>
                        <select name="discount_type" class="form-select">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Value</label>
                        <input type="number" name="discount_value" class="form-input" step="0.01" required>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label">Min Amount</label>
                        <input type="number" name="min_amount" class="form-input" step="0.01">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Uses</label>
                        <input type="number" name="max_uses" class="form-input" value="100">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Expires At</label>
                    <input type="date" name="expires_at" class="form-input">
                </div>
                <button type="submit" class="btn btn-primary">Create Coupon</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">All Coupons</h3></div>
        <div class="table-container">
            <table>
                <thead><tr><th>Code</th><th>Discount</th><th>Uses</th><th>Expires</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($coupons as $c): ?>
                    <tr>
                        <td style="font-weight:700;font-family:monospace"><?= e($c->code) ?></td>
                        <td><?= $c->discount_type === 'percentage' ? $c->discount_value . '%' : format_currency($c->discount_value) ?></td>
                        <td><?= $c->used_count ?>/<?= $c->max_uses ?: '∞' ?></td>
                        <td style="font-size:.8125rem"><?= $c->expires_at ? format_date($c->expires_at) : 'Never' ?></td>
                        <td><?= get_status_badge($c->status) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
