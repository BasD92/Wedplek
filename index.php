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

// Logout when member hasn't an activity for 20 minutes
// 20 minutes in seconds
//$inactive = 5;
//
//$_session['timeout'] = time();
//
//$session_life = time() - $_session['timeout'];
//
//if ($session_life > $inactive) {
//    session_destroy();
//    header("Location: memberActions/logout.php");
//}

$rec_limit = 10;

if (isset($_POST['login'])) {
    if (empty($_POST['username']) || $_POST['password']) {
        $error = 'Wilt uw alle velden invullen alstublieft.';
    }
}

// Check input isnt empty when click "login"
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
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

//    $usernameModerator = 'BasD92';
//    $passwordModerator = '6165069';
//
//    //Moderator check
//    $checkModerator = mysqli_query($con, "SELECT * FROM member
//WHERE username = '$usernameModerator'
//AND password = '$passwordModerator'");
//
//    if ($resultModerator = mysqli_query($con, $moderator)) {
//        $_SESSION['Moderator'] == true;
//    }
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Wedplek</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--[if lte IE 8]>
    <script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/extra.css"/>
    <link rel="stylesheet" href="assets/css/main.css"/>
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
                        <a href="index.php" class="current-page-item">Home</a>
                        <a href="mijnWedplek.php">Mijn Wedplek</a>
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
<div id="banner-wrapper">
    <div class="container">

        <div id="banner">
            <h2>Wedplek</h2>
            <span>Dit is de gratis online plek om tegen elkaar te wedden!</span>
        </div>

    </div>
</div>
<div id="main">
<div class="container">
<div class="row main-row">
    <div class="4u 12u(mobile)">

        <section>
            <h2>Welkom bij Wedplek!</h2>

            <p>Dit is de ideale online plek om weddenschappen te plaatsen en aan te gaan met andere fanatieke leden.
                Registreer jezelf en log in om de strijd aan de gaan. Heb jij het meest verstand van voetbal van heel je
                provincie of misschien zelfs van heel Nederland? Registreer jezelf of log in om de uitdaging met andere
                leden aan te gaan! Alles is helemaal gratis bij Wedplek!</p>
            <footer class="controls">
                <a href="overWedplek.php" class="button">Meer informatie</a>
            </footer>
        </section>

    </div>
    <div class="4u 12u(mobile)">

        <section>

            <!--Personal welcome to the member-->
            <?php if (isset($_SESSION['username'])) {
                ?>
                <h2><?php echo "Wat leuk dat je er weer bent " . $_SESSION['username'] . "!" ?></h2>
                <p>Wedplek waardeert het dat je weer inlogt om eventueel een weddenschapje te plaatsen of andere leden/
                    vrienden uit te dagen. Vertel je vrienden, familie of andere voetbalkennissen over Wedplek. Hoe meer
                    leden, hoe gezelliger en spannender het wordt!</p>
            <?php
            } ?>
            <!--End personal welcome to the member-->

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

        </section>

    </div>
    <div class="4u 12u(mobile)">

        <section>
            <!--<h2>Handige links</h2>-->

            <div>
                <div class="row">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- ad 1 -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-6644658208740536"
                         data-ad-slot="8932351408"
                         data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </div>
        </section>

    </div>
</div>
<div class="row main-row">
<div class="6u 12u(mobile)">

<section id="placebet">
<h2>De geplaatste weddenschappen:</h2>
<ul class="small-image-list">

<!--Show add bet options to give the member the possibility to add a new bet when successfully logged in-->
<?php if (isset($_SESSION['username']) && !isset($_POST['clickSearchUsername'])) {
    ?>
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

        <input type="submit" class="myButton2" name="sendBet" value="Plaats je weddenschap"/><br>
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
        <input type="submit" class="myButton2" name="clickSearchUsername" value="Zoek gebruiker"/><br>
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
        $paginationCtrls .= '<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $previous . '#placebet">Vorige</a> &nbsp; &nbsp; ';

        // Render clickable number links that should appear on the left of the target page number
        for ($i = $pagenum - 4; $i < $pagenum; $i++) {
            if ($i > 0) {
                $paginationCtrls .= '<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $i . '#placebet">' . $i . '</a> &nbsp; ';
            }
        }
    }

    // Render the target page number, but without it being a link
    $paginationCtrls .= '' . $pagenum . ' &nbsp; ';

    // Render clickable number links that should appear on the right of the target page number
    for ($i = $pagenum + 1; $i <= $last; $i++) {
        $paginationCtrls .= '<a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $i . '#placebet">' . $i . '</a> &nbsp; ';
        if ($i >= $pagenum + 4) {
            break;
        }
    }

    // This does the same as above, only checking if we are on the last page, and then generating the "Next"
    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $next . '#placebet">Volgende</a> ';
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

    <li id="placebetStyle">
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
            <input type="submit" class="myButton" name="clickUsername" value="<?php echo $row['username']; ?>"/><br>
        </form>
        <!--End username button-->

        <br>
        <span id="match"><?php echo $row['footballMatch']; ?></span>
            <span id="result"><?php echo $row['guessResult']; ?></span>
        <br>
        <br>

        <!--Create a button to challenge an other member when member successfully logged in-->
        <?php if (isset($_SESSION['username'])) {
            ?>
            <form action="betActions/challengeBet.php" method="post">
                <input type="hidden" name="placebetID"
                       value="<?php echo $row['id_placebet']; ?>"/>
                <input type="submit" class="myButton2" name="challengeBet" value="Daag uit!"/><br>
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
<div class="6u 12u(mobile)">

    <article class="blog-post">
        <h2>Eredivisie</h2>
        <a href="#"><img src="images/eredivisie.jpg" alt="" class="top blog-post-image"/></a>

        <h3>Sensatie</h3>

        <p>Op Wedplek is het momenteel alleen mogelijk om op Eredivisewedstrijden te wedden met andere leden.
            In de toekomst zal dit worden uitgebreid naar internationale competities. De Nederlandse Eredivisie is
            een spannende competitie waarbij veel wedstrijden heel moeilijk te voorspellen zijn. Dit maakt wedden op
            Eredivisiewedstrijden juist zo spannend!</p>
    </article>

</div>
<div class="6u 12u(mobile)">

    <article class="blog-post">
        <h2>Wedplek online!</h2>
        <a href=""><img src="images/wedplek.jpg" alt="wedplek online" class="top blog-post-image"/></a>

        <h3>Feest</h3>

        <p>Het is een feest om te mogen vertellen dat Wedplek op 20 december 2015 officieel in de lucht is
        gegaan! We hopen dat alle gebruikers veel plezier gaan beleven op Wedplek! Op deze plek zullen ook andere
        blogposts komen om je volledig op de hoogte te houden van de laatste updates. De wedstrijden van speelronde
        18 in de Eredivisie zijn nu allemaal beschikbaar. Je kan dus al weddenschappen plaatsen en de uitdaging aangaan
        met andere leden, maar het is even wachten tot speelronde 18 echt gaat beginnen na de winterstop.</p>
    </article>

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