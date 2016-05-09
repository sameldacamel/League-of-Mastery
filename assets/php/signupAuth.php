<?php

    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header("Location: http://na.leagueofmastery.com");
    }

    session_start();

    include("../../classes/LeagueOfMastery.php");
    include("../../classes/database.php");

    $summonerName = $_POST['summonerName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $verifyPass = $_POST['verifyPass'];

    if (strlen($summonerName) < 3 || strlen($summonerName) > 16) {
        echo "Invalid Summoner Name";
    }else {
        if ($password != $verifyPass) {
            echo "Passwords Do Not Match";
        }else {
            $db = new database();
            $answer = $db->checkUserAvailable($summonerName, $username);
            switch($answer) {
                case "summonerna":
                    echo "Summoner is already in the database";
                    break;
                case "userna":
                    echo "Username is already in the database, try a differnet username";
                    break;
                case "clear":
                    $api = new LoM();
                    $data = $api->getSummonerDataByName($summonerName, "../../");
                    if (is_array($data)) {
                        $_SESSION['verifyUser'] = bin2hex(openssl_random_pseudo_bytes(10));
                        $_SESSION['summonerName'] = preg_replace('/\s+/', '', strtolower($summonerName));
                        $_SESSION['summonerId'] = $data[preg_replace('/\s+/', '', strtolower($summonerName))]['id'];
                        $_SESSION['username'] = $username;
                        $_SESSION['password'] = $password;
                        echo "verifyUser";
                    }else {
                        echo $data;
                    }
                    break;
            }
        }
    }

?>