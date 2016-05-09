function signupAuth() {
    if ($("#summonerNameField").val().length < 3 || $("#summonerNameField").val().length > 16) {
        $(".panel").removeClass("panel-info");
        $(".panel").addClass("panel-danger");
        $(".panel-title").html("Invalid Summoner Name");
    }else {
        if ($("#password").val() != $("#verifyPass").val()) {
            $(".panel").removeClass("panel-info");
            $(".panel").addClass("panel-danger");
            $(".panel-title").html("Passwords do not match");
        }else {
            $.ajax({
                url: 'assets/php/signupAuth.php',
                type: 'post',
                data: {'summonerName' : $("#summonerNameField").val(), 'username': $("#username").val(), 'password': $("#password").val(), 'verifyPass' : $("#verifyPass").val()},
                success: function(jsonData, status) {
                    if (jsonData != "verifyUser") {
                        $(".panel").removeClass("panel-info");
                        $(".panel").addClass("panel-danger");
                        $(".panel-title").html(jsonData);
                    }else {
                        window.location.replace("verify");
                    }
                }
            });
        }
    }
}