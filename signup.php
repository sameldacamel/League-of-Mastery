<?php
    session_start();

    if (isset($_SESSION['login'])) {
        header("Location: profile");
    }
    if (isset($_SESSION['verifyUser'])) {
        header("Location: verify");
    }
?>
<html>
    <head>
        <link rel="stylesheet" href="assets/css/bootswatch.css">
        <script src="assets/js/signupAuth.js"></script>
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
                        <a href="login" class="btn btn-primary navbar-btn">Login</a>
                        <a href="signup" class="btn btn-info navbar-btn">Sign up</a>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Sign Up</h3>
                        </div>
                        <div class="panel-body" style="text-align: center;">
                            <form action="javascript:signupAuth();">
                                <div class="form-group">
                                    <input type="text" id="summonerNameField" class="form-control" placeholder="Summoner Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="username" class="form-control" placeholder="Username" pattern=".{5,16}" required title="Usernames must be between 5-16 characters">
                                </div>
                                <div class="form-group">
                                    <input type="password" id="password" class="form-control" placeholder="Password" pattern=".{5,16}" required title="Passwords must be between 5-16 characters">
                                </div>
                                <div class="form-group">
                                    <input type="password" id="verifyPass" class="form-control" placeholder="Re-Enter Password" required>
                                </div>
                                <button type="submit" class="btn btn-info btn-lg">Sign Up</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
    </body>
</html>