<?php
    session_start();
    
    if (!isset($_SESSION['login'])) {
        header("Location: http://na.leagueofmastery.com");
    }

    include("classes/database.php");
    include("classes/LeagueOfTournament.php");

    $db = new database();

    if (!$db->checkForGame()) {
        header("Location: http://na.leagueofmastery.com");
    }

?>
<html ng-app="game">
    <head>
        <link rel="stylesheet" href="assets/css/bootswatch.css">
        <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
        <script src="//cdn.socket.io/socket.io-1.4.5.js"></script>
    </head>
    <body style="background: #434A54;" ng-controller="gameBody">
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
                                        <img class="img-circle" src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/profileicon/<?php echo $_SESSION['profileIconId']; ?>.png" height="30" width="30" style="margin-top: 7px;">  
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
        <div class="container-fluid" style="margin-top: 100px;">
            <div class="row">
                <div class="col-md-3 col-md-offset-2">
                    <div class="panel panel-danger" id="summoner1Panel">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;" id="summoner1PanelTitle">Not Ready</h3>
                        </div>
                        <div class="panel-body" style="text-align: center;">
                            <img class="img-circle" ng-init="summoner1ProfileIcon = 652" ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/profileicon/{{summoner1ProfileIcon}}.png">
                            <h4 id="summoner1Name">{{summoner1Name}}</h4>
                            <button class="btn btn-success btn-lg" id="readySummoner1" ng-click="summoner1Ready()" disabled>Ready</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2" style="text-align: center; color: #F2F1EF; margin-top: 50px;">
                    <img src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/{{champion}}.png" style="height: 100px;">
                    <h1>VS</h1>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-danger" id="summoner2Panel">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;" id="summoner2PanelTitle">Not Ready</h3>
                        </div>
                        <div class="panel-body" style="text-align: center;">
                            <img class="img-circle" ng-init="summoner2ProfileIcon = 653" ng-src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/profileicon/{{summoner2ProfileIcon}}.png">
                            <h4 id="summoner2Name">{{summoner2Name}}</h4>
                            <button class="btn btn-success btn-lg" id="readySummoner2" ng-click="summoner2Ready()" disabled>Ready</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="gameAlert">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="text-align: center;">
                        <h1 class="modal-title" id="alertTitle">Match Starting!</h1>
                    </div>
                    <div class="modal-body" style="text-align: center;">
                        <h2 id="gameTimer">Game Starting in 15</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="matchAlert">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="text-align: center;">
                        <h1 class="modal-title">Match Started!</h1>
                    </div>
                    <div class="modal-body" style="text-align: left; font-size: 24px;">
                        <ol>
                            <li>Open the League of Legends Client</li>
                            <li>Press the Play Button</li>
                            <li>Select Custom</li>
                            <li>Select Tournament Code</li>
                            <li>Enter this tournament code: <code>{{tournamentCode}}</code></li>
                            <li>Wait for opponent and start!</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/js/tournament.js"></script>
        <script src="assets/js/gameSetup.js"></script>
    </body>
</html>