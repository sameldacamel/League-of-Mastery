<?php
    
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header("Location: ../../index");
    }

    include("../../classes/database.php");

    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new database();

    if ($db->checkLoginCreds($username, $password)) {
        $userData = $db->getUserData($username);
        $_SESSION['login'] = array("summonerName" => $userData['summonerName'], "summonerId" => $userData['summonerId']);
        echo "true";
    }else {
        echo "Username and/or Pasword Incorrect";
    }

?>