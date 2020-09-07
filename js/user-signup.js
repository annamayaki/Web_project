$(document).ready(function () {

    var countValid = 0;

    $('#password').on('click', function () {
        if (countValid != 4) {
            $('#message').css("display", "inline-block");
            $('#constraints').css("display", "flex");
        }
    });

    $('#password').on('input', function () {
        // Validate lowercase letters
        //   var lowerCaseLetters = /[a-z]/g;
        //   if(myInput.value.match(lowerCaseLetters)) {
        //     letter.classList.remove("invalid");
        //     letter.classList.add("valid");
        //     $('#password').tooltip({title: "Lowercase OK"});
        //     $('#password').tooltip('update');
        //   } else {
        //     letter.classList.remove("valid");
        //     letter.classList.add("invalid");
        // }

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

    // no need to implement this!!
    // $('#email').on('keyup', function () {
    //   if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($("#email").val())){
    //     console.log("true");
    //   }
    //   else {
    //     console.log("false");
    //   }
    // });

    $('#submitButton').on('click', function (event) {
        event.preventDefault();

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

        //   console.log($("#firstname").val());
        //   console.log($("#lastname").val());
        //   console.log($("#username").val());
        //   console.log($("#email").val());
        //   console.log($("#password").val());
        //   console.log(validForm);

        if (validForm) {
            // AJAX
        }
        else {
            // $('#wrongInput').css("display", "block");
        }

    });

});
