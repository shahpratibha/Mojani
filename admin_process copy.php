<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful, set session variables
        $_SESSION['login_status'] = "success";
        $_SESSION['username'] = $user['username']; // Assuming 'username' is the column name in your 'users' table
        header("Location: adminProfile.php"); // Redirect to your dashboard or adminProfile page
        exit();
    } else {
        // Login failed, set session variables for error handling
        $_SESSION['login_status'] = "failed";
        $_SESSION['error_message'] = "Invalid email or password. Please try again.";
        header("Location: admin.php"); // Redirect back to login page
        exit();
    }
}
?>
