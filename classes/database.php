<?php
    if (!session_id()) {
        session_start();
    }

    include("getChampionName.php");

    class database {
        public $connection;
        
        public function __construct() {
            $this->connection = new mysqli("host", "username", "password", "database");
        }
        
        public function checkLoginCreds($username, $password) {
            $sql = "select * from users where username='$username'";
            if ($result = $this->connection->query($sql)) {
                if ($result->num_rows == 0) {
                    $this->connection->close();
                    return false;
                }else {
                    $user = $result->fetch_assoc();
                    if (hash_equals($user['password'], crypt($password, $user['password']))) {
                        return true;
                    }else {
                        $this->connection->close();
                        return false;
                    }
                }
            }
        }
        
        public function checkUserAvailable($summonerName, $username) {
            $summonerName = preg_replace('/\s+/', '', strtolower($summonerName));
            $sql = "select * from users where summonerName='$summonerName'";
            if ($result = $this->connection->query($sql)) {
                if ($result->num_rows == 0) {
                    $sql = "select * from users where username='$username'";
                    if ($result = $this->connection->query($sql)) {
                        if ($result->num_rows == 0) {
                            $this->connection->close();
                            return "clear";
                        }else {
                            $this->connection->close();
                            return "userna";
                        }   
                    }
                }else {
                    $this->connection->close();
                    return "summonerna";
                }
            }
        }
        
        public function createNewUser($summonerId, $summonerName, $username, $password) {
            
            $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), "+", ".");
            $salt = sprintf("$2a$%02d$", 10).$salt;
            $password = crypt($password, $salt);
            $sql = "insert into users (summonerId, summonerName, username, password) values ('$summonerId', '$summonerName', '$username', '$password')";
            if ($result = $this->connection->query($sql)) {
                $this->connection->close();
                return true;
            }else {
                $this->connection->close();
                return false;
            }
        }
        
        public function getUserData($username) {
            $sql = "select * from users where username='$username'";
            if ($result = $this->connection->query($sql)) {
                $user = $result->fetch_assoc();
                $this->connection->close();
                return $user;
            }
        }
        
        private function createQueue($champion, $mmr) {
            $summonerId = $_SESSION['login']['summonerId'];
            $date = new DateTime();
            $sql = "insert into queues (summonerId, summonerName, champion, mmr, startTime) values ('$summonerId', '".$_SESSION['displayName']."', '$champion', '$mmr', '".$date->getTimestamp()."')";
            if ($result = $this->connection->query($sql)) {
                $this->connection->close();
                return "entered";
            }else {
                $this->connection->close();
                return "Error entering queue";
            }
        }
        
        public function enterSummonerInQueue($champion) {
            $summonerId = $_SESSION['login']['summonerId'];
            $mmr = $this->getMmrForChampion($champion, $summonerId);
            $sql = "select * from queues where champion='$champion'";
            if ($result = $this->connection->query($sql)) {
                if ($result->num_rows == 0) {
                    $this->createQueue($champion, $mmr);
                }else {
                    while($queue = $result->fetch_assoc()) {
                        if (($mmr - 300) <= $queue['mmr'] && $queue['mmr'] <= ($mmr + 300)) {
                            $sql = "delete from queues where summonerId='".$queue['summonerId']."'";
                            if ($result = $this->connection->query($sql)) {
                                return $this->createGame($summonerId, $queue);
                            }else {
                                $this->connection->close();
                                return "Error joining found queue";
                            }
                        }
                    }
                    return $this->createQueue($champion, $mmr);
                }
            }else {
                $this->connection->close();
                return "Error entering in queue";
            }   
        }
        
        public function createGame($summonerId, $queue) {
            $summonerName = $_SESSION['login']['summonerName'];
            $api = new LoT();
            $tournamentCode = $api->generateTournamentCode($summonerId, $queue['summonerId']);
            $sql = "insert into games (summonerId1, summonerName1, summonerId2, summonerName2, champion, tournamentCode) values ('$summonerId', '$summonerName', '".$queue['summonerId']."', '".preg_replace('/\s+/', '', strtolower($queue['summonerName']))."', '".$queue['champion']."', '$tournamentCode')";
            if ($result = $this->connection->query($sql)) {
                $this->connection->close();
                return json_encode(array("summonerName" => $queue['summonerName'], "champion" => $queue['champion']));
            }else {
                $this->connection->close();
                return "Error creating game";
            }
        }
        
        public function checkForGame($summonerId) {
            $summonerId = $_SESSION['login']['summonerId'];
            $sql = "select * from games where (summonerId1='$summonerId' or summonerId2='$summonerId')";
            if ($result = $this->connection->query($sql)) {
                if ($result->num_rows == 0) {
                    return false;
                }else {
                    return true;
                }
            }else {
                return false;
            }
        }
        
        public function deleteGame($summonerName1) {
            $sql = "delete from games where (summonerName1='$summonerName1' or summonerName2='$summonerName1')";
            if ($result = $this->connection->query($sql)) {
                return true;
            }else {
                return false;
            }
        }
        
        public function checkForQ() {
            $summonerId = $_SESSION['login']['summonerId'];
            $sql = "select * from queues where summonerId='$summonerId'";
            if ($result = $this->connection->query($sql)) {
                if ($result->num_rows == 0) {
                    $this->connection->close();
                    return false;
                }else {
                    $this->connection->close();
                    return $result->fetch_assoc();
                }
            }else {
                $this->connection->close();
                return false;
            }
        }
        
        public function leaveQueue() {
            $summonerId = $_SESSION['login']['summonerId'];
            $sql = "delete from queues where summonerId='$summonerId'";
            if ($result = $this->connection->query($sql)) {
                $this->connection->close();
                return true;
            }else {
                $this->connection->close();
                return false;
            }
        }
         
        public function getQueues() {
            $queues = array();
            $sql = "select * from queues";
            if ($result = $this->connection->query($sql)) {
                while($queue = $result->fetch_assoc()) {
                    array_push($queues, $queue);
                }
            }
            return $queues;
        }
        
        public function getGameInfo($summonerName) {
            $sql = "select * from games where (summonerName1='$summonerName' or summonerName2='$summonerName')";
            if ($result = $this->connection->query($sql)) {
                return $result->fetch_assoc();
            }else {
                return "error";
            }
        }
        
        public function updateSummonerReady($numberId) {
            $summonerId = $_SESSION['login']['summonerId'];
            $sql = "update games set summoner".$numberId."Ready='1' where (summonerId1='$summonerId' or summonerId2='$summonerId')";
            if ($result = $this->connection->query($sql)) {
                $this->connection->close();
            }else {
                $this->connection->close();
            }
        }
        
        public function enterSummonerInLeaderboard($summonerId, $summonerName, $champions) {
            foreach($champions as $champion) {
                $sql = "select * from leaderboard where summonerId='$summonerId' and champion='".getChampionName($champion['championId'])."'";
                if ($result = $this->connection->query($sql)) {
                    if ($result->num_rows == 0) {
                        $sql = "insert into leaderboard (champion, mmr, summonerId, summonerName) values ('".getChampionName($champion['championId'])."', '1000', '$summonerId', '$summonerName')";
                        $this->connection->query($sql);
                    }
                }
            }
        }
        
        public function getMmrForChampion($champion, $summonerName) {
            $sql = "select * from leaderboard where champion='$champion' and summonerName='$summonerName'";
            if ($result = $this->connection->query($sql)) {
                $data = $result->fetch_assoc();
                return $data['mmr'];
            }
        }
        
        public function updateChampionMmr($champion, $winner, $summonerName) {
            $currentMmr = $this->getMmrForChampion($champion, $summonerName);
            $newMmr;
            if ($winner == true) {
                $newMmr = $currentMmr + 48;
            }else {
                $newMmr = $currentMmr - 24;
            }
            $sql = "update leaderboard set mmr='$newMmr' where summonerName='$summonerName' and champion='$champion'";
            $this->connection->query($sql);
        }
        
        public function getLeaderboardRankings($champion) {
            $leaderboardArray = array();
            $sql = "select * from leaderboard where champion='$champion' order by mmr desc";
            if ($result = $this->connection->query($sql)) {
                while($row = $result->fetch_assoc()) {
                    array_push($leaderboardArray, $row);
                }
                return $leaderboardArray;
            }
        }
        
        public function getSummonerRankings($champion) {
            $summonerId = $_SESSION['login']['summonerId'];
            $sql = "select champion, summonerId, mmr, @curRank := @curRank + 1 as rank from leaderboard, (select @curRank := 0) r where champion='".getChampionName($champion)."' order by mmr desc";
            if ($result = $this->connection->query($sql)) {
                while($row = $result->fetch_assoc()) {
                    if ($row['summonerId'] == $summonerId) {
                        $returnData = array("rank" => $row['rank'], "champion" => $row['champion'], "mmr" => $row['mmr']);
                        return $returnData;
                    }
                }
            }else {
                $this->connection->close();
                return false;
            }
        }
        
    }
?>