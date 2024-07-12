<?php
session_start();
include('db.php');

// Connect to the database
$conn = pg_connect("host=rr.c01x1jtcm1ms.ap-south-1.rds.amazonaws.com dbname=Mojani user=postgres password=Pmc992101");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php"); // Redirect to login page if not logged in
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

// Count total number of admin users who uploaded files
$total_admin_uploads_sql = "SELECT COUNT(DISTINCT username) as total_admin_uploads FROM public.survey_data";
$total_admin_uploads_result = pg_query($conn, $total_admin_uploads_sql);
$total_admin_uploads = pg_fetch_result($total_admin_uploads_result, 0, 'total_admin_uploads');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/profile.css">

    <style>
        body {
            padding: 0 15px;
        }

        .table-container {
            height: 310px;
            position: relative;
            overflow-y: auto;

        }

        .table-container thead {
            background-color: white;
            z-index: 2;
            position: sticky;
            top: 0;
        }

        .table-container th,
        .table-container td {
            width: 10%;
            box-sizing: border-box;
        }

        .table-container {
            height: 300px;
            position: relative;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        .pagination .page-item .page-link {
            margin: 0 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .pagination .active .page-link {
            font-weight: bold;
        }

        .table-container {
            height: 700px;
            overflow-y: auto;
        }

        .table-container thead {
            background-color: white;
            position: sticky;
            top: 0;
        }

        .card {
            width: 100%;
            border: 4px solid #0080005c;
            border-radius: 15px;
            margin-bottom: 20px;
            font-size: 50px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 20px;
        }

        .card h2 {
            color: black;
            font-size: 1.5em;
        }

        .text {
            color: black;
            font-size: 30px;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .fa-user {
            font-size: 60px;
        }

        .btn {
            font-size: 30px;
        }


        .card-title1 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .card-body1 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .card-container1 {
            display: flex;
            justify-content: space-between;
            gap: 20px;/
        }

        .card1 {
            flex: 1;
            margin: 10px;
            /* border: 4px solid #0080005c; */
            background-color: #d3d3d3;
            box-shadow: 8px 8px 16px rgba(0, 0, 0, 0.6);
        }

        .text1 {
            font-size: 35px;
        }

        .bg-light {
            background-color: gray;
        }

        .tabledata {
            height: 900px;
        }

        #surveyTable th {
            font-size: 40px;
            background-color: #0080005c;
        }

        #surveyTable td {
            font-size: 40px;
        }

        .company-logo {
            height: 80px;
            width: auto;
        }

        .profile-row {
            display: flex;
            align-items: center;
            justify-content: space-between;

        }

        .fa-power-off {
            font-size: 40px;
        }
        .card-body h2{
            font-size: 3px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-3 mt-4">
                <div class="card profile-card">
                    <div class="card-body">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <img src="image/geopulse_logo-removebg-preview.png" alt="Company Logo" class="company-logo ms-5">

                            <a href="logout.php" class="btn mb-4"><i class="fas fa-power-off" style="color: red;"></i></a>

                        </div>

                        <h2 class="text-success mt-3 text-center pb-5 fw-bold">User Profile</h2>

                        <div class="profile-row">
                            <p class="text text-center text-start"><strong class="ms-5">Full Name:</strong> <?php echo htmlspecialchars($logged_user['username']); ?></p>
                            <p class="text text-center text-start"><strong class="ms-5">Email:</strong> <?php echo htmlspecialchars($logged_user['email']); ?></p>
                        </div>

                        <div class="profile-row">
                            <p class="text text-center"><strong class="ms-5">Contact No:</strong> <?php echo htmlspecialchars($logged_user['contact_no']); ?></p>
                            <p class="text text-center"><strong class="ms-5">Occupation:</strong> <?php echo htmlspecialchars($logged_user['occupation']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mt-4">
                            <i class="fas fa-chart-bar text-success"></i>
                        </div>
                        <h2 class="text-success mt-4  text-center fw-bold">Dashboard </h2>

                        <div class="card-container1 mt-4 mb-4">
                            <div class="card1">
                                <div class="card-body1 text-center">
                                    <i class="fas fa-users fa-2x me-3 text-success mt-5"></i>
                                    <h5 class="card-title1 mt-4 ms-2 text1">Total Users</h5>
                                    <p class="card-body1 mt-4 "><?php echo $total_users; ?></p>
                                </div>
                            </div>
                            <div class="card1">
                                <div class="card-body1 text-center">
                                    <i class="fas fa-upload fa-2x me-3 text-success mt-5"></i>
                                    <h5 class="card-title1 mt-4 ms-2 text1">Users Uploaded </h5>
                                    <p class="card-body1 mt-4 "><?php echo $total_user_uploads; ?></p>
                                </div>
                            </div>
                            <div class="card1">
                                <div class="card-body1 text-center">
                                    <i class="fas fa-user-shield fa-2x me-3 text-success mt-5"></i>
                                    <h5 class="card-title1 mt-4 ms-2 text1">Admins Uploaded</h5>
                                    <p class="card-body1 mt-4 "><?php echo $total_admin_uploads; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12 tabledata">
                <h1 class="text-center text-success">Survey Data</h1>
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
                                <th>Timestamp</th>
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
                                    <td><?php echo htmlspecialchars($survey['survey_map_filename']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['village_map_filename']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['pdf_7_12_filename']); ?></td>
                                    <td><?php echo htmlspecialchars($survey['timestamp']); ?></td>
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
    <script>
        const rowsPerPage = 10;
        let currentPage = 1;
        const table = document.getElementById("surveyTable");
        const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        const pageNumbers = document.getElementById("pageNumbers");

        function showPage(page) {
            for (let i = 0; i < totalRows; i++) {
                rows[i].style.display = "none";
            }
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            for (let i = start; i < end && i < totalRows; i++) {
                rows[i].style.display = "";
            }
            document.getElementById("prevBtn").classList.toggle('disabled', page === 1);
            document.getElementById("nextBtn").classList.toggle('disabled', page === totalPages);

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
</body>

</html>

<?php
pg_close($conn);
?>