$("aside").load("sidebar.txt", function(responseTxt, statusTxt, xhr){
    if(statusTxt != "success") {
        failMsg = "Υπήρξε ένα πρόβλημα κατά τη μεταφορά απαραίτητων δεδομένων. Παρακαλούμε ";
        failMsg = failMsg + "προσπαθήστε να φορτώσετε τη σελίδα και πάλι σε μερικά δευτερόλεπτα."
        $('#failureModal .modal-body').html(failMsg);
        $('#successModal').modal();
    }
});

$('.overlay').on('click', function () {
    $('#sidebar').toggleClass('active');
    $('main').toggleClass('active');
    $('.overlay').toggleClass('active');
});

$('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
    $('main').toggleClass('active');
    $('.overlay').toggleClass('active');
});

$('#instrBtn').on('click', function () {
    $('#showInstr').toggleClass('active');
    $('#hideInstr').toggleClass('active');
});

// Prepare modals
$('#successModal').modal({
    show: false
});
$('#failureModal').modal({
    show: false
});

// Initialize the dropzone
var dropzone = new Dropzone(document.body, {
    url: '/',
    previewsContainer: document.createElement('div'), // >> /dev/null
    clickable: false,
    accept: function (file, done) {
        processFile(file);
        dropzone.disable();
    }
});

// For mobile browsers, allow direct file selection as well
$('#file').change(function () {
    processFile(this.files[0]);
    dropzone.disable();
});

function processFile(file) {

    var SCALAR_E7 = 0.0000001; // Since Google Takeout stores latlngs as integers
    var locations_arr = [];

    var os = new oboe();

    os.node('locations.*', function (location) {
        var latitude = location.latitudeE7 * SCALAR_E7,
            longitude = location.longitudeE7 * SCALAR_E7;

        // Handle negative latlngs due to google unsigned/signed integer bug.
        if (latitude > 180) latitude = latitude - (2 ** 32) * SCALAR_E7;
        if (longitude > 180) longitude = longitude - (2 ** 32) * SCALAR_E7;

        var tmp_obj = new Object();
        // attributes present in every object
        tmp_obj.timestamp = location.timestampMs;
        tmp_obj.latitude = latitude;
        tmp_obj.longitude = longitude;
        tmp_obj.accuracy = location.accuracy;

        // optional attributes
        if (location.hasOwnProperty("activity")) {
            let max_conf = -1;
            let max_conf_ind = -1;
            let i = 0;

            for (const elem of location.activity[0].activity) {
                if (elem.confidence > max_conf) {
                    max_conf = elem.confidence;
                    max_conf_ind = i;
                }
                i++;
            }

            tmp_obj.activity_timestampMs = location.activity[0].timestampMs;
            tmp_obj.activity_type = location.activity[0].activity[max_conf_ind].type;
            tmp_obj.activity_confidence = location.activity[0].activity[max_conf_ind].confidence;
        }
        else {
            tmp_obj.activity_timestampMs = null;
            tmp_obj.activity_type = null;
            tmp_obj.activity_confidence = null;
        }

        if (location.hasOwnProperty("heading")) {
            tmp_obj.heading = location.heading;
        }
        else {
            tmp_obj.heading = null;
        }

        if (location.hasOwnProperty("verticalAccuracy")) {
            tmp_obj.verticalAccuracy = location.verticalAccuracy;
        }
        else {
            tmp_obj.verticalAccuracy = null;
        }

        if (location.hasOwnProperty("velocity")) {
            tmp_obj.velocity = location.velocity;
        }
        else {
            tmp_obj.velocity = null;
        }

        if (location.hasOwnProperty("altitude")) {
            tmp_obj.altitude = location.altitude;
        }
        else {
            tmp_obj.altitude = null;
        }

        locations_arr.push(tmp_obj);

        return oboe.drop;

    }).done(function () {

        const jqXHR = $.ajax({
            url    : "/php/user-upload.php",
            type   : 'POST',
            contentType:'application/json; charset=utf-8',
            data   : JSON.stringify(locations_arr)
        });
        jqXHR.done(function(data) {
            if (data.includes("communication error")) {
                $('#failureModal').modal('show');
            }
            else {
                $('#successModal').modal('show');
            }
            dropzone.enable();
        });
        jqXHR.fail(function() {
            $('#failureModal').modal('show');
            dropzone.enable();
        });

    });

    // Now start working!
    parseJSONFile(file, os);
}



function parseJSONFile(file, oboeInstance) {

    // Break file into chunks and emit 'data' to oboe instance
    var fileSize = file.size;
    var chunkSize = 512 * 1024; // bytes
    var offset = 0;
    var chunkReaderBlock = null;
    var readEventHandler = function (evt) {
        if (evt.target.error == null) {
            offset += evt.target.result.length;
            var chunk = evt.target.result;
            oboeInstance.emit('data', chunk); // callback for handling read chunk
        } else {
            return;
        }
        if (offset >= fileSize) {
            oboeInstance.emit('done');
            return;
        }

        // of to the next chunk
        chunkReaderBlock(offset, chunkSize, file);
    }

    chunkReaderBlock = function (_offset, length, _file) {
        var r = new FileReader();
        var blob = _file.slice(_offset, length + _offset);
        r.onload = readEventHandler;
        r.readAsText(blob);
    }

    // now let's start the read with the first block
    chunkReaderBlock(offset, chunkSize, file);
}
