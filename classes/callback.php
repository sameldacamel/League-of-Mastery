<?php

    include("database.php");
    include("LeagueOfTournament.php");

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data['winningTeam'][0] == null && !$data['losingTeam'][0] == null) {
        $participants = array($data['winningTeam'][0]['summonerName'], $data['losingTeam'][0]['summonerName']);
    
        $api = new LoT();
        $db = new database();
        $gameInfo = $db->getGameInfo(preg_replace('/\s+/', '', strtolower($participants[0])));
        $champion = $gameInfo['champion'];
        $matchData = $api->gatherTournamentMatchHistory($data['shortCode'], $data['gameId'], "../");
        
        $db->deleteGame(preg_replace('/\s+/', '', strtolower($participants[0])));
        
        foreach($participants as $participant) {
            if (file_exists("../data/tournamentMatches/".$participant.".json")) {
                $fileData = json_decode(file_get_contents("../data/tournamentMatches/".$participant.".json"), true);
                array_unshift($fileData, array("tournamentCode" => $data['shortCode'], "matchId" => $data['gameId']));
                file_put_contents("../data/tournamentMatches/".$participant.".json", json_encode($fileData));
            }else {
                file_put_contents("../data/tournamentMatches/".$participant.".json", json_encode(array(array("tournamentCode" => $data['shortCode'], "matchId" => $data['gameId']))));
            }
            
            foreach($matchData['participantIdentities'] as $matchParticipant) {
                if ($matchParticipant['player']['summonerName'] == $participant) {
                    $participantId = $matchParticipant['participantId'];
                    foreach($matchData['participants'] as $player) {
                        if ($player['participantId'] == $participantId) {
                            if ($player['stats']['winner'] == true) {
                                $db->updateChampionMmr($champion, true, $participant);
                            }else {
                                $db->updateChampionMmr($champion, false, $participant);
                            }
                        }
                    }
                }
            }
            
        }
    }

?>