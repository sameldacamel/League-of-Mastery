<?php

    class LoM {
        private $api_key = "";
        private $region = "na";
        private $platform = "NA1";
    
        public function getSummonerDataByName($summonerName, $dataFolder) {
            $summonerName = preg_replace('/\s+/', '', strtolower($summonerName));
            $url = "https://".$this->region.".api.pvp.net/api/lol/".$this->region."/v1.4/summoner/by-name/".$summonerName."?api_key=".$this->api_key; 
            $data = $this->downloadJson($url, true, "summonerData", $summonerName, $dataFolder);
            if (is_array(json_decode($data, true))) {
                return json_decode($data, true);
            }else {
                return $data;
            }
        }

        public function checkRunePageForVerifyCode($summonerId, $verifyCode) {
            $url = "https://".$this->region.".api.pvp.net/api/lol/".$this->region."/v1.4/summoner/".$summonerId."/runes?api_key=".$this->api_key; 
            $data = $this->downloadJson($url, false, null, null, null);
            if (is_array(json_decode($data, true))) {
                $data = json_decode($data, true);
                $i = 0;
                foreach($data[$summonerId]["pages"] as $runePage) {
                    if ($runePage["name"] == $verifyCode) {
                        return true;
                    }
                    if ($i == count($data[$summonerId]["pages"])) {
                        return false;
                    }
                    $i++;
                }
            }else {
                return false;
            }
        }
        
        public function getSummonerChampionsMastered($summonerId, $dataFolder) {
            $url = "https://".$this->region.".api.pvp.net/championmastery/location/".$this->platform."/player/".$summonerId."/champions?api_key=".$this->api_key;
            $data = $this->downloadJson($url, true, "championMastery", $summonerId, $dataFolder);
            if (is_array(json_decode($data, true))) {
                $championsMastered = array();
                foreach(json_decode($data, true) as $champion) {
                    if ($champion['championLevel'] == 5) {
                        array_push($championsMastered, $champion);
                    }
                }
                return json_encode($championsMastered);
            }else {
                return $data;
            }
        }
        
        public function getListOfChampions() {
            $url = "https://global.api.pvp.net/api/lol/static-data/".$this->region."/v1.2/champion?api_key=".$this->api_key;
            $data = $this->downloadJson($url, false, null, null, null);
            if (is_array(json_decode($data, true))) {
                $returnData = json_decode($data, true);
                return $returnData['data'];
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
