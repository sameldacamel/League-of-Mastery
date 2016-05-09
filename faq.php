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
                    <div class="panel panel-default" style="text-align: left;">
                    	<div class="panel-body">
                            <h1 style="text-align: center;">FAQ</h1>
                            <br>
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                <h4>What is League of Mastery?</h4>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            League of Mastery is a way for people to 1v1 on their favorite champions and be ranked regionally. Think you are the best Zed NA? Test your skills against other competitors!
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                <h4>How does it work?</h4>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel">
                                        <div class="panel-body">
                                            After your account is linked, you are assigned a base ELO at the bottom of the ladder. Every time you win or lose you gain or looe ELO, similar to normal ranked play. Everyone is then put on the ladder for that specific champion.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
                                                <h4>So how does one win a game?</h4>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel">
                                        <div class="panel-body">
                                            Once you and your partner receive a tournament code, the first person to WIN the game gets the games. It's not first blood, just remember!
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseEight">
                                                <h4>What is the level system for the leaderboards?</h4>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseEight" class="panel-collapse collapse" role="tabpanel">
                                        <div class="panel-body">
                                            Level 1 is anywhere from 0-1299 ELO (or even negatives if you just can't win.<br>
                                            Level 2 is 1300-1499 ELO.<br>
                                            Level 3 is 1500-1749 ELO.<br>
                                            Level 4 is 1750-1899 ELO.<br>
                                            Level 5 is 1900-1999 ELO.<br>
                                            Level 6 is 2000-2499 ELO.<br>
                                            Level 7 is 2500 or higher ELO.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseNine">
                                                <h4>Why are some queue times so long?</h4>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseNine" class="panel-collapse collapse" role="tabpanel">
                                        <div class="panel-body">
                                            We are actively working to try and reduce queue times and prioritize people!
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                                <h4>Why can't I play a certain champion?</h4>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            You can only play champions that you have a mastery rank of 5 or higher. If you recently achieved rank 5 on a champion and it is not showing up as playable simply go to your profile and press the refresh profile button.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                                <h4>How can I get started?</h4>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            After linking your account, simply press the Queues button and follow the on screen instructions. You will be given a tournament key that you must enter to join the queue.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                                <h4>What if my opponent doesn't choose the right champion?</h4>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            We will detect if someone selects a champion that is not correct the queue. If this happens it will be counted as a loss for them. You will not gain or loose any ELO.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                                                <h4>Why can't I use League of Mastery in Russia, Korea, or Japan?</h4>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSix" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            The Riot API does not currently support the tournament API in Russia, Korea, or Japan. If in the future these regions become supported we will integrate them. Sadly, if your account is in one of these regions you cannot use Leauge of Mastery.
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    </body>
</html>
