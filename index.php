<?php
    include 'templates/header.php';
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
