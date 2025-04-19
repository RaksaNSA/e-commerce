<?php

    require_once 'config.php';

    define('DB_SERVER', 'localhost');
    define('DB_USER','root');
    define('DB_PASSWORD', '');
    define('DB_NAME','ecommerce_db');

    function getDbConnection() {
        $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
    if (mysqli_connect_errno()) {
        die('Database connection Failed!...'. mysqli_connect_error());
    }
        return $conn;
    }

    getDbConnection();


    
?>