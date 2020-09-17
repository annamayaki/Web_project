$('#failureModal').modal({
    show: false
});

$("aside").load("sidebar.txt", function(responseTxt, statusTxt, xhr){
    if(statusTxt != "success") {
        $('#failureModal').modal('show');
    }
});

const datesXHR = $.get("/php/user-stats-dates.php?");

datesXHR.then(function(data, textStatus) {

    if (textStatus != "success" ) {
        // modal
    }
    else if (data.includes("communication error")) {
        // modal
        console.log("communication error");
    }
    else {
        dates = JSON.parse(data);
        // console.log(dates);
        if (dates.lastUplDate) {
            hasUploadedData = true;
            $('#start-date').html(dates.startDate);
            $('#end-date').html(dates.endDate);
            $('#last-upload-date').html(dates.lastUplDate);

            const indivScoreXHR = $.get("/php/user-indiv-scores.php?");
            const scoreRanksXHR = $.get("/php/eco-score-rank.php?");

            indivScoreXHR.done(function(indivScoreData) { 
                var php_data = JSON.parse(indivScoreData);
                var labels = php_data[0];
                var scores = php_data[1];
                console.log(labels);
                console.log(scores);

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
            });

            scoreRanksXHR.done(function(scoreRanksData) { 
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
            });

            indivScoreXHR.fail(function() {
                // modal
                console.log("indivScore fail");
            });  
            
            scoreRanksXHR.fail(function() {
                // modal
                console.log("scoreRanks fail");
            }); 
        }
        else {
            // show/hide a few divs
        } 
    }
});

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
