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
            $error = $_FILES[$inputName]['error'];

            // Check for upload errors
            if ($error == UPLOAD_ERR_OK) {
                $targetFile = $targetDirectory . basename($_FILES[$inputName]["name"]);
                if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) {
                    // Return filename
                    return basename($_FILES[$inputName]["name"]);
                } else {
                    // Log error
                    error_log("Error: Unable to move uploaded file.");
                    return false;
                }
            } else {
                // Log specific upload error
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                    UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                    UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
                    UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                    UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
                ];
                $errorMessage = isset($errorMessages[$error]) ? $errorMessages[$error] : 'Unknown upload error.';
                error_log("Upload Error: " . $errorMessage);
                return false;
            }
        }
        // Log no file uploaded error
        error_log("Error: No file uploaded.");
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
        //$_SESSION['error'] = "At least one PDF file is required.";
        //header("Location: form.php");
        //exit();
    }

    // Prepare and execute SQL insert statement
    $stmt = $pdo->prepare("INSERT INTO survey_data (username, district, taluka, village, survey_number, survey_map_filename, village_map_filename, pdf_7_12_filename, user_entry_timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW() AT TIME ZONE 'Asia/Kolkata')");
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
