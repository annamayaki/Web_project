$(document).ready(function () {

    
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
            $.ajax({
                url    : "/php/login.php",
                type   : "POST",
                data   : user,
                success: function(data, status) {
                    if (data == "user") {
                        window.location.href = "/user/statistics.html";
                    }
                    else if (data == "admin") {
                        window.location.href = "/admin/activity-chart.html";
                    }
                    else {
                        $('#loginFailure').css("display", "block");
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                	//On error do this
                		if (xhr.status == 200) {
                
                			alert(ajaxOptions);
                		}
                		else {
                			alert(xhr.status);
                			alert(thrownError);
                		}
                }
                });
        }
        else {
            $('#wrongInput').css("display", "block");
        }

    });

});
