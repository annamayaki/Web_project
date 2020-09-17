<?php

session_start();

require_once 'db_connect.php';

$conn = dbConnect();

$userid = array($_SESSION["userid"]);
$dates = array();

$stmt = "SELECT last_upload_date AS date FROM users WHERE userid = $1";
$result = pg_prepare($conn, "last_upl_date_query", $stmt) or die('communication error');
$result = pg_execute($conn, "last_upl_date_query", $userid);
$arr = pg_fetch_all($result) or die('communication error');

if ($arr[0]["date"] != 0) {
    $dates["lastUplDate"] = date("d-m-Y", strtotime($arr[0]["date"]));
}
else {
    $dates["lastUplDate"] = 0;
}

$stmt = "SELECT DATE(timestampunix) AS date FROM events WHERE userid = $1".
"ORDER BY timestampunix ASC LIMIT 1";
$result = pg_prepare($conn, "start_date_query", $stmt) or die('communication error');
$result = pg_execute($conn, "start_date_query", $userid);
$arr = pg_fetch_all($result) or die('communication error');
$dates["startDate"] = date("d-m-Y", strtotime($arr[0]["date"]));

$stmt = "SELECT DATE(timestampunix) AS date FROM events WHERE userid = $1".
"ORDER BY timestampunix DESC LIMIT 1";
$result = pg_prepare($conn, "end_date_query", $stmt) or die('communication error');
$result = pg_execute($conn, "end_date_query", $userid);
$arr = pg_fetch_all($result) or die('communication error');
$dates["endDate"] = date("d-m-Y", strtotime($arr[0]["date"]));

echo json_encode($dates);

?>
