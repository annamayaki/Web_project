( function ( $, L, prettySize ) {
	var map, heat,
		heatOptions = {
			tileOpacity: 1,
			heatOpacity: 1,
			radius: 25,
			blur: 15
		};

	function status( message ) {
		$( '#currentStatus' ).text( message );
	}
	// Start at the beginning
	stageOne();

	function stageOne () {
		var dropzone;

		// Initialize the map
		map = L.map( 'map' ).setView( [0,0], 2 );
		L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: 'location-history-visualizer is open source and available <a href="https://github.com/theopolisme/location-history-visualizer">on GitHub</a>. Map data &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors.',
			maxZoom: 18,
			minZoom: 2
		} ).addTo( map );

		// Initialize the dropzone
		dropzone = new Dropzone( document.body, {
			url: '/',
			previewsContainer: document.createElement( 'div' ), // >> /dev/null
			clickable: false,
			accept: function ( file, done ) {
				stageTwo( file );
				dropzone.disable(); // Your job is done, buddy
			}
		} );

		// For mobile browsers, allow direct file selection as well
		$( '#file' ).change( function () {
			stageTwo( this.files[0] );
			dropzone.disable();
		} );
	}

	function stageTwo ( file ) {

		heat = L.heatLayer( [], heatOptions ).addTo( map );

		var type = 'json';

		// First, change tabs
		$( '#intro' ).addClass( 'hidden' );

		var SCALAR_E7 = 0.0000001; // Since Google Takeout stores latlngs as integers
		var latlngs = [];
		var locations_arr = []; 
		let k = 0;

		var os = new oboe();

		os.node( 'locations.*', function ( location ) {
			var latitude = location.latitudeE7 * SCALAR_E7,
				longitude = location.longitudeE7 * SCALAR_E7;

			// Handle negative latlngs due to google unsigned/signed integer bug.
			if ( latitude > 180 ) latitude = latitude - (2 ** 32) * SCALAR_E7;
			if ( longitude > 180 ) longitude = longitude - (2 ** 32) * SCALAR_E7;

			latlngs.push( [ latitude, longitude ] );

			var tmp_obj = new Object();
			// attributes present in every object
			tmp_obj.timestamp = location.timestampMs;
			tmp_obj.latitude = latitude;
			tmp_obj.longitude = longitude;
			tmp_obj.accuracy = location.accuracy;

			// optional attributes
			if (location.hasOwnProperty("activity")){
				let max_conf= -1;
				let max_conf_ind = -1;
				let i=0;
				
				for (const elem of location.activity[0].activity){
					if (elem.confidence > max_conf){
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
				// console.log("No activity detected.");
			}

			if (location.hasOwnProperty("heading")){
				tmp_obj.heading = location.heading;
			}
			else {
				tmp_obj.heading = null;
			}

			if (location.hasOwnProperty("verticalAccuracy")){
				tmp_obj.verticalAccuracy = location.verticalAccuracy;
			}
			else {
				tmp_obj.verticalAccuracy = null;
			}

			if (location.hasOwnProperty("velocity")){
				tmp_obj.velocity = location.velocity;
			}
			else {
				tmp_obj.velocity = null;
			}

			if (location.hasOwnProperty("altitude")){
				tmp_obj.altitude = location.altitude;
			}
			else {
				tmp_obj.altitude = null;
			}
			
			locations_arr.push(tmp_obj);

			return oboe.drop;
		} ).done( function () {
			status( 'Generating map...' );
			heat._latlngs = latlngs;

			heat.redraw();
			stageThree(  /* numberProcessed */ latlngs.length );

			console.log(locations_arr);

			// const jqXHR = $.post("new-file-to-db.php", JSON.stringify(locations_arr));
			// jqXHR.done(function(data) {
			// 		// console.log(data);
			// 	}
			// );


			// var form = $('#data_hidden');
    		// $.ajax({
			// url    : form.attr('action'),
			// type   : 'POST',
			// contentType:'application/json; charset=utf-8',
			// data   : JSON.stringify(locations_arr),
			// success: function(data, status){
			// 	//On ajax success do this
			// 	alert("Status: " + status);
			// 	console.log(data);
			// },
			// error: function(xhr, ajaxOptions, thrownError) {
			// 	//On error do this
			// 		if (xhr.status == 200) {
			
			// 			alert(ajaxOptions);
			// 		}
			// 		else {
			// 			alert(xhr.status);
			// 			alert(thrownError);
			// 		}
			// }
			// });

		} );

		var fileSize = prettySize( file.size );

		status( 'Preparing to import file ( ' + fileSize + ' )...' );

		// Now start working!
		if ( type === 'json' ) parseJSONFile( file, os );
	}

	function stageThree ( numberProcessed ) {

		var $done = $( '#done' );

		// Change tabs :D

		// Update count
		$( '#numberProcessed' ).text( numberProcessed.toLocaleString() );

		$( 'body' ).addClass( 'map-active' );

	}

	/*
	Break file into chunks and emit 'data' to oboe instance
	*/

	function parseJSONFile( file, oboeInstance ) {
		var fileSize = file.size;
		var prettyFileSize = prettySize(fileSize);
		var chunkSize = 512 * 1024; // bytes
		var offset = 0;
		var self = this; // we need a reference to the current object
		var chunkReaderBlock = null;
		var startTime = Date.now();
		var endTime = Date.now();
		var readEventHandler = function ( evt ) {
			if ( evt.target.error == null ) {
				offset += evt.target.result.length;
				var chunk = evt.target.result;
				var percentLoaded = ( 100 * offset / fileSize ).toFixed( 0 );
				status( percentLoaded + '% of ' + prettyFileSize + ' loaded...' );
				oboeInstance.emit( 'data', chunk ); // callback for handling read chunk
			} else {
				return;
			}
			if ( offset >= fileSize ) {
				oboeInstance.emit( 'done' );
				return;
			}

			// of to the next chunk
			chunkReaderBlock( offset, chunkSize, file );
		}

		chunkReaderBlock = function ( _offset, length, _file ) {
			var r = new FileReader();
			var blob = _file.slice( _offset, length + _offset );
			r.onload = readEventHandler;
			r.readAsText( blob );
		}

		// now let's start the read with the first block
		chunkReaderBlock( offset, chunkSize, file );
	}

}( jQuery, L, prettySize ) );
