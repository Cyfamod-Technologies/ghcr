<?php

$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_DATABASE') ?: 'testdb';
$user = getenv('DB_USERNAME') ?: 'user';
$pass = getenv('DB_PASSWORD') ?: 'password';

echo "Everything done on GHCR! 👋<br>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "Database connection: ✅ Connected";
} catch (PDOException $e) {
    echo "Database connection: ❌ Failed<br>";
    echo "Error: " . $e->getMessage();
}

?>