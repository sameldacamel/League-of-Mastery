var app = angular.module("game", []);

app.factory("gameSetup", ["$http", function($http) {
    return {
        receiveGameInfo: function(summonerName) {
            return $http.get("assets/php/gameSetup.php?summonerName=" + summonerName).then(function(response) {
                return response;
            });
        },
        setSummonerReady: function(numberId) {
            return $http.get("assets/php/updateReady.php?numberId=" + numberId);
        }
    }
}]);

app.controller("gameBody", function($scope, gameSetup) {
    $scope.gameInfo = gameSetup.receiveGameInfo($("#summonerName").html()).then(function(gameData) {
        
        if (gameData.data[0]['summoner1Ready'] != 0) {
            $("#readySummoner1").remove();
            $("#summoner1PanelTitle").html("Ready");
            $("#summoner1Panel").removeClass("panel-danger");
            $("#summoner1Panel").addClass("panel-success");
        }
        
        if (gameData.data[0]['summoner2Ready'] != 0) {
            $("#readySummoner2").remove();
            $("#summoner2PanelTitle").html("Ready");
            $("#summoner2Panel").removeClass("panel-danger");
            $("#summoner2Panel").addClass("panel-success");
        }
        
        $scope.summoner1Name = gameData.data[1]['name'];
        $scope.summoner2Name = gameData.data[2]['name'];
        
        if ($("#summonerName").html() == $scope.summoner1Name) {
            $("#readySummoner1").removeAttr("disabled");
        }else {
            $("#readySummoner2").removeAttr("disabled");
        }
        
        $scope.champion = gameData.data[0]['champion'];
        
        $scope.tournamentCode = gameData.data[0]['tournamentCode'];
        
        $scope.summoner1ProfileIcon = gameData.data[1]['profileIconId'];
        $scope.summoner2ProfileIcon = gameData.data[2]['profileIconId'];
    });
    
    $scope.summoner1Ready = function() {
        socket.emit("updateReadyMessage", {opponent: $scope.summoner2Name, numberId: 1, region: "NA"});
        
        gameSetup.setSummonerReady(1);
        
        $("#readySummoner1").remove();
        $("#summoner1PanelTitle").html("Ready");
        $("#summoner1Panel").removeClass("panel-danger");
        $("#summoner1Panel").addClass("panel-success");
    }
    
    $scope.summoner2Ready = function() {
        socket.emit("updateReadyMessage", {opponent: $scope.summoner1Name, numberId: 2, region: "NA"});
        
        gameSetup.setSummonerReady(2);
        
        $("#readySummoner2").remove();
        $("#summoner2PanelTitle").html("Ready");
        $("#summoner2Panel").removeClass("panel-danger");
        $("#summoner2Panel").addClass("panel-success");
    }
    
});