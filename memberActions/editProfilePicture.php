<?php

//Database connection
require_once('../dbConnection/connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//Start session
session_start();

// Variable of session username
$username = $_SESSION['username'];

if (isset($_POST['clickProfilePictureEdit'])) {

    $status = false;

//Save uploaded pics in directory
    $target = "../profileImages/";
    $target = $target . basename($_FILES['profilePicture']['name']);

    $profilePicture = ($_FILES['profilePicture']['name']);

    // Array of allowed types profile picture
    $types = array('image/jpeg', 'image/gif', 'image/png');

    $checkResult = mysqli_query($con, "SELECT * FROM member WHERE profielfoto = '$profilePicture'")
    or die(mysqli_error($con));

    // Check photo name exist in database, give error when photo exist
    if (mysqli_num_rows($checkResult) != 0) {
        $profilePictureError = 'Kies een andere foto of verander de naam van je foto.';
        $status = true;
    }

    //Check file type profile picture
    if (!in_array($_FILES['profilePicture']['type'], $types)) {

        // Error message when isn't .jpeg/.jpg, .gif or .png
        $profilePictureError2 = 'Alleen .jpeg/.jpg, .gif of .png bestanden zijn toegestaan.';
        $status = true;
    }

    // Check file size profile picture
    if ($_FILES['profilePicture']['size'] > 2000000) {
        $profilePictureError3 = 'De grootte van de foto mag maximaal 2MB zijn.';
        $status = true;
    }

    // Delete old profile picture in directory
    if($status == false) {
        $result = mysqli_query($con, "SELECT profielfoto FROM member WHERE username = '$username'")
        or die(mysqli_error($con));

        while ($row = mysqli_fetch_array($result)) {
            if (!empty($row['profielfoto'])) {
                unlink('../profileImages/' . $row['profielfoto']);
            }
        }
    }

    // Update new profile picture in directory and database
    if ($status == false) {

        //Add user to database
        mysqli_query($con, "UPDATE member SET profielfoto = '$profilePicture' WHERE username = '$username'")
        or die(mysqli_error($con));

        // Upload picture to directory
        move_uploaded_file($_FILES['profilePicture']['tmp_name'], $target);

        ?>
        <script type="text/javascript">
            alert("Uw profielfoto is succesvol gewijzigd!");
            window.location.href = "../mijnWedplek.php";
        </script>
    <?php
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Profielfoto aanpassen</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--[if lte IE 8]>
    <script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="../assets/css/main.css"/>
    <link rel="stylesheet" href="../assets/css/extra.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../assets/css/ie9.css"/><![endif]-->
</head>
<body>
<div id="page-wrapper">
    <div id="header-wrapper">
        <div class="container">
            <div class="row">
                <div class="12u">

                    <header id="header">
                        <h1><a href="" id="logo">Profielfoto aanpassen</a></h1>
                        <nav id="nav">
                            <a href="../mijnWedplek.php">Ga terug</a>
                        </nav>
                    </header>

                </div>
            </div>
        </div>
    </div>
    <div id="main">
        <div class="container">
            <div class="row main-row">
                <div class="4u 12u(mobile)">

                    <section>

                        <!--Show log in input when member not not logged in-->
                        <?php if (!isset($_SESSION['username'])) {
                            ?>
                            <h2>Inloggen</h2>
                            <form action="" method="post">
                                <input type="text" placeholder="Gebruikersnaam" name="username"/><br>
                                <input type="password" placeholder="Wachtwoord" name="password"/><br>
                                <input type="submit" name="login" value="Inloggen"/><br>
                            </form><br>
                            <span><?php echo isset($error) ? $error : ''; ?></span><br>
                            <a href="../memberActions/register.php">Registreren</a><br>
                        <?php
                        } ?>
                        <!--End show log in input when member not not logged in-->

                        <!--Show data of clicked member when logged in-->
                        <?php if (isset($_SESSION['username'])) {
                            ?>
                            <h2>Profielfoto</h2>
                            <ul class="small-image-list">

                                <?php
                                $result = mysqli_query($con, "SELECT * FROM member WHERE username = '$username'");
                                ?>

                                <?php
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>

                                    <li>
                                        <!--Check profile picture isn't empty in database else show a standard picture-->
                                        <?php
                                        if (!empty($row['profielfoto'])) {
                                            ?>

                                            <?php echo "<img class=left src=../profileImages/" . $row['profielfoto'] . ">"; ?>
                                            <br>

                                        <?php
                                        } else {
                                            echo "<img class='left' src='../profileImages/no-profile.png'><br>";
                                        }
                                        ?>
                                        <!--End check profile picture isn't empty in database else show a standard picture-->

                                    </li>

                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="file" name="profilePicture"><br>
                                        <br>
                                        <input type="submit" name="clickProfilePictureEdit"
                                               value="Verander profielfoto"/><br>
                                        <?php echo isset($profilePictureError) ? $profilePictureError : ''; ?><br>
                                        <?php echo isset($profilePictureError2) ? $profilePictureError2 : ''; ?><br>
                                        <?php echo isset($profilePictureError3) ? $profilePictureError3 : ''; ?><br>
                                    </form>

                                <?php
                                }
                                ?>
                            </ul>
                        <?php
                        }
                        ?>
                        <!--End show data of clicked member when logged in-->

                    </section>

                </div>
            </div>
        </div>
    </div>
    <div id="footer-wrapper">
        <div class="container">
            <div class="row">
                <div class="8u 12u(mobile)">

                    <section>
                        <h2>Sitemap</h2>

                        <div>
                            <div class="row">
                                <div class="3u 12u(mobile)">
                                    <ul class="link-list">
                                        <li><a href="../index.php">Home</a></li>
                                        <li><a href="../contact.php">Contact</a></li>
                                    </ul>
                                </div>
                                <div class="3u 12u(mobile)">
                                    <ul class="link-list">
                                        <li><a href="../mijnWedplek.php">Mijn Wedplek</a></li>
                                    </ul>
                                </div>
                                <div class="3u 12u(mobile)">
                                    <ul class="link-list">
                                        <li><a href="../ranglijsten.php">Ranglijsten</a></li>
                                    </ul>
                                </div>
                                <div class="3u 12u(mobile)">
                                    <ul class="link-list">
                                        <li><a href="../overWedplek.php">over Wedplek</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="row">
                <div class="12u">

                    <div id="copyright">
                        <a href="https://www.facebook.com/wedplek" target="_blank"><img class="socialmediabutton"
                                                                                        src='../images/facebook.png'
                                                                                        alt="Facebook"/></a>

                        <a href="https://twitter.com/wedplek" target="_blank"><img class="socialmediabutton" src='../images/twitter.png'
                                                                                   alt="Twitter"/></a><br><br>
                        @ Wedplek 2015
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/skel.min.js"></script>
<script src="../assets/js/skel-viewport.min.js"></script>
<script src="../assets/js/util.js"></script>
<!--[if lte IE 8]>
<script src="../assets/js/ie/respond.min.js"></script><![endif]-->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/extra.js"></script>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-71618024-1', 'auto');
    ga('send', 'pageview');

</script>

</body>
</html>