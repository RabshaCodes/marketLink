<?php
// $host = 'localhost';
// $dbname = 'market_link';
// $username = 'market_link_app';
// $password = 'marketlink@123';


$host = 'localhost';
$dbname = 'market_link';
$username = 'root';
$password = 'DataBase@2025';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Return objects or associative arrays by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Connection failed: " . $e->getMessage());
}
?>