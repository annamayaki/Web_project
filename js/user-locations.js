var heatOptions = {
    tileOpacity: 1,
    heatOpacity: 2,
    radius: 20,
    blur: 20
};

$(document).ready(function () { 

    $("aside").load("sidebar.txt", function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
            console.log("External content loaded successfully!");
        if(statusTxt == "error")
            console.log("Error: " + xhr.status + ": " + xhr.statusText);
      });

    map = L.map('map').setView([38.230462, 21.753150], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18,
        minZoom: 10
    }).addTo(map);

    $('.overlay').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('main').toggleClass('active');
        $('.overlay').toggleClass('active');
        $('#map').toggleClass('active');
    });
    
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('main').toggleClass('active');
        $('.overlay').toggleClass('active');
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

    $('#submit').on('click', function (event) {
        event.preventDefault();
    
        let requestStr = "http://localhost:4000/php/user-locations.php?";
        let tmp_obj = new Object();
        if ($("#yearRange:checked").length) {
            tmp_obj.yearRange = "multiple";
            tmp_obj.startYear = parseInt($("#startYear").val());
            tmp_obj.endYear = parseInt($("#endYear").val());
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
                requestStr = requestStr + "yearRange=single" +
                    "&startYear=" + parseInt($("#startYear").val());
            }
        }
    
        if ($("#monthRange:checked").length) {
            tmp_obj.monthRange = "multiple";
            tmp_obj.startMonth = parseInt($("#startMonth").val());
            tmp_obj.endMonth = parseInt($("#endMonth").val());
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
                requestStr = requestStr + "&monthRange=single" +
                    "&startMonth=" + parseInt($("#startMonth").val());
            }
        }
    
        console.log("OK object");
        console.log(requestStr);
    
        // get data from server
        const jqXHR = $.get(requestStr);
        jqXHR.done(function(data) {
            console.log("OK data");
            $('main').animate({
                scrollTop: $("#map").offset().top
            }, 700);
            locations_arr = JSON.parse(data);
            requestStr = "";
            // render heat layer
            heat = L.heatLayer(locations_arr, heatOptions).addTo(map);
            heat.redraw();
        });    
    });

});