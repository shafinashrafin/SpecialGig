<?php
class User extends Model
{
    protected string $table = 'users';
    protected array $fillable = [
        'username', 'email', 'password', 'role', 'status',
        'email_verified_at', 'kyc_status', 'two_factor_enabled',
        'two_factor_secret', 'referral_code', 'referred_by',
        'ip_address', 'last_login'
    ];

    public function profile()
    {
        return Database::fetch("SELECT * FROM user_profiles WHERE user_id = :id", ['id' => $this->id]);
    }

    public function wallet()
    {
        return Database::fetch("SELECT * FROM wallets WHERE user_id = :id", ['id' => $this->id]);
    }

    public function skills()
    {
        return Database::fetchAll("SELECT * FROM user_skills WHERE user_id = :id", ['id' => $this->id]);
    }

    public static function getBuyers(int $limit = 20, int $offset = 0): array
    {
        return Database::fetchAll(
            "SELECT u.*, up.full_name, up.avatar, up.country,
                    (SELECT COUNT(*) FROM jobs WHERE user_id = u.id) as total_jobs,
                    (SELECT SUM(total_budget) FROM jobs WHERE user_id = u.id) as total_spent
             FROM users u
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE u.role = 'buyer'
             ORDER BY u.created_at DESC
             LIMIT {$limit} OFFSET {$offset}"
        );
    }

    public static function getWorkers(int $limit = 20, int $offset = 0): array
    {
        return Database::fetchAll(
            "SELECT u.*, up.full_name, up.avatar, up.country,
                    (SELECT COUNT(*) FROM job_applications WHERE worker_id = u.id AND status = 'completed') as total_completed,
                    (SELECT COALESCE(SUM(balance), 0) FROM wallets WHERE user_id = u.id) as total_earned
             FROM users u
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE u.role = 'worker'
             ORDER BY u.created_at DESC
             LIMIT {$limit} OFFSET {$offset}"
        );
    }

    public static function topWorkers(int $limit = 10): array
    {
        return Database::fetchAll(
            "SELECT u.*, up.full_name, up.avatar, up.country,
                    (SELECT COUNT(*) FROM job_applications WHERE worker_id = u.id AND status = 'completed') as jobs_done,
                    (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE to_user_id = u.id) as avg_rating,
                    (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE user_id = u.id AND type = 'payment' AND status = 'completed') as earnings
             FROM users u
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE u.role = 'worker' AND u.status = 'active'
             ORDER BY earnings DESC
             LIMIT {$limit}"
        );
    }
}
