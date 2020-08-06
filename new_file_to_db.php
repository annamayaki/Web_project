<?php

$username = "postgres";
$servername ="localhost";
$password = "anna1234";
$dbname = "supertrouper";
$user = "anna";

// ************* Read as JSON *********************

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

echo "Hello JSON!\n";
var_dump($_POST);

// Create connection
$db_connection = pg_connect("host=localhost dbname=supertrouper user=postgres password=anna1234");
// Check connection
if (!$db_connection) {
    echo "A connection error occurred.\n";
    exit;
}

$prep_stmt = "INSERT INTO events 
    (username, activity_type, longitude, latitude, timestampMs)
    values ($1, $2, $3, $4, to_timestamp($5))";
$result = pg_prepare($db_connection, "new_file_query", $prep_stmt);
if ($result) {
    echo "OK\n";
}
else {
    echo "Houston, we have a problem...";
}

foreach ($_POST as $row){
    $activity_type = $row["activity_type"];
    $long = $row["longitude"];
    $lat = $row["latitude"];
    $timestampMs = $row["timestamp"]/1000;
    $result = pg_execute($db_connection, "new_file_query", array($user, $activity_type, $long, $lat, $timestampMs));
    if (!$result) {
        echo "An error occurred.\n";
        exit;
    }
}

// while ($row = pg_fetch_row($result)) {
//     echo "username: $row[0]  password: $row[1] user_type: $row[2]";
//     echo "<br />\n";
// }


?>