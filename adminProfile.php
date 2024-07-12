
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
$logged_user_sql = "SELECT * FROM public.users WHERE username = $1";
$logged_user_result = pg_query_params($conn, $logged_user_sql, array($logged_in_user));

if (!$logged_user_result) {
    die("Error in SQL query: " . pg_last_error());
}

$logged_user = pg_fetch_assoc($logged_user_result);

if (!$logged_user) {
    die("Logged-in user not found.");
}

// Fetch all user details
$users_sql = "SELECT * FROM public.users";
$users_result = pg_query($conn, $users_sql);

if (!$users_result) {
    die("Error in SQL query: " . pg_last_error());
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
// total_admin_uploads = pg_fetch_result($total_admin_uploads_result, 0, 'total_admin_uploads');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/profile.css">

        
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-8 offset-sm-2 col-md-6 offset-md-3">
            <div class="profile" style="background-color: #f9f9f9; padding: 10px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <h2 class="profile-title" style="font-size: 24px; margin-bottom: 20px;  color: green;">Logged-in User Profile</h2>
                <div class="profile-info" style="font-size: 16px; line-height: 1.6;">
                    <p><strong style="font-weight: bold; margin-right: 8px;">Full Name:</strong> <?php echo htmlspecialchars($logged_user['username']); ?></p>
                    <p><strong style="font-weight: bold; margin-right: 8px;">Email:</strong> <?php echo htmlspecialchars($logged_user['email']); ?></p>
                    <p><strong style="font-weight: bold; margin-right: 8px;">Contact No:</strong> <?php echo htmlspecialchars($logged_user['contact_no']); ?></p>
                    <p><strong style="font-weight: bold; margin-right: 8px;">Occupation:</strong> <?php echo htmlspecialchars($logged_user['occupation']); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="counts" style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 0px;">
                    <h2 style="; font-size: 24px; font-weight: bold; color: green; margin-bottom: 20px;">Dashboard Statistics</h2>
                    <p style="font-size: 18px; margin: 10px 0;"><strong style="color: #343a40;">Total Users:</strong> <?php echo $total_users; ?></p>
                    <p style="font-size: 18px; margin: 10px 0;"><strong style="color: #343a40;">Total Users who Uploaded Files:</strong> <?php echo $total_user_uploads; ?></p>
                    <p style="font-size: 18px; margin: 10px 0;"><strong style="color: #343a40;">Total Admins who Uploaded Files:</strong> <?php echo $total_admin_uploads; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="uploads">
            <h2>All Users</h2>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Occupation</th>
                </tr>
                <?php while ($user = pg_fetch_assoc($users_result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['contact_no']); ?></td>
                        <td><?php echo htmlspecialchars($user['occupation']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>

<?php
pg_close($conn);
?>
