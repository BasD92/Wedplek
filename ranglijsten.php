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
    <title>ranglijsten</title>
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
                        <a href="mijnWedplek.php">Mijn Wedplek</a>
                        <a href="ranglijsten.php" class="current-page-item">Ranglijsten</a>
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
                    <h2>Algemene ranglijst</h2>

                    <!--Show list of scores of all members-->
                    <?php

                    // Pagination
                    // Count rows
                    $countRows = mysqli_query($con, "SELECT COUNT(id_member) FROM member");

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
                    $textline1 = "Aantal leden: <b>$rowsResult</b>";
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

                    // Counter variables
                    $setPagenum = $pagenum - 1;
                    $counter = $setPagenum * $page_rows;

                    $result = mysqli_query($con, "SELECT username, score FROM member ORDER BY score DESC $limit");
                    ?>

                    <!--Show total members and current page number-->
                    <h1><?php echo $textline1; ?></h1>

                    <h1><?php echo $textline2; ?></h1><br>

                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $counter++;
                        ?>

                        <ol>
                            <li><span class="numberRanking"><?php echo $counter; ?></span> <span
                                    class="usernameRanking"><?php echo $row['username']; ?></span>
                                <span class="scoreRanking"><?php echo $row['score']; ?><span> punten</li>
                        </ol>

                    <?php
                    }
                    ?>
                    <!--End show list of scores of all members-->

                    <br>
                    <!--Show pagination controls-->
                    <div id='pagination_controls'><?php echo $paginationCtrls; ?></div>

                    <br>
                    <br>
                    <a href="memberActions/rankProvince.php">Ranglijst per provincie</a>
                </section>

            </div>
            <div class="4u 12u(mobile)">

<!--                <section>-->
<!--                    <!--<h2>Handige links</h2>-->
<!---->
<!--                    <div>-->
<!--                        <div class="row">-->
<!--                            <div class="6u 12u(mobile)">-->
<!--                                <ul class="link-list">-->
<!--                                    <li><a href="#">Sed neque nisi consequat</a></li>-->
<!--                                    <li><a href="#">Dapibus sed mattis blandit</a></li>-->
<!--                                    <li><a href="#">Quis accumsan lorem</a></li>-->
<!--                                    <li><a href="#">Suspendisse varius ipsum</a></li>-->
<!--                                    <li><a href="#">Eget et amet consequat</a></li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                            <div class="6u 12u(mobile)">-->
<!--                                <ul class="link-list">-->
<!--                                    <li><a href="#">Quis accumsan lorem</a></li>-->
<!--                                    <li><a href="#">Sed neque nisi consequat</a></li>-->
<!--                                    <li><a href="#">Eget et amet consequat</a></li>-->
<!--                                    <li><a href="#">Dapibus sed mattis blandit</a></li>-->
<!--                                    <li><a href="#">Vitae magna sed dolore</a></li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </section>-->

                <section>
                    <!--<h2>Handige links</h2>-->

                    <div>
                        <div class="row">
                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <!-- ad 2 -->
                            <ins class="adsbygoogle"
                                 style="display:block"
                                 data-ad-client="ca-pub-6644658208740536"
                                 data-ad-slot="8792750604"
                                 data-ad-format="auto"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
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
                    <a href="https://www.facebook.com/wedplek" target="_blank"><img class="socialmediabutton"
                                                                                    src='images/facebook.png'
                                                                                    alt="Facebook"/></a>

                    <a href="https://twitter.com/wedplek" target="_blank"><img class="socialmediabutton"
                                                                               src='images/twitter.png'
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