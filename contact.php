<?php

session_start();

$to = 'wedplekcontact@gmail.com'; // Hiernaartoe wordt de e-mail verzonden
$subject = 'Contactformulier van Wedplek.nl'; // Onderwerp van het bericht of vraag

// Header instellen, zodat nl2br() werkt
$headers = "MIME-version: 1.0\r\n";
$headers .= "content-type: text/html;charset=utf-8\r\n";

if (isset($_POST['send'])) // Als er op de knop wordt gedrukt, wordt het verzonden
{
    $firstname = trim($_POST['firstname']); // Overbodige spaties uit het voornaam veld verwijderen
    $lastname = trim($_POST['lastname']); // Overbodige spaties uit het achternaam veld verwijderen
    $email = trim($_POST['email']); // Overbodige spaties uit het email veld verwijderen
    $message = trim($_POST['message']); // Overbodige spaties uit het bericht veld verwijderen
    $false = false; // Deze is aangemaakt om te kijken wat er fout is

    if (empty($firstname)) // Als het voornaam veld niet is ingevuld
    {
        $reminder1 = 'Het is verplicht om uw voornaam in te vullen.';
        $false = true; // Zorgen dat het script zometeen weet dat er wat fout is
    }
    if (empty($lastname)) // Als het achternaam veld niet is ingevuld
    {
        $reminder2 = 'Het is verplicht om uw achternaam in te vullen.';
        $false = true;
    }
    if (empty($email)) // Als het email veld niet is ingevuld
    {
        $reminder3 = 'Het is verplicht om uw e-mail in te vullen';
        $false = true;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) // Als het email adres niet correct is
    {
        $reminder4 = 'Uw heeft een onjuist e-mailadres ingevuld. Vul een bestaand e-mailadres in.';
        $false = true;
    }
    if (empty($message)) // Als het bericht veld niet is ingevuld
    {
        $reminder5 = 'Het is verplicht om een bericht of vraag in te vullen.';
        $false = true;
    }

    if ($false == false) // Als er niks fout is (alles is dus netjes ingevuld)
    {
        $headers .= 'From: ' . $firstname . ' ' . $lastname . '<' . $email . '>'; // Een afzender instellen zodat je kan reageren.

        if (mail($to, $subject, nl2br($message), $headers)) {
            $popup = 'Uw vraag of bericht is succesvol verzonden.';
            $reminder6 = "<script type='text/javascript'>alert('$popup'); window.location.href='contact.php';</script>";

        } else {
            $popup2 = 'Helaas, er is wat fout gegaan tijdens het verzenden van het formulier.';
            $reminder7 = "<script type='text/javascript'>alert('$popup2');</script>";
        }
    }
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Contact</title>
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
                            <a href="overWedplek.php">Over Wedplek</a>
                            <a href="contact.php" class="current-page-item">Contact</a>
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
                <div class="3u 12u(mobile)">

                    <section>
                        <h2>Vragen, opmerkingen, klachten of tips?</h2>

                        <p>Uw kunt uw hiervoor het contactformulier gebruiken of een e-mail sturen naar
                            wedplekcontact@gmail.com. Er wordt zo snel mogelijk gereageerd.</p>
                    </section>

                </div>
                <div class="6u 12u(mobile) important(mobile)">

                    <section class="middle-content">
                        <h2>Contactformulier</h2>

                        <form method="post" action="">

                            <label for="name"></label>
                            <input name="firstname" placeholder="Voornaam (vereist)" id="name"><br>
                            <?php echo isset($reminder1) ? $reminder1 : ''; ?><br>

                            <label for="name"></label>
                            <input name="lastname" placeholder="Achternaam (vereist)" id="name"><br>
                            <?php echo isset($reminder2) ? $reminder2 : ''; ?><br>

                            <label for="email"></label>
                            <input name="email" placeholder="E-mail (vereist)" id="email"><br>
                            <?php echo isset($reminder3) ? $reminder3 : ''; ?>
                            <?php echo isset($reminder4) ? $reminder4 : ''; ?><br>

                            <label for="message"></label>
                            <textarea name="message" placeholder="Bericht (vereist)" id="message" cols="30"
                                      rows="10"></textarea><br>
                            <?php echo isset($reminder5) ? $reminder5 : ''; ?><br>
                            <?php echo isset($reminder6) ? $reminder6 : ''; ?>
                            <?php echo isset($reminder7) ? $reminder7 : ''; ?>

                            <input id="send" type="submit" class="myButton2" name="send" value="Verzenden"><br>

                        </form>
                    </section>

                </div>
                <div class="3u 12u(mobile)">

                    <section>
                        <h2>Maker Wedplek</h2>
                        <ul class="small-image-list">
                            <li>
                                <a href=""><img src="profileImages/no-profile.png" alt="Ik" class="left"/></a>
                                <h4>Bas Dingemans</h4>

                                <p>Een grote voetballiefhebber met passie voor programmeren.</p>
                            </li>
                        </ul>
                    </section>

                    <section>
                        <h2>Contactgegevens</h2>
                        <ul class="link-list">
                            <li><span>E-maildres: wedplekcontact@gmail.com</span></li>
                        </ul>
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