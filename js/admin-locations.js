$('#failureModal').modal({
    show: false
});

$('#deleteDbModal').modal({
    show: false
});

$("aside").load("sidebar.txt", function(responseTxt, statusTxt, xhr){
    if(statusTxt != "success") {
        $('#failureModal').modal('show');
    }
});

var heatOptions = {
    tileOpacity: 1,
    heatOpacity: 2,
    radius: 20,
    blur: 20
};


var attrStr = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors,';
attrStr = attrStr + ' <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
var map = L.map('map').setView([38.230462, 21.753150], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution:  attrStr,
    maxZoom: 18,
    minZoom: 10
}).addTo(map);

$('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
    $('main').toggleClass('active');
    $('#map').toggleClass('active');
});

$("#yearRange").on('click', function () {
    if ($("#yearRange:checked").length) {
        $("#endYear").css("visibility", "visible");
        $(".yearLabels").css("visibility", "visible");
        $("#startYear option[value='all']").prop("disabled", true);
    }
    else {
        $("#endYear").css("visibility", "hidden");
        $(".yearLabels").css("visibility", "hidden");
        $("#startYear option[value='all']").prop("disabled", false);
    }
});

$("#monthRange").on('click', function () {
    if ($("#monthRange:checked").length) {
        $("#endMonth").css("visibility", "visible");
        $(".monthLabels").css("visibility", "visible");
        $("#startMonth option[value='all']").prop("disabled", true);
    }
    else {
        $("#endMonth").css("visibility", "hidden");
        $(".monthLabels").css("visibility", "hidden");
        $("#startMonth option[value='all']").prop("disabled", false);
    }
});

$("#dowRange").on('click', function () {
    if ($("#dowRange:checked").length) {
        $("#endDow").css("visibility", "visible");
        $(".dowLabels").css("visibility", "visible");
        $("#startDow option[value='all']").prop("disabled", true);
    }
    else {
        $("#endDow").css("visibility", "hidden");
        $(".dowLabels").css("visibility", "hidden");
        $("#startDow option[value='all']").prop("disabled", false);
    }
});

$("#hourRange").on('click', function () {
    if ($("#hourRange:checked").length) {
        $("#endHour").css("visibility", "visible");
        $(".hourLabels").css("visibility", "visible");
        $("#startHour option[value='all']").prop("disabled", true);
    }
    else {
        $("#endHour").css("visibility", "hidden");
        $(".hourLabels").css("visibility", "hidden");
        $("#startHour option[value='all']").prop("disabled", false);
    }
});

$("#activAll").on('click', function () {
    if ($("#activAll:checked").length) {
        $(".indivActBoxes").prop("checked", true);
    }
    else {
        $(".indivActBoxes").prop("checked", false);
    }
});


var tmp_obj = new Object();
var dataErrorStr = "Υπήρξε πρόβλημα κατά τη μεταφορά των δεδομένων."
    dataErrorStr = dataErrorStr + " Παρακαλούμε προσπαθήστε ξανά σε μερικά δευτερόλεπτα.";

function validateInputs(actionName) {

    if (actionName == "view") {
        var requestStr = "/php/admin-locations.php?";
    }
    else if (actionName == "export") {
        var requestStr = "/php/admin-export-JSON.php?";
    }
    else {
        return false;
    }

    let validFields = true;
    tmp_obj = new Object();

    if ($("#yearRange:checked").length) {
        tmp_obj.yearRange = "multiple";
        tmp_obj.startYear = parseInt($("#startYear").val());
        tmp_obj.endYear = parseInt($("#endYear").val());
        if (isNaN(tmp_obj.startYear) || isNaN(tmp_obj.endYear)){
            validFields = false;
        }
        requestStr = requestStr + "yearRange=multiple" +
            "&startYear=" + parseInt($("#startYear").val()) +
            "&endYear=" + parseInt($("#endYear").val());
    }
    else {
        let strVal = $("#startYear").val();
        if (strVal == "all") {
            tmp_obj.yearRange = "all";
            requestStr = requestStr + "yearRange=all";
        }
        else {
            tmp_obj.yearRange = "single";
            tmp_obj.startYear = parseInt(strVal);
            if (isNaN(tmp_obj.startYear)){
                validFields = false;
            }
            requestStr = requestStr + "yearRange=single" +
                "&startYear=" + parseInt($("#startYear").val());
        }
    }

    if ($("#monthRange:checked").length) {
        tmp_obj.monthRange = "multiple";
        tmp_obj.startMonth = parseInt($("#startMonth").val());
        tmp_obj.endMonth = parseInt($("#endMonth").val());
        if (isNaN(tmp_obj.startMonth) || isNaN(tmp_obj.endMonth)){
            validFields = false;
        }
        requestStr = requestStr + "&monthRange=multiple" +
            "&startMonth=" + parseInt($("#startMonth").val()) +
            "&endMonth=" + parseInt($("#endMonth").val());
    }
    else {
        let strVal = $("#startMonth").val();
        if (strVal == "all") {
            tmp_obj.monthRange = "all";
            requestStr = requestStr + "&monthRange=all";
        }
        else {
            tmp_obj.monthRange = "single";
            tmp_obj.startMonth = parseInt(strVal);
            if (isNaN(tmp_obj.startMonth)){
                validFields = false;
            }
            requestStr = requestStr + "&monthRange=single" +
                "&startMonth=" + parseInt($("#startMonth").val());
        }
    }

    if ($("#dowRange:checked").length) {
        tmp_obj.dowRange = "multiple";
        tmp_obj.startDow = parseInt($("#startDow").val());
        tmp_obj.endDow = parseInt($("#endDow").val());
        if (isNaN(tmp_obj.startDow) || isNaN(tmp_obj.endDow)){
            validFields = false;
        }
        requestStr = requestStr + "&dowRange=multiple" +
            "&startDow=" + parseInt($("#startDow").val()) +
            "&endDow=" + parseInt($("#endDow").val());
    }
    else {
        let strVal = $("#startDow").val();
        if (strVal == "all") {
            tmp_obj.dowRange = "all";
            requestStr = requestStr + "&dowRange=all";
        }
        else {
            tmp_obj.dowRange = "single";
            tmp_obj.startDow = parseInt(strVal);
            if (isNaN(tmp_obj.startDow)){
                validFields = false;
            }
            requestStr = requestStr + "&dowRange=single" +
                "&startDow=" + parseInt($("#startDow").val());
        }
    }

    if ($("#hourRange:checked").length) {
        tmp_obj.hourRange = "multiple";
        tmp_obj.startHour = parseInt($("#startHour").val());
        tmp_obj.endHour = parseInt($("#endHour").val());
        if (isNaN(tmp_obj.startHour) || isNaN(tmp_obj.endHour)){
            validFields = false;
        }
        requestStr = requestStr + "&hourRange=multiple" +
            "&startHour=" + parseInt($("#startHour").val()) +
            "&endHour=" + parseInt($("#endHour").val());
    }
    else {
        let strVal = $("#startHour").val();
        if (strVal == "all") {
            tmp_obj.hourRange = "all";
            requestStr = requestStr + "&hourRange=all";
        }
        else {
            tmp_obj.hourRange = "single";
            tmp_obj.startHour = parseInt(strVal);
            if (isNaN(tmp_obj.startHour)){
                validFields = false;
            }
            requestStr = requestStr + "&hourRange=single" +
                "&startHour=" + parseInt($("#startHour").val());
        }
    }

    if ($("#activAll:checked").length) {
        tmp_obj.activities = "all";
        requestStr = requestStr + "&activities=all"
    }
    else {
        let count = 0;
        let actStr = "&actType=";
        var types = [];
        if ($("#vehicle:checked").length) {
            types.push("IN_VEHICLE");
            actStr = actStr + "IN_VEHICLE";
            count++;
        }
        if ($("#bicycle:checked").length) {
            types.push("ON_BICYCLE");
            if (count) {
                actStr = actStr + ",ON_BICYCLE";
            }
            else {
                actStr = actStr + "ON_BICYCLE";
            }
            count++;
        }
        if ($("#foot:checked").length) {
            types.push("ON_FOOT");
            requestStr = requestStr + "&actType=ON_FOOT";
            if (count) {
                actStr = actStr + ",ON_FOOT";
            }
            else {
                actStr = actStr + "ON_FOOT";
            }
            count++;
        }
        if ($("#running:checked").length) {
            types.push("RUNNING");
            requestStr = requestStr + "&actType=RUNNING";
            if (count) {
                actStr = actStr + ",RUNNING";
            }
            else {
                actStr = actStr + "RUNNING";
            }
            count++;
        }
        if ($("#still:checked").length) {
            types.push("STILL");
            requestStr = requestStr + "&actType=STILL";
            if (count) {
                actStr = actStr + ",STILL";
            }
            else {
                actStr = actStr + "STILL";
            }
            count++;
        }
        if ($("#tilting:checked").length) {
            types.push("TILTING");
            requestStr = requestStr + "&actType=TILTING";
            if (count) {
                actStr = actStr + ",TILTING";
            }
            else {
                actStr = actStr + "TILTING";
            }
            count++;
        }
        if ($("#walking:checked").length) {
            types.push("WALKING");
            requestStr = requestStr + "&actType=WALKING";
            if (count) {
                actStr = actStr + ",WALKING";
            }
            else {
                actStr = actStr + "WALKING";
            }
            count++;
        }
        if (count > 0) {
            tmp_obj.activities = "multiple";
            tmp_obj.types = types;
            requestStr = requestStr + "&activities=multiple" + actStr;
        }
        else {
            validFields = false;
        }
    }

    if (validFields) {
        return requestStr;
    }
    else {
        return false;
    }
}

$('#submit').on('click', function (event) {
    event.preventDefault();
    
    let validResult = validateInputs("view");
    if (validResult) {

        $('#invalid-form').css("display", "none");
        $('main').animate({
            scrollTop: $("#map").offset().top
        }, 700);

        // Get location data from server
        const jqXHR = $.get(validResult);
        jqXHR.done(function(data) {
            if (data.includes("communication error")) {
                $('#map').html(dataErrorStr);
            }
            else {
                locations_arr = JSON.parse(data);
                // render heat layer
                heat = L.heatLayer(locations_arr, heatOptions).addTo(map);
                heat.redraw();
            }
        });
    }
    else {
        $('#invalid-form').css("display", "flex");
        $('main').animate({
            scrollTop: $("#invalid-form").offset().top
        }, 700);
    }
    
});

$('#exportJSON').on('click', function (event) {
    event.preventDefault();

    let validResult = validateInputs("export");
    if (validResult) {

        $('#invalid-form').css("display", "none");
        $('main').animate({
            scrollTop: $("#map").offset().top
        }, 700);

        window.location = validResult;
    }
    else {
        $('#invalid-form').css("display", "flex");
        $('main').animate({
            scrollTop: $("#invalid-form").offset().top
        }, 700);
    }
});

$('#clearDB').on('click', function (event) {
    event.preventDefault();
    $('#deleteDbModal').modal('show');
});

$('#confirmBtn').on('click', function (event) {
    event.preventDefault();
    $('#deleteDbModal').modal('hide');
    const delXHR = $.get("/php/admin-delete-data.php?");
    delXHR.done(function(data) {
        if (data.includes("communication error")) {
            $('#failureModal').modal('show');
        }
        else {
            dataDeletedStr = '<div class="row"> <h2 id="page-header">Επισκόπηση Δεδομένων Τοποθεσίας';
            dataDeletedStr = dataDeletedStr + ' Χρηστών</h2> <p>[Δεν υπάρχουν δεδομένα]</p> </div>';
            $('main').html(dataDeletedStr);
            $('main p').css("color", "red");
        }
    });
});

// $("#startMonth").each(function(){
//     console.log($(this).val());
// //   if ($(this).val().toLowerCase() == "stackoverflow") {
// //     $(this).attr("disabled", "disabled");
// //   }
// });

// var k=5;
// var myOpts = document.getElementById('startMonth').options;
// for (let i=k-1; i>0; i--) {
//     myOpts[i].disabled = true;
// }

// var k=1;
// var myOpts = document.getElementById('endYear').options;
// for (let i=k-1; i>0; i--) {
//     myOpts[i].disabled = true;
// }

// $("#startMonth option[value='all']").prop("disabled", false);
