<?php


$host = "157.173.222.9";
$port = "5432";
$dbname = "Mojani_new";
$user = "postgres";
$password = "Mojani@992101";


// $host = "rr.c01x1jtcm1ms.ap-south-1.rds.amazonaws.com";
// $port = "5432";
// $dbname = "Mojani";
// $user = "postgres";
// $password = "Pmc992101";




try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}
?>
