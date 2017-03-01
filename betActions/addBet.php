<?php

//Database connection
require_once('../dbConnection/connect.php');
mysql_connect("$dbHost", "$dbUser", "$dbPass") or die(mysql_error());
mysql_select_db("$dbDatabase") or die(mysql_error());

//Split value from matches and get id_footballMatch and footballMatch
$match = explode("_", $_POST['matches']);
$id_footballMatch = $match[0];
$footballMatch = $match[1];

//Get values from input
$guessResult = $_POST['guessResult'];
$memberID = $_POST['memberID'];

//Check member bets not more than one time on the same football match
$checkMatch = mysql_query("SELECT * FROM placebet
WHERE placebet.member_id = $memberID
AND footballMatch = '$footballMatch'");

//Check member has not more than two placebets
$checkAmount = mysql_query("SELECT * FROM placebet
WHERE placebet.member_id = $memberID");

//Check member hasn't more then 2 placebets
if (mysql_num_rows($checkAmount) == 2) {
    ?>
    <script type="text/javascript">
        alert("Uw heeft al het maximale aantal van 2 weddenschappen geplaatst.");
        window.history.go(-1);
    </script>
<?php
}

//Check row exist. If row exist give error message
elseif (mysql_num_rows($checkMatch) != 0) {
    ?>
    <script type="text/javascript">
        alert("Uw heeft al een weddenschap met deze wedstrijd geplaatst. Kies een andere wedstrijd.");
        window.history.go(-1);
    </script>
<?php
}

else {
    //Add values to database
    $add = mysql_query("INSERT INTO placebet (member_id, footballMatch_fk, footballMatch, guessResult)
VALUES ($memberID, '$id_footballMatch', '$footballMatch', '$guessResult')");

//Pop up when add a bet is successfull
    if ($add == true) {

        ?>
        <script type="text/javascript">
            alert("Uw heeft succesvol een weddenschap geplaatst!");
            window.history.go(-1);
        </script>
    <?php
    } else {
        ?>
        <script type="text/javascript">
            alert("Er is wat misgegaan tijdens het plaatsen van de weddenschap. Probeer het opnieuw.");
            window.history.go(-1);
        </script>
    <?php
    }
}
