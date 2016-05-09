$(function() {
    $.ajax({
        url: 'assets/php/leaderboardHandler.php',
        type: 'get',
        data: {'action': 'getChampionList'},
        success: function(jsonData, status) {
            var data = JSON.parse(jsonData);
            var row = document.getElementById("champions").insertRow(-1);
            row.style.textAlign = "center";
            var i = 0;
            for(var key in data) {
                var cell = row.insertCell(-1);
                cell.style.paddingBottom = "30";
                cell.innerHTML = "<a href='?champion=" + key + "'><img src='http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/" + key + ".png' height='75' width='75'></a><br><h5 style='color: white;'>" + data[key]['name'] + "</h5>";           
                if ((i + 1) % 11 == 0) {
                    var row = document.getElementById("champions").insertRow(-1);
                    row.style.textAlign = "center";
                }
                i++;
            }
        }
    });
});