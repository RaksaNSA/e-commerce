<?php
    require_once __DIR__ . '/includes/config.php';
    include __DIR__ . '/templates/header.php';
    include __DIR__ . '/templates/navegation.php';


    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    $pagepath = 'pages/' . $page . '.php';
    if (file_exists($pagepath)) {
        include $pagepath;
    } else {
        include 'pages/404.php';
    }
    include 'pages/home.php';
    include 'templates/footer.php';
?>
