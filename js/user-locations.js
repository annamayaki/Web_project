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

    // map = L.map('map').setView([38.230462, 21.753150], 10);
    // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //     attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    //     maxZoom: 18,
    //     minZoom: 10
    // }).addTo(map);

    $('.overlay').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('main').toggleClass('active');
        // $('.overlay').toggleClass('active');
        // $('#map').toggleClass('active');
    });
    
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('main').toggleClass('active');
        // $('.overlay').toggleClass('active');
        // $('#map').toggleClass('active');
    });

});

