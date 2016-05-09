function verifyUser() {
    $.ajax({
        url: 'assets/php/verifyAuth.php',
        success: function(jsonData, status) {
            if (jsonData != "verified") {
                $(".jumbotron").prepend("<div class='alert alert-danger' id='alert'><strong>" + jsonData + "</strong></div>");
                $("#alert").fadeOut(4000);
            }else {
                window.location.replace("profile");
            }
        }
    });
}