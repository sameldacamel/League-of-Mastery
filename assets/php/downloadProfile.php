<?php
    
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header("Location: http://na.leagueofmastery.com");
    }

    session_start();

    include("../../classes/database.php");
    include("../../classes/LeagueOfMastery.php");
    include("../../classes/LeagueOfTournament.php");
    
    $action = $_GET['action'];

    $api = new LoM();
    $db = new database();

    switch($action) {
        case "downloadChampions":
            $data = $api->getSummonerChampionsMastered($_SESSION['login']['summonerId'], "../../");
            $db->enterSummonerInLeaderboard($_SESSION['login']['summonerId'], $_SESSION['displayName'], json_decode($data, true));
            echo $data;
            break;
        case "downloadMatchHistory":
            if (file_exists("../../data/tournamentMatches/".$_SESSION['displayName'].".json")) {
                $returnData = array();
                $api = new LoT();
                $matches = json_decode(file_get_contents("../../data/tournamentMatches/".$_SESSION['displayName'].".json"), true);
                $matches = array_splice($matches, 0, 5);
                for ($i = 0; $i != count($matches); $i++) {
                    $data = $api->gatherTournamentMatchHistory($matches[$i]['tournamentCode'], $matches[$i]['matchId'], "../../");
                    if (!is_string($data)) {
                        foreach($data['participantIdentities'] as $pi) {
                            if ($pi['player']['summonerName'] == $_SESSION['displayName']) {
                                foreach($data['participants'] as $participant) {
                                    if ($participant['participantId'] == $pi['participantId']) {
                                        array_push($returnData, $participant);
                                    }
                                }
                            }
                        }
                    }
                }
                echo json_encode($returnData);
            }else {
                echo "No Matches";
            }
            break;
        case "getLeaderboardRankings":
            $rData = array();
            $masteredChampions = json_decode($api->getSummonerChampionsMastered($_SESSION['login']['summonerId'], "../../"), true);
            foreach ($masteredChampions as $masteredChampion) {
                $data = $db->getSummonerRankings($masteredChampion['championId']);
                array_push($rData, $data);
            }
            echo json_encode($rData);
            break;
    }

?>