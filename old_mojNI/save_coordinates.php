<?php
// save_coordinates.php

session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['lat']) && isset($data['lng']) && isset($data['user_id'])) {
        $lat = $data['lat'];
        $lng = $data['lng'];
        $user_id = $data['user_id'];
        $coordinates = $lat . ',' . $lng;

        // Update the user_login table to store the coordinates
        $sql = "UPDATE user_login SET coordinates = :coordinates WHERE user_id = :user_id AND id = :login_record_id";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':coordinates', $coordinates, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':login_record_id', $_SESSION['login_record_id'], PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Coordinates saved successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error saving coordinates.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
