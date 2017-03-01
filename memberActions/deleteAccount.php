<?php

require_once('../dbConnection/connect.php');
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbDatabase);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if (isset($_POST['deleteAccount'])) {

    $id = $_POST['memberID'];
    $resultMatches = mysqli_query($con, "SELECT * FROM challengebet INNER JOIN placebet
                            ON challengebet.id_placebet_fk = placebet.id_placebet
                            INNER JOIN member ON challengebet.member_opponent_id = member.id_member
                            WHERE challengebet.member_opponent_id = $id OR placebet.member_id = $id
                            ORDER BY id_challengebet DESC");

    if (mysqli_num_rows($resultMatches) != 0) {

        echo "<script type='text/javascript'>
            alert('Uw heeft nog een openstaande wedstrijd met een opponent. Na deze wedstrijd kunt uw het account ' +
             'verwijderen.');
            window.location.href = '../index.php';
            </script>";

    } else {
        $result = mysqli_query($con, "SELECT profielfoto FROM member WHERE member.id_member = $id") or die(mysqli_error($con));

        while ($row = mysqli_fetch_array($result)) {
            if (!empty($row['profielfoto'])) {
                unlink('../profileImages/' . $row['profielfoto']);
            }
        }

        mysqli_query($con, "DELETE FROM member WHERE member.id_member = $id") or die(mysqli_error($con));

        session_start();
        session_destroy();
        header('Location:../index.php');
    }
}