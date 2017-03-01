<?php

require_once('../dbConnection/connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_POST['register'])) {

    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $age = $_POST['age'];

    //Save uploaded pics in directory
    $target = "../profileImages/";
    $target = $target . basename($_FILES['profilePicture']['name']);

    $profilePicture = ($_FILES['profilePicture']['name']);

    $province = $_POST['province'];
    $email = trim($_POST['email']);
    $checkEmail = trim($_POST['checkEmail']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $captcha = $_POST['g-recaptcha-response'];
    $status = false;

    // First name check
    if (empty($firstName)) {
        $reminder = 'Het is verplicht om uw voornaam in te vullen.';
        $status = true;
    }

    // Last name check
    if (empty($lastName)) {
        $reminder2 = 'Het is verplicht om uw achternaam in te vullen.';
        $status = true;
    }

    // Age empty check
    if (empty($age)) {
        $reminder3 = 'Het is verplicht om uw leeftijd in te vullen.';
        $status = true;
    }

    // Check age isn't empty
    if (!empty($age)) {
        // Check value is a number
        if (!is_numeric($age)) {
            $checkNumber = 'Bij een leeftijd horen alleen getallen. Probeer het opnieuw.';
            $status = true;
        }

        // Check member is 18 years or older
        if ($age < 18) {
            $checkAge = 'Je moet 18 jaar of ouder zijn om je te kunnen registreren bij Wedplek.';
            $status = true;
        }
    }

    // Check profile picture isn't empty
    if (!empty($profilePicture)) {
        // Array of allowed types profile picture
        $types = array('image/jpeg', 'image/gif', 'image/png');

        $checkResult = mysqli_query($con, "SELECT * FROM member WHERE profielfoto = '$profilePicture'")
        or die(mysqli_error($con));

        // Check photo name exist in database, give error when photo exist
        if (mysqli_num_rows($checkResult) != 0) {
            $profilePictureError = 'Kies een andere foto of verander de naam van je foto.';
            $status = true;
        }

        //Check file type profile picture
        if (!in_array($_FILES['profilePicture']['type'], $types)) {

            // Error message when isn't .jpeg/.jpg, .gif or .png
            $profilePictureError2 = 'Alleen .jpeg/.jpg, .gif of .png bestanden zijn toegestaan.';
            $status = true;
        }

        // Check file size profile picture
        if ($_FILES['profilePicture']['size'] > 2000000) {
            $profilePictureError3 = 'De grootte van de foto mag maximaal 2MB zijn.';
            $status = true;
        }
    }

    // E-mail check
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $reminder4 = 'Het is verplicht om een bestaand e-mailadres in te vullen.';
        $status = true;
    }

    // E-mail check
    if ($checkEmail != $email) {
        $reminder7 = 'De e-mail komt niet overeen. Probeer het nog een keer.';
        $status = true;
    }

    // Check username exist in database
    $query = mysqli_query($con, "SELECT username FROM member WHERE username='$username'");

    if (mysqli_num_rows($query) != 0) {
        $exist = "Deze gebruikersnaam bestaat al.";
        $status = true;
    }

    // Username check
    if (empty($username)) {
        $reminder5 = 'Het is verplicht om een gebruikersnaam in te vullen.';
        $status = true;
    }

    // Password check
    if (empty($password)) {
        $reminder6 = 'Het is verplicht om een wachtwoord in te vullen.';
        $status = true;
    }

    // Check reCAPTCHA
    if (!$captcha) {
        $captchaError = 'Vergeet niet om het reCAPTCHA formulier in te vullen.';
        $status = true;
    }

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lfg9RITAAAAAH5qoOwTThkVMsNdSWIiJ8NDIiew&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);

    if ($response == false) {
        $captchaError2 = 'Je bent een spammer!';
        $status = true;
    }

    //E-mail variables
    $wedplek = 'Wedplek';
    $emailwedplek = 'wedplekcontact@gmail.com';
    $to = $email;
    $subject = 'Bevestiging van e-mail';

    $headers = "MIME-version: 1.0\r\n";
    $headers .= "content-type: text/html;charset=utf-8\r\n";

    $message = 'Als uw deze mail leest in de mailbox betekent dit dat het juiste e-mailadres geregistreerd staat!
    Veel plezier bij Wedplek!';

    if ($status == false) {

        // Upload picture to directory
        move_uploaded_file($_FILES['profilePicture']['tmp_name'], $target);

        //Add user to database
        mysqli_query($con, "INSERT INTO member (username, password, email, voornaam, achternaam, leeftijd, profielfoto, province, score)
VALUES ('$username', '$password', '$email', '$firstName', '$lastName', '$age', '$profilePicture', '$province', 10)") or die(mysqli_error($con));

        $headers .= 'From: ' . $wedplek . '<' . $emailwedplek . '>';

        mail($to, $subject, nl2br($message), $headers);

        ?>
        <script type="text/javascript">
            alert("Uw bent succesvol geregistreerd! Welkom bij Wedplek! Kijk voor de zekerheid in uw mailbox of de " +
            "bevestigingsmail is aangekomen. Mocht dit niet het geval zijn kunt uw het emailadres controleren en " +
            "eventueel veranderen in Mijn Wedplek. Het kan zijn dat de mail de de spambox terecht komt.");
            window.location.href = "../index.php";
        </script>
    <?php
    }
}

mysqli_close($con);

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Registreren</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--[if lte IE 8]>
    <script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="../assets/css/main.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../assets/css/ie9.css"/><![endif]-->
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<div id="page-wrapper">
    <div id="header-wrapper">
        <div class="container">
            <div class="row">
                <div class="12u">

                    <header id="header">
                        <h1><a href="" id="logo">Registratie</a></h1>
                        <nav id="nav">
                            <a href="../index.php">Ga terug</a>
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
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="text" maxlength="20" placeholder="Voornaam" name="firstName"/><br>
                            <?php echo isset($reminder) ? $reminder : ''; ?><br>
                            <input type="text" maxlength="20" placeholder="Achternaam" name="lastName"/><br>
                            <?php echo isset($reminder2) ? $reminder2 : ''; ?><br>
                            <input type="text" placeholder="Leeftijd" name="age" maxlength="2"/><br>
                            <?php echo isset($reminder3) ? $reminder3 : ''; ?>
                            <?php echo isset($checkNumber) ? $checkNumber : ''; ?>
                            <?php echo isset($checkAge) ? $checkAge : ''; ?><br>
                            <span>Profielfoto</span><br>
                            <input type="file" name="profilePicture"><br>
                            <?php echo isset($profilePictureError) ? $profilePictureError : ''; ?>
                            <?php echo isset($profilePictureError2) ? $profilePictureError2 : ''; ?>
                            <?php echo isset($profilePictureError3) ? $profilePictureError3 : ''; ?><br>
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
                            <br>
                            <input type="text" placeholder="E-mail" name="email"/><br>
                            <?php echo isset($reminder4) ? $reminder4 : ''; ?><br>
                            <input type="text" placeholder="E-mail controle" name="checkEmail"/><br>
                            <?php echo isset($reminder7) ? $reminder7 : ''; ?><br>
                            <input type="text" maxlength="15" placeholder="Gebruikersnaam" name="username"/><br>
                            <?php echo isset($reminder5) ? $reminder5 : ''; ?>
                            <?php echo isset($exist) ? $exist : ''; ?><br>
                            <input type="password" placeholder="Wachtwoord" name="password"/><br>
                            <?php echo isset($reminder6) ? $reminder6 : ''; ?><br>

                            <div class="g-recaptcha" data-sitekey="6Lfg9RITAAAAAI7L6AP-wQyFwxqMdP4xLtZ8-fse"></div>
                            <br>
                            <?php echo isset($captchaError) ? $captchaError : ''; ?>
                            <?php echo isset($captchaError2) ? $captchaError2 : ''; ?><br>
                            <input type="submit" name="register" value="Registreer"/><br>
                            <?php echo isset($succeed) ? $succeed : ''; ?><br>
                        </form>
                        <br>
                        <br>
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

