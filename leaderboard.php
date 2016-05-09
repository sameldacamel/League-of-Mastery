<?php
    session_start();
    
    if (isset($_GET['champion'])) {
        include("leaderboardChampion.php");
    }else {
        include("leaderboardInterface.php");
    }

?>