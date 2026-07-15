<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('VIEWS_PATH', APP_PATH . '/views');
define('UPLOADS_PATH', PUBLIC_PATH . '/assets/uploads');
define('UPLOADS_URL', 'assets/uploads');

define('ADMIN_PREFIX', 'admin');

define('USER_ROLES', ['admin', 'buyer', 'worker']);
define('JOB_STATUSES', ['pending', 'active', 'paused', 'completed', 'cancelled', 'rejected']);
define('APPLICATION_STATUSES', ['pending', 'in_progress', 'submitted', 'approved', 'rejected', 'completed']);
define('TRANSACTION_TYPES', ['deposit', 'withdrawal', 'payment', 'refund', 'commission', 'referral', 'bonus']);
define('PAYMENT_METHODS', ['bkash', 'nagad', 'rocket', 'bank_transfer', 'paypal', 'stripe', 'crypto_usdt', 'manual']);
define('TICKET_STATUSES', ['open', 'in_progress', 'waiting', 'resolved', 'closed']);
define('NOTIFICATION_TYPES', ['info', 'success', 'warning', 'error']);

define('CATEGORIES', [
    'facebook' => 'Facebook',
    'youtube' => 'YouTube',
    'instagram' => 'Instagram',
    'tiktok' => 'TikTok',
    'telegram' => 'Telegram',
    'twitter' => 'Twitter/X',
    'website_visit' => 'Website Visit',
    'app_install' => 'App Install',
    'survey' => 'Survey',
    'signup' => 'Signup',
    'review' => 'Review',
    'seo' => 'SEO',
    'ai_tasks' => 'AI Tasks',
    'other' => 'Other Tasks',
]);
