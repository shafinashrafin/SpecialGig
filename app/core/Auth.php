<?php
class Auth
{
    public static function attempt(string $email, string $password, bool $remember = false): bool
    {
        $user = Database::fetch(
            "SELECT * FROM users WHERE email = :email LIMIT 1",
            ['email' => $email]
        );

        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        if ($user->status !== 'active') {
            return false;
        }

        self::login($user);

        if ($remember) {
            self::setRememberToken($user->id);
        }

        Database::query(
            "UPDATE users SET last_login = NOW(), ip_address = :ip WHERE id = :id",
            ['ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', 'id' => $user->id]
        );

        Database::insert('login_history', [
            'user_id' => $user->id,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'status' => 'success',
        ]);

        return true;
    }

    public static function login($user): void
    {
        Session::set('user_id', $user->id);
        Session::set('user_role', $user->role);
        Session::set('username', $user->username);
        Session::set('email', $user->email);
        Session::regenerate();
    }

    public static function logout(): void
    {
        $userId = self::id();
        if ($userId) {
            Database::query("UPDATE users SET remember_token = NULL WHERE id = :id", ['id' => $userId]);
        }
        Session::destroy();
    }

    public static function user()
    {
        if (!self::check()) {
            return null;
        }
        return Database::fetch(
            "SELECT u.*, up.full_name, up.avatar, up.country, up.phone, up.bio
             FROM users u
             LEFT JOIN user_profiles up ON u.id = up.user_id
             WHERE u.id = :id",
            ['id' => Session::get('user_id')]
        );
    }

    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function role(): ?string
    {
        return Session::get('user_role');
    }

    public static function isAdmin(): bool
    {
        return self::check() && self::role() === 'admin';
    }

    public static function isBuyer(): bool
    {
        return self::check() && self::role() === 'buyer';
    }

    public static function isWorker(): bool
    {
        return self::check() && self::role() === 'worker';
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            Session::setInfo('Please login to continue.');
            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        if (!self::isAdmin()) {
            Session::setError('Access denied. Admin only.');
            header('Location: /');
            exit;
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAuth();
        if (self::role() !== $role) {
            Session::setError('Access denied.');
            header('Location: /');
            exit;
        }
    }

    public static function guest(): void
    {
        if (self::check()) {
            header('Location: /dashboard');
            exit;
        }
    }

    private static function setRememberToken(int $userId): void
    {
        $token = bin2hex(random_bytes(32));
        Database::query("UPDATE users SET remember_token = :token WHERE id = :id", [
            'token' => $token,
            'id' => $userId,
        ]);
        setcookie('remember_token', $token, time() + 86400 * 30, '/', '', true, true);
    }
}
