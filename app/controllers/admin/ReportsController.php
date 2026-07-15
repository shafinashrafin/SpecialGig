<?php
class ReportsController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $reports = [
            'daily_users' => Database::fetchAll(
                "SELECT DATE(created_at) as date, COUNT(*) as count, 
                        SUM(CASE WHEN role = 'buyer' THEN 1 ELSE 0 END) as buyers,
                        SUM(CASE WHEN role = 'worker' THEN 1 ELSE 0 END) as workers
                 FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                 GROUP BY DATE(created_at) ORDER BY date DESC"
            ),
            'daily_jobs' => Database::fetchAll(
                "SELECT DATE(created_at) as date, COUNT(*) as count,
                        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active
                 FROM jobs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                 GROUP BY DATE(created_at) ORDER BY date DESC"
            ),
            'daily_revenue' => Database::fetchAll(
                "SELECT DATE(created_at) as date, COALESCE(SUM(fee), 0) as revenue,
                        COALESCE(SUM(CASE WHEN type = 'deposit' THEN amount ELSE 0 END), 0) as deposits,
                        COALESCE(SUM(CASE WHEN type = 'withdrawal' THEN amount ELSE 0 END), 0) as withdrawals
                 FROM transactions WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND status = 'completed'
                 GROUP BY DATE(created_at) ORDER BY date DESC"
            ),
            'top_countries' => Database::fetchAll(
                "SELECT country, COUNT(*) as count FROM user_profiles 
                 WHERE country IS NOT NULL AND country != ''
                 GROUP BY country ORDER BY count DESC LIMIT 10"
            ),
            'job_stats' => Job::stats(),
        ];

        $this->renderAdmin('admin/reports/index', ['title' => 'Reports & Analytics', 'reports' => $reports]);
    }
}
