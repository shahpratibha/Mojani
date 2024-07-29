<?php
session_start();
include('db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("UPDATE users SET accepted_terms = TRUE WHERE user_id = ?");
    $stmt->execute([$userId]);

    // Redirect to index page after accepting terms
    header("Location: form.php");
    exit();

  
}


$logged_in_user = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mojani</title>
    <link rel="icon" href="image\LOGO_DP_Zoning.png" type="image/x-icon">
    <style>
           /* Styles for modal popup */
           .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 10px;
            /* Decrease padding */
            border: 1px solid #888;
            width: 30%;
            /* Set the width to 30% */
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 2);
        }

        h2 {
            text-align: center;
        }

        form {
            margin-top: 20px;
            text-align: left;
            /* Align form elements to the left */
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        button {
            display: block;
            width: 80%;
            padding: 10px;
            margin: 20px auto 0;
            border: none;
            background-color: #0077DA;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
        }

        button a {
            text-decoration: none;
            color: inherit;
        }

        .heading {
            color: #0077DA;
            font-size: 20px;
            padding-bottom: 10px;
            text-align: center;
        }

        span {
            color: #1324BC;
            font-size: 30px;
        }

        a.fw-bold {
            text-decoration: none;
        }

        .login-title {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .center-content {
            display: flex;
            align-items: center;
        }

        .scrollable-container {
            width: 100%;
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
        }

        ol, ul {
            padding-left: 10px;
            text-align: left;
            font-size: 14  qpx;
        }

        li {
            margin-bottom: 10px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .checkbox-container label {
            margin-bottom: 0;
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .header .logo img {
            width: 50px;
            height: 50px;
        }

        .profile {
            display: flex;
            align-items: center;
            z-index: 1;
        }

        .profile ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .profile .nav-item {
            position: relative;
        }

        .profile .nav-link {
            color: #0077DA;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            text-shadow: #fff;
            margin-right: 20px;
            cursor: pointer;
        }

        .profile .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            z-index: 1;
        }

        .profile .dropdown-menu.show {
            display: block;
        }

        .profile .dropdown-item {
            padding: 10px 20px;
            color: #0077DA;
            text-decoration: none;
            display: block;
        }

        .profile .dropdown-item:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
<header class="header">
        <div class="logo">
            <a class="Geo" href="#"><img src="image/LOGO_DP_Zoning.png" alt="Logo"></a>
        </div>
        <div class="profile justify-content-end">
            <ul>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" aria-expanded="false">
                        <?php echo $logged_in_user; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php?username=<?php echo $logged_in_user; ?>">View Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="logout.php" method="post">
                                <button class="dropdown-item" type="submit" name="Logout">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </header>

    <div class="container">
        <h2 class="login-title">
            <img src="image/LOGO_DP_Zoning.png" class="logo" alt="" style="width:50px;height:50px;">
            <!-- <span class="fw-bold">Geopulsea</span> -->
        </h2>

        <h4 class="heading mt-4 mb-3 text-center">Terms and Conditions</h4>
        <!-- Modal popup content -->
        <div id="myModal" class="modal">
            <div class="modal-content scrollable-container">
                <span class="close" onclick="closeModal()">&times;</span>
                <p class="mt-4">Please accept the terms and conditions to proceed.</p>
            </div>
        </div>

        <form method="POST" onsubmit="return validateForm()" action="terms_and_condition.php">
            <div class="scrollable-container">
                <ol class="mt-4">

                    <ul>
                        <li>Zoning Demarcation of The Property is Necessary.</li>
                        <li>Changes are possible while final sanctioning to substantial modifications kept in abeyance.</li>
                        <li>सदर मिळकतीचा झोन मान्यता प्राप्त आर.पी./डी.पी. नकाशा याप्रमाणे दिलेला असुन अंतिम मान्यता मिळतेवेळी यामध्ये बदल होण्याची शक्यता नाकारता येत नाही याची नोंद घ्यावी.</li>
                        <li>प्रादेशिक आराखड्यातील अद्ययावत फेरबदल / मॉडिफिकेशन बाबत खातरजमा होणेवर.</li>
                        <li>नवीन / जुना किंवा एकत्रित झालेल्या स.नं./ग.नं. बाबत अर्जदार यांनी आपल्या स्तरावर खात्री करावी.</li>
                        <li>शासनाकडून वेळोवेळी होत असलेल्या मॉडिफिकेशन / फेरबदल यांमुळे सदर झोनिंग ३ महिने कालावधीपतर्यंत ग्राह्य धरण्यात येईल.</li>
                        <li>सदर संकेतस्थळावर दर्शविलेली माहिती तसेच आपणास देण्यात आलेले झोनिंग हे केवळ आपल्या माहिती साठी आणि आपली जमिन कोणत्या विभागात येते याचा अभ्यास करण्याच्या हेतुने देण्यात आलेले असुन सदर माहिती कोणत्याही शासकीय अथवा कायदेहीर बाबींसाठी वापरता येणार नाही, याची नोंद घ्यावी.</li>
                        <li>आपल्या मिळकतीचे झोनिंग करुन घेण्यासाठी सरकारी मोजणी, फाळणीबारा, गावनकाशा, गुगल लोकेशन ई. बाबींची आवश्यकता आहे.</li>
                        <li>सदर झोनिंग हे शासनाकडुन वेळोवेळी उपलब्ध करुन देण्यात आलेल्या प्रादेशिक योजना / विकास योजना यांच्या नकाशावरुन आपण उपलब्ध करुन दिलेली सरकारी मोजणी नकाशा यावर बसविण्यात आलेले असुन आमच्या सर्वोत्तम ज्ञानातुन देण्यात आलेले आहे, तरी त्याची खातरजमा आपण संबंधित शासकिय कार्यालयात करुन घेणे आवश्यक आहे.</li>
                    </ul>

                    <p>1) भूनकाशावरील नकाशे नागरिकांना माहितीस्तव उपलब्ध करुन देण्यात आलेला आहे.</p>
                    <p>2) वरील भूनकाशाचा कोणत्याही व्यावसायिक व कोणत्याही कायदेशीर कामासाठी वापर करता येणार नाही.</p>
                    <p>3) नागरिकांनी वरील नकाशाची प्रमाणित प्रत संबंधित तालुक्याच्या ऊप अधीक्षक भूमी अभिलेख कार्यालयातून उपलब्ध करून घेणे आवश्यक आहे.</p>

                    <ul>
                        <li>1) Maps available on Bhunaksha portal are only for viewing purposes.</li>
                        <li>2) The maps available on Bhunaksha portal cannot be used for commercial or legal purposes.</li>
                        <li>3) Citizens can get authorized copy of maps from the concerned taluka Deputy Superintendent of Land Records Office.</li>
                    </ul>

                    <h2>Terms and Conditions</h2>

                    <ul>
                        <li><strong>Acceptance of Terms</strong>
                            <p>By accessing or using [Your Company/Organization] (hereinafter referred to as "the platform"), you agree to be bound by these terms and conditions. If you do not agree with any part of these terms, you may not use the platform.</p>
                        </li>
                        <li><strong>Informational Purpose Only</strong>
                            <p>The content provided on the platform is for general informational purposes only. It is not intended to constitute professional advice, and should not be relied upon as such. [Your Company/Organization] makes no representations or warranties regarding the accuracy, completeness, or suitability of the information on the platform for any purpose.</p>
                        </li>
                        <li><strong>No Legal, Financial, or Medical Advice</strong>
                            <p>The information on the platform is not intended to be a substitute for professional advice, including but not limited to legal, financial, or medical advice. You should seek advice from qualified professionals regarding any specific situation.</p>
                        </li>
                        <li><strong>Use of Information</strong>
                            <p>You may view, download, and print content from the platform for your personal, non-commercial use only. Any other use of the content, including but not limited to reproduction, distribution, or modification, without the express written consent of [Your Company/Organization], is strictly prohibited.</p>
                        </li>
                        <li><strong>No Warranty</strong>
                            <p>[Your Company/Organization] makes no warranty, express or implied, regarding the accuracy, reliability, or completeness of the information on the platform. The platform is provided on an "as-is" basis.</p>
                        </li>
                        <li><strong>Limitation of Liability</strong>
                            <p>In no event shall [Your Company/Organization] be liable for any direct, indirect, incidental, consequential, or punitive damages arising out of your use of or inability to use the platform.</p>
                        </li>
                        <li><strong>Changes to Terms</strong>
                            <p>[Your Company/Organization] reserves the right to modify, add, or remove any part of these terms and conditions at any time. Your continued use of the platform following the posting of changes constitutes your acceptance of such changes.</p>
                        </li>
                        <li><strong>Governing Law</strong>
                            <p>These terms and conditions are governed by and construed in accordance with the laws of [Your Jurisdiction]. Any disputes arising under or in connection with these terms shall be subject to the exclusive jurisdiction of the courts located in [Your Jurisdiction].</p>
                        </li>
                    </ul>

                    <p>Contact Information:</p>
                    <p>[Geopulse]</p>
                    <p>[3 rd Floor, Royal House ,
                        karve putla Kothrud , 411038]</p>
                    <p>[admin@geopulsea.com]</p>
                    <p>[+918767432963]</p>

                    <p>By using the platform, you acknowledge that you have read, understood, and agree to these terms and conditions.</p>
                    <label class="">




                </ol>
            </div>
            <!-- <input type="checkbox" id="terms" class="mt-4">
            <label for="terms">I accept the terms and conditions</label> -->

            <input type="checkbox" id="terms" class="" style="display: inline-block; margin-right: 10px; font-size: 12px; margin-top:15px;"><label for="terms" style="display: inline-block;">I accept the terms and conditions</label>

            <button id="acceptButton" type="submit" disabled style="background-color: #808080; width: 200px;">Accept T&C</button>
        </form>
    </div>

    <!-- JavaScript to handle form validation and modal display -->
    <script>
        function openModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        function validateForm() {
            var checkBox = document.getElementById("terms");
            var acceptButton = document.getElementById("acceptButton");

            if (checkBox.checked == true) {
                return true;
            } else {
                openModal();
                return false;
            }
        }

        function toggleButton() {
            var checkBox = document.getElementById("terms");
            var acceptButton = document.getElementById("acceptButton");

            if (checkBox.checked == true) {
                acceptButton.style.backgroundColor = "#0077DA";
                acceptButton.disabled = false;
            } else {
                acceptButton.style.backgroundColor = "#808080";
                acceptButton.disabled = true;
            }
        }

        document.getElementById("terms").addEventListener("change", toggleButton);
    </script>
</body>

</html>