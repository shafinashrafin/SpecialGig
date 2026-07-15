<?php
class DashboardController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $stats = [
            'total_users' => Database::fetch("SELECT COUNT(*) as count FROM users")->count,
            'total_buyers' => Database::fetch("SELECT COUNT(*) as count FROM users WHERE role = 'buyer'")->count,
            'total_workers' => Database::fetch("SELECT COUNT(*) as count FROM users WHERE role = 'worker'")->count,
            'total_jobs' => Database::fetch("SELECT COUNT(*) as count FROM jobs")->count,
            'active_jobs' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE status = 'active'")->count,
            'pending_jobs' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE status = 'pending'")->count,
            'completed_jobs' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE status = 'completed'")->count,
            'total_deposits' => Database::fetch("SELECT COALESCE(SUM(amount), 0) as total FROM deposits WHERE status = 'completed'")->total,
            'total_withdrawals' => Database::fetch("SELECT COALESCE(SUM(amount), 0) as total FROM withdrawals WHERE status = 'completed'")->total,
            'pending_deposits' => Database::fetch("SELECT COUNT(*) as count FROM deposits WHERE status = 'pending'")->count,
            'pending_withdrawals' => Database::fetch("SELECT COUNT(*) as count FROM withdrawals WHERE status = 'pending'")->count,
            'pending_reviews' => Database::fetch("SELECT COUNT(*) as count FROM job_applications WHERE status = 'submitted'")->count,
            'open_disputes' => Database::fetch("SELECT COUNT(*) as count FROM disputes WHERE status IN ('open', 'under_review')")->count,
            'wallet_balance' => Wallet::totalBalance(),
            'pending_balance' => Wallet::totalPending(),
            'revenue' => Database::fetch("SELECT COALESCE(SUM(fee), 0) as total FROM transactions WHERE type = 'commission'")->total,
        ];

        $recentUsers = Database::fetchAll("SELECT u.*, up.full_name, up.avatar FROM users u LEFT JOIN user_profiles up ON u.id = up.user_id ORDER BY u.created_at DESC LIMIT 10");
        $recentJobs = Database::fetchAll("SELECT j.*, u.username FROM jobs j LEFT JOIN users u ON j.user_id = u.id ORDER BY j.created_at DESC LIMIT 10");
        $pendingJobs = Database::fetchAll("SELECT j.*, c.name as category_name, u.username FROM jobs j LEFT JOIN categories c ON j.category_id = c.id LEFT JOIN users u ON j.user_id = u.id WHERE j.status = 'pending' ORDER BY j.created_at DESC LIMIT 10");

        $this->renderAdmin('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentJobs' => $recentJobs,
            'pendingJobs' => $pendingJobs,
        ]);
    }
}
