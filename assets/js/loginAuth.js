function checkUserAuth() {
    $.ajax({
        url: 'assets/php/loginAuth.php',
        type: 'post',
        data: {'username': $("#username").val(), 'password': $("#password").val()},
        success: function(jsonData, status) {
            if (jsonData != "true") {
                $(".panel").removeClass("panel-primary");
                $(".panel").addClass("panel-danger");
                $(".panel-title").html(jsonData);
            }else {
                window.location.replace("profile");
            }
        }
    });
}