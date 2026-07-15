<?php
class Notification extends Model
{
    protected string $table = 'notifications';
    protected array $fillable = ['user_id', 'type', 'title', 'message', 'link', 'is_read'];

    public static function send(int $userId, string $type, string $title, string $message, string $link = null): int
    {
        return Database::insert('notifications', [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => 0,
        ]);
    }

    public static function recent(int $userId, int $limit = 10): array
    {
        return Database::fetchAll(
            "SELECT * FROM notifications WHERE user_id = :id ORDER BY created_at DESC LIMIT {$limit}",
            ['id' => $userId]
        );
    }

    public static function unreadCount(int $userId): int
    {
        return (int) Database::fetch(
            "SELECT COUNT(*) as count FROM notifications WHERE user_id = :id AND is_read = 0",
            ['id' => $userId]
        )->count;
    }

    public static function markRead(int $id): void
    {
        Database::query("UPDATE notifications SET is_read = 1 WHERE id = :id", ['id' => $id]);
    }

    public static function markAllRead(int $userId): void
    {
        Database::query("UPDATE notifications SET is_read = 1 WHERE user_id = :id", ['id' => $userId]);
    }
}
