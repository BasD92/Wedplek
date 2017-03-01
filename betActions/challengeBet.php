<?php

//Database connection
require_once('../dbConnection/connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire'); // works
//session_cache_limiter('public'); // works too

//Start session
session_start();

if (isset($_POST['challengeBet'])) {
    //Get values from input. Create session to use in other if statement
    $_SESSION['placebetID'] = $_POST['placebetID'];
}

if (isset($_POST['startBet'])) {

    //Split value from matches and get id_footballMatch and footballMatch
    $match = explode("_", $_POST['matches']);
    $foreign_key_footballMatch = $match[0];
    $footballMatch = $match[1];

    //Get values from input
    $guessResult = $_POST['guessResult'];
    $memberID = $_POST['memberID'];
    $placebetID = $_SESSION['placebetID'];

    //Check member respond to own bet
    $checkOwn = mysqli_query($con, "SELECT * FROM placebet WHERE id_placebet = $placebetID
    AND member_id = '$memberID'")
    or die(mysqli_error($con));

    //Check member don't choose same result as his opponent
    $checkResult = mysqli_query($con, "SELECT * FROM placebet WHERE id_placebet = $placebetID
    AND guessResult = '$guessResult'")
    or die(mysqli_error($con));

    //Check member don't respond to more then 2 placebets
    $checkAmount = mysqli_query($con, "SELECT * FROM challengebet WHERE member_opponent_id = $memberID")
    or die(mysqli_error($con));

    //Check member don't respond to same match twice
    $checkMatch = mysqli_query($con, "SELECT * FROM challengebet WHERE member_opponent_id = $memberID
    AND footballMatch_challengebet = '$footballMatch'")
    or die(mysqli_error($con));

    //Check member don't respond to same placebet twice
//    $checkBet = mysqli_query($con, "SELECT * FROM placebet INNER JOIN challengebet
//                                                            ON placebet.id_placebet = challengebet.id_placebet_fk
//                                                            INNER JOIN member
//                                                            ON challengebet.member_opponent_id = member.id_member
//                                                            WHERE placebet.id_placebet = $placebetID
//                                                            AND challengebet.member_opponent_id = $memberID")
//    or die(mysqli_error($con));

    if (mysqli_num_rows($checkOwn) != 0) {
        //Error message when member respond to his own bet.
        $error = 'Uw kunt niet reageren op uw eigen weddenschap. Kies een andere weddenschap.';
    }

    //Error message when amount of rows is 2
    elseif (mysqli_num_rows($checkAmount) == 2) {
        $error2 = "Uw heeft al op het maximale aantal van 2 weddenschappen gewedt.";
    }

    //Error message when amount of rows isn't 0
    elseif (mysqli_num_rows($checkMatch) != 0) {
        $error3 = "Uw heeft al op deze wedstrijd gewedt. Kies een andere wedstrijd.";
    }

    elseif (mysqli_num_rows($checkResult) != 0) {
        //Error message when member add the same result value as his opponent.
        $error4 = 'Deze uitslag heeft uw tegenstander ook al ingevuld. Het is alleen mogelijk om een andere uitslag in
        te vullen.';
    }

    //Error message when amount of bets isn't 0
//    elseif (mysqli_num_rows($checkBet) != 0) {
//        $error5 = "Uw heeft al op deze weddenschap gereageerd. Kies een andere weddenschap.";
//    }

    //Check if row exist, if not, insert values to database
    else {
        //Add values to database
        $result = mysqli_query($con, "INSERT INTO challengebet (member_opponent_id, id_placebet_fk, footballMatch_fk, footballMatch_challengebet, guessResult_challengebet)
VALUES ($memberID, $placebetID, '$foreign_key_footballMatch', '$footballMatch', '$guessResult')") or die(mysqli_error($con));

        //Pop up when challengebet is successfull
        if ($result == true) {

            echo "<script>alert('Uw bent succesvol de uitdaging aangegaan!');</script>";
            echo '<script type="text/javascript">location.replace("../index.php");</script>';
        } else {
        //Pop up when challengebet isn't succesfull
            echo "<script>alert('Er is wat misgegaan. Probeer het opnieuw.');</script>";
            echo '<script type="text/javascript">location.replace("../index.php");</script>';
        }
    }
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Uitdaging aangaan</title>
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
                        <h1><a href="../index.php" id="logo">Ga de uitdaging aan!</a></h1>
                        <nav id="nav">
                            <a href="../index.php#placebet">Ga terug</a>
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
                        <form action="" method="post">

                            <!--Get footballMatch foreign key and match from database connect with football match from placebet-->
                            <?php
                            $placebetID = $_SESSION['placebetID'];
                            $result = mysqli_query($con, "SELECT * FROM placebet WHERE id_placebet = $placebetID");
                            ?>

                            <select name="matches">
                                <?php
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <option
                                        value="<?php echo $row['footballMatch_fk']; ?>_<?php echo $row['footballMatch']; ?>"><?php echo $row['footballMatch']; ?></option>
                                <?php
                                }
                                ?>
                            </select><br>
                            <!--End get footballMatch foreign key and match from database connect with football match from placebet-->

                            <select name="guessResult">
                                <option>1</option>
                                <option>X</option>
                                <option>2</option>
                            </select><br>

                            <!--Get id of the member to connect with the bet-->
                            <?php
                            $username = $_SESSION['username'];
                            $result = mysqli_query($con, "SELECT * FROM member WHERE member.username = '$username'");
                            ?>

                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <input type="hidden" name="memberID" value="<?php echo $row['id_member']; ?>"/>
                            <?php
                            }
                            ?>
                            <!--End get id of the member to connect with the bet-->

                            <input type="submit" name="startBet" value="Start de weddenschap"/><br>
                        </form>
                        <br>
                        <!--Error messages-->
                        <?php echo isset($error) ? $error : ''; ?>
                        <?php echo isset($error2) ? $error2 : ''; ?>
                        <?php echo isset($error3) ? $error3 : ''; ?>
                        <?php echo isset($error4) ? $error4 : ''; ?><br>
                        <br>
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

