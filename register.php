<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojani</title>
    <link rel="icon" href="image\LOGO_DP_Zoning.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script>
        function validateEmail() {
            const email = document.querySelector('input[name="email"]').value;
            const messageElement = document.getElementById('emailError');
            
            if (!email.endsWith('.com')) {
                messageElement.textContent = 'Email must end with .com';
                return false;
            }
            
            messageElement.textContent = '';
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="right-section">
            <div class="login-container">
                <form class="login-form" action="register_process.php" method="post" onsubmit="return validateEmail()">
                    <h2 class="login-title"><img src="image\LOGO_DP_Zoning.png" class="logo" alt="" style=""></h2>
                    <div class="form-control">
                        <i class="fas fa-user icon"></i>
                        <input type="text" name="username" placeholder="UserName" required>
                    </div>
                    <div class="form-control">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" placeholder="Email" required>
                        <span id="emailError" style="color: red;"></span>
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
                        <input type="tel" name="contact_no" placeholder="Contact Number" required>
                    </div>
                    <button type="submit" value="Register">Register</button>
                    <p class="endline"><a href="login.php" class="btn">I am already a member</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
