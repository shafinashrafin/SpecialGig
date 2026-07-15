<?php
class SettingsController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $group = $_GET['group'] ?? 'general';
        $settings = Database::fetchAll("SELECT * FROM settings WHERE group_name = :group ORDER BY id ASC", ['group' => $group]);

        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                if (str_starts_with($key, 'setting_')) {
                    $settingKey = substr($key, 8);
                    Database::query("UPDATE settings SET `value` = :val WHERE `key` = :key", [
                        'val' => is_array($value) ? json_encode($value) : $value,
                        'key' => $settingKey,
                    ]);
                }
            }
            Session::setSuccess('Settings updated successfully.');
            $this->redirect('/admin/settings?group=' . $group);
        }

        $this->renderAdmin('admin/settings/index', [
            'title' => 'Settings',
            'settings' => $settings,
            'currentGroup' => $group,
        ]);
    }

    public function categories(): void
    {
        $categories = Database::fetchAll("SELECT * FROM categories ORDER BY order_column ASC");

        if ($this->isPost()) {
            $name = $this->getInput('name');
            $slug = slugify($name);
            $icon = $this->getInput('icon');
            $description = $this->getInput('description');

            if (!Database::exists('categories', 'slug = :slug', ['slug' => $slug])) {
                Database::insert('categories', [
                    'name' => $name,
                    'slug' => $slug,
                    'icon' => $icon,
                    'description' => $description,
                ]);
                Session::setSuccess('Category created.');
            } else {
                Session::setError('Category already exists.');
            }
            $this->redirect('/admin/settings/categories');
        }

        $this->renderAdmin('admin/settings/categories', [
            'title' => 'Categories',
            'categories' => $categories,
        ]);
    }

    public function deleteCategory(int $id): void
    {
        Database::delete('categories', 'id = :id', ['id' => $id]);
        Session::setSuccess('Category deleted.');
        $this->redirect('/admin/settings/categories');
    }

    public function badges(): void
    {
        if ($this->isPost()) {
            Database::insert('badges', [
                'name' => $this->getInput('name'),
                'slug' => slugify($this->getInput('name')),
                'description' => $this->getInput('description'),
                'criteria' => $this->getInput('criteria'),
            ]);
            Session::setSuccess('Badge created.');
            $this->redirect('/admin/settings/badges');
        }

        $badges = Database::fetchAll("SELECT * FROM badges ORDER BY id ASC");
        $this->renderAdmin('admin/settings/badges', ['title' => 'Badges', 'badges' => $badges]);
    }

    public function levels(): void
    {
        if ($this->isPost()) {
            Database::insert('levels', [
                'name' => $this->getInput('name'),
                'slug' => slugify($this->getInput('name')),
                'min_earnings' => (float) $this->getInput('min_earnings'),
                'max_earnings' => (float) $this->getInput('max_earnings') ?: null,
                'benefits' => $this->getInput('benefits'),
            ]);
            Session::setSuccess('Level created.');
            $this->redirect('/admin/settings/levels');
        }

        $levels = Database::fetchAll("SELECT * FROM levels ORDER BY min_earnings ASC");
        $this->renderAdmin('admin/settings/levels', ['title' => 'Levels', 'levels' => $levels]);
    }

    public function coupons(): void
    {
        if ($this->isPost()) {
            Database::insert('coupons', [
                'code' => strtoupper($this->getInput('code')),
                'discount_type' => $this->getInput('discount_type'),
                'discount_value' => (float) $this->getInput('discount_value'),
                'min_amount' => (float) $this->getInput('min_amount') ?: null,
                'max_uses' => (int) $this->getInput('max_uses'),
                'expires_at' => $this->getInput('expires_at') ?: null,
            ]);
            Session::setSuccess('Coupon created.');
            $this->redirect('/admin/settings/coupons');
        }

        $coupons = Database::fetchAll("SELECT * FROM coupons ORDER BY created_at DESC");
        $this->renderAdmin('admin/settings/coupons', ['title' => 'Coupons', 'coupons' => $coupons]);
    }
}
