<?php
include('db.php');

// Connect to the database
$conn = pg_connect("host=rr.c01x1jtcm1ms.ap-south-1.rds.amazonaws.com dbname=Mojani user=postgres password=Pmc992101");

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
$uploads_sql = "SELECT * FROM public.survey_data WHERE username = $1 ORDER BY timestamp DESC";
$uploads_result = pg_query_params($conn, $uploads_sql, array($username));

if (!$uploads_result) {
    die("Error in SQL query: " . pg_last_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
     <!-- bootstrap -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="css/profile.css">
    <script>
        function openModal(pdfUrl) {
            var modal = document.getElementById('pdfModal');
            var modalContent = modal.querySelector('.modal-content');
            modalContent.innerHTML = '<iframe src="' + pdfUrl + '" style="width: 100%; height: 100%; border: none;"></iframe>';
            modal.style.display = 'block';
        }

        function closeModal() {
            var modal = document.getElementById('pdfModal');
            modal.style.display = 'none';
            var modalContent = modal.querySelector('.modal-content');
            modalContent.innerHTML = '';
        }
    </script>
</head>
<body>
<div class="row">
<div class="profile col-sm-4">
    <h2 >User Profile</h2>
    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Contact No:</strong> <?php echo htmlspecialchars($user['contact_no']); ?></p>
    <p><strong>Occupation:</strong> <?php echo htmlspecialchars($user['occupation']); ?></p>
    <a href="logout.php" class="btn btn-danger">Logout</a> <!-- Add logout button -->
</div>

<div class="uploads col-sm-8">
    <h2 >Upload History</h2>
    <table>
        <tr>
            <th>District</th>
            <th>Taluka</th>
            <th>Village</th>
            <th>Survey Map PDF</th>
            <th>Village Map PDF</th>
            <th>7/12 PDF</th>
            <th>Upload Date</th>
        </tr>
        <?php while ($upload = pg_fetch_assoc($uploads_result)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($upload['district']); ?></td>
                <td><?php echo htmlspecialchars($upload['taluka']); ?></td>
                <td><?php echo htmlspecialchars($upload['village']); ?></td>
                <td>
                    <?php
                    $surveyMapFilePath = 'uploads/' . $upload['survey_map_filename'];
                    echo '<a href="#" onclick="openModal(\'' . $surveyMapFilePath . '\')">' . htmlspecialchars($upload['survey_map_filename']) . '</a>';
                    ?>
                </td>
                <td>
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
                </td>
                <td><?php echo htmlspecialchars($upload['timestamp']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<div id="pdfModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <iframe id="pdfViewer" style="width: 100%; height: 100%; border: none;"></iframe>
    </div>
</div>
</div>
<script>
    function openModal(pdfUrl) {
        var modal = document.getElementById('pdfModal');
        var modalContent = modal.querySelector('.modal-content');
        var pdfViewer = modalContent.querySelector('#pdfViewer');
        pdfViewer.src = pdfUrl;
        modal.style.display = 'block';
    }

    function closeModal() {
        var modal = document.getElementById('pdfModal');
        modal.style.display = 'none';
        var pdfViewer = modal.querySelector('#pdfViewer');
        pdfViewer.src = '';
    }
</script>

</body>
</html>

<?php
pg_close($conn);
?>
