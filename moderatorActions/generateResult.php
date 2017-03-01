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

// Moderator security
if (isset($_SESSION['username'])) {
    //Exit when moderator isn't logged in
    if ($_SESSION['username'] != 'BasD92') {
        exit;
    }
} else {
    // Exit when session dont exist
    exit;
}

if (isset($_POST['generateResults'])) {

    //Get guess results from placebet, challengebet and the real results from footballmatch
    $matchbetsResults = mysqli_query($con, "SELECT *
                                            FROM placebet
                                            INNER JOIN challengebet ON placebet.id_placebet = challengebet.id_placebet_fk
                                            INNER JOIN footballmatch ON placebet.footballMatch_fk = footballmatch.id_footballMatch");

    //fetch results from $matchbetsResults
    while ($row = mysqli_fetch_array($matchbetsResults)) {

        $member_id = $row['member_id'];
        $member_opponent_id = $row['member_opponent_id'];
        $placebetResult = $row['guessResult'];
        $challengebetResult = $row['guessResult_challengebet'];
        $realResult = $row['result'];

        //Update points to member who has started the bet and win
        if ($placebetResult == $realResult) {
            mysqli_query($con, "UPDATE member
                                SET score = score + 2
                                WHERE id_member = $member_id") or die(mysqli_error($con));
        }

        //Update points to member who react to a placebet and win
        if ($challengebetResult == $realResult) {
            mysqli_query($con, "UPDATE member
                                SET score = score + 2
                                WHERE id_member = $member_opponent_id") or die(mysqli_error($con));
        }

        //Update points to member who has started the bet and lose. Below 0 isn't possible.
        if ($placebetResult != $realResult) {
            mysqli_query($con, "UPDATE member
                                SET score = score - 1
                                WHERE id_member = $member_id
                                AND score > 0") or die(mysqli_error($con));
        }

        //Update points to member who react to a placebet and lose. Below 0 isn't possible.
        if ($challengebetResult != $realResult) {
            mysqli_query($con, "UPDATE member
                                SET score = score - 1
                                WHERE id_member = $member_opponent_id
                                AND score > 0") or die(mysqli_error($con));
        }
    }
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Uitslagen wedstrijden genereren</title>
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
                        <h1><a href="../index.php" id="logo">Uitslagen wedstrijden genereren</a></h1>
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
                        <form action="" method="post">
                            <input type="submit" name="generateResults" value="Genereer de resultaten"/><br>
                        </form>
                    </section>

                </div>
            </div>
            <!--Close connection-->
            <?php
            mysqli_close($con);
            ?>
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

</body>
</html>

