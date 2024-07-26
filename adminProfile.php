<?php
session_start();
include('db.php');

// Connect to the database
$conn = pg_connect("host=157.173.222.9 dbname=Mojani_new user=postgres password=Mojani@992101");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php"); 
    exit();
}

// Get logged-in user's name
$logged_in_user = $_SESSION['username'];

// Fetch logged-in user details
$logged_user_sql = "SELECT * FROM public.admin_users WHERE username = $1";
$logged_user_result = pg_query_params($conn, $logged_user_sql, array($logged_in_user));

if (!$logged_user_result) {
    die("Error in SQL query: " . pg_last_error());
}

$logged_user = pg_fetch_assoc($logged_user_result);

if (!$logged_user) {
    die("Logged-in user not found.");
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['uploadFile'])) {
    $survey_id = $_POST['survey_id'];
    $username = $_POST['username']; // This should be the survey_data username
    $admin_username = $_SESSION['username'];
    $file = $_FILES['uploadFile'];

    // File upload path
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Allow certain file formats (optional)
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($fileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Insert file data into the admin_uploads table
            $insert_sql = "INSERT INTO public.admin_uploads (survey_id, user_name, admin_username, file_path) VALUES ($1, $2, $3, $4)";
            $insert_result = pg_query_params($conn, $insert_sql, array($survey_id, $username, $admin_username, $target_file));

            if (!$insert_result) {
                die("Error in SQL query: " . pg_last_error());
            } else {
                echo "The file " . basename($file["name"]) . " has been uploaded.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch survey data
$survey_data_sql = "SELECT * FROM public.survey_data";
$survey_data_result = pg_query($conn, $survey_data_sql);

if (!$survey_data_result) {
    die("Error in SQL query: " . pg_last_error());
}

// Fetch all rows into an array for pagination
$survey_data = [];
while ($row = pg_fetch_assoc($survey_data_result)) {
    $survey_data[] = $row;
}

// Count total number of users
$total_users_sql = "SELECT COUNT(*) as total_users FROM public.users";
$total_users_result = pg_query($conn, $total_users_sql);
$total_users = pg_fetch_result($total_users_result, 0, 'total_users');

// Count total number of users who uploaded files
$total_user_uploads_sql = "SELECT COUNT(DISTINCT username) as total_user_uploads FROM public.survey_data";
$total_user_uploads_result = pg_query($conn, $total_user_uploads_sql);
$total_user_uploads = pg_fetch_result($total_user_uploads_result, 0, 'total_user_uploads');


// Count total number of admin uploads by the logged-in user
$total_admin_uploads_sql = "SELECT COUNT(*) as total_admin_uploads FROM public.admin_uploads WHERE admin_username = $1";
$total_admin_uploads_result = pg_query_params($conn, $total_admin_uploads_sql, array($logged_in_user));
if (!$total_admin_uploads_result) {
    die("Error in SQL query: " . pg_last_error());
}
$total_admin_uploads = pg_fetch_result($total_admin_uploads_result, 0, 'total_admin_uploads');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mojani</title>
    <link rel="icon" href="image\LOGO_DP_Zoning.png" type="image/x-icon">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/profile.css">
    <!-- <script src="./js/index.js"></script> -->
        
    <style>
       
    </style>
</head>

<body>
    <div class="container-fluid d-block d-lg-none">

        <!-- Header Row -->
<div class="header-row d-flex justify-content-between align-items-center mt-4">
    <img src="image/LOGO_DP_Zoning.png" alt="Company Logo" class="company-logo ">
    <button type="button" class="btn btn-primary admin fw-bold" id="toggleButton">Show Profile</button>
    <a href="logout.php" class="btn"><i class="fas fa-power-off" style="color: red;"></i></a>
</div>

        <div class="row">
            <div class="col-12 mb-3 mt-4">
                <div class="card profile-card mt-4 ms-4">
                <div class="card-body">
                        <div class="profile-row">
                            <p class="text text-center text-start"><strong class="ms-2">Full Name:</strong> <?php echo htmlspecialchars($logged_user['username']); ?></p>
                            <p class="text text-center text-start"><strong class="ms-5">Email:</strong> <?php echo htmlspecialchars($logged_user['email']); ?></p>
                        </div>


                        <div class="profile-row">
                            <p class="text text-center"><strong class="">Contact No:</strong> <?php echo htmlspecialchars($logged_user['contact_no']); ?></p>
                            <p class="text text-center"><strong class="ms-5">Occupation:</strong> <?php echo htmlspecialchars($logged_user['occupation']); ?></p>
                        </div>
                </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class=" card stats-card counts">
                <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-3 mt-4">
                            <!-- <i class="fa-solid fa-gauge "></i> -->
                            <img src="./image/Dashboard.svg" alt="Total Users" class="card-icon ">
                        </div>

                     <h2 class="font mt-4  text-center fw-bold">Dashboard </h2>
               
                     <!-- <div class="card-container1 mt-4 mb-4">
                            <div class="card1">
                                <div class="card-body1 text-center">
                                <img src="./image/Users.svg" alt="Total Users" class="card-icon mt-4">
                                    <h5 class="card-title1 mt-4 ms-2 text1">Total Users</h5>
                                    <p class="card-body1 mt-4 "><?php echo $total_users; ?></p>
                                </div>
                            </div>
                            <div class="card1">
                                <div class="card-body1 text-center">
                                <img src="./image/Upload.svg" alt="Total Users" class="card-icon mt-4">
                                    <h5 class="card-title1 mt-4 ms-2 text1">Users Uploaded </h5>
                                    <p class="card-body1 mt-4 "><?php echo $total_user_uploads; ?>                               </div>
                            </div>
                            <div class="card1">
                                <div class="card-body1 text-center">
                                <img src="./image/Admin.svg" alt="Total Users" class="card-icon mt-4">
                                    <h5 class="card-title1 mt-4 ms-2 text1">Admins Uploaded</h5>
                                    <p class="card-body1 mt-4 "><?php echo $total_admin_uploads; ?></p>
                                </div>
                            </div>
                        </div> -->
                        <div class="card-container1 mt-4 mb-4">
    <div class="card1">
        <div class="card-body1 text-center">
            <img src="./image/Users.svg" alt="Total Users" class="card-icon mt-4">
            <h5 class="card-title1 mt-4 ms-2 text1">Total Users</h5>
            <p class="card-body1 mt-4 "><?php echo $total_users; ?></p>
        </div>
    </div>
    <div class="card1">
        <div class="card-body1 text-center">
            <img src="./image/Upload.svg" alt="Uploads by User" class="card-icon mt-4">
            <h5 class="card-title1 mt-4 ms-2 text1">Uploads by You</h5>
            <p class="card-body1 mt-4 "><?php echo $total_user_uploads; ?></p>
        </div>
    </div>
    <div class="card1">
        <div class="card-body1 text-center">
            <img src="./image/Admin.svg" alt="Admins Uploaded" class="card-icon mt-4">
            <h5 class="card-title1 mt-4 ms-2 text1">Admins Uploaded</h5>
            <p class="card-body1 mt-4 "><?php echo $total_admin_uploads; ?></p>
        </div>
    </div>
</div>


                        <!--  -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="uploads col-12 tabledata">
            <h2 class="font text-center fw-bold">User Activity Logs</h2>

                <div class="table-container">
                    <table class="table table-bordered" id="surveyTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>District</th>
                                <th>Taluka</th>
                                <th>Village</th>
                                <th>Survey Number</th>
                                <th>Survey Map</th>
                                <th>Village Map</th>
                                <th>PDF 7/12</th>
                                <th>Date & Time</th>
                                <th>Upload File</th>
                                <th>Uploaded File Name</th>
                                <th>Uploaded Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($survey_data as $survey) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($survey['id']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['username']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['district']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['taluka']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['village']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['survey_number']); ?></td>
                                    <td><a href="uploads/<?php echo htmlspecialchars($survey['survey_map_filename']); ?>" download><?php echo htmlspecialchars($survey['survey_map_filename']); ?></a></td>
                                    <td><a href="uploads/<?php echo htmlspecialchars($survey['village_map_filename']); ?>" download><?php echo htmlspecialchars($survey['village_map_filename']); ?></a></td>
                                    <td><a href="uploads/<?php echo htmlspecialchars($survey['pdf_7_12_filename']); ?>" download><?php echo htmlspecialchars($survey['pdf_7_12_filename']); ?></a></td>
                                    <td><?php echo htmlspecialchars($survey['timestamp']); ?></td>
                                    <td>
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="survey_id" value="<?php echo htmlspecialchars($survey['id']); ?>" />
                                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($survey['username']); ?>" />
                                            <input type="file" name="uploadFile" />
                                            <button type="submit" class="btnn">Upload</button>
                                        </form>
                                    </td>
                                    <td>
                                        <?php
                                        $upload_query = "SELECT file_path, upload_time FROM public.admin_uploads WHERE survey_id = $1 AND admin_username = $2";
                                        $upload_result = pg_query_params($conn, $upload_query, array($survey['id'], $logged_in_user));
                                        if ($upload_result) {
                                            while ($upload_row = pg_fetch_assoc($upload_result)) {
                                                echo htmlspecialchars(basename($upload_row['file_path']));
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($upload_result) {
                                            pg_result_seek($upload_result, 0); // Reset result pointer to the beginning
                                            while ($upload_row = pg_fetch_assoc($upload_result)) {
                                                echo htmlspecialchars($upload_row['upload_time']);
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous" onclick="prevPage()">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <span id="pageNumbers"></span>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next" onclick="nextPage()">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>


    <!-- for meadia query -->
     <!-- laptop view -->

    <div class="container d-none d-lg-block">
        <div class="row">
        <div class="card-body d-flex justify-content-between align-items-center">
                            <img src="image/LOGO_DP_Zoning.png" alt="Company Logo" class="company-logo-lg ">

                            <a href="logout.php" class="btn mb-4"><i class="fas fa-power-off off-lg" style="color: red;"></i></a>

                        </div>

            <div class="col-12  col-md-3 col-lg">
                <div class="card profile-card-lg">
                <div class="card-bodyl-lg ">
               
                    <span class="icon"><i class="fas fa-user"></i></span>
         


                        <h2 class="font text-center  pb-1 fw-bold heading-lg">User Profile</h2>
                     
                            <p class="text-lg text-center pt-2 text-start capitalize"><strong class="">Full Name : &nbsp;</strong>   <span class="capitalize"><?php echo htmlspecialchars($logged_user['username']); ?></span></p>
                            <p class="text-lg text-center text-start"><strong class="">Email :&nbsp; </strong> <?php echo htmlspecialchars($logged_user['email']); ?></p>
                                  
                             
                            <p class="text-lg text-center"><strong class="">Contact No : &nbsp;</strong> <?php echo htmlspecialchars($logged_user['contact_no']); ?></p>
                          
                            <p class="text-lg text-center capitalize"><strong class="">Occupation : &nbsp;</strong> <span class ="capitalize"><?php echo htmlspecialchars($logged_user['occupation']); ?></span></p>  
              
                </div>
                </div>  
            </div>
            
            <div class="col-12 col-md-9 col-lg1">
                <div class=" card-dashboard-lg stats-card counts">
                <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                <img src="./image/Dashboard.svg" alt="Total Users" class="card-icon ">
                        </div>

                     <h2 class="font mt-2 text-center fw-bold ">Dashboard </h2>
               
                     <div class="card-container1-lg">
                            <div class="card1-lg">
                                <div class="card-body1 text-center">
                                    <img src="./image/Users.svg" alt="Total Users" class="card-icon mt-2">
                                    <h5 class="card-title1 mt-3 ms-2 text1-lg">Total Users</h5>
                                    <p class="card-body1 mt-2 "><?php echo $total_users; ?></p>
                                </div>
                            </div>
                            <div class="card1">
                                <div class="card-body1 text-center">
                                    <img src="./image/Upload.svg" alt="Total Users" class="card-icon mt-2">
                                    <h5 class="card-title1 mt-3 ms-2 text1-lg">Users Uploaded </h5>
                                    <p class="card-body1 mt-2 "><?php echo $total_user_uploads; ?></p>
                                </div>
                            </div>
                            <div class="card1">
                                <div class="card-body1 text-center">
                                    <img src="./image/Admin.svg" alt="Total Users" class="card-icon mt-2">
                                    <h5 class="card-title1 mt-3 ms-2 text1-lg">Admins Uploaded</h5>
                                    <p class="card-body1 mt-2 "><?php echo $total_admin_uploads; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="uploads col-12 tabledata">
            <h2 class="font text-center fw-bold recent-lg">Recent Activity</h2>

                <div class="table-container">
                    <table class="table table-bordered" id="surveyTable1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>District</th>
                                <th>Taluka</th>
                                <th>Village</th>
                                <th>Survey Number</th>
                                <th>Survey Map</th>
                                <th>Village Map</th>
                                <th>PDF 7/12</th>
                                <th>Date & Time</th>
                                <th>Upload File</th>
                                <th>Uploaded File Name</th>
                                <th>Uploaded Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($survey_data as $survey) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($survey['id']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['username']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['district']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['taluka']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['village']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['survey_number']); ?></td>
                                    <td><a href="uploads/<?php echo htmlspecialchars($survey['survey_map_filename']); ?>" download><?php echo htmlspecialchars($survey['survey_map_filename']); ?></a></td>
                                    <td><a href="uploads/<?php echo htmlspecialchars($survey['village_map_filename']); ?>" download><?php echo htmlspecialchars($survey['village_map_filename']); ?></a></td>
                                    <td><a href="uploads/<?php echo htmlspecialchars($survey['pdf_7_12_filename']); ?>" download><?php echo htmlspecialchars($survey['pdf_7_12_filename']); ?></a></td>
                                    <td><?php echo htmlspecialchars($survey['timestamp']); ?></td>
                                    <td>
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="survey_id" value="<?php echo htmlspecialchars($survey['id']); ?>" />
                                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($survey['username']); ?>" />
                                            <input type="file" name="uploadFile" />
                                            <button type="submit" class="btnn">Upload</button>
                                        </form>
                                    </td>
                                    <td>
                                        <?php
                                        $upload_query = "SELECT file_path, upload_time FROM public.admin_uploads WHERE survey_id = $1 AND admin_username = $2";
                                        $upload_result = pg_query_params($conn, $upload_query, array($survey['id'], $logged_in_user));
                                        if ($upload_result) {
                                            while ($upload_row = pg_fetch_assoc($upload_result)) {
                                                echo htmlspecialchars(basename($upload_row['file_path']));
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($upload_result) {
                                            pg_result_seek($upload_result, 0);
                                            while ($upload_row = pg_fetch_assoc($upload_result)) {
                                                echo htmlspecialchars($upload_row['upload_time']);
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <ul id="pageNumbers" class="pagination">
    <li class="page-item">
        <a class="page-link" href="#" aria-label="Previous" onclick="prevPage()">
            <span aria-hidden="true">«</span>
        </a>
    </li>
    <!-- Page numbers will be inserted here -->
    <li class="page-item">
        <a class="page-link" href="#" aria-label="Next" onclick="nextPage()">
            <span aria-hidden="true">»</span>
        </a>
    </li>
</ul>

            </div>
        </div>
    </div> 

    


   
    <script>
           const rowsPerPage = 6;
        let currentPage = 1;
        const table = document.getElementById("surveyTable");
        const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        const pageNumbers = document.getElementById("pageNumbers");

    function closeModal() {
        var modal = document.getElementById('pdfModal');
        modal.style.display = 'none';
        var pdfViewer = modal.querySelector('#pdfViewer');
        pdfViewer.src = '';
    }

    

        function showPage(page) {
            for (let i = 0; i < totalRows; i++) {
                rows[i].style.display = "none";
            }
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            for (let i = start; i < end && i < totalRows; i++) {
                rows[i].style.display = "";
            }
           

            updatePageNumbers();
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                showPage(currentPage);
            }
        }

        function updatePageNumbers() {
            pageNumbers.innerHTML = "";
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement("li");
                li.className = "page-item" + (i === currentPage ? " active" : "");
                li.innerHTML = `<a class="page-link" href="#" onclick="goToPage(${i})">${i}</a>`;
                pageNumbers.appendChild(li);
            }
        }

        function goToPage(page) {
            currentPage = page;
            showPage(currentPage);
        }

        document.addEventListener("DOMContentLoaded", () => {
            showPage(currentPage);
        });

    </script>

<script>
    // code for hide and show 

    document.getElementById('toggleButton').addEventListener('click', function() {
        var profileCard = document.querySelector('.profile-card');
        if (profileCard.style.display === 'none' || profileCard.style.display === '') {
            profileCard.style.display = 'block';
            this.textContent = 'Hide Profile';
        } else {
            profileCard.style.display = 'none';
            this.textContent = 'Show  Profile';
        }
    });
</script>



</body>

</html>

<?php
pg_close($conn);
?>