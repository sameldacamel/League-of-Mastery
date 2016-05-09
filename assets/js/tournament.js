var socket = io.connect("http://leagueofmastery.com");

var updateMessageString = "updateReadyMessage" + $("#summonerName").html() + "NA";
var sendString = "sendTournamentCode" + $("#summonerName").html() + "NA";

socket.on(updateMessageString, function(numberId) {
    $("#readySummoner" + numberId).remove();
    $("#summoner" + numberId + "PanelTitle").html("Ready");
    $("#summoner" + numberId + "Panel").removeClass("panel-danger");
    $("#summoner" + numberId + "Panel").addClass("panel-success");
    
    if ($("#summoner1Panel").hasClass("panel-success") && $("#summoner2Panel").hasClass("panel-success")) {
        socket.emit("sendTournamentCode", {opponent: $("#summoner" + numberId + "Name").html(), region: "NA"});
        $("#gameAlert").modal({backdrop: "static", keyboard: false});
        updateGameAlert();
    }
    
});

socket.on(sendString, function(data) {
    $("#gameAlert").modal({backdrop: "static", keyboard: false});
    updateGameAlert();
});

var timer = 15; 

function updateGameAlert() {
    return window.setInterval(function() {
        timer--;
        $("#gameTimer").html("Game Starting in " + timer);
        if (timer == 0) {
            $("#gameTimer").modal("hide");
            $("#matchAlert").modal({backdrop: "static", keyboard: false});
            window.clearInterval(this);
        }
    }, 1000);
}

$("#matchAlert").on("shown.bs.modal", function(e) {
    var countdown = 600;
    var interval = window.setInterval(function() {
        countdown--;
        if (countdown == 0) {
            window.location.replace("profile");
        }
    }, 1000);
});