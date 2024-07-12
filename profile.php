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
     <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
                 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">

    </script>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href=css/profileeee.css>
   
  
    <!-- <link rel="stylesheet" href="css/profileeee.css"> -->
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
<div class="container-fluid">
        <div class="row">
        <div class="col-mb-3 mt-4">
        <div class="card profile-card">
                <div class="card-body">
                <div class="card-body d-flex justify-content-between align-items-center">
                <img src="https://geopulsea.com/image/transparent_logo.png" alt=alt="Profile Image" class="profile-img">
                 
                <a href="logout.php" class="btn mb-4"><i class="fas fa-power-off" style="color: red;"></i></a>
               
    </div>
    <h2 class="text-success mt-3 text-center pb-5 fw-bold">User Profile</h2>
            <div class="profile-row"> <p class="text text-center text-start"><strong class="ms-5">Full Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                                      <p class="text text-center text-start"><strong class="ms-5">Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    </div>
           <div class="profile-row">  <p class="text text-center text-start"><strong class="ms-5">Contact No:</strong> <?php echo htmlspecialchars($user['contact_no']); ?></p>
    
                <p class="text text-center text-start"><strong>Occupation:</strong class="ms-5"> <?php echo htmlspecialchars($user['occupation']); ?></p>
    </div>
              
                    <button type="button" class="btn btn-outline-primary  mt-3 text-center">back</button>
                
             
            
        </div>
    </div>
</div>
    </div>

<div class="row">
        <div class="uploads col-12 tabledata">
            <h2 class="text-center text-success mt-5">Upload History</h2>
            <div class="table-container">
                <table class="table table-bordered" id="uploadTable">
                    <thead>
                        <tr>
                            <th>District</th>
                            <th>Taluka</th>
                            <th >Village</th>
                            <th>Survey Map PDF</th>
                            <th >Village Map PDF</th>
                            <th>7/12 PDF</th>
                            <th>Upload Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php while ($upload = pg_fetch_assoc($uploads_result)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($upload['district']); ?></td>
                                <td><?php echo htmlspecialchars($upload['taluka']); ?></td>
                                <td ><?php echo htmlspecialchars($upload['village']); ?></td>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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

    function toggleProfile() {
        var profileDetails = document.querySelector('.profile-details');
        if (profileDetails.style.display === 'none' || profileDetails.style.display === '') {
            profileDetails.style.display = 'block';
        } else {
            profileDetails.style.display = 'none';
        }
    }
</script>

</body>
</html>

<?php
pg_close($conn);
?>
