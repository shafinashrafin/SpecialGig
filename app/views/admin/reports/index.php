<div class="page-header">
    <h1 class="page-title" style="color:#fff">Reports & Analytics</h1>
</div>

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem">
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Users (Last 30 Days)</h3></div>
        <div class="table-container">
            <table>
                <thead><tr><th>Date</th><th>Total</th><th>Buyers</th><th>Workers</th></tr></thead>
                <tbody>
                    <?php foreach ($reports['daily_users'] as $r): ?>
                    <tr><td><?= $r->date ?></td><td><?= $r->count ?></td><td><?= $r->buyers ?></td><td><?= $r->workers ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Revenue (Last 30 Days)</h3></div>
        <div class="table-container">
            <table>
                <thead><tr><th>Date</th><th>Revenue</th><th>Deposits</th><th>Withdrawals</th></tr></thead>
                <tbody>
                    <?php foreach ($reports['daily_revenue'] as $r): ?>
                    <tr><td><?= $r->date ?></td><td><?= format_currency($r->revenue) ?></td><td><?= format_currency($r->deposits) ?></td><td><?= format_currency($r->withdrawals) ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Top Countries</h3></div>
        <div class="table-container">
            <table>
                <thead><tr><th>Country</th><th>Users</th></tr></thead>
                <tbody>
                    <?php foreach ($reports['top_countries'] as $r): ?>
                    <tr><td><?= e($r->country) ?></td><td><?= $r->count ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3 style="font-size:1rem">Job Statistics</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div style="text-align:center;padding:1rem;background:#f8fafc;border-radius:.75rem">
                    <p style="font-size:1.5rem;font-weight:800;color:#6366f1"><?= $reports['job_stats']['total'] ?></p>
                    <p style="font-size:.8125rem;color:#64748b">Total Jobs</p>
                </div>
                <div style="text-align:center;padding:1rem;background:#f8fafc;border-radius:.75rem">
                    <p style="font-size:1.5rem;font-weight:800;color:#10b981"><?= $reports['job_stats']['active'] ?></p>
                    <p style="font-size:.8125rem;color:#64748b">Active Jobs</p>
                </div>
                <div style="text-align:center;padding:1rem;background:#f8fafc;border-radius:.75rem">
                    <p style="font-size:1.5rem;font-weight:800;color:#f59e0b"><?= $reports['job_stats']['pending'] ?></p>
                    <p style="font-size:.8125rem;color:#64748b">Pending Jobs</p>
                </div>
                <div style="text-align:center;padding:1rem;background:#f8fafc;border-radius:.75rem">
                    <p style="font-size:1.5rem;font-weight:800;color:#3b82f6"><?= $reports['job_stats']['completed'] ?></p>
                    <p style="font-size:.8125rem;color:#64748b">Completed Jobs</p>
                </div>
            </div>
        </div>
    </div>
</div>
