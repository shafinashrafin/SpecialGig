<?php
class UsersController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $role = $_GET['role'] ?? '';
        $search = $_GET['search'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $where = "1=1";
        $params = [];

        if ($role) {
            $where .= " AND u.role = :role";
            $params['role'] = $role;
        }
        if ($search) {
            $where .= " AND (u.username LIKE :search OR u.email LIKE :search2)";
            $params['search'] = "%{$search}%";
            $params['search2'] = "%{$search}%";
        }

        $total = Database::fetch("SELECT COUNT(*) as count FROM users u WHERE {$where}", $params);
        $users = Database::fetchAll(
            "SELECT u.*, up.full_name, up.avatar, up.country, up.phone,
                    (SELECT COALESCE(balance, 0) FROM wallets WHERE user_id = u.id) as wallet_balance
             FROM users u
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE {$where}
             ORDER BY u.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $this->renderAdmin('admin/users/index', [
            'title' => 'Manage Users',
            'users' => $users,
            'total' => $total->count ?? 0,
            'page' => $page,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
            'currentRole' => $role,
            'search' => $search,
        ]);
    }

    public function view(int $id): void
    {
        $user = Database::fetch(
            "SELECT u.*, up.* FROM users u LEFT JOIN user_profiles up ON u.id = up.user_id WHERE u.id = :id",
            ['id' => $id]
        );
        if (!$user) {
            Session::setError('User not found.');
            $this->redirect('/admin/users');
        }

        $wallet = Wallet::getOrCreate($id);
        $jobs = Database::fetchAll("SELECT * FROM jobs WHERE user_id = :id ORDER BY created_at DESC LIMIT 10", ['id' => $id]);
        $transactions = Database::fetchAll("SELECT * FROM transactions WHERE user_id = :id ORDER BY created_at DESC LIMIT 20", ['id' => $id]);
        $loginHistory = Database::fetchAll("SELECT * FROM login_history WHERE user_id = :id ORDER BY created_at DESC LIMIT 10", ['id' => $id]);

        $this->renderAdmin('admin/users/view', [
            'title' => 'User: ' . $user->username,
            'user' => $user,
            'wallet' => $wallet,
            'jobs' => $jobs,
            'transactions' => $transactions,
            'loginHistory' => $loginHistory,
        ]);
    }

    public function suspend(int $id): void
    {
        Database::query("UPDATE users SET status = 'suspended' WHERE id = :id AND role != 'admin'", ['id' => $id]);
        Session::setSuccess('User suspended.');
        $this->redirect('/admin/users');
    }

    public function activate(int $id): void
    {
        Database::query("UPDATE users SET status = 'active' WHERE id = :id", ['id' => $id]);
        Session::setSuccess('User activated.');
        $this->redirect('/admin/users');
    }

    public function ban(int $id): void
    {
        Database::query("UPDATE users SET status = 'banned' WHERE id = :id AND role != 'admin'", ['id' => $id]);
        Session::setSuccess('User banned.');
        $this->redirect('/admin/users');
    }
}
