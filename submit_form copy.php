<?php
session_start();
include('db.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $district = $_POST['input1'];
    $taluka = $_POST['input2'];
    $village = $_POST['input3'];
    $survey_number = $_POST['input4'];

    function handleFileUpload($inputName, $targetDirectory)
    {
        if (isset($_FILES[$inputName])) {
            $targetFile = $targetDirectory . basename($_FILES[$inputName]["name"]);
            if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) {
                return $targetFile;
            } else {
                return false;
            }
        }
        return null;
    }

    // Directory where files will be stored
    $uploadDir = "uploads/";

    // Check if directory exists and create it if necessary
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory recursively
    }

    // Handle file uploads and get file paths
    $surveyMapPath = handleFileUpload("survey_map", $uploadDir);
    $villageMapPath = handleFileUpload("village_map", $uploadDir);
    $pdf712Path = handleFileUpload("pdf_7_12", $uploadDir);

    // Check for errors after handling file uploads
    if ($surveyMapPath === false || $villageMapPath === false || $pdf712Path === false) {
        echo "Error uploading files.";
        error_log("File upload error: " . json_encode($_FILES)); // Log $_FILES array to identify specific errors
        exit;
    }


    if ($success) {
        echo "Data inserted successfully.";
    } else {
        echo "Error inserting data.";
    }


    // Retrieve logged-in user's username from session
    if (isset($_SESSION['username'])) {
        $logged_in_user = $_SESSION['username'];
    } else {

        exit("User not logged in.");
    }

    // // Prepare and execute SQL insert statement
    // Prepare and execute SQL insert statement
    $stmt = $pdo->prepare("INSERT INTO survey_data (username, district, taluka, village, survey_number, survey_map_path, village_map_path, pdf_7_12_path, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $success = $stmt->execute([$logged_in_user, $district, $taluka, $village, $survey_number, $surveyMapPath, $villageMapPath, $pdf712Path]);

    // Redirect back to form or success page
    header("Location: index.php"); // Replace with your desired redirect location
    exit();
}
