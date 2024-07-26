<?php
// form.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$logged_in_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojani</title>
    <link rel="icon" href="image\LOGO_DP_Zoning.png" type="image/x-icon">
    <link rel="stylesheet" href="mainmodal/legend.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <!-- <link rel="stylesheet" href="css/index_new.css"> -->
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <!-- fontawsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- leaflet-ajax -->
    <script src="https://unpkg.com/leaflet-ajax/dist/leaflet.ajax.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    <style>
        .modal-content {
            height: auto;
            max-height: 400px; /* Adjust this value as needed */
         
        }
        .modal-body {
            padding: 10px; /* Reduce padding if needed */
        }
        .modal-footer {
            padding: 10px; /* Reduce padding if needed */
        }
    </style>

<style>

.leaflet-control-scale-line {
    border: 2px solid #777;
    border-top: none;
    line-height: 1.1;
    padding: 2px 1px;
    white-space: nowrap;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    background: rgba(255, 255, 255, 0.8);
    text-shadow: 1px 1px #fff;
    font-size: 7px;
}
.leaflet-touch .leaflet-bar a {
    width: 20px;
    font-size: 18px;
    height: 20px;
    line-height: 20px;
}
.leaflet-touch .leaflet-control-layers-toggle {
    width: 25px;
    height: 25px;
}

.leaflet-retina .leaflet-control-layers-toggle {
    background-image: url('./image/Layers_Icon.png');
    background-size: 29px 29px;
}

    .north-arrow {
    width: 20px;
    height: 27px;
    background-color: white;
    border: 2px solid darkblue;
}
        .upload-container {
            width: 300px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .file-input-container {
            margin-bottom: 20px;
        }

        .file-input-container label {
            display: block;
            cursor: pointer;
            padding: 10px;
            border: 2px dashed #ccc;
            text-align: center;
            border-radius: 5px;
            transition: border-color 0.3s ease;
            background-color: white;
        }

        .file-input-container input {
            display: none;
        }

        .file-input-container label:hover {
            border-color: #888;
        }

        .file-progress {
            margin-top: 10px;
        }

        .file-progress span {
            display: block;
        }
    </style>
</head>

<body>

    <section class="row">
        <div class="col-12">

            <div class="profile justify-content-end ">
                <ul>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                            <?php echo $logged_in_user; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php?username=<?php echo $logged_in_user; ?>">View Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="logout.php" method="post">
                                    <button class="dropdown-item" type="submit" name="Logout">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <a class="Geo" href="#"><img src="image/LOGO_DP_Zoning.png" alt=""></a>
        

            <!-- Success Message Modal -->
            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Success</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Your form has been submitted successfully!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <?php
       if (isset($_SESSION['error'])) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
              
            <button type="button" class="menu-bar" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <img src="image/menu.png" alt=" image not found" height="25" width="25">
            </button>

            <div class="col-12 col-md-3 from">
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog draggable-modal">
                        <div class="modal-content">
                         
                            <div class="modal-body">
                            <div class="toggle-switch">
                <input type="checkbox" id="toggle" class="toggle-input">
                <label for="toggle" class="toggle-label">
                    <span class="toggle-text toggle-text-left">State</span>
                    <span class="toggle-handle"></span>
                    <span class="toggle-text toggle-text-right">Maharashtra</span>
                </label>
            </div>
                                <button type="button" class="btn-close custom-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                                <hr>
                           
                            
    <div class="modal-body">                  
    <form method="post" action="submit_form.php" enctype="multipart/form-data">
    <div class="form-group">
        <label>District</label>
        <select class="form-control" name="input1" id="input1"></select>
    </div>
    
    <div class="form-group">
        <label>Taluka</label>
        <select class="form-control" name="input2" id="input2"></select>
    </div>

    <div class="form-group">
        <label>Village</label>
        <select class="form-control" name="input3" id="input3"></select>
    </div>

    <div class="form-group">
        <label>Survey No<span class="text-danger fs-3">*</span></label>
        <input class="form-control" type="text" name="input4" id="input4" required>
    </div>

                <div class="upload-container">
    <form action="submit_form.php" method="post" enctype="multipart/form-data">
        <div class="file-input-container">
            <label>
                <input type="file" class="file-input" accept=".pdf" name="survey_map" onchange="handleFileUpload(this, 'surveyMapFilePath')" required multiple>
                Upload Survey Map PDF 
            </label>
            <div id="surveyMapFilePath" class="file-progress"></div>
        </div>

        <div class="file-input-container">
            <label>
                <input type="file" class="file-input" accept=".pdf" name="village_map" onchange="handleFileUpload(this, 'villageMapFilePath')" multiple>
                Upload Village Map PDF
            </label>
            <div id="villageMapFilePath" class="file-progress"></div>
        </div>

        <div class="file-input-container">
            <label>
                <input type="file" class="file-input" accept=".pdf" name="pdf_7_12" onchange="handleFileUpload(this, 'pdf7_12FilePath')" multiple>
                Upload 7/12 PDF
            </label>
            <div id="pdf7_12FilePath" class="file-progress"></div>
        </div>

        <div class="file-input-container">
            <button type="submit" value="Submit" class="btn btn-outline-success">Submit</button>
        </div>

        
    </form>
</div>
       </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="map"></div>
            </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="js/index.js"></script>
    <script src="mainmodal/legend.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        function handleFileUpload(input, targetId) {
            // Display the file name in the specified target element
            var fileList = input.files;
            var fileNames = [];
            for (var i = 0; i < fileList.length; i++) {
                fileNames.push(fileList[i].name);
            }
            document.getElementById(targetId).innerText = fileNames.join(', ');
        }

        $(document).ready(function() {
            <?php if (isset($_GET['success']) && $_GET['success'] == 'true') { ?>
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            <?php } ?>
        });


        
    </script>
</body>

</html>
