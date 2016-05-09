var http = require("http");
var io = require("socket.io");

var server = http.createServer(function(request, response) {
    response.writeHead(301, {Location: "http://na.leagueofmastery.com"});
    response.end();
});

server.listen(65348);

var listener = io.listen(server);

listener.sockets.on("connection", function(socket) {
    socket.on("updateSummonerQ", function(data) {
        var updateSummonerString = "updateSummonerQ" + data.receiver + data.region;
        listener.emit(updateSummonerString, {opponent: data.sender});
    });
    socket.on("updateReadyMessage", function(data) {
        var updateString = "updateReadyMessage" + data.opponent + data.region;
        listener.emit(updateString, data.numberId);
    });
    socket.on("sendTournamentCode", function(data) {
        var sendString = "sendTournamentCode" + data.opponent + data.region;
        listener.emit(sendString, {});
    });
});