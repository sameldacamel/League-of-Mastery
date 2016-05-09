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
                $("#cmPanel").append("<div class='alert alert-danger'><strong>Error:</strong> " + jsonData + "</div>");
            }else {
                $("#cmloader").remove();
                $("#cmPanel").removeAttr("style");
                angular.element($("#mainPanel")).scope().updateChampions(data);
                $("#cmPanel").css("max-height", "500px");
                $("#cmPanel").css("overflow-y", "auto");
            }
        }
    });
    $.ajax({
        url: 'assets/php/downloadProfile.php',
        type: 'get',
        data: {'action' : "downloadMatchHistory"},
        success: function(jsonData, status) {
            if (jsonData == "No Matches") {
                $("#mhloader").remove();
                $("#mhPanel").removeAttr("style");
                $("#mhPanel").append("<div class='alert alert-info' style='text-align: center;'><h3>No Matches Have Been Played</h3></div>");
                return;
            }
            var data = JSON.parse(jsonData);
            if (!$.isArray(data)) {
                $("#mhloader").remove();
                $("#mhPanel").removeAttr("style");
                $("#mhPanel").append("<div class='alert alert-danger'><strong>Error:</strong> " + jsonData + "</div>");
            }else {
                $("#mhloader").remove();
                $("#mhPanel").removeAttr("style");
                angular.element($("#mainPanel")).scope().updateMatchHistory(data);
                $("#mhPanel").css("max-height", "600px");
                $("#mhPanel").css("overflow-y", "scroll");
            }
        }
    });
    $.ajax({
        url: 'assets/php/downloadProfile.php',
        type: 'get',
        data: {'action' : "getLeaderboardRankings"},
        success: function(jsonData, status) {
            var data = JSON.parse(jsonData);
            if (!$.isArray(data)) {
                $("#mhloader").remove();
                $("#mhPanel").removeAttr("style");
                $("#mhPanel").append("<div class='alert alert-danger'><strong>Error:</strong> " + jsonData + "</div>");
            }else {
                $("#rloader").remove();
                $("#rPanel").removeAttr("style");
                angular.element($("#mainPanel")).scope().updateRankings(data);
                $("#rPanel").css("max-height", "800px");
                $("#rPanel").css("overflow-y", "auto");
            }
        }
    });
});

var app = angular.module("profile", []);

app.controller("profile", function($scope, $interval) {
    $scope.getChampionName = function(championId) {
        return getChampionName(championId);
    }
    $scope.getSummonerSpell = function(spellId) {
        return getSummonerSpell(spellId);
    }
    $scope.updateChampions = function(data) {
        $scope.champions = data;
        $scope.$apply();
    }
    $scope.updateMatchHistory = function(data) {
        $scope.matches = data;
        $scope.$apply();
    }
    $scope.updateRankings = function(data) {
        $scope.rankings = data;
        $scope.$apply();
    }
});