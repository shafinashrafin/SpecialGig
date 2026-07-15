<?php
class Job extends Model
{
    protected string $table = 'jobs';
    protected array $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'description', 'instructions',
        'proof_requirements', 'reward', 'available_slots', 'filled_slots', 'total_budget',
        'country_restriction', 'device_restriction', 'browser_restriction',
        'completion_time_limit', 'approval_time_limit', 'is_manual_approval',
        'is_hidden', 'is_featured', 'is_urgent', 'difficulty', 'status',
        'rejection_reason', 'approved_at'
    ];

    public function category()
    {
        return Database::fetch("SELECT * FROM categories WHERE id = :id", ['id' => $this->category_id]);
    }

    public function buyer()
    {
        return Database::fetch("SELECT u.*, up.full_name, up.avatar FROM users u LEFT JOIN user_profiles up ON u.id = up.user_id WHERE u.id = :id", ['id' => $this->user_id]);
    }

    public function applications()
    {
        return Database::fetchAll("SELECT * FROM job_applications WHERE job_id = :id ORDER BY created_at DESC", ['id' => $this->id]);
    }

    public function files()
    {
        return Database::fetchAll("SELECT * FROM job_files WHERE job_id = :id", ['id' => $this->id]);
    }

    public static function featured(int $limit = 12): array
    {
        return Database::fetchAll(
            "SELECT j.*, c.name as category_name, c.slug as category_slug,
                    u.username, up.full_name, up.avatar,
                    (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id) as total_applications,
                    (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE job_id = j.id) as rating
             FROM jobs j
             LEFT JOIN categories c ON j.category_id = c.id
             LEFT JOIN users u ON j.user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE j.status = 'active' AND j.is_hidden = 0 AND j.available_slots > j.filled_slots
             ORDER BY j.is_featured DESC, j.created_at DESC
             LIMIT {$limit}"
        );
    }

    public static function search(string $query, int $categoryId = null, string $country = null, int $page = 1, int $perPage = 20): array
    {
        $where = "j.status = 'active' AND j.is_hidden = 0 AND j.available_slots > j.filled_slots";
        $params = [];

        if ($query) {
            $where .= " AND (j.title LIKE :query OR j.description LIKE :query2)";
            $params['query'] = "%{$query}%";
            $params['query2'] = "%{$query}%";
        }

        if ($categoryId) {
            $where .= " AND j.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        if ($country) {
            $where .= " AND (j.country_restriction IS NULL OR j.country_restriction = '' OR j.country_restriction = :country)";
            $params['country'] = $country;
        }

        $offset = ($page - 1) * $perPage;
        $total = Database::fetch(
            "SELECT COUNT(*) as count FROM jobs j WHERE {$where}", $params
        );

        $data = Database::fetchAll(
            "SELECT j.*, c.name as category_name, c.slug as category_slug,
                    u.username, up.full_name, up.avatar,
                    (SELECT COUNT(*) FROM job_applications WHERE job_id = j.id) as total_applications,
                    (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE job_id = j.id) as rating
             FROM jobs j
             LEFT JOIN categories c ON j.category_id = c.id
             LEFT JOIN users u ON j.user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE {$where}
             ORDER BY j.is_featured DESC, j.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return [
            'data' => $data,
            'total' => $total->count ?? 0,
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => ceil(($total->count ?? 0) / $perPage),
        ];
    }

    public static function stats(): array
    {
        return [
            'active' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE status = 'active'")->count,
            'pending' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE status = 'pending'")->count,
            'completed' => Database::fetch("SELECT COUNT(*) as count FROM jobs WHERE status = 'completed'")->count,
            'total' => Database::fetch("SELECT COUNT(*) as count FROM jobs")->count,
            'total_budget' => Database::fetch("SELECT COALESCE(SUM(total_budget), 0) as total FROM jobs WHERE status IN ('active', 'completed')")->total,
        ];
    }
}
