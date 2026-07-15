<?php
class LogsController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 50;
        $offset = ($page - 1) * $perPage;

        $total = Database::fetch("SELECT COUNT(*) as count FROM activity_logs");
        $logs = Database::fetchAll(
            "SELECT al.*, u.username FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );

        $this->renderAdmin('admin/logs/index', [
            'title' => 'Activity Logs',
            'logs' => $logs,
            'total' => $total->count ?? 0,
            'page' => $page,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
        ]);
    }
}
