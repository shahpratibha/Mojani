<?php
include('db.php');

// Connect to the database
$conn = pg_connect("host=157.173.222.9 dbname=Mojani_new user=postgres password=Mojani@992101");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Assume username is passed via GET
$username = $_GET['username'] ?? 'default_username'; // For example purposes

// Fetch user details
$user_sql = "SELECT * FROM public.users WHERE username = $1";
$user_result = pg_query_params($conn, $user_sql, array($username));

if (!$user_result) {
    die("Error in SQL query: " . pg_last_error());
}

$user = pg_fetch_assoc($user_result);

if (!$user) {
    die("User not found.");
}

// Fetch upload history
$uploads_sql = "SELECT sd.*, au.file_path 
                FROM public.survey_data sd
                LEFT JOIN public.admin_uploads au ON sd.id = au.survey_id
                WHERE sd.username = $1 
                ORDER BY sd.timestamp DESC";
$uploads_result = pg_query_params($conn, $uploads_sql, array($username));

if (!$uploads_result) {
    die("Error in SQL query: " . pg_last_error());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mojani</title>
    <link rel="icon" href="image\LOGO_DP_Zoning.png" type="image/x-icon">
    <!-- bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>


    <!-- <link rel="stylesheet" href="css/profile.css">   -->
    <link rel="stylesheet" href=css/profileeee.css>


    <!-- <link rel="stylesheet" href="css/profileeee.css"> -->

</head>

<body>
    <div class="container-fluid ">
    
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="profile">
                    <img src="image/LOGO_DP_Zoning.png" alt="Profile Image" class="profile-img">

                   
                    <!-- Button to toggle the profile card -->
                    <button id="toggleProfileCardBtn" class="user">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <span class="text">Profile</span>
                    </button>


                    <form action="logout.php" method="post" style="display: inline;">
                        <button type="submit" name="Logout" class="btn">
                            <i class="fas fa-power-off" style="color: red;"></i>
                        </button>
                    </form>
                </div>

                <div class="card profile-card card-header" id="profileCard" style="display: none;">
                    <div class="profile-row">
                        <p class="text text-start text-center occupation-text"><strong class="ms-1">Full Name:</strong> <span><?php echo htmlspecialchars($user['username']); ?></span></p>
                        <p class="text text-start text-center"><strong class="ms-5">Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="profile-row  contact">
                        <p class="text text-start text-center"><strong class="ms-4">Contact No:</strong> <?php echo htmlspecialchars($user['contact_no']); ?></p>
                        <p class="text text-start text-center occupation-text"><strong class="ms-4">Occupation:</strong> <span><?php echo htmlspecialchars($user['occupation']); ?> </span></p>
                    </div>
                    <a href="form.php" class="fa ">&#xf0a8;</a>

                    <!-- Logout Button (Visible on Large Screens) -->


                </div>
            </div>
        </div>

        <div class="row">
            <div class="uploads col-12 tabledata">
                <h2 class="text-center text-success mt-5 underlined">Upload Log</h2>
                <div class="table-responsive-x">
                    <table class="table table-bordered" id="uploadTable">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>District</th>
                                <th>Taluka</th>
                                <th>Village</th>
                                <th>Survey No</th>
                                <th>Upload Pdf</th>
                                <!-- <th>Village Map Pdf</th>
                                <th>7/12 Pdf</th> -->
                                <th>Upload Date</th>
                                <th>Download File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($upload = pg_fetch_assoc($uploads_result)) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($upload['id']); ?></td>
                                    <td><?php echo htmlspecialchars($upload['district']); ?></td>
                                    <td><?php echo htmlspecialchars($upload['taluka']); ?></td>
                                    <td><?php echo htmlspecialchars($upload['village']); ?></td>
                                    <td><?php echo htmlspecialchars($upload['survey_number']); ?></td>
                                    <td>
                                        <?php
                                        $surveyMapFilePath = 'uploads/' . $upload['survey_map_filename'];
                                        echo '<a href="#" onclick="openModal(\'' . $surveyMapFilePath . '\')">' . htmlspecialchars($upload['survey_map_filename']) . '</a>';
                                        ?>
                                    </td>
                                    <!-- <td>
                                        <?php
                                        $villageMapFilePath = 'uploads/' . $upload['village_map_filename'];
                                        echo '<a href="#" onclick="openModal(\'' . $villageMapFilePath . '\')">' . htmlspecialchars($upload['village_map_filename']) . '</a>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $pdf712FilePath = 'uploads/' . $upload['pdf_7_12_filename'];
                                        echo '<a href="#" onclick="openModal(\'' . $pdf712FilePath . '\')">' . htmlspecialchars($upload['pdf_7_12_filename']) . '</a>';
                                        ?>
                                    </td> -->
                                    <td><?php echo htmlspecialchars($upload['timestamp']); ?></td>
                                    <td>
                                        <?php if ($upload['file_path']) : ?>
                                            <a href="<?php echo htmlspecialchars($upload['file_path']); ?>" download>
                                                <?php echo htmlspecialchars(basename($upload['file_path'])); ?>
                                                <i class="fa fa-download download-icon"></i>
                                            </a>
                                        <?php else : ?>
                                            <span>In Progress...</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
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

        <!-- Modal for PDF Viewer -->
        <div id="pdfModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
    </div>
    
    <a href="form.php" class="back-button">
    <i class="fas fa-arrow-left"></i> 
     </a>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        const rowsPerPage = 6;
        let currentPage = 1;
        const table = document.getElementById("uploadTable");
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

        function openModal(filePath) {
            var modal = document.getElementById('pdfModal');
            var pdfViewer = modal.querySelector('#pdfViewer');
            pdfViewer.src = filePath;
            modal.style.display = 'block';
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

        document.querySelector('.user .icon').addEventListener('click', function() {
            var profileCard = document.getElementById('profileCard');
            if (profileCard.style.display === 'none' || profileCard.style.display === '') {
                profileCard.style.display = 'block';
                document.getElementById('toggleProfileCardBtn').classList.add('fixed');
            } else {
                profileCard.style.display = 'none';
                document.getElementById('toggleProfileCardBtn').classList.remove('fixed');
            }
        });

        document.querySelector('.user .text').addEventListener('click', function() {
            var profileCard = document.getElementById('profileCard');
            if (profileCard.style.display === 'none' || profileCard.style.display === '') {
                profileCard.style.display = 'block';
                document.getElementById('toggleProfileCardBtn').classList.add('fixed');
            } else {
                profileCard.style.display = 'none';
                document.getElementById('toggleProfileCardBtn').classList.remove('fixed');
            }
        });
    </script>

</body>

</html>

<?php
pg_close($conn);
?>