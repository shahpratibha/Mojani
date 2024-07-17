<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login_status'] = "success";
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id']; // Changed from 'id' to 'user_id'

        // Debug output
        echo "User ID: " . $_SESSION['user_id'] . "<br>";
        echo "Accepted Terms: " . ($user['accepted_terms'] ? 'true' : 'false') . "<br>";

        if ($user['accepted_terms']) {
            echo "Redirecting to index.php<br>"; // Debug output
            header("Location: index.php");
        } else {
            echo "Redirecting to terms_and_condition.php<br>"; // Debug output
            header("Location: terms_and_condition.php");
        }
        exit();
    } else {
        $_SESSION['login_status'] = "failed";
        $_SESSION['error_message'] = "Invalid email or password. Please try again.";
        header("Location: login.php");
        exit();
    }
}
