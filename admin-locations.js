

$("#startMonth").each(function(){
    console.log($(this).val());
//   if ($(this).val().toLowerCase() == "stackoverflow") {
//     $(this).attr("disabled", "disabled");
//   }
});

var k=5;
var myOpts = document.getElementById('startMonth').options;
for (let i=k-1; i>0; i--) {
    myOpts[i].disabled = true;
}

var k=1;
var myOpts = document.getElementById('endYear').options;
for (let i=k-1; i>0; i--) {
    myOpts[i].disabled = true;
}

$(document).ready(function () {
    $("#sidebarCollapse").on("click", function () {
        $("#sidebar").toggleClass("active");
        $("main").toggleClass("active");
    });
});