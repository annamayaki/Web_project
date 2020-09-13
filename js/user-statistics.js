$(document).ready(function () {

    $("aside").load("sidebar.txt", function(responseTxt, statusTxt, xhr){
        if(statusTxt == "success")
            console.log("External content loaded successfully!");
        if(statusTxt == "error")
            console.log("Error: " + xhr.status + ": " + xhr.statusText);
    });

    // const jqXHR = $.get("http://localhost:4000/php/user-stats-dates.php?");
    // jqXHR.done(function(data) {
    //     dates = JSON.parse(data);
    //     $('#start-date) = dates[startDate];
    //     $('#end-date) = dates[endDate];
    //     $('#last-upload-date) = dates[lastUplDate];
    // });

    $('.overlay').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('main').toggleClass('active');
        $('.overlay').toggleClass('active');
        $('#page-header').toggleClass('active');
        $('.stat-descr').toggleClass('active');
        $('#eco-score-chart').toggleClass('active');
        $('#eco-score-rankings').toggleClass('active');
    });
    
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('main').toggleClass('active');
        $('.overlay').toggleClass('active');
        $('#page-header').toggleClass('active');
        $('.stat-descr').toggleClass('active');
        $('#eco-score-chart').toggleClass('active');
        $('#eco-score-rankings').toggleClass('active');
    });

    // const jqXHR = $.get("http://localhost:4000/php/eco-score-indiv.php?");
    // jqXHR.done(function(chart-data) {
    //     indiv-scores = JSON.parse(chart-data);
    
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBasic);

        function drawBasic() {

            var data = new google.visualization.DataTable();
            data.addColumn('timeofday', 'Time of Day');
            data.addColumn('number', 'Motivation Level');

            data.addRows([
                [{v: [8, 0, 0], f: '8 am'}, 1],
                [{v: [9, 0, 0], f: '9 am'}, 2],
                [{v: [10, 0, 0], f:'10 am'}, 3],
                [{v: [11, 0, 0], f: '11 am'}, 4],
                [{v: [12, 0, 0], f: '12 pm'}, 5],
                [{v: [13, 0, 0], f: '1 pm'}, 6],
                [{v: [14, 0, 0], f: '2 pm'}, 7],
                [{v: [15, 0, 0], f: '3 pm'}, 8],
                [{v: [16, 0, 0], f: '4 pm'}, 9],
                [{v: [17, 0, 0], f: '5 pm'}, 10],
            ]);

            var options = {
                title: 'Motivation Level Throughout the Day',
                hAxis: {
                title: 'Time of Day',
                format: 'h:mm a',
                viewWindow: {
                    min: [7, 30, 0],
                    max: [17, 30, 0]
                }
                },
                vAxis: {
                title: 'Rating (scale of 1-10)'
                }
            };

            var chart = new google.visualization.ColumnChart(
                document.getElementById('eco-score-chart'));

            chart.draw(data, options);
        }

    // });

    // const jqXHR = $.get("http://localhost:4000/php/eco-score-rank.php?");
    // jqXHR.done(function(table-data) {
    //     rank-scores = JSON.parse(table-data);

        google.charts.load('current', {'packages':['table']});
        google.charts.setOnLoadCallback(drawTable);

        function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('number', 'Salary');
            data.addColumn('boolean', 'Full Time Employee');
            data.addRows([
                ['Mike',  {v: 10000, f: '$10,000'}, true],
                ['Jim',   {v:8000,   f: '$8,000'},  false],
                ['Alice', {v: 12500, f: '$12,500'}, true],
                ['Bob',   {v: 7000,  f: '$7,000'},  true]
            ]);

            var table = new google.visualization.Table(document.getElementById('eco-score-rankings'));

            table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
        }


    // });

});