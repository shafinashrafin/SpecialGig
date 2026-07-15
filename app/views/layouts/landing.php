<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'SpecialGig') ?> | SpecialGig</title>
    <meta name="description" content="<?= e($meta_description ?? 'Premium Micro Job Marketplace') ?>">
    <meta property="og:title" content="<?= e($title ?? 'SpecialGig') ?>">
    <meta property="og:description" content="<?= e($meta_description ?? 'Premium Micro Job Marketplace') ?>">
    <meta property="og:type" content="website">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= url() ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/assets/css/app.css">
    <style>
        body { font-family: 'Inter', 'Hind Siliguri', sans-serif; }
    </style>
</head>
<body>
    <?php require VIEWS_PATH . '/partials/header.php'; ?>
    <main>
        <?= $viewContent ?? '' ?>
    </main>
    <?php require VIEWS_PATH . '/partials/footer.php'; ?>
    <?php flash_messages(); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <script src="/public/assets/js/app.js"></script>
</body>
</html>
