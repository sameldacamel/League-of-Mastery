<html>
    <head>
        <link rel="stylesheet" href="assets/css/bootswatch.css">
        <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="//cdn.socket.io/socket.io-1.4.5.js"></script>
        <style>
            @-webkit-keyframes scaleChampion {
                to {
                    transform: scale(1.2);
                }
            }
            img:hover {
                -webkit-animation-name: scaleChampion;
                -webkit-animation-duration: 0.5s;
                -webkit-animation-fill-mode: forwards;
            }
        </style>
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
                <div class="col-md-8 col-md-offset-2">
                    <div class="well well-sm">
                        <div class="page-header" style="text-align: center;">
                            <h3>Select a Champion's Leaderboard</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <table id="champions" style="width: 100%; table-layout: fixed;">
                    </table>
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
        <script src="assets/js/leaderboardUI.js"></script>
    </body>
</html>
