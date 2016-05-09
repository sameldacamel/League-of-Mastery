<?php

    class LoT {
        
        private $api_key = "";
        private $region = "na";
        private $platform = "NA1";
        
        private $providerId = "";
        private $tournamentId = "";
        
        public function generateTournamentCode($summonerId1, $summonerId2) {
            $url = "https://global.api.pvp.net/tournament/public/v1/code?tournamentId=".$this->tournamentId."&count=1";
            $postJson = json_encode(array("teamSize" => 1, "allowedSummonerIds" => array("participants" => array($summonerId1,$summonerId2)), "spectatorType" => "NONE", "pickType" => "BLIND_PICK", "mapType" => "HOWLING_ABYSS"));
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "X-Riot-Token: ".$this->api_key));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postJson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = json_decode(curl_exec($ch));
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (is_array($data)) {
                curl_close($ch);
                return $data[0];
            }else {
                if ($responseCode == 429) {
                    sleep(2);
                    $data = curl_exec($ch);
                    $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if (is_array($data)) {
                        curl_close($ch);
                        return $data[0];
                    }else {
                        return null;
                    }
                }
            }
            
        }
        
        public function gatherTournamentMatchHistory($tournamentCode, $matchId, $dataFolder) {
            $url = "https://".$this->region.".api.pvp.net/api/lol/".$this->region."/v2.2/match/for-tournament/".$matchId."?tournamentCode=".$tournamentCode."&api_key=".$this->api_key; 
            $data = $this->downloadJson($url, true, "matchData", $matchId, $dataFolder);
            if (is_array(json_decode($data, true))) {
                return json_decode($data, true);
            }else {
                return $data;
            }
        }
        
        private function downloadJson($url, $saveFile, $type, $fileName, $dataFolder) {
            if ($saveFile) {
                $fileUrl = $dataFolder."data/".$type."/".$fileName.".json";
                if (file_exists($fileUrl)) {
                    $data = file_get_contents($fileUrl);
                    return $data;
                }else {
                    $data = $this->gatherJson($url);
                    if (is_array(json_decode($data, true))) {
                        file_put_contents($fileUrl, $data);
                        return $data;
                    }else {
                        return $data;
                    }
                }
            }else {
                $data = $this->gatherJson($url);
                return $data;
            }
        }

        private function gatherJson($url) {
            $ch = curl_init($url);
        
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
            $data = curl_exec($ch);
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($responseCode != 200) {
                return $this->displayError($responseCode);
            }else {
                return $data;
            }
        }
        
        private function displayError($code) {
            switch($code) {
                case 403:
                    return "Blacklisted";
                case 404:
                    return "Summoner Doesn't Exist";
                case 429:
                    sleep(2);
                    return "Rate Limit Exceeded";
                 case 503:
                    return "Riot Server Error";
            }
        }
        
    }
?>
