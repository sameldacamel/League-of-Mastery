<?php
    session_start();
?>
<html>
    <head>
        <link rel="stylesheet" href="assets/css/bootswatch.css">
        <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
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
                    <a class="navbar-brand" href="#">League of Mastery</a>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-1">
                    <div class="jumbotron" style="text-align: center;">
                        <h1>Welcome to League of Mastery</h1>
                        <p>Want to be the best champion of your region?<br>Want to crush some noobs and prove you are a better Lucian?<br>Want to show all of your friends how good you are at a champion?<br>Well here's the place to do so.</p>
                        <p><a class="btn btn-primary btn-lg" href="faq" role="button">Learn more</a></p>
                    </div>
                    <div class="well well-sm" style="text-align: center;">
                        <h3>If you think you are ready then<br><br><a href="signup" class="btn btn-info btn-lg">Sign up!</a></h3>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="panel panel-default" style="text-align: center; border-color: #9A12B3;">
                        <div class="panel-heading" style="background-color: #9B59B6; color: white; border-color: #9A12B3;">
                            <h3 class="panel-title">Discord</h3>
                        </div>
                        <div class="panel-body">
                            <a data-toggle="modal" data-target="#discordServer" class="btn btn-default btn-lg">Join the discussion</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="discordServer">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="text-align: center;">
                        <h1 class="modal-title">Join us on Discord!</h1>
                    </div>
                    <div class="modal-body" style="text-align: center;">
                        <iframe src="https://discordapp.com/widget?id=165891115063574529&theme=dark" width="650" height="500" allowtransparency="true" frameborder="0"></iframe>
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
    </body>
</html>
