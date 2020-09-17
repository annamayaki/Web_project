$('#failureModal').modal({
    show: false
});

$("aside").load("sidebar.txt", function(responseTxt, statusTxt, xhr){
    if(statusTxt != "success") {
        $('#failureModal').modal('show');
    }
});

$('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
    $('main').toggleClass('active');
    $('#stat-descr').toggleClass('active');
    $('.chart-div').toggleClass('active');
});

var dataErrorStr = "Υπήρξε πρόβλημα κατά τη μεταφορά των δεδομένων."
dataErrorStr = dataErrorStr + " Παρακαλούμε προσπαθήστε ξανά σε μερικά δευτερόλεπτα.";

// Fetch activity data from server
const activityXHR = $.get("/php/admin-activity-chart.php");
activityXHR.done(function (data) {
    if (data.includes("communication error")) {
        $('#activity-chart').html(dataErrorStr);
        $('#activity-chart').css("color", "red");
        $('#activity-chart').css("height", "5vh");
    }
    else {
        var activity_data = JSON.parse(data);

        // Load google charts
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            let chart_data = new google.visualization.DataTable();
            chart_data.addColumn('string', 'Τύπος Δραστηριότητας');
            chart_data.addColumn('number', '#Εγγραφών');
            chart_data.addRow(['IN_VEHICLE', activity_data['IN_VEHICLE']]);
            chart_data.addRow(['ON_BICYCLE', activity_data['ON_BICYCLE']]);
            chart_data.addRow(['ON_FOOT', activity_data['ON_FOOT']]);
            chart_data.addRow(['RUNNING', activity_data['RUNNING']]);
            chart_data.addRow(['STILL', activity_data['STILL']]);
            chart_data.addRow(['TILTING', activity_data['TILTING']]);
            chart_data.addRow(['UNKNOWN', activity_data['UNKNOWN']]);

            // Optional; add a title and set the width and height of the chart
            var options = { 'width': '100%', 'height': '100%', 'pieHole': 0.4 };

            // Display the chart inside the <div> element
            var chart = new google.visualization.PieChart(document.getElementById('activity-chart'));
            chart.draw(chart_data, options);
        }
    }
});
activityXHR.fail(function () {
    $('#activity-chart').html(dataErrorStr);
    $('#activity-chart').css("color", "red");
    $('#activity-chart').css("height", "5vh");
});

// Fetch user data from server
const userXHR = $.get("/php/admin-user-chart.php");
userXHR.done(function (data) {
    if (data.includes("communication error")) {
        $('#user-chart').html(dataErrorStr);
        $('#user-chart').css("color", "red");
        $('#user-chart').css("height", "5vh");
    }
    else {
        var user_data = JSON.parse(data);

        // Load google charts
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            let chart_data = new google.visualization.DataTable();
            chart_data.addColumn('string', 'Όνομα Χρήστη');
            chart_data.addColumn('number', '#Εγγραφών');
            for (const row of user_data) {
                chart_data.addRow([row[0], row[1]]);
            }

            // Optional; add a title and set the width and height of the chart
            var options = { 'width': '100%', 'height': '100%' };

            // Display the chart inside the <div> element
            var chart = new google.visualization.BarChart(document.getElementById("user-chart"));
            chart.draw(chart_data, options);
        }
    }
});
userXHR.fail(function () {
    $('#user-chart').html(dataErrorStr);
    $('#user-chart').css("color", "red");
    $('#user-chart').css("height", "5vh");
});

// Fetch year data from server
const yearXHR = $.get("/php/admin-year-chart.php");
yearXHR.done(function (data) {
    if (data.includes("communication error")) {
        $('#year-chart').html(dataErrorStr);
        $('#year-chart').css("color", "red");
        $('#year-chart').css("height", "5vh");
    }
    else {
        year_data = JSON.parse(data);

        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            let chart_data = new google.visualization.DataTable();
            chart_data.addColumn('string', 'Έτος');
            chart_data.addColumn('number', '#Εγγραφών');
            chart_data.addRows([
                ['2015', year_data['2015']],
                ['2016', year_data['2016']],
                ['2017', year_data['2017']],
                ['2018', year_data['2018']],
                ['2019', year_data['2019']],
                ['2020', year_data['2020']]
            ]);

            // Optional; add a title and set the width and height of the chart
            var options = { 'width': '100%', 'height': '100%', 'pieHole': 0.4, 'sliceVisibilityThreshold': 0 };

            // Display the chart inside the <div> element
            var chart = new google.visualization.PieChart(document.getElementById('year-chart'));
            chart.draw(chart_data, options);
        }
    }
});
yearXHR.fail(function () {
    $('#year-chart').html(dataErrorStr);
    $('#year-chart').css("color", "red");
    $('#year-chart').css("height", "5vh");
});

// Fetch month data from server
const monthXHR = $.get("/php/admin-month-chart.php");
monthXHR.done(function (data) {
    if (data.includes("communication error")) {
        $('#month-chart').html(dataErrorStr);
        $('#month-chart').css("color", "red");
        $('#month-chart').css("height", "5vh");
    }
    else {
        month_data = JSON.parse(data);

        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            let chart_data = new google.visualization.DataTable();
            chart_data.addColumn('string', 'Μήνας');
            chart_data.addColumn('number', '#Εγγραφών');
            chart_data.addRow(['Ιαν', month_data['Jan']]);
            chart_data.addRow(['Φεβ', month_data['Feb']]);
            chart_data.addRow(['Μαρ', month_data['Mar']]);
            chart_data.addRow(['Απρ', month_data['Apr']]);
            chart_data.addRow(['Μαι', month_data['May']]);
            chart_data.addRow(['Ιουν', month_data['Jun']]);
            chart_data.addRow(['Ιουλ', month_data['Jul']]);
            chart_data.addRow(['Αυγ', month_data['Aug']]);
            chart_data.addRow(['Σεπ', month_data['Sep']]);
            chart_data.addRow(['Οκτ', month_data['Oct']]);
            chart_data.addRow(['Νοε', month_data['Nov']]);
            chart_data.addRow(['Δεκ', month_data['Dec']]);

            // Optional; add a title and set the width and height of the chart
            var options = { 'width': '100%', 'height': '100%' };

            // Display the chart inside the <div> element
            var chart = new google.visualization.BarChart(document.getElementById('month-chart'));
            chart.draw(chart_data, options);
        }
    }
});
monthXHR.fail(function () {
    $('#month-chart').html(dataErrorStr);
    $('#month-chart').css("color", "red");
    $('#month-chart').css("height", "5vh");
});

// Fetch dow data from server
const dowXHR = $.get("/php/admin-dow-chart.php");
dowXHR.done(function (data) {
    if (data.includes("communication error")) {
        $('#dow-chart').html(dataErrorStr);
        $('#dow-chart').css("color", "red");
        $('#dow-chart').css("height", "5vh");
    }
    else {
        dow_data = JSON.parse(data);
        
        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            let chart_data = new google.visualization.DataTable();
            chart_data.addColumn('string', 'Μήνας');
            chart_data.addColumn('number', '#Εγγραφών');
            chart_data.addRow(['Δευτέρα', dow_data['Mon']]);
            chart_data.addRow(['Τρίτη', dow_data['Tue']]);
            chart_data.addRow(['Τετάρτη', dow_data['Wed']]);
            chart_data.addRow(['Πέμπτη', dow_data['Thu']]);
            chart_data.addRow(['Παρασκευή', dow_data['Fri']]);
            chart_data.addRow(['Σάββατο', dow_data['Sat']]);
            chart_data.addRow(['Κυριακή', dow_data['Sun']]);

            // Optional; add a title and set the width and height of the chart
            var options = { 'width': '100%', 'height': '100%' };

            // Display the chart inside the <div> element
            var chart = new google.visualization.BarChart(document.getElementById('dow-chart'));
            chart.draw(chart_data, options);
        }
    }
});
dowXHR.fail(function () {
    $('#dow-chart').html(dataErrorStr);
    $('#dow-chart').css("color", "red");
    $('#dow-chart').css("height", "5vh");
});

// Fetch hour data from server
const hourXHR = $.get("/php/admin-hour-chart.php");
hourXHR.done(function (data) {
    if (data.includes("communication error")) {
        $('#hour-chart').html(dataErrorStr);
        $('#hour-chart').css("color", "red");
        $('#hour-chart').css("height", "5vh");
    }
    else {
         hour_data = JSON.parse(data);

        // Load google charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
            let chart_data = new google.visualization.DataTable();
            chart_data.addColumn('string', 'Ώρα');
            chart_data.addColumn('number', '#Εγγραφών');
            chart_data.addRows([
                ['00',  hour_data['0']],
                ['01',  hour_data['1']],
                ['02',  hour_data['2']],
                ['03',  hour_data['3']],
                ['04',  hour_data['4']],
                ['05',  hour_data['5']],
                ['06',  hour_data['6']],
                ['07',  hour_data['7']],
                ['08',  hour_data['8']],
                ['09',  hour_data['9']],
                ['10',  hour_data['10']],
                ['11',  hour_data['11']],
                ['12',  hour_data['12']],
                ['13',  hour_data['13']],
                ['14',  hour_data['14']],
                ['15',  hour_data['15']],
                ['16',  hour_data['16']],
                ['17',  hour_data['17']],
                ['18',  hour_data['18']],
                ['19',  hour_data['19']],
                ['20',  hour_data['20']],
                ['21',  hour_data['21']],
                ['22',  hour_data['22']],
                ['23',  hour_data['23']]
            ]);

            // Optional; add a title and set the width and height of the chart
            var options = { 'curveType': 'function', 'width': '100%', 'height': '100%', 'pointSize': 5 };

            // Display the chart inside the <div> element with id="chart"
            var chart = new google.visualization.LineChart(document.getElementById('hour-chart'));
            chart.draw(chart_data, options);
        }
    }
});
hourXHR.fail(function () {
    $('#hour-chart').html(dataErrorStr);
    $('#hour-chart').css("color", "red");
    $('#hour-chart').css("height", "5vh");
});
