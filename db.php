<?php
$host = "157.173.222.9";
$port = "5432";
$dbname = "Mojani";
$user = "postgres";
$password = "Geo992101";


try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}
?>
