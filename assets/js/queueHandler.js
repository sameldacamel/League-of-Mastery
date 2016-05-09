$(function() {
   $.ajax({
        url: 'assets/php/downloadProfile.php',
        type: 'get',
        data: {'action' : "downloadChampions"},
        success: function(jsonData, status) {
            var data = JSON.parse(jsonData);
            if (!$.isArray(data)) {
                $("#cmloader").remove();
                $("#cmPanel").removeAttr("style");
                $("#cmPanel").append("<div class='alert alert-warning'><strong>Error:</strong> " + jsonData + "</div>");
            }else {
                $("#cmloader").remove();
                $("#cmPanel").removeAttr("style");
                angular.element($("#qPanelHeading")).scope().updatePage(data);
            }
        }
    }); 
});

var inQ = checkForQ();

var app = angular.module("queues", []);

app.factory("tournamentClass", ["$http", function($http) {
    return {
        enterQueue: function(champion) {
            return $http.get("assets/php/handleProfile.php?action=enterQueue&champion=" + champion).then(function(response) {
                return response;
            });
        },
        leaveQueue: function() {
            return $http.get("assets/php/handleProfile.php?action=leaveQueue").then(function(response) {
                return response;
            })
        }
    }
}]);

app.controller("queue", function($scope, tournamentClass, $interval) {
    $scope.inQ = inQ;
    $scope.getChampionName = function (championId) {
        return getChampionName(championId);
    }
    $scope.updatePage = function(data) {
        $scope.champions = data;
        $scope.$apply();
    }
    
    $scope.enterQueue = function(champion) {
        if ($scope.selectedChampion != null) {
            tournamentClass.enterQueue(champion).then(function(data) {
                if (angular.isObject(data.data)) {
                    socket.emit("updateSummonerQ", {sender: $("#ssummonerName").html(), receiver: data.data.summonerName, region: "NA"});
                    $("#gameAlert").modal({backdrop: "static", keyboard: false});
                    updateGameAlert();
                }else {
                    if (data.data == "") {
                        $scope.inQ = true;
                        $scope.interval = $interval(function() { $scope.updateQTime() }, 1000);
                        $("#qPanelHeading").removeClass("panel-danger");
                        $("#qPanelHeading").addClass("panel-default");
                        $("#qPanelText").html("Searching for Opponent: " + $scope.queueTime);
                        $("#qSubmit").removeClass("btn-primary");
                        $("#qSubmit").addClass("btn-danger");
                        $("#qSubmit").html("Leave Queue");
                        $("#qChampSelect").attr("disabled", "");
                    }else {
                        $("#qPanelHeading").removeClass("panel-default");
                        $("#qPanelHeading").addClass("panel-danger");
                        $("#qPanelText").html(data.data);
                    }
                }
            });
        }else {
            $("#qPanelHeading").removeClass("panel-default");
            $("#qPanelHeading").addClass("panel-danger");
            $("#qPanelText").html("Must Select a Champion to Enter Queue");
        }
    }
    
    $scope.queueTime = 0;
    
    $scope.updateQTime = function() {
        $scope.queueTime++;
        var m = Math.floor($scope.queueTime % 3600 / 60);
        var s = Math.floor($scope.queueTime % 3600 % 60);
        $("#qPanelText").html("Searching for Opponent: " + m + ":" + (s < 10 ? "0" : "") + s);
    }
    
    $scope.leaveQueue = function() {
        tournamentClass.leaveQueue().then(function(data) {
            if (data.data == true) {
                $scope.inQ = false;
                $scope.queueTime = 0;
                $interval.cancel($scope.interval);
                $("#qSubmit").removeClass("btn-danger");
                $("#qSubmit").addClass("btn-primary");
                $("#qSubmit").html("Enter Queue");
                $("#qPanelText").html("Queue");
                $("#qChampSelect").removeAttr("disabled");
            }else {
                $("#qPanelHeading").removeClass("panel-default");
                $("#qPanelHeading").addClass("panel-danger");
                $("#qPanelText").html("Failed to leave queue, try refreshing the page");
            } 
        });
    }
    
    $scope.updatePanelText = function() {
        $scope.interval = $interval(function() { $scope.updateQTime() }, 1000);
        $("#qPanelText").html("Searching for Opponent: " + $scope.queueTime);
    }
    
});