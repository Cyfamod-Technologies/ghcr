<?php

$host = getenv('DB_HOST') ?: 'localhost';
$db   = geenv('DB_DATABASE') ?: 'testdb';
$user = getenv('DB_USERNAME') ?: 'user';
$pass = getenv('DB_PASSWORD') ?: 'password';

echo "Rollback CICD done on GHCR! 👋<br>";

try {
    $pdo = new PO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "Database connection: ✅ Connected";
} catch (PDOException $e) {
    echo "Database connection: ❌ Failed<br>";
    echo "Error: " . $e->getMessage();
}

?>