$('#failureModal').modal({
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
    attribution: attrStr,
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
        $("#endYear").prop("required", true);
    }
    else {
        $("#endYear").css("visibility", "hidden");
        $(".yearLabels").css("visibility", "hidden");
        $("#startYear option[value='all']").prop("disabled", false);
        $("#endYear").prop("required", false);
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

    let validNumbers = true;
    let requestStr = "";
    let tmp_obj = new Object();
    if ($("#yearRange:checked").length) {
        tmp_obj.yearRange = "multiple";
        tmp_obj.startYear = parseInt($("#startYear").val());
        tmp_obj.endYear = parseInt($("#endYear").val());
        if (isNaN(tmp_obj.startYear) || isNaN(tmp_obj.endYear)){
            validNumbers = false;
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
                validNumbers = false;
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
            validNumbers = false;
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
                validNumbers = false;
            }
            requestStr = requestStr + "&monthRange=single" +
                "&startMonth=" + parseInt($("#startMonth").val());
        }
    }

    if (validNumbers) {

        $('#invalid-form').css("display", "none");
        $('main').animate({
            scrollTop: $("#map").offset().top
        }, 700);

        var dataErrorStr = "Υπήρξε πρόβλημα κατά τη μεταφορά των δεδομένων."
        dataErrorStr = dataErrorStr + " Παρακαλούμε προσπαθήστε ξανά σε μερικά δευτερόλεπτα.";

        //Fetch location data from server
        const pointsXHR = $.get("/php/user-locations.php?" + requestStr);
        pointsXHR.done(function(data) {
            if (data.includes("communication error")) {
                $('#map').html(dataErrorStr);
                $('#map').css("color", "red");
            }
            else {
                locations_arr = JSON.parse(data);
                // render heat layer
                heat = L.heatLayer(locations_arr, heatOptions).addTo(map);
                heat.redraw();
            }
        }); 
        pointsXHR.fail(function () {
            $('#map').html(dataErrorStr);
            $('#map').css("color", "red");
        });
            
        // Fetch activity chart data from server
        const activitiesXHR = $.get("/php/user-activities.php?" + requestStr);
        activitiesXHR.done(function (data, status) {
            if (data.includes("communication error")) {
                $('#activity-chart').html(dataErrorStr);
                $('#activity-chart').css("color", "red");
            }
            else {
                activity_data = JSON.parse(data);
                // Load google charts
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                // Draw the chart and set the chart values
                function drawChart() {
                    let activity_chart_data = new google.visualization.DataTable();
                    activity_chart_data.addColumn('string', 'Detected Activity Type');
                    activity_chart_data.addColumn('number', '#Entries');
                    activity_chart_data.addRow(['IN_VEHICLE', activity_data['IN_VEHICLE']]);
                    activity_chart_data.addRow(['ON_BICYCLE', activity_data['ON_BICYCLE']]);
                    activity_chart_data.addRow(['ON_FOOT', activity_data['ON_FOOT']]);
                    activity_chart_data.addRow(['RUNNING', activity_data['RUNNING']]);
                    activity_chart_data.addRow(['STILL', activity_data['STILL']]);
                    activity_chart_data.addRow(['TILTING', activity_data['TILTING']]);
                    activity_chart_data.addRow(['UNKNOWN', activity_data['UNKNOWN']]);

                    // Optional; add a title and set the width and height of the chart
                    var options = { 'pieHole': 0.4 };

                    var chart = new google.visualization.PieChart(document.getElementById('activity-chart'));
                    chart.draw(activity_chart_data, options);
                }
            }
        });
        activitiesXHR.fail(function () {
            $('#activity-chart').html(dataErrorStr);
            $('#activity-chart').css("color", "red");
        });

        const activityHourXHR = $.get("/php/user-activity-hour.php?" + requestStr);
        activityHourXHR.done(function (data, status) {
            if (data.includes("communication error")) {
                $('#hour-table-div').html(dataErrorStr);
                $('#hour-table-div').css("color", "red");
            }
            else {
                hour_data = JSON.parse(data);
                // Load google charts
                google.charts.load('current', {'packages':['table']});
                google.charts.setOnLoadCallback(drawTable);

                // // Draw the chart and set the chart values
                function drawTable() {
                    let hour_chart_data = new google.visualization.DataTable();
                    hour_chart_data.addColumn('string', 'Δραστηριότητα');
                    hour_chart_data.addColumn('string', 'Ώρα Περισσότερων Εγγραφών');
                    hour_chart_data.addRow(['IN_VEHICLE', hour_data['IN_VEHICLE']]);
                    hour_chart_data.addRow(['ON_BICYCLE', hour_data['ON_BICYCLE']]);
                    hour_chart_data.addRow(['ON_FOOT', hour_data['ON_FOOT']]);
                    hour_chart_data.addRow(['RUNNING', hour_data['RUNNING']]);
                    hour_chart_data.addRow(['STILL', hour_data['STILL']]);
                    hour_chart_data.addRow(['TILTING', hour_data['TILTING']]);
                    hour_chart_data.addRow(['UNKNOWN', hour_data['UNKNOWN']]);

                    var hour_table = new google.visualization.Table(document.getElementById('hour-table-div'));
                    hour_table.draw(hour_chart_data, {showRowNumber: false, width: '100%', height: '100%'});
                }
            }
        });
        activityHourXHR.fail(function () {
            $('#hour-table-div').html(dataErrorStr);
            $('#hour-table-div').css("color", "red");
        });

        const activityDowXHR = $.get("/php/user-activity-dow.php?" + requestStr);
        activityDowXHR.done(function (data, status) {
            if (data.includes("communication error")) {
                $('#dow-table-div').html(dataErrorStr);
                $('#dow-table-div').css("color", "red");
            }
            else {
                dow_data = JSON.parse(data);
                // Load google charts
                google.charts.load('current', {'packages':['table']});
                google.charts.setOnLoadCallback(drawTable);

                // Draw the chart and set the chart values
                function drawTable() {
                    let dow_chart_data = new google.visualization.DataTable();
                    dow_chart_data.addColumn('string', 'Δραστηριότητα');
                    dow_chart_data.addColumn('string', 'Ημέρα Περισσότερων Εγγραφών');
                    dow_chart_data.addRow(['IN_VEHICLE', dow_data['IN_VEHICLE']]);
                    dow_chart_data.addRow(['ON_BICYCLE', dow_data['ON_BICYCLE']]);
                    dow_chart_data.addRow(['ON_FOOT', dow_data['ON_FOOT']]);
                    dow_chart_data.addRow(['RUNNING', dow_data['RUNNING']]);
                    dow_chart_data.addRow(['STILL', dow_data['STILL']]);
                    dow_chart_data.addRow(['TILTING', dow_data['TILTING']]);
                    dow_chart_data.addRow(['UNKNOWN', dow_data['UNKNOWN']]);

                    var dow_table = new google.visualization.Table(document.getElementById('dow-table-div'));
                    dow_table.draw(dow_chart_data, {showRowNumber: false, width: '100%', height: '100%'});
                }
            }
        });
        activityDowXHR.fail(function () {
            $('#dow-table-div').html(dataErrorStr);
            $('#dow-table-div').css("color", "red");
        });
            
        $('.charts').css("display", "flex");
    }
    else {
        $('#invalid-form').css("display", "flex");
    }

});