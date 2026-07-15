<?php
class Mail
{
    public static function send(string $to, string $subject, string $message, array $headers = []): bool
    {
        $appConfig = require CONFIG_PATH . '/app.php';
        $mailConfig = $appConfig['mail'];

        $defaultHeaders = [
            'From' => "{$mailConfig['from_name']} <{$mailConfig['from_address']}>",
            'Reply-To' => $mailConfig['from_address'],
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/html; charset=UTF-8',
            'X-Mailer' => 'SpecialGig Mailer',
        ];

        $headers = array_merge($defaultHeaders, $headers);
        $headerString = '';
        foreach ($headers as $key => $value) {
            $headerString .= "{$key}: {$value}\r\n";
        }

        return mail($to, $subject, $message, $headerString);
    }

    public static function sendTemplate(string $to, string $subject, string $template, array $data = []): bool
    {
        $body = self::renderTemplate($template, $data);
        return self::send($to, $subject, $body);
    }

    private static function renderTemplate(string $template, array $data = []): string
    {
        $templateFile = VIEWS_PATH . "/emails/{$template}.php";
        if (file_exists($templateFile)) {
            extract($data);
            ob_start();
            require $templateFile;
            return ob_get_clean();
        }
        return '';
    }
}
