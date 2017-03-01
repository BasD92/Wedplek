<?php

//Database connection
require_once('dbConnection/connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

session_start();

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Weddenschappen</title>
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
                        <h1><a href="index.php" id="logo">Wedplek</a></h1>
                        <nav id="nav">
                            <a href="index.php">Home</a>
                            <a href="mijnWedplek.php">Mijn Wedplek</a>
                            <a href="ranglijsten.php" class="current-page-item">Weddenschappen</a>
                            <a href="overWedplek.php">Over Wedplek</a>
                            <a href="contact.php">Contact</a>
                            <?php if (isset($_SESSION['username'])) {
                                ?>
                                <a id="logout" href="memberActions/logout.php">Uitloggen</a>
                            <?php
                            } ?>
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
                <h2>De geplaatste weddenschappen:</h2>
                <ul class="small-image-list">

                <!--Show add bet options to give the member the possibility to add a new bet when successfully logged in-->
                <?php if (isset($_SESSION['username']) && !isset($_POST['clickSearchUsername'])) {
                    ?>
                    <form action="betActions/addBet.php" method="post">

                        <!--Get id and matches from database -->
                        <?php
                        $result = mysqli_query($con, "SELECT * FROM footballMatch");
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

                            // Create sessions
                            $_SESSION['firstName'] = $row['voornaam'];
                            $_SESSION['lastName'] = $row['achternaam'];
                            $_SESSION['emailMember'] = $row['email'];
                            ?>
                            <input type="hidden" name="memberID" value="<?php echo $row['id_member']; ?>"/>
                        <?php
                        }
                        ?>
                        <!--End get id of the member to connect with the bet-->

                        <input type="submit" name="sendBet" value="Plaats je weddenschap"/><br>
                        <br>
                    </form>

                <?php
                } ?>
                <!--End show add bet options to give the member the possibility to add a new bet when successfully logged in-->

                <?php if (isset($_SESSION['username'])) {
                    ?>
                    <!--Form search username-->
                    <form action="" method="post">
                        <input type="text" name="searchUsername"/><br>
                        <input type="submit" name="clickSearchUsername" value="Zoek gebruiker"/><br>
                        <br>
                    </form>

                <?php
                } ?>

                <!--Show all the bets that don't match a challengebet. Last bet at the top of the list-->
                <?php

                // Pagination
                // Count rows
                $countRows = mysqli_query($con, "SELECT COUNT(id_placebet) FROM placebet LEFT JOIN challengebet
                                                              ON placebet.id_placebet = challengebet.id_placebet_fk
                                                                             INNER JOIN member
                                                              ON placebet.member_id = member.id_member
                                                                   WHERE placebet.member_id = member.id_member
                                                                   AND challengebet.id_placebet_fk IS NULL
                                                                   ORDER BY placebet.id_placebet DESC");

                $rowResult = mysqli_fetch_row($countRows);

                // Here we have the total row count
                $rowsResult = $rowResult[0];

                // This is the number of results we want to display per page
                $page_rows = 10;

                // This tells us the page number of our last page
                $last = ceil($rowsResult / $page_rows);

                // This makes sure $last cannot not be less than 1
                if ($last < 1) {
                    $last = 1;
                }

                // Establish the $pagenum variable
                $pagenum = 1;

                // Get pagenum from URL vars if it is present, else it is 1
                if (isset($_GET['pn'])) {
                    $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
                }

                // This makes sure the page number isn't below 1, or more than our $last page
                if ($pagenum < 1) {
                    $pagenum = 1;
                } else if ($pagenum > $last) {
                    $pagenum = $last;
                }

                // This sets the range of rows to query for the chosen $pagenum
                $limit = 'LIMIT ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;

                // This shows the member what page they are on, and the total number of pages
                $textline1 = "Geplaatste weddenschappen: <b>$rowsResult</b>";
                $textline2 = "Pagina <b>$pagenum</b> van de <b>$last</b>";

                // Establish the $paginationCtrls variable
                $paginationCtrls = '';

                // If there is more than 1 page worth of results
                if ($last != 1) {
                    // First we check if we are on page one. If we are then we don't need a link to the previous page or the first page
                    // so we do nothing. If we aren't then we generate links to the first page, and to the previous page.
                    if ($pagenum > 1) {
                        $previous = $pagenum - 1;
                        $paginationCtrls .= '<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $previous . '">Vorige</a> &nbsp; &nbsp; ';

                        // Render clickable number links that should appear on the left of the target page number
                        for ($i = $pagenum - 4; $i < $pagenum; $i++) {
                            if ($i > 0) {
                                $paginationCtrls .= '<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $i . '">' . $i . '</a> &nbsp; ';
                            }
                        }
                    }

                    // Render the target page number, but without it being a link
                    $paginationCtrls .= '' . $pagenum . ' &nbsp; ';

                    // Render clickable number links that should appear on the right of the target page number
                    for ($i = $pagenum + 1; $i <= $last; $i++) {
                        $paginationCtrls .= '<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $i . '">' . $i . '</a> &nbsp; ';
                        if ($i >= $pagenum + 4) {
                            break;
                        }
                    }

                    // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                    if ($pagenum != $last) {
                        $next = $pagenum + 1;
                        $paginationCtrls .= ' &nbsp; &nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $next . '">Volgende</a> ';
                    }
                }

                // Give result of searched username when click the submit button clickSearchUsername
                if (isset($_POST['clickSearchUsername'])) {
                    $searchUsername = $_POST['searchUsername'];

                    $result = mysqli_query($con, "SELECT * FROM placebet LEFT JOIN challengebet
                                                              ON placebet.id_placebet = challengebet.id_placebet_fk
                                                                             INNER JOIN member
                                                              ON placebet.member_id = member.id_member
                                                                   WHERE placebet.member_id = member.id_member
                                                                   AND challengebet.id_placebet_fk IS NULL
                                                                   AND member.username = '$searchUsername'
                                                                   ORDER BY placebet.id_placebet DESC");

                    // Check member exist
                    $resultMember = mysqli_query($con, "SELECT * FROM member WHERE username = '$searchUsername'");

                    //Pop up when search username is successfull
                    if (mysqli_num_rows($resultMember) == 1) {

                        echo '<script type="text/javascript">location.replace("index.php#placebet");</script>';
                    } else {
                        //Pop up when search username isn't succesfull
                        echo "<script>alert('De opgegeven username bestaat niet of er is niks ingevuld. Probeer het opnieuw.');</script>";
                        echo '<script type="text/javascript">location.replace("index.php#placebet");</script>';
                    }
                } else {
                    $result = mysqli_query($con, "SELECT * FROM placebet LEFT JOIN challengebet
                                                              ON placebet.id_placebet = challengebet.id_placebet_fk
                                                                             INNER JOIN member
                                                              ON placebet.member_id = member.id_member
                                                                   WHERE placebet.member_id = member.id_member
                                                                   AND challengebet.id_placebet_fk IS NULL
                                                                   ORDER BY placebet.id_placebet DESC
                                                                   $limit");
                }

                if (!$result) {
                    die('Could not get data: ' . mysqli_error($con));
                }

                // Check click to find for username, if not show texlines
                if (!isset($_POST['clickSearchUsername'])) {
                    ?>

                    <!--Show total placebets and current page number-->
                    <h1><?php echo $textline1; ?></h1>
                    <h1><?php echo $textline2; ?></h1><br>

                <?php
                }

                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                    ?>

                    <li>
                        <!--Check profile picture isn't empty in database else show a standard picture-->
                        <?php
                        if (!empty($row['profielfoto'])) {
                            ?>

                            <?php echo "<img class=left src=profileImages/" . $row['profielfoto'] . ">"; ?>

                        <?php
                        } else {
                            echo "<img class='left' src='profileImages/no-profile.png'>";
                        }
                        ?>
                        <!--End check profile picture isn't empty in database else show a standard picture-->

                        <!--Username button-->
                        <form action="memberActions/profileOtherMember.php" method="post">
                            <input type="hidden" name="placebetMemberID"
                                   value="<?php echo $row['id_member']; ?>"/>
                            <input type="submit" name="clickUsername" value="<?php echo $row['username']; ?>"/><br>
                        </form>
                        <!--End username button-->

                        <p><?php echo $row['footballMatch']; ?>
                            <span><?php echo $row['guessResult']; ?></span>
                        </p>

                        <!--Create a button to challenge an other member when member successfully logged in-->
                        <?php if (isset($_SESSION['username'])) {
                            ?>
                            <form action="betActions/challengeBet.php" method="post">
                                <input type="hidden" name="placebetID"
                                       value="<?php echo $row['id_placebet']; ?>"/>
                                <input type="submit" name="challengeBet" value="Daag uit!"/><br>
                            </form>
                        <?php
                        }
                        ?>
                        <!--End create a button to challenge a other member when member successfully logged in-->
                    </li>
                <?php
                }
                //End show all the bets that don't match a challengebet. Last bet at the top of the list

                // Check click to find for username, if not show pagination
                if (!isset($_POST['clickSearchUsername'])) {

                    // Show pagination controls
                    echo "<div id='pagination_controls'>$paginationCtrls</div>";

                }

                // Show 'Terug naar alle weddenschappen' option when click 'clickSearchUsername' submit button
                if (isset($_POST['clickSearchUsername'])) {
                    $link = 'index.php';
                    echo "<a href='$link'>Terug naar alle weddenschappen</a>";
                }
                ?>
                </ul>
                </section>

                </div>
                <div class="4u 12u(mobile)">

                    <section>
                        <h2>Handige links</h2>

                        <div>
                            <div class="row">
                                <div class="6u 12u(mobile)">
                                    <ul class="link-list">
                                        <li><a href="#">Sed neque nisi consequat</a></li>
                                        <li><a href="#">Dapibus sed mattis blandit</a></li>
                                        <li><a href="#">Quis accumsan lorem</a></li>
                                        <li><a href="#">Suspendisse varius ipsum</a></li>
                                        <li><a href="#">Eget et amet consequat</a></li>
                                    </ul>
                                </div>
                                <div class="6u 12u(mobile)">
                                    <ul class="link-list">
                                        <li><a href="#">Quis accumsan lorem</a></li>
                                        <li><a href="#">Sed neque nisi consequat</a></li>
                                        <li><a href="#">Eget et amet consequat</a></li>
                                        <li><a href="#">Dapibus sed mattis blandit</a></li>
                                        <li><a href="#">Vitae magna sed dolore</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
                        <h2>Neem contact op</h2>

                        <p>Neem contact op als je vragen, opmerkingen, klachten of tips hebt.</p>
                        <footer class="controls">
                            <a href="contact.php" class="button">Contact opnemen</a>
                        </footer>
                    </section>

                </div>
            </div>
            <div class="row">
                <div class="12u">

                    <div id="copyright">
                        &copy; Wedplek
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

</body>
</html>