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
        $coordinates = $lat . ',' . $lng;
        $district = $data['district'];
        $taluka = $data['taluka'];
        $village = $data['village'];
        $survey_number = $data['survey_number'];
        $username = $data['username'];

        // Insert the coordinates into survey_data table
        $sql = "INSERT INTO survey_data (district, taluka, village, survey_number, username, coordinates) 
                VALUES (:district, :taluka, :village, :survey_number, :username, :coordinates)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':district', $district, PDO::PARAM_STR);
        $stmt->bindParam(':taluka', $taluka, PDO::PARAM_STR);
        $stmt->bindParam(':village', $village, PDO::PARAM_STR);
        $stmt->bindParam(':survey_number', $survey_number, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':coordinates', $coordinates, PDO::PARAM_STR);

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
