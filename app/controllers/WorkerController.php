<?php
class WorkerController extends Controller
{
    public function __construct()
    {
        Auth::requireRole('worker');
    }

    public function dashboard(): void
    {
        $userId = Auth::id();
        $wallet = Wallet::getOrCreate($userId);
        $stats = [
            'available_jobs' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE status = 'active' AND available_slots > filled_slots AND is_hidden = 0")->count,
            'accepted_jobs' => Database::fetch("SELECT COUNT(*) as count FROM job_applications WHERE worker_id = :id AND status = 'accepted'", ['id' => $userId])->count,
            'pending_approval' => Database::fetch("SELECT COUNT(*) as count FROM job_applications WHERE worker_id = :id AND status = 'submitted'", ['id' => $userId])->count,
            'completed_jobs' => Database::fetch("SELECT COUNT(*) as count FROM job_applications WHERE worker_id = :id AND status = 'approved'", ['id' => $userId])->count,
            'earnings' => $wallet->total_earned,
            'referral_income' => $wallet->referral_earnings,
        ];

        $ranking = Database::fetch(
            "SELECT COUNT(*) as rank FROM users u
             WHERE u.role = 'worker' AND u.status = 'active' AND
             (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE user_id = u.id AND type = 'payment' AND status = 'completed') >
             (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE user_id = :id AND type = 'payment' AND status = 'completed')",
            ['id' => $userId]
        );

        $avgRating = Review::averageRating($userId);
        $recentTasks = Database::fetchAll(
            "SELECT ja.*, j.title as job_title, j.slug as job_slug, j.reward
             FROM job_applications ja
             JOIN jobs j ON ja.job_id = j.id
             WHERE ja.worker_id = :id
             ORDER BY ja.created_at DESC LIMIT 5",
            ['id' => $userId]
        );

        $badges = Database::fetchAll(
            "SELECT b.*, ub.earned_at FROM user_badges ub
             JOIN badges b ON ub.badge_id = b.id
             WHERE ub.user_id = :id",
            ['id' => $userId]
        );

        $this->render('worker/dashboard', [
            'title' => 'Worker Dashboard',
            'wallet' => $wallet,
            'stats' => $stats,
            'ranking' => ($ranking->rank ?? 0) + 1,
            'avgRating' => $avgRating,
            'recentTasks' => $recentTasks,
            'badges' => $badges,
        ]);
    }

    public function browse(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $query = $_GET['q'] ?? '';
        $categoryId = !empty($_GET['category']) ? (int) $_GET['category'] : null;
        $country = $_GET['country'] ?? '';
        $difficulty = $_GET['difficulty'] ?? '';
        $minReward = (float) ($_GET['min_reward'] ?? 0);

        $where = "j.status = 'active' AND j.is_hidden = 0 AND j.available_slots > j.filled_slots";
        $params = [];

        if ($query) {
            $where .= " AND (j.title LIKE :query OR j.description LIKE :query2)";
            $params['query'] = "%{$query}%";
            $params['query2'] = "%{$query}%";
        }
        if ($categoryId) {
            $where .= " AND j.category_id = :cat_id";
            $params['cat_id'] = $categoryId;
        }
        if ($country) {
            $where .= " AND (j.country_restriction IS NULL OR j.country_restriction = '' OR j.country_restriction = :country)";
            $params['country'] = $country;
        }
        if ($difficulty) {
            $where .= " AND j.difficulty = :difficulty";
            $params['difficulty'] = $difficulty;
        }
        if ($minReward > 0) {
            $where .= " AND j.reward >= :min_reward";
            $params['min_reward'] = $minReward;
        }

        $userCountry = Database::fetch("SELECT country FROM user_profiles WHERE user_id = :id", ['id' => Auth::id()]);

        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        $total = Database::fetch("SELECT COUNT(*) as count FROM jobs j WHERE {$where}", $params);

        $jobs = Database::fetchAll(
            "SELECT j.*, c.name as category_name, c.slug as category_slug,
                    u.username, up.full_name, up.avatar,
                    (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id) as total_applications,
                    (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE job_id = j.id) as rating,
                    (SELECT id FROM job_applications WHERE job_id = j.id AND worker_id = :uid) as my_application
             FROM jobs j
             LEFT JOIN categories c ON j.category_id = c.id
             LEFT JOIN users u ON j.user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE {$where}
             ORDER BY j.is_featured DESC, j.is_urgent DESC, j.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            array_merge($params, ['uid' => Auth::id()])
        );

        $categories = Category::active();
        $acceptedIds = Database::fetchAll(
            "SELECT job_id FROM job_applications WHERE worker_id = :id",
            ['id' => Auth::id()]
        );
        $acceptedJobIds = array_map(fn($a) => $a->job_id, $acceptedIds);

        $this->render('worker/browse', [
            'title' => 'Browse Jobs',
            'jobs' => $jobs,
            'categories' => $categories,
            'total' => $total->count ?? 0,
            'page' => $page,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
            'query' => $query,
            'selectedCategory' => $categoryId,
            'selectedDifficulty' => $difficulty,
            'minReward' => $minReward,
            'userCountry' => $userCountry->country ?? '',
            'acceptedJobIds' => $acceptedJobIds,
        ]);
    }

    public function myTasks(): void
    {
        $userId = Auth::id();
        $status = $_GET['status'] ?? '';
        $where = "ja.worker_id = :worker_id";
        $params = ['worker_id' => $userId];

        if ($status && in_array($status, ['accepted', 'submitted', 'approved', 'rejected', 'cancelled'])) {
            $where .= " AND ja.status = :status";
            $params['status'] = $status;
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $total = Database::fetch("SELECT COUNT(*) as count FROM job_applications ja WHERE {$where}", $params);

        $tasks = Database::fetchAll(
            "SELECT ja.*, j.title as job_title, j.slug as job_slug, j.reward, j.description,
                    j.instructions, j.proof_requirements, j.completion_time_limit,
                    c.name as category_name,
                    u.username as buyer_username, up.full_name as buyer_name
             FROM job_applications ja
             JOIN jobs j ON ja.job_id = j.id
             LEFT JOIN categories c ON j.category_id = c.id
             LEFT JOIN users u ON j.user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE {$where}
             ORDER BY ja.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $this->render('worker/my-tasks', [
            'title' => 'My Tasks',
            'tasks' => $tasks,
            'total' => $total->count ?? 0,
            'page' => $page,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
            'currentStatus' => $status,
        ]);
    }

    public function submitProof(int $applicationId): void
    {
        if ($this->isPost()) {
            $proof = $this->getInput('proof');
            $notes = $this->getInput('notes');

            $application = Database::fetch(
                "SELECT ja.*, j.title as job_title, j.user_id as buyer_id
                 FROM job_applications ja
                 JOIN jobs j ON ja.job_id = j.id
                 WHERE ja.id = :id AND ja.worker_id = :uid AND ja.status = 'accepted'",
                ['id' => $applicationId, 'uid' => Auth::id()]
            );

            if (!$application) {
                Session::setError('Application not found or not in accepted status.');
                $this->redirect('/worker/my-tasks');
            }

            $proofFiles = '';
            if (!empty($_FILES['proof_files']['name'][0])) {
                $uploadDir = UPLOADS_PATH . '/proofs/' . $applicationId;
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $files = [];
                foreach ($_FILES['proof_files']['tmp_name'] as $key => $tmpName) {
                    if ($_FILES['proof_files']['error'][$key] === 0) {
                        $ext = pathinfo($_FILES['proof_files']['name'][$key], PATHINFO_EXTENSION);
                        $fileName = random_string() . '.' . $ext;
                        move_uploaded_file($tmpName, $uploadDir . '/' . $fileName);
                        $files[] = 'assets/uploads/proofs/' . $applicationId . '/' . $fileName;
                    }
                }
                $proofFiles = implode(',', $files);
            }

            Database::query(
                "UPDATE job_applications SET status = 'submitted', proof = :proof, proof_files = :files, worker_notes = :notes WHERE id = :id",
                ['proof' => $proof, 'files' => $proofFiles, 'notes' => $notes, 'id' => $applicationId]
            );

            Notification::send($application->buyer_id, 'info', 'Proof Submitted', 'Proof has been submitted for: ' . $application->job_title, '/buyer/applications');

            Session::setSuccess('Proof submitted successfully! Waiting for buyer approval.');
            $this->redirect('/worker/my-tasks');
        }

        $application = Database::fetch(
            "SELECT ja.*, j.title as job_title, j.slug as job_slug, j.reward, j.instructions, j.proof_requirements
             FROM job_applications ja
             JOIN jobs j ON ja.job_id = j.id
             WHERE ja.id = :id AND ja.worker_id = :uid AND ja.status = 'accepted'",
            ['id' => $applicationId, 'uid' => Auth::id()]
        );

        if (!$application) {
            Session::setError('Task not found.');
            $this->redirect('/worker/my-tasks');
        }

        $this->render('worker/submit-proof', [
            'title' => 'Submit Proof',
            'application' => $application,
        ]);
    }

    public function wallet(): void
    {
        $wallet = Wallet::getOrCreate(Auth::id());
        $transactions = Wallet::transactions(Auth::id(), 20);
        $this->render('worker/wallet', [
            'title' => 'My Wallet',
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }

    public function withdraw(): void
    {
        if ($this->isPost()) {
            $amount = (float) $this->getInput('amount');
            $method = $this->getInput('payment_method');
            $paymentDetails = $this->getInput('payment_details');

            $wallet = Wallet::getOrCreate(Auth::id());
            $minWithdrawal = (float) get_setting('min_withdrawal', 5);
            $maxWithdrawal = (float) get_setting('max_withdrawal', 10000);

            if ($amount < $minWithdrawal) {
                Session::setError("Minimum withdrawal amount is {$minWithdrawal}.");
                $this->redirect('/worker/withdraw');
            }

            if ($amount > $maxWithdrawal) {
                Session::setError("Maximum withdrawal amount is {$maxWithdrawal}.");
                $this->redirect('/worker/withdraw');
            }

            if ($wallet->balance < $amount) {
                Session::setError('Insufficient balance.');
                $this->redirect('/worker/withdraw');
            }

            $withdrawalId = Database::insert('withdrawals', [
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'fee' => 0,
                'payment_method' => $method,
                'payment_details' => $paymentDetails,
                'status' => 'pending',
                'reference' => generate_reference(),
            ]);

            Database::query(
                "UPDATE wallets SET balance = balance - :amount, pending_balance = pending_balance + :amount2 WHERE user_id = :uid",
                ['amount' => $amount, 'amount2' => $amount, 'uid' => Auth::id()]
            );

            Session::setSuccess('Withdrawal request submitted. Awaiting admin approval.');
            $this->redirect('/worker/withdraw');
        }

        $wallet = Wallet::getOrCreate(Auth::id());
        $withdrawals = Database::fetchAll(
            "SELECT * FROM withdrawals WHERE user_id = :id ORDER BY created_at DESC LIMIT 10",
            ['id' => Auth::id()]
        );

        $this->render('worker/withdraw', [
            'title' => 'Withdraw Funds',
            'wallet' => $wallet,
            'withdrawals' => $withdrawals,
        ]);
    }

    public function referral(): void
    {
        $userId = Auth::id();
        $referrals = Database::fetchAll(
            "SELECT r.*, u.username, up.full_name, up.avatar, u.created_at as joined_date
             FROM referrals r
             LEFT JOIN users u ON r.referred_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE r.referrer_id = :id
             ORDER BY r.created_at DESC",
            ['id' => $userId]
        );

        $user = Database::fetch("SELECT * FROM users WHERE id = :id", ['id' => $userId]);
        $referralCount = Database::fetch(
            "SELECT COUNT(*) as count, COALESCE(SUM(reward), 0) as total_reward FROM referrals WHERE referrer_id = :id",
            ['id' => $userId]
        );

        $wallet = Wallet::getOrCreate($userId);

        $topReferrers = Database::fetchAll(
            "SELECT u.id, u.username, up.full_name, up.avatar,
                    (SELECT COUNT(*) FROM referrals WHERE referrer_id = u.id) as referral_count
             FROM users u
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE (SELECT COUNT(*) FROM referrals WHERE referrer_id = u.id) > 0
             ORDER BY referral_count DESC LIMIT 10"
        );

        $this->render('worker/referral', [
            'title' => 'Referral Program',
            'referrals' => $referrals,
            'referralCode' => $user->referral_code,
            'referralCount' => $referralCount,
            'wallet' => $wallet,
            'topReferrers' => $topReferrers,
        ]);
    }

    public function bonuses(): void
    {
        $badges = Database::fetchAll(
            "SELECT b.*, ub.earned_at FROM user_badges ub
             RIGHT JOIN badges b ON ub.badge_id = b.id AND ub.user_id = :id
             WHERE b.status = 'active'
             ORDER BY ub.earned_at DESC",
            ['id' => Auth::id()]
        );

        $bonusTransactions = Database::fetchAll(
            "SELECT * FROM transactions WHERE user_id = :id AND type IN ('bonus', 'referral') ORDER BY created_at DESC LIMIT 20",
            ['id' => Auth::id()]
        );

        $this->render('worker/bonuses', [
            'title' => 'Bonuses & Badges',
            'badges' => $badges,
            'bonusTransactions' => $bonusTransactions,
        ]);
    }

    public function leaderboard(): void
    {
        $period = $_GET['period'] ?? 'all';

        $workers = Database::fetchAll(
            "SELECT u.id, u.username, up.full_name, up.avatar, up.country,
                    (SELECT COUNT(*) FROM job_applications WHERE worker_id = u.id AND status = 'approved') as jobs_done,
                    (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE to_user_id = u.id) as avg_rating,
                    (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE user_id = u.id AND type = 'payment' AND status = 'completed') as earnings
             FROM users u
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE u.role = 'worker' AND u.status = 'active'
             ORDER BY earnings DESC
             LIMIT 50"
        );

        $this->render('worker/leaderboard', [
            'title' => 'Leaderboard',
            'workers' => $workers,
            'currentUserId' => Auth::id(),
        ]);
    }

    public function settings(): void
    {
        if ($this->isPost()) {
            $profileData = [
                'full_name' => $this->getInput('full_name'),
                'phone' => $this->getInput('phone'),
                'bio' => $this->getInput('bio'),
                'country' => $this->getInput('country'),
                'city' => $this->getInput('city'),
            ];

            $existing = Database::fetch("SELECT id FROM user_profiles WHERE user_id = :id", ['id' => Auth::id()]);
            if ($existing) {
                Database::update('user_profiles', $profileData, 'user_id = :uid', ['uid' => Auth::id()]);
            } else {
                $profileData['user_id'] = Auth::id();
                Database::insert('user_profiles', $profileData);
            }

            $skills = $this->getInput('skills', '');
            Database::delete('user_skills', 'user_id = :id', ['id' => Auth::id()]);
            foreach (explode(',', $skills) as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    Database::insert('user_skills', ['user_id' => Auth::id(), 'skill' => $skill]);
                }
            }

            $password = $this->getInput('password');
            if (!empty($password)) {
                $current = $this->getInput('current_password');
                $user = Database::fetch("SELECT * FROM users WHERE id = :id", ['id' => Auth::id()]);
                if (password_verify($current, $user->password)) {
                    Database::query("UPDATE users SET password = :pwd WHERE id = :id", [
                        'pwd' => password_hash($password, PASSWORD_DEFAULT),
                        'id' => Auth::id(),
                    ]);
                } else {
                    Session::setError('Current password is incorrect.');
                    $this->redirect('/worker/settings');
                }
            }

            Session::setSuccess('Settings updated.');
            $this->redirect('/worker/settings');
        }

        $user = Auth::user();
        $skills = Database::fetchAll("SELECT skill FROM user_skills WHERE user_id = :id", ['id' => Auth::id()]);
        $skillList = implode(', ', array_map(fn($s) => $s->skill, $skills));

        $this->render('worker/settings', [
            'title' => 'Settings',
            'user' => $user,
            'skillList' => $skillList,
            'skills' => $skills,
        ]);
    }

    public function support(): void
    {
        if ($this->isPost()) {
            Database::insert('support_tickets', [
                'user_id' => Auth::id(),
                'subject' => $this->getInput('subject'),
                'message' => $this->getInput('message'),
                'priority' => $this->getInput('priority', 'medium'),
            ]);
            Session::setSuccess('Support ticket created.');
            $this->redirect('/worker/support');
        }

        $tickets = Database::fetchAll("SELECT * FROM support_tickets WHERE user_id = :id ORDER BY created_at DESC", ['id' => Auth::id()]);
        $this->render('worker/support', ['title' => 'Support', 'tickets' => $tickets]);
    }
}
