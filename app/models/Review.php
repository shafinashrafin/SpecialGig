<?php
class Review extends Model
{
    protected string $table = 'reviews';
    protected array $fillable = ['from_user_id', 'to_user_id', 'job_id', 'application_id', 'rating', 'review'];

    public static function forUser(int $userId): array
    {
        return Database::fetchAll(
            "SELECT r.*, u.username, up.full_name, up.avatar, j.title as job_title
             FROM reviews r
             LEFT JOIN users u ON r.from_user_id = u.id
             LEFT JOIN user_profiles up ON u.id = up.user_id
             LEFT JOIN jobs j ON r.job_id = j.id
             WHERE r.to_user_id = :id
             ORDER BY r.created_at DESC",
            ['id' => $userId]
        );
    }

    public static function averageRating(int $userId): float
    {
        $result = Database::fetch(
            "SELECT COALESCE(AVG(rating), 0) as avg FROM reviews WHERE to_user_id = :id",
            ['id' => $userId]
        );
        return round((float) $result->avg, 1);
    }

    public static function ratingCount(int $userId): int
    {
        return (int) Database::fetch(
            "SELECT COUNT(*) as count FROM reviews WHERE to_user_id = :id",
            ['id' => $userId]
        )->count;
    }
}
