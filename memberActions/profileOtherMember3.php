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

// Check input isnt empty when click "login"
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    // Escape special characters in a string for use in an SQL statement
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Check username and password in database
    $sql = "SELECT  * FROM `member` WHERE `Username` = '" . $username . "' AND `Password` = '" . $password . "' LIMIT 1";

    if ($result = mysqli_query($con, $sql)) {
        $row = mysqli_fetch_assoc($result);
        if ($row == true) {
            // Create sessions
            $_SESSION['login'] = true;
            $_SESSION['username'] = $_POST['username'];
            // Send to same page
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $error = 'Gebruikersnaam of wachtwoord is ongeldig.';
        }
    } else {
        // Error message
        die('Error: ' . mysqli_error($con));
    }
}

if (isset($_POST['clickUsername3'])) {
    $_SESSION['opponentMemberID2'] = $_POST['opponentMemberID2'];
}

$opponentMemberID2 = $_SESSION['opponentMemberID2'];

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Profiel</title>
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
                    <h1><a href="" id="logo">Profiel</a></h1>
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
                $result = mysqli_query($con, "SELECT * FROM member WHERE id_member = $opponentMemberID2");
                ?>

                <?php
                while ($row = mysqli_fetch_array($result)) {
                    ?>

                    <li>
                        <!--Check profile picture isn't empty in database else show a standard picture-->
                        <?php
                        if (!empty($row['profielfoto'])) {
                            ?>

                            <a href=""><?php echo "<img class=left src=../profileImages/" . $row['profielfoto'] . ">"; ?></a>

                        <?php
                        } else {
                            echo "<a href=''><img class='left' src='../profileImages/no-profile.png'></a>";
                        }
                        ?>
                        <!--End check profile picture isn't empty in database else show a standard picture-->

                        <!--Show username of clicked member-->
                        <h4><?php echo $row['username'] ?></h4>
                        <!--End show username of clicked member-->
                    </li>

                <?php
                }
                ?>
            </ul>
        <?php
        }
        ?>
        <!--End show data of clicked member when logged in-->

    </section>

    <section>
        <!--Show all the matches with other members of the member when the member successfully logged in.-->
        <?php if (isset($_SESSION['username'])) {
            ?>

            <h2>Profielgegevens</h2>

            <ul class="small-image-list">

                <!--Get id of the clicked member-->
                <?php
                $result = mysqli_query($con, "SELECT * FROM member WHERE id_member = $opponentMemberID2");
                ?>
                <!--End Get id of the clicked member-->

                <?php
                while ($row = mysqli_fetch_array($result)) {
                    ?>

                    <li>
                        <span>Voornaam: <?php echo $row['voornaam']; ?></span>
                        <br>
                    </li>
                    <li>
                        <span>Achternaam: <?php echo $row['achternaam']; ?></span>
                        <br>
                    </li>
                    <li>
                        <span>Leeftijd: <?php echo $row['leeftijd']; ?></span>
                        <br>
                    </li>
                    <li>
                        <span>Provincie: <?php echo $row['province']; ?></span>
                        <br>
                    </li>
                <?php
                }
                ?>
            </ul>

        <?php
        }
        ?>
    </section>

</div>
<div class="8u 12u(mobile) important(mobile)">

    <?php
    if (isset($_SESSION['username'])) {

    $result = mysqli_query($con, "SELECT * FROM member WHERE id_member = $opponentMemberID2");
    ?>

<?php
while ($row = mysqli_fetch_array($result)) {
?>
    <section class="right-content">
        <!--Username clicked member-->
        <h2><?php echo "Dit is het profiel van: " . $row['username'] ?></h2>

        <h3>Totaal behaalde punten: <?php echo $row['score']; ?></h3>

        <?php
        }
        }
        ?>
    </section>

    <!--Show trophies of the member when member logged in-->
    <?php if (isset($_SESSION['username'])) {

        // Select score from logged in member
        $resultScore = mysqli_query($con, "SELECT score FROM member
                              WHERE member.id_member = $opponentMemberID2");
        ?>

        <section class="right-content">
            <h2>Prijzenkast</h2>

            <?php
            while ($rowScore = mysqli_fetch_array($resultScore)) {
                ?>
                <div>
                    <div class="row">
                        <div class="6u 12u(mobile)">
                            <ul class="link-list">
                                <li><span class="awardText">Amateur</span><br><br>
                                    <?php
                                    // Show award when member reach score
                                    if ($rowScore['score'] >= 20) {
                                    ?>
                                    <img src="awards/bronze.png" alt="Brons" class="award"></li>
                                <?php
                                // Show message when member haven't the award already
                                } else {
                                    echo "<span>Award te behalen bij 20 of meer punten</span>";
                                }
                                ?>
                                <li><span class="awardText">Professioneel</span><br><br>
                                    <?php
                                    if ($rowScore['score'] >= 80) {
                                    ?>
                                    <img src="../awards/gold.png" alt="Goud" class="award"></li>
                            <?php
                            } else {
                                echo "<span>Award te behalen bij 80 of meer punten</span>";
                            }
                            ?>
                                <li><span class="awardText">Legendarisch</span><br><br>
                                    <?php
                                    if ($rowScore['score'] >= 500) {
                                    ?>
                                    <img src="../awards/gold-trophy1.png" alt="Legendarische prijs" class="award"></li>
                            <?php
                            } else {
                                echo "<span>Award te behalen bij 500 of meer punten</span>";
                            }
                            ?>
                            </ul>
                        </div>
                        <div class="6u 12u(mobile)">
                            <ul class="link-list">
                                <li><span class="awardText">Semi-prof</span><br><br>
                                    <?php
                                    if ($rowScore['score'] >= 50) {
                                    ?>
                                    <img src="../awards/silver.png" alt="Zilver" class="award"></li>
                                <?php
                                } else {
                                    echo "<span>Award te behalen bij 50 of meer punten</span>";
                                }
                                ?>
                                <li><span class="awardText">Wereldklasse</span><br><br>
                                    <?php
                                    if ($rowScore['score'] >= 200) {
                                    ?>
                                    <img src="../awards/gold-trophy3.png" alt="Wereldklasse prijs" class="award"></li>
                            <?php
                            } else {
                                echo "<span>Award te behalen bij 200 of meer punten</span>";
                            }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </section>
    <?php
    } ?>
    <!--End show trophies of the member when member logged in-->

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