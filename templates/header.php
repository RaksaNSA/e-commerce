<?php require_once __DIR__ . '/../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>

        <!-- Site CSS -->
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/tiny-slider.css">

        <!-- Bootstrap CSS (from CDN or local if you move it into assets) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>
<body>
