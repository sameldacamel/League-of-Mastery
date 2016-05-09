<html ng-app="championLeaderboard">
    <head>
        <link rel="stylesheet" href="assets/css/bootswatch.css">
        <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
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
        <div class="container-fluid" ng-controller="leaderboard" id="leaderboardTable">
            <div class="row">
                <div class="col-md-8 col-md-offset-2" style="text-align: center;">
                    <img class="img-circle" src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/<?php echo $_GET['champion']; ?>.png" style="margin-bottom: 21px;">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table table-striped" style="font-size: 24px; table-layout: fixed; text-align: center;" ng-hide="rankings.length == 0">
                                <tr>
                                    <th style="text-align: center;">Rank</th>
                                    <th style="text-align: center;">Level</th>
                                    <th style="text-align: center;">Summoner</th>
                                    <th style="text-align: center;">ELO</th>
                                </tr>
                                <tr ng-repeat="rank in rankings track by $index">
                                    <td style="vertical-align: middle;">{{$index + 1}}</td>
                                    <td>
                                        <img ng-if="(rank.mmr >= 1000 || rank.mmr <= 1000) && rank.mmr < 1300" src="assets/images/cmi1.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 1300 && rank.mmr < 1500" src="assets/images/cmi2.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 1500 && rank.mmr < 1750" src="assets/images/cmi3.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 1750 && rank.mmr < 1900" src="assets/images/cmi4.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 1900 && rank.mmr < 2000" src="assets/images/cmi5.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 2000 && rank.mmr < 2500" src="assets/images/cmi6.png" height="80" width="auto">
                                        <img ng-if="rank.mmr >= 2500" src="assets/images/cmi7.png" height="80" width="auto">
                                    </td>
                                    <td style="vertical-align: middle;">{{rank.summonerName}}</td>
                                    <td style="vertical-align: middle;">{{rank.mmr}}</td>
                                </tr>
                            </table>
                            <h1 ng-show="rankings.length == 0">No one is level 5+ with this champion yet, be the first one!</h1>
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
            function getChampion() {
                return "<?php echo $_GET['champion']; ?>";
            }
        </script>
        <script src="assets/js/queue.js"></script>
        <script src="assets/js/leaderboardHandler.js"></script>
    </body>
</html>
