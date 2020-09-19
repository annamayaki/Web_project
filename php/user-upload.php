<?php

session_start();

require_once 'db_connect.php';
$conn = dbConnect();

// Read as JSON
$data_json = file_get_contents("php://input");
$_POST = json_decode($data_json, true);

$userid = $_SESSION["userid"];

$prep_stmt = "INSERT INTO events 
(userid, heading, activity_type, activity_confidence, activity_timestampms,
verticalaccuracy, velocity, accuracy, longitude, latitude, altitude, timestampms, timestampunix)
values ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, to_timestamp($13))";
$result = pg_prepare($conn, "new_file_query", $prep_stmt) or die('communication error');

foreach ($_POST as $row){
    $arr = array($userid, $row["heading"], $row["activity_type"], 
        $row["activity_confidence"], $row["activity_timestampMs"],
        $row["verticalAccuracy"], $row["velocity"], $row["accuracy"],
        $row["longitude"], $row["latitude"], $row["altitude"],
        $row["timestamp"], $row["timestamp"]/1000);
    $result = pg_execute($conn, "new_file_query", $arr) or die('communication error');
}

$prep_stmt = "UPDATE users SET last_upload_date = DATE(now()) WHERE userid = $1";
$result = pg_prepare($conn, "new_upload_date_query", $prep_stmt) or die('communication error');
$result = pg_execute($conn, "new_upload_date_query", array($userid)) or die('communication error');

echo "Done";

?>