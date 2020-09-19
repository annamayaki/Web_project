$('#failureModal').modal({
    show: false
});

var countValid = 0;

$('#password').on('click', function () {
    if (countValid != 4) {
        $('#message').css("display", "inline-block");
        $('#constraints').css("display", "flex");
    }
});

$('#password').on('input', function () {

    countValid = 0;

    // Validate capital letters
    var upperCaseLetters = /[A-Z]/g;
    if ($('#password').val().match(upperCaseLetters)) {
        $('#capital').removeClass("invalid");
        $('#capital').addClass("valid");
        $('#capital i').text("done");
        countValid++;
    }
    else {
        $('#capital').removeClass("valid");
        $('#capital').addClass("invalid");
        $('#capital i').text("close");
    }

    // Validate numbers
    var numbers = /[0-9]/g;
    if ($('#password').val().match(numbers)) {
        $('#number').removeClass("invalid");
        $('#number').addClass("valid");
        $('#number i').text("done");
        countValid++;
    }
    else {
        $('#number').removeClass("valid");
        $('#number').addClass("invalid");
        $('#number i').text("close");
    }

    // Validate length
    if ($('#password').val().length >= 8) {
        $('#length').removeClass("invalid");
        $('#length').addClass("valid");
        $('#length i').text("done");
        countValid++;
    }
    else {
        $('#length').removeClass("valid");
        $('#length').addClass("invalid");
        $('#length i').text("close");
    }

    //Validate special chars
    var chars = /[!@#$%^&*]/g
    if ($('#password').val().match(chars)) {
        $('#char').removeClass("invalid");
        $('#char').addClass("valid");
        $('#char i').text("done");
        countValid++;
    }
    else {
        $('#char').removeClass("valid");
        $('#char').addClass("invalid");
        $('#char i').text("close");
    }

    if (countValid == 4) {
        $('#constraints').css("display", "none");
        $('#message').css("display", "none");
    }
    else {
        $('#message').css("display", "inline-block");
        $('#constraints').css("display", "flex");
    }

});

$('#submitButton').on('click', function (event) {

    event.preventDefault();

    $('#wrongInput').css("display", "none");
    $('#idTaken').css("display", "none");

    var validForm = true;

    validForm = validForm && $("#firstname")[0].checkValidity()
        && ($("#firstname").val().length > 0);
    validForm = validForm && $("#lastname")[0].checkValidity()
        && ($("#lastname").val().length > 0);
    validForm = validForm && $("#username")[0].checkValidity()
        && ($("#username").val().length > 0);
    validForm = validForm && $("#email")[0].checkValidity()
        && ($("#email").val().length > 0);
    validForm = validForm && $("#password")[0].checkValidity()
        && ($("#password").val().length > 0);
    
    if (validForm) {
        var user = {
            firstname: $("#firstname").val(),
            lastname: $("#lastname").val(),
            username: $("#username").val(),
            email: $("#email").val(),
            password: $("#password").val()
        }
        const userXHR = $.ajax({
            url    : "/php/user-signup.php",
            type   : "POST",
            data   : user
        });
        userXHR.done(function(data, status){
            if (data == "OK redirect"){
                window.location.href = "/user/statistics.php";
            }
            else if (data == "ID taken") {
                $('#idTaken').css("display", "block");
            }
            else {
                $('#failureModal').modal('show');
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

