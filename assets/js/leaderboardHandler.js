$(function() {
    $.ajax({
        url: 'assets/php/leaderboardHandler.php',
        type: 'get',
        data: {'action' : "getChampionLeaderboard", "champion" : getChampion()},
        success: function(jsonData, status) {
            var data = JSON.parse(jsonData);
            angular.element($("#leaderboardTable")).scope().updateLeaderboard(data);
        }
    });
});

var app = angular.module("championLeaderboard", []);

app.controller("leaderboard", function($scope) {
    $scope.updateLeaderboard = function(data) {
        $scope.rankings = data;
        $scope.$apply();
    }
});