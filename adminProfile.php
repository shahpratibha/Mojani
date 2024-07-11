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
    <link rel="stylesheet" href="css/profile.css">
    <style>
    
    .table-container {
    height: 300px;
    /* overflow-y: auto; */
    position: relative;
}

.table-container table {
    width: 100%;
    border-collapse: collapse;
}

.table-container thead,
.table-container tbody {
    display: block;
}

.table-container thead {
    background-color: white;
    z-index: 2;
    position: sticky;
    top: 0;
}

.table-container tbody {
    height: 250px; /* Adjust this value to fit within the container height */
    overflow-y: auto;
    scrollbar-width:thin ;
}

.table-container th,
.table-container td {
    width: 10%; /* Adjust based on the number of columns */
    box-sizing: border-box;
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
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="profile">
                <h2>Logged-in User Profile</h2>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($logged_user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($logged_user['email']); ?></p>
                <p><strong>Contact No:</strong> <?php echo htmlspecialchars($logged_user['contact_no']); ?></p>
                <p><strong>Occupation:</strong> <?php echo htmlspecialchars($logged_user['occupation']); ?></p>
                <a href="logout.php" class="btn btn-danger">Logout</a> <!-- Add logout button -->
            </div>
        </div>
        <div class="col-sm-8">
            <div class="counts">
                <h2>Dashboard Statistics</h2>
                <p><strong>Total Users:</strong> <?php echo $total_users; ?></p>
                <p><strong>Total Users who Uploaded Files:</strong> <?php echo $total_user_uploads; ?></p>
                <p><strong>Total Admins who Uploaded Files:</strong> <?php echo $total_admin_uploads; ?></p>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- <div class="uploads">
            <h2>Survey Data</h2>
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
                <ul class="pagination">
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
        </div> -->
        <div class="uploads">
    <h2>Survey Data</h2>
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
        <ul class="pagination">
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
