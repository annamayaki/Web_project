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

    var map = L.map('map').setView([38.230462, 21.753150], 10);
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
        
        let baseStr = "http://localhost:4000/php/user-locations.php?";
        let requestStr = "";
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
        console.log(baseStr + requestStr);

        $('.charts').css("display", "block");
    
        // get data from server
        const jqXHR = $.get(baseStr + requestStr);
        jqXHR.done(function(data) {
            console.log("OK data");
            console.log(data);
            $('main').animate({
                scrollTop: $("#map").offset().top
            }, 700);
            locations_arr = JSON.parse(data);
            requestStr = "";
            // render heat layer
            heat = L.heatLayer(locations_arr, heatOptions).addTo(map);
            heat.redraw();
        }); 
        
        // Fetch data from server
        baseStr = "http://localhost:4000/php/user-activities.php?";
        $.get(baseStr + requestStr, function (data, status) {
            console.log(status);
            console.log(data);
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
                var options = {
                // 'title':'Κατανομή Δραστηριότητας', 
                // 'height':'40vh', 'width':'70vw', 
                'pieHole': 0.4};

                var chart = new google.visualization.PieChart(document.getElementById('activity-chart'));
                chart.draw(activity_chart_data, options);
            }
        });

        baseStr = "http://localhost:4000/php/user-activity-hour.php?";
        $.get(baseStr + requestStr, function (data, status) {
            console.log(status);
            console.log(data);
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

                // Optional; add a title and set the width and height of the chart
                // var options = {
                // 'title':'Κατανομή Δραστηριότητας', 
                // 'height':'40vh', 'width':'70vw', 
                // 'pieHole': 0.4};

                var hour_table = new google.visualization.Table(document.getElementById('hour-table-div'));
                hour_table.draw(hour_chart_data, {showRowNumber: false, width: '100%', height: '100%'});
            }

        });

        baseStr = "http://localhost:4000/php/user-activity-dow.php?";
        $.get(baseStr + requestStr, function (data, status) {
            console.log(status);
            console.log(data);
            dow_data = JSON.parse(data);
            // Load google charts
            google.charts.load('current', {'packages':['table']});
            google.charts.setOnLoadCallback(drawTable);


            // // Draw the chart and set the chart values
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

                // Optional; add a title and set the width and height of the chart
                // var options = {
                // 'title':'Κατανομή Δραστηριότητας', 
                // 'height':'40vh', 'width':'70vw', 
                // 'pieHole': 0.4};

                var dow_table = new google.visualization.Table(document.getElementById('dow-table-div'));
                dow_table.draw(dow_chart_data, {showRowNumber: false, width: '100%', height: '100%'});
            }

        });

        $('.charts').css("display", "flex");

    });

});