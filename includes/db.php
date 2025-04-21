<?php
$host = 'localhost';
$db   = 'ecommerce_db';
$user = 'root';  // or your DB username
$pass = '';      // your password if any

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage());
}
?>
