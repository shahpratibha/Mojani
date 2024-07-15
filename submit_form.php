<?php
session_start();
include('db.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve logged-in user's username from session
    if (isset($_SESSION['username'])) {
        $logged_in_user = $_SESSION['username'];
    } else {
        exit("User not logged in.");
    }

    // Fetch input values
    $district = $_POST['input1'];
    $taluka = $_POST['input2'];
    $village = $_POST['input3'];
    $survey_number = $_POST['input4'];

    // Function to handle file uploads
    function handleFileUpload($inputName, $targetDirectory)
    {
        if (isset($_FILES[$inputName])) {
            $targetFile = $targetDirectory . basename($_FILES[$inputName]["name"]);
            if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) {
                return basename($_FILES[$inputName]["name"]); // Return only the filename
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

    // Handle file uploads and get file names
    $surveyMapFileName = handleFileUpload("survey_map", $uploadDir);
    $villageMapFileName = handleFileUpload("village_map", $uploadDir);
    $pdf712FileName = handleFileUpload("pdf_7_12", $uploadDir);

    // Check for errors after handling file uploads
    if ($surveyMapFileName === false || $villageMapFileName === false || $pdf712FileName === false) {
        echo "Error uploading files.";
        error_log("File upload error: " . json_encode($_FILES)); // Log $_FILES array to identify specific errors
        exit;
    }

    // Prepare and execute SQL insert statement
    $stmt = $pdo->prepare("INSERT INTO survey_data (username, district, taluka, village, survey_number, survey_map_filename, village_map_filename, pdf_7_12_filename, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $success = $stmt->execute([$logged_in_user, $district, $taluka, $village, $survey_number, $surveyMapFileName, $villageMapFileName, $pdf712FileName]);

    // Check if insertion was successful
    if ($success) {
        header("Location: index.php?success=true");
    } else {
        header("Location: index.php?success=false");
    }

    exit();
}
?>
