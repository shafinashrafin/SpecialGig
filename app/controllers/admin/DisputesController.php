<?php
class DisputesController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $status = $_GET['status'] ?? '';
        $where = "1=1";
        $params = [];
        if ($status) {
            $where .= " AND d.status = :status";
            $params['status'] = $status;
        }

        $disputes = Database::fetchAll(
            "SELECT d.*, j.title as job_title, 
                    b.username as buyer_username, w.username as worker_username
             FROM disputes d
             LEFT JOIN jobs j ON d.job_id = j.id
             LEFT JOIN users b ON d.buyer_id = b.id
             LEFT JOIN users w ON d.worker_id = w.id
             WHERE {$where}
             ORDER BY d.created_at DESC LIMIT 50",
            $params
        );

        $this->renderAdmin('admin/disputes/index', ['title' => 'Disputes', 'disputes' => $disputes]);
    }

    public function view(int $id): void
    {
        $dispute = Database::fetch("SELECT d.*, j.title as job_title, j.slug as job_slug FROM disputes d LEFT JOIN jobs j ON d.job_id = j.id WHERE d.id = :id", ['id' => $id]);
        if (!$dispute) {
            Session::setError('Dispute not found.');
            $this->redirect('/admin/disputes');
        }

        if ($this->isPost()) {
            $resolution = $this->getInput('resolution');
            $status = $this->getInput('status', 'resolved');

            Database::query("UPDATE disputes SET status = :status, resolution = :resolution, resolved_by = :admin, resolved_at = NOW() WHERE id = :id", [
                'status' => $status,
                'resolution' => $resolution,
                'admin' => Auth::id(),
                'id' => $id,
            ]);

            Notification::send($dispute->buyer_id, 'info', 'Dispute Resolved', 'Dispute for "' . $dispute->job_title . '" has been resolved.');
            Notification::send($dispute->worker_id, 'info', 'Dispute Resolved', 'Dispute for "' . $dispute->job_title . '" has been resolved.');

            Session::setSuccess('Dispute resolved.');
            $this->redirect('/admin/disputes');
        }

        $this->renderAdmin('admin/disputes/view', ['title' => 'Dispute #' . $id, 'dispute' => $dispute]);
    }
}
