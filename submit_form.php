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
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == UPLOAD_ERR_OK) {
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

    // Check if at least one PDF file is uploaded
    if (!$surveyMapFileName && !$villageMapFileName && !$pdf712FileName) {
        $_SESSION['error'] = "At least one PDF file is required.";
        header("Location: form.php");
        exit();
    }

    // Prepare and execute SQL insert statement
    $stmt = $pdo->prepare("INSERT INTO survey_data (username, district, taluka, village, survey_number, survey_map_filename, village_map_filename, pdf_7_12_filename, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $success = $stmt->execute([
        $logged_in_user, 
        $district, 
        $taluka, 
        $village, 
        $survey_number, 
        $surveyMapFileName, 
        $villageMapFileName, 
        $pdf712FileName
    ]);

    // Check if insertion was successful
    if ($success) {
        header("Location: form.php?success=true");
    } else {
        $_SESSION['error'] = "Error inserting data.";
        header("Location: form.php");
    }

    exit();
}
?>
