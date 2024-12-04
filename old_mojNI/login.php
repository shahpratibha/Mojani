<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojani</title>
    <link rel="icon" href="image/LOGO_DP_Zoning.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>

    <?php
    // Start the session
    session_start();

    // Display error message if login failed
    if (isset($_SESSION['login_status']) && $_SESSION['login_status'] == "failed") {
        echo '<script type="text/javascript">',
        'document.addEventListener("DOMContentLoaded", function() {',
        'showPopup("<strong>That\'s login info didn\'t work</strong><br><br>Check your Email or Password and try again");',
        '});',
        '</script>';
        // Clear the session variables
        unset($_SESSION['login_status']);
        unset($_SESSION['error_message']);
    }
    ?>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <p id="modalMessage"></p>
            <div class="modal-footer">
                <button class="modal-button" onclick="closeModal()">OK</button>
            </div>
        </div>
    </div>

    <div class="container">
    <div class="login-container1">
    <a href="index.html" class="homebtn">
        <img src="./image/Expand_up.svg" alt="Home">
    </a>
            </div>
        <div class="right-section">
            
            <div class="login-container">
                <!-- <a href="index.html">
                    <i class="fa-solid fa-house icon-top-10"></i>
                </a> -->

                <form class="login-form" action="login_process.php" method="post">
                    <h2 class="login-title"><img src="image/LOGO_DP_Zoning.png" class="logo" alt=""></h2>
                    <div class="form-control">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" placeholder="abc@gmail.com" required>
                    </div>
                    <div class="form-control">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" name="password" placeholder="Password" id="password" required>
                    </div>
                    <button type="submit" value="Login">Login</button>
                    <p class="endline">
                        <a href="register.php" class="btn">Create an account</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to display popup message
        function showPopup(message) {
            var modal = document.getElementById("myModal");
            var modalMessage = document.getElementById("modalMessage");
            modalMessage.innerHTML = message; // Use innerHTML to handle HTML content
            modal.style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        // Close the modal when clicking outside of the modal content
        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>