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
  
    <?php
    // Display error message if login failed
    if (isset($_SESSION['login_status']) && $_SESSION['login_status'] == "failed") {
        echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
    }
    ?>

    <div class="container">
        
        <div class="right-section">
            <div class="login-container">
                <form class="login-form" action="login_process.php" method="post">
                    <h2 class="login-title"><img src="image\LOGO_DP_Zoning.png" class="logo" alt="" style=""></h2>
              
                        <?php
                        // Display error message if login failed
                        if (isset($_SESSION['login_status']) && $_SESSION['login_status'] == "failed") {
                            echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
                            // Clear the session variables
                            unset($_SESSION['login_status']);
                            unset($_SESSION['error_message']);
                        }
                        ?>           
                    <div class="form-control">
                        <i class="fas fa-envelope icon"></i>
                        <!-- <input type="text" placeholder="Username" id="username" required> -->
                        <input type="email" name="email"  placeholder="abc@gmail.com" required>
                    </div>
                    <div class="form-control">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" placeholder="Password" id="password" >
                    </div>
                   
                    <button type="submit" value="Login">Login</button>
                    
                    <p class="endline">
                    <a href="register.php" class="btn">Create an account</a>
                    </p>

                </form>
            </div>
        </div>
    </div>
</body>
</html>
