<?php

    $summonerName = $_GET['summonerName'];

    include("../../classes/database.php");
    include("../../classes/LeagueOfMastery.php");

    $db = new database();

    $data = $db->getGameInfo($summonerName);

    if (is_array($data)) {
        $api = new LoM();

        $summonerName1 = preg_replace('/\s+/', '', strtolower($data['summonerName1']));
        $summonerName2 = preg_replace('/\s+/', '', strtolower($data['summonerName2']));
        
        $summoner1Data = $api->getSummonerDataByName($summonerName1, "../../");
        $summoner2Data = $api->getSummonerDataByName($summonerName2, "../../");
        
        $returnData = array($data, $summoner1Data[$summonerName1], $summoner2Data[$summonerName2]);
        echo json_encode($returnData);
    }else {
        echo $data;
    }

?>