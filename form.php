<?php
// form.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Page</title>
    <link rel="stylesheet" type="text/css" href="css/form.css">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-family: Roboto;
            font-size: 16px;
        }

        .container {
            max-width: 400px;
            background-color: #fff;
            padding: 10px 16px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            font-size: 16px;
            margin: 0 auto;
            margin-top: 0;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            color: black;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .header i {
            width: 32px;
            height: 32px;
            cursor: pointer;
        }

        .header span {
            color: var(--Grey-800, #262626);
            font-family: Roboto;
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            margin-left: 70%;
        }

        h2 {
            color: var(--Grey-800, #262626);
            font-family: Roboto;
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
        }

        .form-label {
            color: var(--Grey-800, #262626);
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
            margin-bottom: 5px;
        }

        .form-control {
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 14px;
            color: var(--Grey-800, #262626);
            border: 1px solid var(--Grey-300, #A8A8A8);
        }

        .btn-submit {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            font-size: 14px;
        }

        .file-upload {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .file-upload input[type="file"] {
            display: none;
        }

        .file-upload label {
            background-color: #007bff;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .file-upload span {
            flex-grow: 1;
            margin-left: 10px;
            color: #6c757d;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            text-align: center;
        }

        .scrollable-form {
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
            box-sizing: border-box;
            width: 100%;
        }

        .scrollable-form::-webkit-scrollbar {
            width: 8px;
        }

        .scrollable-form::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .scrollable-form::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .scrollable-form::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .dropdown-item i {
            margin-right: 5px;
        }

        .Geo{
            position: absolute;
            z-index: 9999;
            top: 0;
            color: black;
            /* width: 4dvw; */
            height: 7dvh;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="header">
            <img src="./image/geopulse_logo-removebg-preview.png" alt="Logo" class="Geo mt-3">
            <span>New User</span>
            <div class="dropdown">
                <i class="fas fa-user-circle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false"></i>
                <ul class="dropdown-menu" aria-labelledby="userMenu">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> User Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        <h2>Upload Form</h2>
        <form method="post" action="submit_form.php" enctype="multipart/form-data" class="scrollable-form">
            <div class="mb-3">
                <label for="input1" class="form-label">District</label>
                <input type="text" class="form-control" name="input1" id="input1" required>
            </div>
            <div class="mb-3">
                <label for="input2" class="form-label">Taluka</label>
                <input type="text" class="form-control" name="input2" id="input2" required>
            </div>
            <div class="mb-3">
                <label for="input3" class="form-label">Village</label>
                <input type="text" class="form-control" name="input3" id="input3" required>
            </div>
            <div class="mb-3">
                <label for="input4" class="form-label">Survey Number <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="input4" id="input4" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload Survey Map PDF <span class="text-danger">*</span></label>
                <div class="file-upload">
                    <label for="surveyMap">Choose File</label>
                    <input type="file" id="surveyMap" accept=".pdf" name="survey_map" onchange="handleFileUpload(this, 'surveyMapFilePath')" required>
                    <span id="surveyMapFilePath">No file chosen</span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload Village Map PDF</label>
                <div class="file-upload">
                    <label for="villageMap">Choose File</label>
                    <input type="file" id="villageMap" accept=".pdf" name="village_map" onchange="handleFileUpload(this, 'villageMapFilePath')">
                    <span id="villageMapFilePath">No file chosen</span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload 7/12 PDF</label>
                <div class="file-upload">
                    <label for="pdf7_12">Choose File</label>
                    <input type="file" id="pdf7_12" accept=".pdf" name="pdf_7_12" onchange="handleFileUpload(this, 'pdf7_12FilePath')">
                    <span id="pdf7_12FilePath">No file chosen</span>
                </div>
            </div>
            <button type="submit" class="btn-submit">Submit</button>
        </form>
    </div>

    <script>
        function handleFileUpload(input, targetId) {
            const fileName = input.files[0] ? input.files[0].name : 'No file chosen';
            document.getElementById(targetId).textContent = fileName;
        }

        document.getElementById('cancel-icon').addEventListener('click', function () {
        if (confirm("Are you sure you want to cancel?")) {
            window.location.href = "index.php";
        }
    });
    </script>
</body>

</html>