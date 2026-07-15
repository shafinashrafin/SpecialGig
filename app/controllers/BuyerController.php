<?php
class BuyerController extends Controller
{
    public function __construct()
    {
        Auth::requireRole('buyer');
    }

    public function dashboard(): void
    {
        $userId = Auth::id();
        $wallet = Wallet::getOrCreate($userId);
        $stats = [
            'active_jobs' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE user_id = :id AND status = 'active'", ['id' => $userId])->count,
            'pending_jobs' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE user_id = :id AND status = 'pending'", ['id' => $userId])->count,
            'completed_jobs' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE user_id = :id AND status = 'completed'", ['id' => $userId])->count,
            'total_spent' => Database::fetch("SELECT COALESCE(SUM(total_budget), 0) as total FROM jobs WHERE user_id = :id", ['id' => $userId])->total,
            'pending_reviews' => Database::fetch(
                "SELECT COUNT(*) as count FROM job_applications ja
                 JOIN jobs j ON ja.job_id = j.id
                 WHERE j.user_id = :id AND ja.status = 'submitted'",
                ['id' => $userId]
            )->count,
        ];

        $recentJobs = Database::fetchAll(
            "SELECT j.*, c.name as category_name,
                    (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id) as applications
             FROM jobs j
             LEFT JOIN categories c ON j.category_id = c.id
             WHERE j.user_id = :id
             ORDER BY j.created_at DESC LIMIT 5",
            ['id' => $userId]
        );

        $recentActivity = Database::fetchAll(
            "SELECT * FROM notifications WHERE user_id = :id ORDER BY created_at DESC LIMIT 5",
            ['id' => $userId]
        );

        $this->render('buyer/dashboard', [
            'title' => 'Buyer Dashboard',
            'wallet' => $wallet,
            'stats' => $stats,
            'recentJobs' => $recentJobs,
            'recentActivity' => $recentActivity,
        ]);
    }

    public function createJob(): void
    {
        if ($this->isPost()) {
            $title = $this->getInput('title');
            $categoryId = (int) $this->getInput('category_id');
            $description = $this->getInput('description');
            $instructions = $this->getInput('instructions');
            $proofRequirements = $this->getInput('proof_requirements');
            $reward = (float) $this->getInput('reward');
            $availableSlots = (int) $this->getInput('available_slots');
            $countryRestriction = $this->getInput('country_restriction');
            $deviceRestriction = $this->getInput('device_restriction');
            $browserRestriction = $this->getInput('browser_restriction');
            $completionTimeLimit = (int) $this->getInput('completion_time_limit');
            $approvalTimeLimit = (int) $this->getInput('approval_time_limit');
            $isManualApproval = $this->getInput('is_manual_approval') ? 1 : 0;
            $isHidden = $this->getInput('is_hidden') ? 1 : 0;
            $isFeatured = $this->getInput('is_featured') ? 1 : 0;
            $isUrgent = $this->getInput('is_urgent') ? 1 : 0;
            $difficulty = $this->getInput('difficulty', 'beginner');

            if (empty($title) || empty($description) || $reward <= 0 || $availableSlots <= 0) {
                Session::setError('Please fill in all required fields.');
                $this->redirect('/buyer/create-job');
            }

            $totalBudget = $reward * $availableSlots;
            $wallet = Wallet::getOrCreate(Auth::id());
            if ($wallet->balance < $totalBudget) {
                Session::setError('Insufficient balance. Please deposit funds.');
                $this->redirect('/buyer/create-job');
            }

            $slug = slugify($title);
            $baseSlug = $slug;
            $counter = 1;
            while (Database::exists('jobs', 'slug = :slug', ['slug' => $slug])) {
                $slug = $baseSlug . '-' . $counter++;
            }

            Database::getInstance()->beginTransaction();
            try {
                $jobId = Database::insert('jobs', [
                    'user_id' => Auth::id(),
                    'category_id' => $categoryId,
                    'title' => $title,
                    'slug' => $slug,
                    'description' => $description,
                    'instructions' => $instructions,
                    'proof_requirements' => $proofRequirements,
                    'reward' => $reward,
                    'available_slots' => $availableSlots,
                    'total_budget' => $totalBudget,
                    'country_restriction' => $countryRestriction,
                    'device_restriction' => $deviceRestriction,
                    'browser_restriction' => $browserRestriction,
                    'completion_time_limit' => $completionTimeLimit ?: null,
                    'approval_time_limit' => $approvalTimeLimit ?: null,
                    'is_manual_approval' => $isManualApproval,
                    'is_hidden' => $isHidden,
                    'is_featured' => $isFeatured,
                    'is_urgent' => $isUrgent,
                    'difficulty' => $difficulty,
                    'status' => 'pending',
                ]);

                Wallet::deductBalance(Auth::id(), $totalBudget, 'payment', 'Job posting: ' . $title, 'JOB-' . $jobId);

                if (!empty($_FILES['attachments']['name'][0])) {
                    $uploadDir = UPLOADS_PATH . '/jobs/' . $jobId;
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    foreach ($_FILES['attachments']['tmp_name'] as $key => $tmpName) {
                        if ($_FILES['attachments']['error'][$key] === 0) {
                            $ext = pathinfo($_FILES['attachments']['name'][$key], PATHINFO_EXTENSION);
                            $fileName = random_string() . '.' . $ext;
                            move_uploaded_file($tmpName, $uploadDir . '/' . $fileName);
                            Database::insert('job_files', [
                                'job_id' => $jobId,
                                'file_path' => 'assets/uploads/jobs/' . $jobId . '/' . $fileName,
                                'file_type' => $ext,
                            ]);
                        }
                    }
                }

                Database::getInstance()->commit();
                Session::setSuccess('Job created successfully! It will be reviewed by an admin.');
            } catch (\Exception $e) {
                Database::getInstance()->rollBack();
                Session::setError('Failed to create job. Please try again.');
            }

            $this->redirect('/buyer/my-jobs');
        }

        $categories = Category::active();
        $wallet = Wallet::getOrCreate(Auth::id());
        $this->render('buyer/create-job', [
            'title' => 'Create Job',
            'categories' => $categories,
            'wallet' => $wallet,
        ]);
    }

    public function myJobs(): void
    {
        $userId = Auth::id();
        $status = $_GET['status'] ?? '';
        $where = "j.user_id = :user_id";
        $params = ['user_id' => $userId];

        if ($status && in_array($status, JOB_STATUSES)) {
            $where .= " AND j.status = :status";
            $params['status'] = $status;
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $total = Database::fetch(
            "SELECT COUNT(*) as count FROM jobs j WHERE {$where}", $params
        );

        $jobs = Database::fetchAll(
            "SELECT j.*, c.name as category_name,
                    (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id) as total_applications,
                    (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id AND status = 'submitted') as pending_review
             FROM jobs j
             LEFT JOIN categories c ON j.category_id = c.id
             WHERE {$where}
             ORDER BY j.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $this->render('buyer/my-jobs', [
            'title' => 'My Jobs',
            'jobs' => $jobs,
            'total' => $total->count ?? 0,
            'page' => $page,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
            'currentStatus' => $status,
        ]);
    }

    public function editJob(int $id): void
    {
        $job = Database::fetch("SELECT * FROM jobs WHERE id = :id AND user_id = :uid", ['id' => $id, 'uid' => Auth::id()]);
        if (!$job || !in_array($job->status, ['pending', 'active', 'paused'])) {
            Session::setError('Job not found or cannot be edited.');
            $this->redirect('/buyer/my-jobs');
        }

        if ($this->isPost()) {
            $updates = [
                'title' => $this->getInput('title'),
                'description' => $this->getInput('description'),
                'instructions' => $this->getInput('instructions'),
                'proof_requirements' => $this->getInput('proof_requirements'),
                'reward' => (float) $this->getInput('reward'),
                'available_slots' => (int) $this->getInput('available_slots'),
                'country_restriction' => $this->getInput('country_restriction'),
                'device_restriction' => $this->getInput('device_restriction'),
                'browser_restriction' => $this->getInput('browser_restriction'),
                'completion_time_limit' => (int) $this->getInput('completion_time_limit') ?: null,
                'approval_time_limit' => (int) $this->getInput('approval_time_limit') ?: null,
                'difficulty' => $this->getInput('difficulty', 'beginner'),
            ];

            Database::update('jobs', $updates, 'id = :id', ['id' => $id]);
            Session::setSuccess('Job updated successfully.');
            $this->redirect('/buyer/my-jobs');
        }

        $categories = Category::active();
        $files = Database::fetchAll("SELECT * FROM job_files WHERE job_id = :id", ['id' => $id]);
        $this->render('buyer/edit-job', [
            'title' => 'Edit Job',
            'job' => $job,
            'categories' => $categories,
            'files' => $files,
        ]);
    }

    public function pauseJob(int $id): void
    {
        $job = Database::fetch("SELECT id, status FROM jobs WHERE id = :id AND user_id = :uid", ['id' => $id, 'uid' => Auth::id()]);
        if ($job && $job->status === 'active') {
            Database::query("UPDATE jobs SET status = 'paused' WHERE id = :id", ['id' => $id]);
            Session::setSuccess('Job paused.');
        }
        $this->redirect('/buyer/my-jobs');
    }

    public function resumeJob(int $id): void
    {
        $job = Database::fetch("SELECT id, status FROM jobs WHERE id = :id AND user_id = :uid", ['id' => $id, 'uid' => Auth::id()]);
        if ($job && $job->status === 'paused') {
            Database::query("UPDATE jobs SET status = 'active' WHERE id = :id", ['id' => $id]);
            Session::setSuccess('Job resumed.');
        }
        $this->redirect('/buyer/my-jobs');
    }

    public function deleteJob(int $id): void
    {
        $job = Database::fetch("SELECT id, total_budget FROM jobs WHERE id = :id AND user_id = :uid AND status IN ('pending', 'paused')", ['id' => $id, 'uid' => Auth::id()]);
        if ($job) {
            Database::getInstance()->beginTransaction();
            try {
                Wallet::addBalance(Auth::id(), $job->total_budget, 'refund', 'Refund for cancelled job #' . $id);
                Database::query("UPDATE jobs SET status = 'cancelled' WHERE id = :id", ['id' => $id]);
                Database::getInstance()->commit();
                Session::setSuccess('Job deleted and budget refunded.');
            } catch (\Exception $e) {
                Database::getInstance()->rollBack();
                Session::setError('Failed to delete job.');
            }
        }
        $this->redirect('/buyer/my-jobs');
    }

    public function applications(): void
    {
        $userId = Auth::id();
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $total = Database::fetch(
            "SELECT COUNT(*) as count FROM job_applications ja
             JOIN jobs j ON ja.job_id = j.id
             WHERE j.user_id = :id",
            ['id' => $userId]
        );

        $applications = Database::fetchAll(
            "SELECT ja.*, j.title as job_title, j.slug as job_slug, j.reward,
                    u.username, up.full_name, up.avatar, up.country
             FROM job_applications ja
             JOIN jobs j ON ja.job_id = j.id
             LEFT JOIN users u ON ja.worker_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE j.user_id = :id
             ORDER BY ja.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            ['id' => $userId]
        );

        $this->render('buyer/applications', [
            'title' => 'Applications',
            'applications' => $applications,
            'total' => $total->count ?? 0,
            'page' => $page,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
        ]);
    }

    public function reviewProof(int $applicationId): void
    {
        $application = Database::fetch(
            "SELECT ja.*, j.title as job_title, j.user_id as buyer_id, j.slug as job_slug, j.reward
             FROM job_applications ja
             JOIN jobs j ON ja.job_id = j.id
             WHERE ja.id = :id AND j.user_id = :uid AND ja.status = 'submitted'",
            ['id' => $applicationId, 'uid' => Auth::id()]
        );

        if (!$application) {
            Session::setError('Application not found.');
            $this->redirect('/buyer/applications');
        }

        $worker = Database::fetch(
            "SELECT u.*, up.full_name, up.avatar, up.country, up.bio
             FROM users u LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE u.id = :id",
            ['id' => $application->worker_id]
        );

        $this->render('buyer/review-proof', [
            'title' => 'Review Proof',
            'application' => $application,
            'worker' => $worker,
        ]);
    }

    public function approveProof(int $applicationId): void
    {
        $application = Database::fetch(
            "SELECT ja.*, j.user_id as buyer_id, j.reward, j.title as job_title
             FROM job_applications ja
             JOIN jobs j ON ja.job_id = j.id
             WHERE ja.id = :id AND j.user_id = :uid AND ja.status = 'submitted'",
            ['id' => $applicationId, 'uid' => Auth::id()]
        );

        if (!$application) {
            Session::setError('Application not found.');
            $this->redirect('/buyer/applications');
        }

        Database::getInstance()->beginTransaction();
        try {
            Database::query(
                "UPDATE job_applications SET status = 'approved', completed_at = NOW() WHERE id = :id",
                ['id' => $applicationId]
            );

            $commission = (float) get_setting('commission_rate', 10);
            $fee = $application->reward * ($commission / 100);
            $payout = $application->reward - $fee;

            Wallet::addBalance($application->worker_id, $payout, 'payment', 'Payment for: ' . $application->job_title, 'APP-' . $applicationId);

            $remaining = Database::fetch(
                "SELECT COUNT(*) as total,
                        (SELECT COUNT(*) FROM job_applications WHERE job_id = :job_id AND status IN ('approved', 'cancelled')) as done
                 FROM job_applications WHERE job_id = :job_id2",
                ['job_id' => $application->job_id, 'job_id2' => $application->job_id]
            );

            if ($remaining->done >= $remaining->total) {
                Database::query("UPDATE jobs SET status = 'completed' WHERE id = :id", ['id' => $application->job_id]);
            }

            Notification::send($application->worker_id, 'success', 'Proof Approved', 'Your proof for "' . $application->job_title . '" has been approved!', '/worker/my-tasks');

            Database::getInstance()->commit();
            Session::setSuccess('Proof approved! Payment has been released to the worker.');
        } catch (\Exception $e) {
            Database::getInstance()->rollBack();
            Session::setError('Failed to approve proof.');
        }

        $this->redirect('/buyer/applications');
    }

    public function rejectProof(int $applicationId): void
    {
        $application = Database::fetch(
            "SELECT ja.*, j.user_id as buyer_id, j.title as job_title
             FROM job_applications ja
             JOIN jobs j ON ja.job_id = j.id
             WHERE ja.id = :id AND j.user_id = :uid AND ja.status = 'submitted'",
            ['id' => $applicationId, 'uid' => Auth::id()]
        );

        if (!$application) {
            Session::setError('Application not found.');
            $this->redirect('/buyer/applications');
        }

        $notes = $this->getInput('rejection_reason', 'Proof did not meet requirements.');
        Database::query(
            "UPDATE job_applications SET status = 'rejected', buyer_notes = :notes WHERE id = :id",
            ['notes' => $notes, 'id' => $applicationId]
        );

        Database::query(
            "UPDATE jobs SET filled_slots = filled_slots - 1 WHERE id = :id AND filled_slots > 0",
            ['id' => $application->job_id]
        );

        Notification::send($application->worker_id, 'error', 'Proof Rejected', 'Your proof for "' . $application->job_title . '" was rejected. Reason: ' . $notes, '/worker/my-tasks');

        Session::setSuccess('Proof rejected. The worker has been notified.');
        $this->redirect('/buyer/applications');
    }

    public function wallet(): void
    {
        $wallet = Wallet::getOrCreate(Auth::id());
        $transactions = Wallet::transactions(Auth::id(), 20);
        $this->render('buyer/wallet', [
            'title' => 'My Wallet',
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }

    public function deposit(): void
    {
        if ($this->isPost()) {
            $amount = (float) $this->getInput('amount');
            $method = $this->getInput('payment_method');

            if ($amount <= 0) {
                Session::setError('Invalid amount.');
                $this->redirect('/buyer/deposit');
            }

            if (!in_array($method, array_keys(PAYMENT_METHODS))) {
                Session::setError('Invalid payment method.');
                $this->redirect('/buyer/deposit');
            }

            $wallet = Wallet::getOrCreate(Auth::id());
            $depositId = Database::insert('deposits', [
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'fee' => 0,
                'payment_method' => $method,
                'status' => 'pending',
                'reference' => generate_reference(),
            ]);

            Session::setSuccess('Deposit request submitted. Please complete the payment.');
            $this->redirect('/buyer/deposit');
        }

        $wallet = Wallet::getOrCreate(Auth::id());
        $deposits = Database::fetchAll(
            "SELECT * FROM deposits WHERE user_id = :id ORDER BY created_at DESC LIMIT 10",
            ['id' => Auth::id()]
        );

        $this->render('buyer/deposit', [
            'title' => 'Deposit Funds',
            'wallet' => $wallet,
            'deposits' => $deposits,
        ]);
    }

    public function invoices(): void
    {
        $transactions = Database::fetchAll(
            "SELECT t.*, j.title as job_title
             FROM transactions t
             LEFT JOIN jobs j ON t.reference LIKE CONCAT('JOB-', j.id)
             WHERE t.user_id = :id AND t.status = 'completed'
             ORDER BY t.created_at DESC LIMIT 50",
            ['id' => Auth::id()]
        );
        $this->render('buyer/invoices', ['title' => 'Invoices', 'transactions' => $transactions]);
    }

    public function settings(): void
    {
        if ($this->isPost()) {
            $data = [
                'full_name' => $this->getInput('full_name'),
                'phone' => $this->getInput('phone'),
                'bio' => $this->getInput('bio'),
                'country' => $this->getInput('country'),
                'city' => $this->getInput('city'),
                'address' => $this->getInput('address'),
            ];

            $existing = Database::fetch("SELECT id FROM user_profiles WHERE user_id = :id", ['id' => Auth::id()]);
            if ($existing) {
                Database::update('user_profiles', $data, 'user_id = :uid', ['uid' => Auth::id()]);
            } else {
                $data['user_id'] = Auth::id();
                Database::insert('user_profiles', $data);
            }

            $password = $this->getInput('password');
            if (!empty($password)) {
                $currentPassword = $this->getInput('current_password');
                $user = Database::fetch("SELECT * FROM users WHERE id = :id", ['id' => Auth::id()]);
                if (password_verify($currentPassword, $user->password)) {
                    Database::query("UPDATE users SET password = :pwd WHERE id = :id", [
                        'pwd' => password_hash($password, PASSWORD_DEFAULT),
                        'id' => Auth::id(),
                    ]);
                } else {
                    Session::setError('Current password is incorrect.');
                    $this->redirect('/buyer/settings');
                }
            }

            Session::setSuccess('Settings updated successfully.');
            $this->redirect('/buyer/settings');
        }

        $user = Auth::user();
        $this->render('buyer/settings', ['title' => 'Settings', 'user' => $user]);
    }

    public function rate(int $applicationId): void
    {
        if ($this->isPost()) {
            $rating = (int) $this->getInput('rating');
            $review = $this->getInput('review');

            $application = Database::fetch(
                "SELECT ja.*, j.user_id as buyer_id, j.slug as job_slug
                 FROM job_applications ja
                 JOIN jobs j ON ja.job_id = j.id
                 WHERE ja.id = :id AND j.user_id = :uid AND ja.status = 'approved'",
                ['id' => $applicationId, 'uid' => Auth::id()]
            );

            if ($application) {
                Database::insert('reviews', [
                    'from_user_id' => Auth::id(),
                    'to_user_id' => $application->worker_id,
                    'job_id' => $application->job_id,
                    'application_id' => $applicationId,
                    'rating' => $rating,
                    'review' => $review,
                ]);
                Session::setSuccess('Rating submitted.');
            }

            $this->redirect('/buyer/applications');
        }
    }

    public function support(): void
    {
        if ($this->isPost()) {
            $subject = $this->getInput('subject');
            $message = $this->getInput('message');
            $priority = $this->getInput('priority', 'medium');

            Database::insert('support_tickets', [
                'user_id' => Auth::id(),
                'subject' => $subject,
                'message' => $message,
                'priority' => $priority,
            ]);

            Session::setSuccess('Support ticket created.');
            $this->redirect('/buyer/support');
        }

        $tickets = Database::fetchAll(
            "SELECT * FROM support_tickets WHERE user_id = :id ORDER BY created_at DESC",
            ['id' => Auth::id()]
        );

        $this->render('buyer/support', ['title' => 'Support', 'tickets' => $tickets]);
    }
}
