<?php
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header("Location: http://na.leagueofmastery.com");
    }

    include("../../classes/database.php");
    include("../../classes/LeagueOfMastery.php");

    $api = new LoM();
    $db = new database();

    $action = $_GET['action'];

    switch($action) {
        case "getChampionList":
            $data = $api->getListOfChampions();
            ksort($data);
            echo json_encode($data);
            break;
        case "getChampionLeaderboard":
            $champion = $_GET['champion'];
            $data = $db->getLeaderboardRankings($champion);
            echo json_encode($data);
            break;
    }

?>