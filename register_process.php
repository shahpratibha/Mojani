<?php

date_default_timezone_set('UTC');
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $occupation = $_POST["occupation"];
    $contact_no = $_POST["contact_no"];

    // Prepare the SQL statement with a placeholder for the current timestamp
    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password, occupation, contact_no, user_entry_timestamp) 
        VALUES (?, ?, ?, ?, ?, NOW() AT TIME ZONE 'Asia/Kolkata')
    ");
    $stmt->execute([$username, $email, $password, $occupation, $contact_no]);
    
    // Redirect to form.php after successful registration
    header("Location: login.php");
    exit();
}
?>
