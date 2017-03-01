<?php

require_once('../dbConnection/connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

session_start();

$counterProvince = 0;

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Ranglijst per provincie</title>
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
                        <h1><a href="" id="logo">Ranglijst per provincie</a></h1>
                        <nav id="nav">
                            <a href="../ranglijsten.php">Ga terug</a>
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
                        <h2>Ranglijst per provincie (top 50)</h2>

                        <form action="" method="post">
                            <select name="province">
                                <option>Drenthe</option>
                                <option>Flevoland</option>
                                <option>Friesland</option>
                                <option>Gelderland</option>
                                <option>Groningen</option>
                                <option>Limburg</option>
                                <option>Noord-Brabant</option>
                                <option>Noord-Holland</option>
                                <option>Overijssel</option>
                                <option>Utrecht</option>
                                <option>Zeeland</option>
                                <option>Zuid-Holland</option>
                                <option>Buitenland</option>
                            </select><br>
                            <input type="submit" name="chooseProvince" value="Kies een provincie"/><br>
                        </form>

                        <br>

                        <!--Show chosen province when click-->
                        <?php
                        if (isset($_POST['chooseProvince'])) {
                            ?>
                            <h1>Top 50 van <?php echo $_POST['province']; ?></h1>
                        <?php
                        }
                        ?>

                        <br>

                        <!--Show list of scores of all members from a choosen province-->
                        <?php
                        if (isset($_POST['chooseProvince'])) {
                            $province = $_POST['province'];

                            $resultProvince = mysqli_query($con, "SELECT * FROM member WHERE province = '$province'
                                                                  ORDER BY score DESC LIMIT 50");
                            while ($rowProvince = mysqli_fetch_array($resultProvince)) {
                                $counterProvince++;
                                ?>

                                <ol>
                                    <li><span class="numberRanking"><?php echo $counterProvince; ?></span> <span
                                            class="usernameRanking"><?php echo $rowProvince['username']; ?></span>
                                        <span class="scoreRanking"><?php echo $rowProvince['score']; ?><span> punten</li>
                                </ol>

                            <?php
                            }
                        }
                        ?>
                        <!--End show list of scores of all members from a choosen province-->
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

