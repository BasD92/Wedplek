<?php

require_once('../dbConnection/connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//Start session
session_start();

// Send mail to opponent placebet

// Emailadress of opponent
$emailadress = $_SESSION['emailadress'];

// Logged in member data
$username = $_SESSION['username'];
$email = $_SESSION['emailMember'];

if (isset($_POST['mailSend'])) {

    $message = $_POST['message'];

    $status = false;

    // Emtpy message check
    if (empty($message)) {
        $error = 'Het is verplicht om een iets in te vullen.';
        $status = true;
    }

    //E-mail variables
    $to = $emailadress;
    $subject = 'Een bericht van je tegenstander op Wedplek.nl';

    $headers = "MIME-version: 1.0\r\n";
    $headers .= "content-type: text/html;charset=utf-8\r\n";

    if ($status == false) {

        $headers .= 'From: ' . $username . '<' . $email . '>';

        mail($to, $subject, nl2br($message), $headers);

        ?>
        <script type="text/javascript">
            alert("Het bericht is succesvol naar uw tegenstander verzonden!");
            window.location.href = "../mijnWedplek.php";
        </script>
    <?php
    }
}

mysqli_close($con);

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Verstuur mail</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--[if lte IE 8]>
    <script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="../assets/css/main.css"/>
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
                        <h1><a href="" id="logo">Verstuur mail</a></h1>
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
                <div class="8u 12u(mobile)">

                    <section class="left-content">
                        <form action="" method="post" enctype="multipart/form-data">
                            <label for="message"></label>
                            <textarea name="message" placeholder="Bericht (vereist)" id="message" cols="30"
                                      rows="10"></textarea><br>
                            <input type="submit" name="mailSend" value="Verstuur mail"/><br>
                            <?php echo isset($error) ? $error : ''; ?><br>
                        </form>
                        <br>
                        <span>*Als je een e-mail naar je tegenstander stuurt is jouw e-mail ook zichtbaar voor
                        de tegenstander. Mocht je dit niet willen i.v.m. bijvoorbeeld privacyredenen verstuur
                        dan geen e-mail.</span>
                    </section>

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

