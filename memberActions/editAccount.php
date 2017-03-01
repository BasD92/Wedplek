<?php

require_once('../dbConnection/connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

// Start session
session_start();

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_POST['edit'])) {

    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $age = $_POST['age'];

    $province = $_POST['province'];
    $email = trim($_POST['email']);
    $checkEmail = trim($_POST['checkEmail']);
    $password = $_POST['password'];
    $status = false;

    // Variable session Member id
    $memberID = $_SESSION['memberID'];

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

    // Password check
    if (empty($password)) {
        $reminder6 = 'Het is verplicht om een wachtwoord in te vullen.';
        $status = true;
    }

    if ($status == false) {
        //Edit member data
        mysqli_query($con, "UPDATE member SET password = '$password', email = '$email', voornaam = '$firstName',
        achternaam = '$lastName', leeftijd = $age, province = '$province'
        WHERE member.id_member = $memberID") or die(mysqli_error($con));

        ?>
        <script type="text/javascript">
            alert("Uw gegevens zijn succesvol aangepast!");
            window.location.href = "../mijnWedplek.php";
        </script>
    <?php
    }
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Account aanpassen</title>
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
                        <h1><a href="" id="logo">Account aanpassen</a></h1>
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
                <div class="8u 12u(mobile)">

                    <?php

                    // Variable session Member id
                    $memberID = $_SESSION['memberID'];

                    //Edit member data
                    $result = mysqli_query($con, "SELECT * FROM member WHERE id_member = $memberID")
                    or die(mysqli_error($con));

                    while ($row = mysqli_fetch_array($result)) {
                        ?>

                        <section class="left-content">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="text" placeholder="Voornaam"
                                       value="<?php echo $row['voornaam']; ?>" name="firstName"/><br>
                                <?php echo isset($reminder) ? $reminder : ''; ?><br>
                                <input type="text" placeholder="Achternaam"
                                       value="<?php echo $row['achternaam']; ?>" name="lastName"/><br>
                                <?php echo isset($reminder2) ? $reminder2 : ''; ?><br>
                                <input type="text" placeholder="Leeftijd"
                                       value="<?php echo $row['leeftijd']; ?>" name="age"/><br>
                                <?php echo isset($reminder3) ? $reminder3 : ''; ?>
                                <?php echo isset($checkNumber) ? $checkNumber : ''; ?>
                                <?php echo isset($checkAge) ? $checkAge : ''; ?><br>
                                <select name="province">
                                    <option selected="selected"><?php echo $row['province']; ?></option>
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
                                <input type="text" placeholder="E-mail"
                                       value="<?php echo $row['email']; ?>" name="email"/><br>
                                <?php echo isset($reminder4) ? $reminder4 : ''; ?><br>
                                <input type="text" placeholder="E-mail controle"
                                       value="<?php echo $row['email']; ?>" name="checkEmail"/><br>
                                <?php echo isset($reminder7) ? $reminder7 : ''; ?><br>
                                <input type="password" placeholder="Wachtwoord"
                                       value="<?php echo $row['password']; ?>" name="password"/><br>
                                <?php echo isset($reminder6) ? $reminder6 : ''; ?><br>
                                <input type="submit" name="edit" value="Pas aan"/><br>
                                <?php echo isset($succeed) ? $succeed : ''; ?><br>
                            </form>
                            <br>
                            <br>
                        </section>

                    <?php
                    }
                    mysqli_close($con);
                    ?>

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

