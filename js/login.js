$('#toggleVisibility').on('click', function () {

    event.preventDefault();

    $(this).toggleClass("material-off");
    $('#showPass').toggleClass("active");
    $('#hidePass').toggleClass("active");

    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    }
    else {
        x.type = "password";
    }
});

$('#failureModal').modal({
    show: false
});

$('#submitButton').on('click', function (event) {

    event.preventDefault();

    $('#wrongInput').css("display", "none");
    $('#loginFailure').css("display", "none");

    var validForm = true;
    validForm = validForm && $("#email")[0].checkValidity()
        && ($("#email").val().length > 0);
    validForm = validForm && $("#password")[0].checkValidity()
        && ($("#password").val().length > 0);
    
    if (validForm) {
        var user = {
            email: $("#email").val(),
            password: $("#password").val()
        }
        const userXHR = $.ajax({
            url    : "/php/common-login.php",
            type   : "POST",
            data   : user
        });
        userXHR.done(function(data) {
            if (data == "user") {
                window.location.href = "/user/statistics.php";
            }
            else if (data == "admin") {
                window.location.href = "/admin/statistics.php";
            }
            else {
                $('#loginFailure').css("display", "block");
            }
        });
        userXHR.fail(function(xhr, ajaxOptions, thrownError) {
            $('#failureModal').modal('show');
        });
    }
    else {
        $('#wrongInput').css("display", "block");
    }

});
