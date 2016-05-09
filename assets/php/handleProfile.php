<?php

    include("../../classes/LeagueOfTournament.php");
    include("../../classes/database.php");
    include("../../classes/LeagueOfMastery.php");

    $action = $_GET['action'];
    $db = new database();
    switch($action) {
        case "enterQueue":
            $champion = $_GET['champion'];
            $response = $db->enterSummonerInQueue($champion);
            echo $response;
            break;
        case "leaveQueue":
            $response = $db->leaveQueue();
            echo $response;
            break;
        case "deleteGame":
            $response = $db->deleteGame();
            echo $response;
            break;
    }
?>