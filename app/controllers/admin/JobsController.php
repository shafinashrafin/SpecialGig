<?php
class JobsController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $status = $_GET['status'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $where = "1=1";
        $params = [];

        if ($status) {
            $where .= " AND j.status = :status";
            $params['status'] = $status;
        }

        $total = Database::fetch("SELECT COUNT(*) as count FROM jobs j WHERE {$where}", $params);
        $jobs = Database::fetchAll(
            "SELECT j.*, c.name as category_name, u.username, up.full_name
             FROM jobs j
             LEFT JOIN categories c ON j.category_id = c.id
             LEFT JOIN users u ON j.user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE {$where}
             ORDER BY j.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $this->renderAdmin('admin/jobs/index', [
            'title' => 'Manage Jobs',
            'jobs' => $jobs,
            'total' => $total->count ?? 0,
            'page' => $page,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
            'currentStatus' => $status,
        ]);
    }

    public function view(int $id): void
    {
        $job = Database::fetch(
            "SELECT j.*, c.name as category_name, u.username, up.full_name, up.avatar
             FROM jobs j
             LEFT JOIN categories c ON j.category_id = c.id
             LEFT JOIN users u ON j.user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE j.id = :id",
            ['id' => $id]
        );

        if (!$job) {
            Session::setError('Job not found.');
            $this->redirect('/admin/jobs');
        }

        $files = Database::fetchAll("SELECT * FROM job_files WHERE job_id = :id", ['id' => $id]);
        $applications = Database::fetchAll(
            "SELECT ja.*, u.username, up.full_name, up.avatar
             FROM job_applications ja
             LEFT JOIN users u ON ja.worker_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE ja.job_id = :id ORDER BY ja.created_at DESC",
            ['id' => $id]
        );

        $this->renderAdmin('admin/jobs/view', [
            'title' => 'Job: ' . $job->title,
            'job' => $job,
            'files' => $files,
            'applications' => $applications,
        ]);
    }

    public function approve(int $id): void
    {
        Database::query(
            "UPDATE jobs SET status = 'active', approved_at = NOW() WHERE id = :id AND status = 'pending'",
            ['id' => $id]
        );

        $job = Database::fetch("SELECT * FROM jobs WHERE id = :id", ['id' => $id]);
        if ($job) {
            Notification::send($job->user_id, 'success', 'Job Approved', 'Your job "' . $job->title . '" has been approved and is now live!', '/buyer/my-jobs');
        }

        Session::setSuccess('Job approved.');
        $this->redirect('/admin/jobs');
    }

    public function reject(int $id): void
    {
        $reason = $this->getInput('reason', 'Does not meet our guidelines.');

        $job = Database::fetch("SELECT * FROM jobs WHERE id = :id AND status = 'pending'", ['id' => $id]);
        if ($job) {
            Wallet::addBalance($job->user_id, $job->total_budget, 'refund', 'Refund for rejected job: ' . $job->title);
            Database::query(
                "UPDATE jobs SET status = 'rejected', rejection_reason = :reason WHERE id = :id",
                ['reason' => $reason, 'id' => $id]
            );
            Notification::send($job->user_id, 'error', 'Job Rejected', 'Your job "' . $job->title . '" was rejected. Reason: ' . $reason, '/buyer/my-jobs');
            Session::setSuccess('Job rejected and budget refunded.');
        }

        $this->redirect('/admin/jobs');
    }

    public function delete(int $id): void
    {
        $job = Database::fetch("SELECT * FROM jobs WHERE id = :id", ['id' => $id]);
        if ($job && in_array($job->status, ['pending', 'active', 'paused'])) {
            Wallet::addBalance($job->user_id, $job->total_budget, 'refund', 'Refund for deleted job: ' . $job->title);
        }
        Database::delete('jobs', 'id = :id', ['id' => $id]);
        Session::setSuccess('Job deleted.');
        $this->redirect('/admin/jobs');
    }

    public function edit(int $id): void
    {
        $job = Database::fetch("SELECT * FROM jobs WHERE id = :id", ['id' => $id]);
        if (!$job) {
            Session::setError('Job not found.');
            $this->redirect('/admin/jobs');
        }

        if ($this->isPost()) {
            $updates = [
                'title' => $this->getInput('title'),
                'category_id' => (int) $this->getInput('category_id'),
                'description' => $this->getInput('description'),
                'reward' => (float) $this->getInput('reward'),
                'available_slots' => (int) $this->getInput('available_slots'),
                'status' => $this->getInput('status'),
            ];
            Database::update('jobs', $updates, 'id = :id', ['id' => $id]);
            Session::setSuccess('Job updated.');
            $this->redirect('/admin/jobs');
        }

        $categories = Category::active();
        $this->renderAdmin('admin/jobs/edit', ['title' => 'Edit Job', 'job' => $job, 'categories' => $categories]);
    }
}
