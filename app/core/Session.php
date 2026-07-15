<?php
class Session
{
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, string $message = null): ?string
    {
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;
            return null;
        }
        if (isset($_SESSION['_flash'][$key])) {
            $msg = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $msg;
        }
        return null;
    }

    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    public static function setError(string $message): void
    {
        self::flash('error', $message);
    }

    public static function setSuccess(string $message): void
    {
        self::flash('success', $message);
    }

    public static function setInfo(string $message): void
    {
        self::flash('info', $message);
    }

    public static function setWarning(string $message): void
    {
        self::flash('warning', $message);
    }

    public static function error(): ?string
    {
        return self::flash('error');
    }

    public static function success(): ?string
    {
        return self::flash('success');
    }

    public static function info(): ?string
    {
        return self::flash('info');
    }

    public static function warning(): ?string
    {
        return self::flash('warning');
    }

    public static function destroy(): void
    {
        session_destroy();
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }
}
