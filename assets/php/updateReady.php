<?php
    session_start();

    include("../../classes/database.php");

    $numberId = $_GET['numberId'];

    $db = new database();

    $db->updateSummonerReady($numberId);

?>