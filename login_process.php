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
        $_SESSION['user_id'] = $user['user_id'];

        if ($user['accepted_terms']) {
            header("Location: index.php");
        } else {
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
