<?php

date_default_timezone_set('Asia/Kolkata');
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $occupation = $_POST["occupation"];
    $contact_no = $_POST["contact_no"];

    // Validate the email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && substr($email, -4) === '.com') {
        // Prepare the SQL statement with a placeholder for the current timestamp
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, occupation, contact_no, user_entry_timestamp) 
            VALUES (?, ?, ?, ?, ?, NOW() AT TIME ZONE 'Asia/Kolkata')
        ");
        $stmt->execute([$username, $email, $password, $occupation, $contact_no]);
        
        // Redirect to login.php after successful registration
        header("Location: login.php");
        exit();
    } else {
        // Display an error message if email is invalid
        echo "Invalid email address. Please ensure your email ends with '.com'.";
    }
}
?>
