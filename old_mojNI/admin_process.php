<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login_status'] = "success";
        $_SESSION['username'] = $user['username'];
        header("Location: adminProfile.php");
        exit();
    } else {
        $_SESSION['login_status'] = "failed";
        $_SESSION['error_message'] = "Invalid email or password. Please try again.";
        header("Location: admin.php");
        exit();
    }
}
?>
