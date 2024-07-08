
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
        <div class="col-sm-4">
            <div class="profile">
                <h2>Logged-in User Profile</h2>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($logged_user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($logged_user['email']); ?></p>
                <p><strong>Contact No:</strong> <?php echo htmlspecialchars($logged_user['contact_no']); ?></p>
                <p><strong>Occupation:</strong> <?php echo htmlspecialchars($logged_user['occupation']); ?></p>
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
