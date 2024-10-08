<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojani</title>
    <link rel="icon" href="image\LOGO_DP_Zoning.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
     
    <div class="container">
        
        <div class="right-section">
            <div class="login-container">
                <form class="login-form" action="Adminregister_process.php" method="post">
                <h2 class="login-title"><img src="image\LOGO_DP_Zoning.png" class="logo" alt="" style=""></h2>
                    <div class="form-control">
                        <i class="fas fa-user icon"></i>
                        <input type="text" name="username" placeholder="UserName" required>
                    </div>
                    <div class="form-control">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    
                    <div class="form-control">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" placeholder="Password" id="password" required>
                    </div>
                    <div class="form-control">
                        <i class="fas fa-briefcase icon"></i>
                        <input type="text" name="occupation" placeholder="Occupation" required>
                    </div>
                    <div class="form-control">
                        <i class="fas fa-phone icon"></i>
                        <input type="tel" name="contact_no" placeholder="contact number" required>
                    </div>
                   
                    <button type="submit" value="Register">Register</button>
                    <p class="endline"><a href="admin.php">I am already a member</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
