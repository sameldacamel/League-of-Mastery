<?php
    
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header("Location: ../../index");
    }

    session_start();
    
    include("../../classes/LeagueOfMastery.php");
    include("../../classes/database.php");
    
    $verifyCode = $_SESSION['verifyUser'];
    $summonerId = $_SESSION['summonerId'];

    $api = new LoM();
    if ($api->checkRunePageForVerifyCode($summonerId, $verifyCode)) {
        unset($_SESSION['verifyUser']);
        
        $db = new database();
        if ($db->createNewUser($summonerId, $_SESSION['summonerName'], $_SESSION['username'], $_SESSION['password'])) {
            $_SESSION['login'] = array("summonerName" => $_SESSION['summonerName'], "summonerId" => $_SESSION['summonerId']);
            unset($_SESSION['summonerName']);
            unset($_SESSION['summonerId']);
            echo "verified";
        }
    }else {
        echo "Account not verified";
    }
    
?>