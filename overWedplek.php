<?php

session_start();

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>over Wedplek</title>
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
                            <a href="ranglijsten.php">Ranglijsten</a>
                            <a href="overWedplek.php" class="current-page-item">Over Wedplek</a>
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
                <div class="12u">

                    <section>
                        <h2>Over Wedplek</h2>

                        <p>Wedplek is een online plek waar op voetbalwedstrijden uit de Eredivisie gegokt kan
                            worden. Op Wedplek.nl worden voetballiefhebbers samengebracht om de strijd aan te gaan. Heb
                            jij veel verstand van voetbal en durf je wel een weddenschapje aan? Dan is dit de ideale
                            plek voor jouw! Op Wedplek wordt echter niet voor geld gewedt, maar voor punten. Hoe meer
                            punten je behaalt, hoe hoger je komt in de regionale en nationale ranglijst. Wel is er de
                            mogelijkheid om een bericht te sturen naar degene met wie je een weddenschap hebt
                            afgesloten. Dit bericht wordt naar het e-mail van je tegenstander gestuurd. Hier kan
                            bijvoorbeeld afgesproken worden om naast de punten ook voor geld te wedden. Wedplek is
                            echter niet aansprakelijk als onderlinge afspraken niet nagekomen worden. Afspraken via
                            de mail worden dus op eigen risico genomen. Wel kan je bewijs sturen van het niet nakomen
                            van een gewonnen bedrag, zodat Wedplek de dader kan straffen in puntenaantal. Mocht een
                            persoon vaker een betaling niet nakomen dan volgt er verbanning van Wedplek.</p>

                        <p>In de toekomst kan er naast Eredivisie wedstrijden ook op internationale wedstrijden gewedt
                            worden. Tijdens het EK zal het zeker mogelijk zijn om de uitdaging met andere leden aan te
                            gaan op Wedplek!</p>

                        <h2>Spelregels</h2>

                        <p>De spelregels zijn vrij eenvoudig. 1 betekent winst voor de thuisploeg, X betekent een
                            gelijkspel en 2 betekent winst voor het uitspelende team. Het is niet mogelijk om hetzelfde
                            resultaat te kiezen als je tegenstander. Als je een account aanmaakt krijg
                            je gelijk 10 punten. Elke gewonnen weddenschap levert 2 punten op. Echter wordt er 1 punt
                            afgetrokken bij elke verloren weddenschap. Per speelronde mag je maximaal 2 weddenschappen
                            plaatsen en reageren op maximaal 2 geplaatste weddenschappen. Het is niet mogelijk om 2
                            weddenschappen te plaatsen van dezelfde wedstrijd. Ook is het niet mogelijk om 2 keer op
                            dezelfde weddenschap en wedstrijd te reageren. 2 uur voor de eerste wedstrijd van de
                            desbetreffende speelronde is het niet meer mogelijk om een weddenschap te plaatsen of een
                            uitdaging aan te gaan. De punten worden verwerkt na de laatste wedstrijd van de
                            desbetreffende speelronde.</p>
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