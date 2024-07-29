<?php
session_start();
include('db.php');

// Set the default time zone to your local time zone
date_default_timezone_set('Asia/Kolkata'); // Replace 'Asia/Kolkata' with your local time zone

if (isset($_POST['Logout'])) {
    $login_record_id = $_SESSION['login_record_id'];

    // Get the current time in local time zone
    $current_time = date('Y-m-d H:i:s');

    // Update logout time in the user_login table
    $updateStmt = $pdo->prepare("UPDATE user_login SET logout_time = ? WHERE id = ?");
    $updateStmt->execute([$current_time, $login_record_id]);

    // Clear the session
    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}
?>
