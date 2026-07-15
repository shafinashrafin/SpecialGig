<?php
class Category extends Model
{
    protected string $table = 'categories';
    protected array $fillable = ['name', 'slug', 'icon', 'description', 'parent_id', 'order_column', 'status'];

    public static function active(): array
    {
        return Database::fetchAll("SELECT * FROM categories WHERE status = 'active' ORDER BY order_column ASC");
    }

    public static function withJobCount(): array
    {
        return Database::fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM jobs WHERE category_id = c.id AND status = 'active') as job_count
             FROM categories c
             WHERE c.status = 'active'
             ORDER BY c.order_column ASC"
        );
    }
}
