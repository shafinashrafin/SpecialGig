<?php
session_start();

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, '"\'');
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/App.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/Model.php';
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/core/Auth.php';
require_once __DIR__ . '/app/core/Validator.php';
require_once __DIR__ . '/app/core/Mail.php';

require_once __DIR__ . '/app/helpers/functions.php';

$app = new App();
$app->run();
