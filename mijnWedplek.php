<?php

//Database connection
require_once('dbConnection/connect.php');
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

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>mijn Wedplek</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--[if lte IE 8]>
    <script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css"/>
    <link rel="stylesheet" href="assets/css/extra.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="assets/css/ie9.css"/><![endif]-->
</head>
<body>
<div id="page-wrapper">
<div id="header-wrapper">
    <div class="container">
        <div class="row">
            <div class="12u">

                <header id="header">
                    <a href="index.php"><img id="logo" src="images/wedplekLogo.png"></a>
                    <nav id="nav">
                        <a href="index.php">Home</a>
                        <a href="mijnWedplek.php" class="current-page-item">Mijn Wedplek</a>
                        <a href="ranglijsten.php">Ranglijsten</a>
                        <a href="overWedplek.php">Over Wedplek</a>
                        <a href="contact.php">Contact</a>
                        <!--Create log out button when member successfully logged in-->
                        <?php if (isset($_SESSION['username'])) {
                            ?>
                            <a id="logout" href="memberActions/logout.php">Uitloggen</a>
                        <?php
                        } ?>
                        <!--End create log out button when member successfully logged in-->
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
            <input type="submit" class="myButton2" name="login" value="Inloggen"/><br>
        </form><br>
        <span><?php echo isset($error) ? $error : ''; ?></span><br><br>
        <p>Wachtwoord vergeten? Neem <a href="contact.php">contact</a> op voor een nieuw wachtwoord. Verstuur
            een bericht met je e-mail die geregistreerd staat bij Wedplek en geef duidelijk aan dat je het
            wachtwoord bent vergeten.</p>
        <a href="memberActions/register.php">Registreren</a><br>
    <?php
    } ?>
    <!--End show log in input when member not not logged in-->

    <!--Show all the bets of the member when the member successfully logged in. Give also a
    possibility to add new bets-->
    <?php if (isset($_SESSION['username'])) {
        ?>
        <h2>Mijn weddenschappen</h2>
        <ul class="small-image-list">
            <form action="betActions/addBet.php" method="post">

                <!--Get id and matches from database -->
                <?php
                $result = mysqli_query($con, "SELECT * FROM footballmatch") or die(mysqli_error($con));
                ?>

                <select name="matches">
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <option
                            value="<?php echo $row['id_footballMatch']; ?>_<?php echo $row['footballMatch']; ?>"><?php echo $row['footballMatch']; ?></option>
                    <?php
                    }
                    ?>
                </select><br>
                <!--End get id and matches from database-->

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
                    // Create session of the member's id
                    $_SESSION['memberID'] = $row['id_member'];
                }
                ?>
                <!--End get id of the member to connect with the bet-->

                <input type="submit" class="myButton2" name="sendBet" value="Plaats je weddenschap"/><br>
                <br>
            </form>

            <?php
            // show all the bets of the member with the correct id when successfully logged in. Last
            // added bet at the top of the list. Bets aren't connected with a challengebet.
            $memberID = $_SESSION['memberID'];
            $result = mysqli_query($con, "SELECT * FROM placebet
                                                  LEFT JOIN challengebet
                                                    ON placebet.id_placebet = challengebet.id_placebet_fk
                                                  INNER JOIN member
                                                    ON placebet.member_id = member.id_member WHERE placebet.member_id = $memberID
                                                        AND challengebet.id_placebet_fk IS NULL
                                                        ORDER BY placebet.id_placebet DESC");
            ?>

            <?php
            while ($row = mysqli_fetch_array($result)) {
                ?>

                <li>
                    <!--Check profile picture isn't empty in database else show a standard picture-->
                    <?php
                    if (!empty($row['profielfoto'])) {
                        ?>

                        <a href=""><?php echo "<img class=left src=profileImages/" . $row['profielfoto'] . ">"; ?></a>

                    <?php
                    } else {
                        echo "<a href=''><img class='left' src='profileImages/no-profile.png'></a>";
                    }
                    ?>
                    <!--End check profile picture isn't empty in database else show a standard picture-->

                    <!--Show username at the bets of the member when successfully logged in-->
                    <?php if (isset($_SESSION['username'])) {
                        ?>
                        <h4 class="usernameStyle"><?php echo $_SESSION['username'] ?></h4>
                    <?php
                    }
                    ?>
                    <!--End show username at the bets of the member when successfully logged in-->

                    <span id="match"><?php echo $row['footballMatch']; ?></span>
                        <span id="result"><?php echo $row['guessResult']; ?></span>
                    <br>
                    <br>

                    <form action="betActions/deleteBet.php" method='post'>
                        <input type='submit' class="myButton2" name='deleteBet' value='Verwijder weddenschap'/>
                        <input type="hidden" name="id" value="<?php echo $row['id_placebet']; ?>"/>
                    </form>
                </li>

            <?php
            }
            ?>
        </ul>
    <?php
    }
    ?>
    <!--End show all the bets of the member when the member successfully logged in. Give also a
    possibility to add new bets-->

</section>

<section>
    <!--Show all the matches with other members of the member when the member successfully logged in.-->
    <?php if (isset($_SESSION['username'])) {
        ?>

        <h2>Mijn wedstrijden</h2>

        <ul class="small-image-list">

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
                // Create session of the member's id
                $_SESSION['memberID'] = $row['id_member'];
            }
            ?>
            <!--End get id of the member to connect with the bet-->

            <?php
            // show all the bet matches of the member with the correct id when successfully logged in. Last
            // added bet match at the top of the list
            $memberID = $_SESSION['memberID'];
            $result = mysqli_query($con, "SELECT * FROM challengebet INNER JOIN placebet
                            ON challengebet.id_placebet_fk = placebet.id_placebet
                            INNER JOIN member ON challengebet.member_opponent_id = member.id_member
                            WHERE challengebet.member_opponent_id = $memberID OR placebet.member_id = $memberID
                            ORDER BY id_challengebet DESC");
            ?>

            <?php
            while ($row = mysqli_fetch_array($result)) {
                ?>

                <li id="matchStyle">
                    <?php
                    // Placebet
                    // Show username connect with placebet match and his bet
                    if ($row['member_id'] == $memberID) {
                        ?>
                        <span class="usernameStyle"><?php echo $username ?></span><br>
                        <br>
                    <?php
                    } else {
                        ?>
                        <!--Give only possibility to show profile of other members than logged in member-->
                        <span><?php

                            $idPlacebetMember = $row['member_id'];

                            $resultMember = mysqli_query($con, "SELECT * FROM member
                            WHERE id_member = $idPlacebetMember");

                            while ($rowMember = mysqli_fetch_array($resultMember)) {
                                $opponentMemberID = $rowMember['id_member'];
                                $nameMember = $rowMember['username'];
                                $_SESSION['emailadress'] = $rowMember['email'];

                                echo "<form action='memberActions/profileOtherMember2.php' method='post'>
                                    <input type='hidden' name='opponentMemberID' value='$opponentMemberID'/>
                                    <input type='submit' class='myButton' name='clickUsername2' value='$nameMember'/><br>
                                    </form>";
                                }

                            // Send mail to opponent placebet member
                            ?>
                            <form action="memberActions/sendMailToOpponent.php" method="post">
                                <input type="submit" class="myButtonMail" name="sendMail" value="Verstuur mail"/>
                            </form></span>
                        <br>
                    <?php
                    }
                    ?>
                    <span id="match"><?php echo $row['footballMatch']; ?>
                        <span id="result"><?php echo $row['guessResult']; ?></span>
                    </span><br>

                    <br>
                    <span id="vs">VS</span><br>
                    <br>

                    <?php
                    // Challengebet
                    // Show username connect with challengebet match and his bet
                    if ($row['username'] != $username) {
                        ?>
                        <!--Give only possibility to show profile of other members than logged in member-->
                        <span>
                            <form action='memberActions/profileOtherMember3.php' method='post'>
                                <input type='hidden' name='opponentMemberID2' value='<?php echo $row['id_member']; ?>'/>
                                <input type='submit' class='myButton' name='clickUsername3' value='<?php echo $row['username']; ?>'/><br>
                            </form>

                            <!--Send mail to opponent challengebet member-->
                            <form action="memberActions/sendMailToOpponent2.php" method="post">
                                <?php $_SESSION['emailadress2'] = $row['email']; ?>
                                <input type="submit" class="myButtonMail" name="sendMail2" value="Verstuur mail"/>
                            </form></span>
                        <br>
                    <?php
                    } else {
                        ?>
                        <span class="usernameStyle"><?php echo $username ?></span><br>
                        <br>
                    <?php
                    }
                    ?>
                    <span id="match"><?php echo $row['footballMatch_challengebet']; ?>
                        <span id="result"><?php echo $row['guessResult_challengebet']; ?></span>
                    </span><br>
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

    <!--Personal welcome to the member-->
    <section class="right-content">
        <?php if (isset($_SESSION['username'])) {
            ?>
            <h2><?php echo "Welkom " . $_SESSION['username'] . "! Daag mensen uit of neem de uitdaging aan!" ?></h2>
        <?php
        } ?>
        <!--End personal welcome to the member-->

        <?php
        //Show score when member logged in
        if (isset($_SESSION['username'])) {

            // show the points from the logged in member
            $memberID = $_SESSION['memberID'];
            $result = mysqli_query($con, "SELECT * FROM member
                             WHERE member.id_member = $memberID");
            ?>

            <?php
            while ($row = mysqli_fetch_array($result)) {
                ?>

                <h3>Uw heeft <?php echo $row['score']; ?> punten</h3>

            <?php
            }
        }
        ?>
    </section>

    <!--Show trophies of the member when member logged in-->
    <?php if (isset($_SESSION['username'])) {
        $memberID = $_SESSION['memberID'];

        // Select score from logged in member
        $resultScore = mysqli_query($con, "SELECT score FROM member
                              WHERE member.id_member = $memberID");
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
                                    <img src="awards/gold.png" alt="Goud" class="award"></li>
                            <?php
                            } else {
                                echo "<span>Award te behalen bij 80 of meer punten</span>";
                            }
                            ?>
                                <li><span class="awardText">Legendarisch</span><br><br>
                                    <?php
                                    if ($rowScore['score'] >= 500) {
                                    ?>
                                    <img src="awards/gold-trophy1.png" alt="Legendarische prijs" class="award"></li>
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
                                    <img src="awards/silver.png" alt="Zilver" class="award"></li>
                                <?php
                                } else {
                                    echo "<span>Award te behalen bij 50 of meer punten</span>";
                                }
                                ?>
                                <li><span class="awardText">Wereldklasse</span><br><br>
                                    <?php
                                    if ($rowScore['score'] >= 200) {
                                    ?>
                                    <img src="awards/gold-trophy3.png" alt="Wereldklasse prijs" class="award"></li>
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
                                    <li><a href="index.php">Home</a></li>
                                    <li><a href="contact.php">Contact</a></li>
                                </ul>
                            </div>
                            <div class="3u 12u(mobile)">
                                <ul class="link-list">
                                    <li><a href="mijnWedplek.php">Mijn Wedplek</a></li>
                                </ul>
                            </div>
                            <div class="3u 12u(mobile)">
                                <ul class="link-list">
                                    <li><a href="ranglijsten.php">Ranglijsten</a></li>
                                </ul>
                            </div>
                            <div class="3u 12u(mobile)">
                                <ul class="link-list">
                                    <li><a href="overWedplek.php">over Wedplek</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
            <div class="4u 12u(mobile)">

                <section>

                    <!--Delete account-->
                    <?php if (isset($_SESSION['username'])) {
                        ?>
                        <?php
                        // Select logged in member
                        $username = $_SESSION['username'];
                        $result = mysqli_query($con, "SELECT * FROM member WHERE member.username = '$username'");
                        ?>
                        <h2>Account</h2>
                        <form action="memberActions/editAccount.php" method="post">
                            <input type="submit" name="editAccount" value="Gegevens bekijken/aanpassen"/>
                        </form><br>
                        <form action="memberActions/editProfilePicture.php" method="post">
                            <input type="submit" name="editProfilePicture" value="Profielfoto veranderen"/>
                        </form><br>
                        <form action="memberActions/deleteAccount.php" method="post">
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <input type="hidden" name="memberID" value="<?php echo $row['id_member']; ?>"/>
                                <input type="submit" name="deleteAccount" onclick="return confirm('Weet je het zeker?')"
                                       value="Verwijder account"/>
                            <?php
                            }
                            ?>
                        </form><br>
                    <?php
                    } ?>
                    <!--End delete account-->

                </section>

            </div>
        </div>
        <div class="row">
            <div class="12u">

                <div id="copyright">
                    <a href="https://www.facebook.com/wedplek" target="_blank"><img class="socialmediabutton"
                                                                                    src='images/facebook.png'
                                                                                    alt="Facebook"/></a>

                    <a href="https://twitter.com/wedplek" target="_blank"><img class="socialmediabutton" src='images/twitter.png'
                                                                               alt="Twitter"/></a><br><br>
                    @ Wedplek 2015
                </div>

            </div>
        </div>
    </div>
</div>
</div>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/skel.min.js"></script>
<script src="assets/js/skel-viewport.min.js"></script>
<script src="assets/js/util.js"></script>
<!--[if lte IE 8]>
<script src="assets/js/ie/respond.min.js"></script><![endif]-->
<script src="assets/js/main.js"></script>
<script src="assets/js/extra.js"></script>

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