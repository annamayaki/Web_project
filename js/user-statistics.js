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
        $('#failureModal').modal('show');
    }
    else if (data.includes("communication error")) {
        $('#failureModal').modal('show');
    }
    else {
        dates = JSON.parse(data);
        if (dates.lastUplDate) {
            hasUploadedData = true;
            $('#start-date').html(dates.startDate);
            $('#end-date').html(dates.endDate);
            $('#last-upload-date').html(dates.lastUplDate);

            const indivScoreXHR = $.get("/php/user-indiv-scores.php?");
            const scoreRanksXHR = $.get("/php/user-score-rank.php?");

            indivScoreXHR.done(function(indivScoreData) { 
                var php_data = JSON.parse(indivScoreData);
                var labels = php_data[0];
                var scores = php_data[1];

                // Load google charts
                google.charts.load('current', {packages: ['corechart', 'bar']});
                google.charts.setOnLoadCallback(drawBasic);

                // Draw the chart and set the chart values
                function drawBasic() {

                    var chart_data = new google.visualization.DataTable();
                    chart_data.addColumn('string', 'Μήνας');
                    chart_data.addColumn('number', 'Σκορ (%)');
                    for (let i=0; i<12; i++) {
                        chart_data.addRow([labels[i], scores[i]]);
                    }

                    // Optional; add a title and set the width and height of the chart
                    var options = { 'width': '100%', 'height': '100%' };

                    // Display the chart inside the <div> element
                    var chart = new google.visualization.ColumnChart(document.getElementById('eco-score-chart'));
                    chart.draw(chart_data, options);
                }
            });

            scoreRanksXHR.done(function(scoreRanksData) { 
                rank_data = JSON.parse(scoreRanksData);
                names = rank_data[0];
                scores = rank_data[1];

                if (names[3] == names[0] || names[3] == names[1] || names[3] == names[2]) {
                    $('#pers-rank-msg').html("Συγχαρητήρια! Συνεχίστε την καλή προσπάθεια!");
                }
                else {
                    $('#pers-rank-msg').html("Συνεχίστε την προσπάθεια μέχρι να βρεθείτε στην κορυφή!");
                }

                // Load google charts
                google.charts.load('current', {'packages':['table']});
                google.charts.setOnLoadCallback(drawTable);

                // Draw the chart and set the chart values
                function drawTable() {
                    var chart_data = new google.visualization.DataTable();
                    chart_data.addColumn('string', 'Κατάταξη');
                    chart_data.addColumn('string', 'Όνομα Χρήστη');
                    chart_data.addColumn('number', 'Σκορ (%)');
                    chart_data.addRow(["1", names[0], scores[0]]);
                    chart_data.addRow(["2", names[1], scores[1]]);
                    chart_data.addRow(["3", names[2], scores[2]]);
                    chart_data.addRow(["Εσείς", names[3], scores[3]]);

                    // Optional; add a title and set the width and height of the chart
                    var options = {'showRowNumber': false, 'width': '100%', 'height': '100%'};

                    // Display the chart inside the <div> element
                    var table = new google.visualization.Table(document.getElementById('eco-score-rankings'));
                    table.draw(chart_data, options);
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
