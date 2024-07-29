<?php
session_start();


include('db.php');

// Set the default time zone to your local time zone
date_default_timezone_set('Asia/Kolkata'); // Replace 'Asia/Kolkata' with your local time zone

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login_status'] = "success";
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id'];

        // Get the current time in local time zone
        $current_time = date('Y-m-d H:i:s');

        // Insert login time and username into the user_login table
        $insertStmt = $pdo->prepare("INSERT INTO user_login (user_id, username, login_time) VALUES (?, ?, ?)");
        $insertStmt->execute([$user['user_id'], $user['username'], $current_time]);

        $_SESSION['login_record_id'] = $pdo->lastInsertId();

        if ($user['accepted_terms']) {
            echo "Redirecting to form.php"; // Debug statement
            header("Location: form.php");
            exit();
        } else {
            echo "Redirecting to terms_and_condition.php"; // Debug statement
            header("Location: terms_and_condition.php");
            exit();
        }
    } else {
        $_SESSION['login_status'] = "failed";
        $_SESSION['error_message'] = "Invalid email or password. Please try again.";
        header("Location: login.php");
        exit();
    }
}
?>
