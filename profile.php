<?php

    session_start();

    if (!isset($_SESSION['login'])) {
        header("Location: index");
    }
    
    include("classes/database.php");
    include("classes/LeagueOfMastery.php");

    $api = new LoM();
    $summonerData = $api->getSummonerDataByName($_SESSION['login']['summonerName'], null);
    $_SESSION['profileIconId'] = $summonerData[$_SESSION['login']['summonerName']]['profileIconId'];
    $_SESSION['displayName'] = $summonerData[$_SESSION['login']['summonerName']]['name'];

    $db = new database();

    if ($db->checkForGame()) {
        header("Location: game");
    }else {
        $qData = $db->checkForQ();
        if ($qData == false) {
            $q = false;
        }else {
            $q = true;
        }
    }

?>
<html ng-app="profile">
    <head>
        <title><?php echo $_SESSION['displayName']; ?> - Profile</title>
        <link rel="stylesheet" href="assets/css/bootswatch.css">
        <link rel="stylesheet" href="assets/css/loader.css">
        <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/getChampionName.js"></script>
        <script src="assets/js/getSummonerSpell.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
        <script src="//cdn.socket.io/socket.io-1.4.5.js"></script>
    </head>
    <body style="background: #434A54;">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="http://na.leagueofmastery.com">League of Mastery</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="leaderboard">Leaderboards</a></li>
                        <li><a href="queues">Queues</a></li>
                        <li><a href="faq">FAQ</a></li>
                        <li><a href="about">About</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Region <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="http://br.leagueofmastery.com">Brazil</a></li>
                                <li><a href="http://eune.leagueofmastery.com">Europe Nordic and East</a></li>
                                <li><a href="http://euw.leagueofmastery.com">Europe West</a></li>
                                <li><a href="http://lan.leagueofmastery.com">Latin America North</a></li>
                                <li><a href="http://las.leagueofmastery.com">Latin America South</a></li>
                                <li><a href="http://na.leagueofmastery.com">North America</a></li>
                                <li><a href="http://oce.leagueofmastery.com">Oceania</a></li>
                                <li><a href="http://tr.leagueofmastery.com">Turkey</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav nav-navbar navbar-right">
                        <li style="height: 45px;"><a href="profile" style="padding-top: 0px; padding-bottom: 0px; height: 45px; background: inherit;">
                            <img class="img-circle" src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/profileicon/<?php echo $_SESSION['profileIconId']; ?>.png" height="30" width="30" style="margin-top: 7px;">  
                            <p class="navbar-text pull-right" style="font-size: 16px;" id="summonerName"><?php echo $_SESSION['displayName']; ?></p>
                        </a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid" id="mainPanel" ng-controller="profile" <?php if ($q != false) { echo "ng-init='".$qData['champion']."'"; } ?>>
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;">Summoner Info</h3>
                        </div>
                        <div class="panel-body" style="padding: 0 0 20px 10px;">
                            <img class="img-circle" src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/profileicon/<?php echo $_SESSION['profileIconId']; ?>.png" height="100" width="100">
                            <table style="display: inline; margin-left: 20px; margin-bottom: 10px;">
                                <tr>
                                    <td><h3><?php echo $_SESSION['displayName']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($_COOKIE['update'])){echo '<a class="btn btn-danger" disabled><span class="glyphicon glyphicon-refresh"></span> Update</a>';}else {echo '<a href="assets/php/update?summonerName='.$_SESSION["login"]["summonerName"].'&summonerId='.$_SESSION["login"]["summonerId"].'" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> Update</a>';} ?></h3></td>
                                </tr>
                                <tr>
                                    <td><h4>Region: <b>North America</b></h4></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;">Champions Mastered</h3>
                        </div>
                        <div class="panel-body" style="height: 250px;" id="cmPanel">
                            <div class="loader" id="cmloader">Loading...</div>
                            <table class="table">
                                <tr ng-repeat="champion in champions">
                                    <td style="text-align: center;">
                                        <img src="assets/images/cm5.png" style="position: relative; height: 105; width: auto;"><img src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/{{getChampionName(champion.championId)}}.png" style="position: relative; height: 53; width: auto; top: -18; left: -56;">
                                        <h5 style="display: inline;">Total Champion Points: <strong>{{champion.championPoints | number: 0}}</strong></h5>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;">Leaderboard Rankings</h3>
                        </div>
                        <div class="panel-body" style="height: 250px;" id="rPanel">
                            <div class="loader" id="rloader">Loading...</div>
                            <table style="width: 100%; table-layout: fixed; text-align: center; font-size: 24px;" class="table table-striped" ng-hide="rankings.length == 0">
                                <tr>
                                    <th style="text-align: center;">Rank</th>
                                    <th style="text-align: center;">Level</th>
                                    <th style="text-align: center;">Champion</th>
                                    <th style="text-align: center;">ELO</th>
                                </tr>
                                <tr ng-repeat="rank in rankings">
                                    <td style="vertical-align: middle;">{{rank.rank}}</td>
                                    <td>
                                        <img ng-if="(rank.mmr >= 1000 || rank.mmr <= 1000) && rank.mmr < 1300" src="assets/images/cmi1.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 1300 && rank.mmr < 1500" src="assets/images/cmi2.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 1500 && rank.mmr < 1750" src="assets/images/cmi3.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 1750 && rank.mmr < 1900" src="assets/images/cmi4.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 1900 && rank.mmr < 2000" src="assets/images/cmi5.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 2000 && rank.mmr < 2500" src="assets/images/cmi6.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 2500" src="assets/images/cmi7.png" height="80" width="auto">
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <img src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/{{rank.champion}}.png" height="50" width="50">
                                    </td>
                                    <td style="vertical-align: middle;">{{rank.mmr}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;">Tournament Match History</h3>
                        </div>
                        <div class="panel-body" style="height: 250px;" id="mhPanel">
                            <div class="loader" id="mhloader">Loading...</div>
                            <table ng-hide="matches.length == 0" style="width: 100%;">
                                <tr ng-repeat="match in matches">
                                    <td>
                                        <div ng-class="match.stats.winner ? 'panel panel-success' : 'panel panel-danger'">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>{{getChampionName(match.championId)}}</strong> - {{match.stats.winner ? 'Victory' : 'Defeat'}}</h3>
                                            </div>
                                            <div class="panel-body">
                                                <table style="border-spacing: 10px 0; border-collapse: separate; width: 100%;">
                                                    <tr>
                                                        <td>
                                                            <img ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/{{getChampionName(match.championId)}}.png" height="50" width="50">
                                                        </td>
                                                        <td>
                                                            <table style="border-spacing: 0 5px; border-collapse: separate;">
                                                                <tr>
                                                                    <td>
                                                                        <img ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/spell/Summoner{{getSummonerSpell(match.spell1Id)}}.png" height="20" width="20">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <img ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/spell/Summoner{{getSummonerSpell(match.spell2Id)}}.png" height="20" width="20">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td>
                                                            <h4 style="margin-top: 10.5;">{{match.stats.kills + "/" + match.stats.deaths + "/" + match.stats.assists}}</h4>
                                                        </td>
                                                        <td>
                                                            <img ng-hide="match.stats.item0 == 0" ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/item/{{match.stats.item0}}.png" height="22" width="22">
                                                            <img ng-hide="match.stats.item1 == 0" ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/item/{{match.stats.item1}}.png" height="22" width="22">
                                                            <img ng-hide="match.stats.item2 == 0" ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/item/{{match.stats.item2}}.png" height="22" width="22">
                                                            <img ng-hide="match.stats.item3 == 0" ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/item/{{match.stats.item3}}.png" height="22" width="22">
                                                            <img ng-hide="match.stats.item4 == 0" ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/item/{{match.stats.item4}}.png" height="22" width="22">
                                                            <img ng-hide="match.stats.item5 == 0" ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/item/{{match.stats.item5}}.png" height="22" width="22">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger" disabled>Report</button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="gameAlert">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="text-align: center;">
                        <h1 class="modal-title">Match Found!</h1>
                    </div>
                    <div class="modal-body" style="text-align: center;">
                        <h2 id="gameTimer">Game Starting in 30</h2>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/js/queue.js"></script>
        <script src="assets/js/profile.js"></script>
    </body>
</html>
