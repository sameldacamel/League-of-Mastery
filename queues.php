<?php
    session_start();

    include("classes/database.php");

    $db = new database();

    $queues = $db->getQueues();

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
<html ng-app="queues">
    <head>
        <link rel="stylesheet" href="assets/css/bootswatch.css">
        <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/getChampionName.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
        <script src="//cdn.socket.io/socket.io-1.4.5.js"></script>
    </head>
    <body style="background: #434A54;">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
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
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                            if (isset($_SESSION['login'])) {
                                ?>
                                    <li style="height: 45px;"><a href="profile" style="padding-top: 0px; padding-bottom: 0px; height: 45px; background: inherit;">
                                        <img class="img-circle" src="http://ddragon.leagueoflegends.com/cdn/6.8.1/img/profileicon/<?php echo $_SESSION['profileIconId']; ?>.png" height="30" width="30" style="margin-top: 7px;">  
                                        <p class="navbar-text pull-right" style="font-size: 16px;" id="summonerName"><?php echo $_SESSION['displayName']; ?></p>
                                    </a></li>
                                <?php
                            }else {
                                ?>
                                    <a href="login" class="btn btn-primary navbar-btn">Login</a>
                                    <a href="signup" class="btn btn-info navbar-btn">Sign up</a>
                                <?php
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <?php
                        if (isset($_SESSION['login'])) {
                            ?>
                                <div class="panel panel-default" id="qPanelHeading" ng-controller="queue">
                                    <div class="panel-heading">
                                        <h3 class="panel-title" style="text-align: center;" id="qPanelText" ng-init="queueText = inQ ? updatePanelText() : ''">Queue{{queueText}}</h3>
                                    </div>
                                    <div class="panel-body" id="tournamentQueue" style="text-align: center;">
                                        <?php
                                            if ($q != false) {
                                                ?>
                                                    <img ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/{{selectedChampion}}.png" src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/<?php echo $qData['champion']; ?>.png" id="qChampImg" height="100" width="100">
                                                    <select ng-model="selectedChampion" class="form-control" id="qChampSelect" style="margin-left: 20px; width: 100px; display: inline;" disabled>
                                                        <option ng-repeat="champion in champions" ng-value="getChampionName(champion.championId)">{{getChampionName(champion.championId)}}</option>
                                                    </select>
                                                    <button type="button" class="btn btn-danger btn-lg" id="qSubmit" style="margin-left: 20px;" ng-click="inQ ? leaveQueue() : enterQueue(selectedChampion)">Leave Queue</button>
                                                <?php
                                            }else {
                                                ?>
                                                    <img ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/{{selectedChampion}}.png" id="qChampImg" height="100" width="100">
                                                    <select ng-model="selectedChampion" class="form-control" id="qChampSelect" style="margin-left: 20px; width: 100px; display: inline;">
                                                        <option ng-repeat="champion in champions" ng-value="getChampionName(champion.championId)">{{getChampionName(champion.championId)}}</option>
                                                    </select>
                                                    <button type="button" class="btn btn-primary btn-lg" id="qSubmit" style="margin-left: 20px;" ng-click="inQ ? leaveQueue() : enterQueue(selectedChampion)">Enter Queue</button>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;">Total queues: <b><?php echo count($queues); ?></b></h3>
                        </div>
                        <div class="panel-body" ng-controller="queueTable">
                            <table class="table" style="table-layout: fixed;">
                                <tr ng-repeat="queue in queues" style="text-align: center;">
                                    <td style="vertical-align: middle;"><h3>{{queue.summonerName}}</h3><h5 id="qTime">{{time(queue.startTime)}}</h5></td>
                                    <td style="vertical-align: middle;"><img class="img-circle" ng-src="http://ddragon.leagueoflegends.com/cdn/6.8.1/img/champion/{{queue.champion}}.png" id="qChampImg" height="85" width="85"></td>
                                    <td style="vertical-align: middle;"><a class="btn btn-default btn-lg" disabled>Coming Soon!</a></td>
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
        <script>
            function getQueues() {
                return <?php echo json_encode($queues); ?>;
            }
            function checkForQ() {
                return <?php echo $q; ?>;
            }
        </script>
        <script src="assets/js/queue.js"></script>
        <script src="assets/js/queueHandler.js"></script>
        <script>
            app.controller("queueTable", function($scope, $interval) {
                $scope.queues = getQueues();
                $scope.qTime = 0;
                $scope.time = function(start) {
                    var today = new Date();
                    var time = ((today/1000) - start);
                    if (!angular.isNumber($scope.qTime)) {
                        $scope.qTime = time;
                    }
                    var hours = Math.floor(time / 3600);
                    var minutes = Math.floor((time - (hours * 3600)) / 60);
                    var seconds = Math.floor(time - (hours * 3600) - (minutes * 60));
                    if (hours   < 10) {hours = "0" + hours;}
                    if (minutes < 10) {minutes = "0" + minutes;}
                    if (seconds < 10) {seconds = "0" + seconds;}
                    return hours + ":" + minutes + ":" + seconds;
                }
                
                $scope.updateQTimes = function() {
                    $scope.qTime++;
                    $("#qTime").html($scope.time($scope.qTime));
                }
                $scope.interval2 = window.setInterval($scope.updateQTimes(), 1000);
            });
        </script>
    </body>
</html>
