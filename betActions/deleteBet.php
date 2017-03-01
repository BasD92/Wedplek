<?php

require_once('../dbConnection/connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_POST['deleteBet'])) {

    $id = $_POST['id'];

    $delete = mysqli_query($con, "DELETE FROM placebet WHERE placebet.id_placebet = $id") or die(mysqli_error($con));

    //Pop up when delete is succesfull
    if ($delete == true) {

        echo "<script>alert('Uw heeft de weddenschap succesvol verwijderd.');</script>";
        echo '<script type="text/javascript">location.replace("../mijnWedplek.php");</script>';
    } else {

        echo "<script>alert('Er is wat tijdens het verwijderen van de weddenschap. Probeer het opnieuw.');</script>";
        echo '<script type="text/javascript">location.replace("../mijnWedplek.php");</script>';
    }
}