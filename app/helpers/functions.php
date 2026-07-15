<?php
function asset(string $path): string
{
    return '/public/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    $base = rtrim((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/');
    return $base . '/' . ltrim($path, '/');
}

function route(string $path = ''): string
{
    return '/' . ltrim($path, '/');
}

function old(string $key, $default = '')
{
    return $_POST[$key] ?? $default;
}

function csrf_field(): void
{
    $token = $_SESSION['_token'] ?? bin2hex(random_bytes(32));
    $_SESSION['_token'] = $token;
    echo '<input type="hidden" name="_token" value="' . $token . '">';
}

function csrf_token(): string
{
    if (empty($_SESSION['_token'])) {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_token'];
}

function verify_csrf(string $token): bool
{
    return hash_equals($_SESSION['_token'] ?? '', $token);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function format_currency(float $amount, string $currency = 'USD'): string
{
    return '$' . number_format($amount, 2);
}

function format_date(string $date, string $format = 'M d, Y'): string
{
    return date($format, strtotime($date));
}

function time_ago(string $date): string
{
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;

    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('M d, Y', $timestamp);
}

function truncate(string $text, int $length = 100): string
{
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

function slugify(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'n-a';
}

function random_string(int $length = 16): string
{
    return bin2hex(random_bytes($length / 2));
}

function get_avatar($user, int $size = 80): string
{
    if (!empty($user->avatar)) {
        return asset($user->avatar);
    }
    $name = $user->full_name ?? $user->username ?? 'U';
    $initial = strtoupper(substr($name, 0, 1));
    return "https://ui-avatars.com/api/?name=" . urlencode($initial) . "&size={$size}&background=6366f1&color=fff";
}

function get_stars(int $rating): string
{
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '<svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>';
        } else {
            $stars .= '<svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>';
        }
    }
    return $stars;
}

function get_status_badge(string $status): string
{
    $map = [
        'active' => 'bg-green-100 text-green-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
        'completed' => 'bg-blue-100 text-blue-800',
        'cancelled' => 'bg-red-100 text-red-800',
        'rejected' => 'bg-red-100 text-red-800',
        'paused' => 'bg-gray-100 text-gray-800',
        'submitted' => 'bg-purple-100 text-purple-800',
        'approved' => 'bg-green-100 text-green-800',
        'in_progress' => 'bg-blue-100 text-blue-800',
        'suspended' => 'bg-red-100 text-red-800',
        'open' => 'bg-blue-100 text-blue-800',
        'resolved' => 'bg-green-100 text-green-800',
        'closed' => 'bg-gray-100 text-gray-800',
        'waiting' => 'bg-yellow-100 text-yellow-800',
    ];
    $class = $map[$status] ?? 'bg-gray-100 text-gray-800';
    return "<span class=\"px-2.5 py-0.5 rounded-full text-xs font-medium {$class}\">" . ucfirst(str_replace('_', ' ', $status)) . '</span>';
}

function get_setting(string $key, $default = null)
{
    static $settings = null;
    if ($settings === null) {
        $rows = Database::fetchAll("SELECT `key`, `value` FROM settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row->key] = $row->value;
        }
    }
    return $settings[$key] ?? $default;
}

function is_active(string $path): string
{
    $current = $_GET['url'] ?? '';
    return $current === ltrim($path, '/') ? 'active' : '';
}

function get_route(): string
{
    return $_GET['url'] ?? 'home';
}

function has_permission(string $permission): bool
{
    $role = Auth::role();
    if ($role === 'admin') return true;
    return false;
}

function generate_reference(): string
{
    return 'SG-' . strtoupper(random_string(8));
}

function flash_messages(): void
{
    foreach (['error', 'success', 'info', 'warning'] as $type) {
        $msg = Session::flash($type);
        if ($msg): ?>
            <div class="alert alert-<?= $type ?> fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white text-sm
                <?= $type === 'error' ? 'bg-red-500' : ($type === 'success' ? 'bg-green-500' : ($type === 'info' ? 'bg-blue-500' : 'bg-yellow-500')) ?>"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <div class="flex items-center gap-2">
                    <span><?= $type === 'error' ? '✕' : ($type === 'success' ? '✓' : ($type === 'info' ? 'ℹ' : '⚠')) ?></span>
                    <span><?= e($msg) ?></span>
                    <button @click="show = false" class="ml-4 opacity-70 hover:opacity-100">&times;</button>
                </div>
            </div>
        <?php endif;
    }
}

function get_difficulty_color(string $difficulty): string
{
    return match ($difficulty) {
        'beginner' => 'bg-green-100 text-green-700',
        'intermediate' => 'bg-yellow-100 text-yellow-700',
        'advanced' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-700',
    };
}
