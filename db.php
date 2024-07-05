<?php
$host = "rr.c01x1jtcm1ms.ap-south-1.rds.amazonaws.com";
$port = "5432";
$dbname = "Mojani";
$user = "postgres";
$password = "Pmc992101";


try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}
?>
