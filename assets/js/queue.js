var socket = io.connect("http://leagueofmastery.com");

var socketUpdateString = "updateSummonerQ" + $("#summonerName").html() + "NA";

socket.on(socketUpdateString, function(data) {
    $("#gameAlert").modal({backdrop: "static", keyboard: false});
    updateGameAlert();
});

var timer = 30;

function updateGameAlert() {
    return window.setInterval(function() {
        timer--;
        $("#gameTimer").html("Game Starting in " + timer);
        if (timer == 0) {
            window.location.replace("game");
        }
    }, 1000);
}