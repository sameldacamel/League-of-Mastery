<?php
    if (isset($_GET['summonerName']) && isset($_GET['summonerId'])) {
        unlink("../../data/summonerData/".$_GET['summonerName'].".json");
        unlink("../../data/championMastery/".$_GET['summonerId'].".json");
        setcookie("update", true, time() + 3600, "/", "na.leagueofmastery.com");
        header("Location: http://na.leagueofmastery.com/profile");
    }
?>