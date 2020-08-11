<?php

$username = "postgres";
$servername ="localhost";
$password = "anna1234";
$dbname = "supertrouper";
$user = "anna";

// ************* Read as JSON *********************

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

// var_dump($_POST);

// Create connection
$db_connection = pg_connect("host=localhost dbname=supertrouper user=postgres password=anna1234");
// Check connection
if (!$db_connection) {
    echo "A connection error occurred.\n";
    exit;
}

$prep_stmt = "INSERT INTO events 
    (username, heading, activity_type, activity_confidence, activity_timestampMs,
    verticalAccuracy, velocity, accuracy, longitude, latitude, altitude, timestampMs)
    values ($1, $2, $3, $4, to_timestamp($5), $6, $7, $8, $9, $10, $11, to_timestamp($12))";
$result = pg_prepare($db_connection, "new_file_query", $prep_stmt);
if ($result) {
    echo "OK\n";
}
else {
    echo "Houston, we have a problem...\n";
}

foreach ($_POST as $row){
    $arr = array($user, $row["heading"], $row["activity_type"], 
        $row["activity_confidence"], $row["activity_timestampMs"]/1000,
        $row["verticalAccuracy"], $row["velocity"], $row["accuracy"],
        $row["longitude"], $row["latitude"], $row["altitude"],
        $row["timestamp"]/1000);
    $result = pg_execute($db_connection, "new_file_query", $arr);
    if (!$result) {
        echo "An error occurred.\n";
        exit;
    }
}

echo "OK\n";

// while ($row = pg_fetch_row($result)) {
//     echo "username: $row[0]  password: $row[1] user_type: $row[2]";
//     echo "<br />\n";
// }

?>