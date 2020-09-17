<?php

session_start();

require_once 'db_connect.php';

$conn = dbConnect();

// Build query string
$stmt = "SELECT latitude, longitude FROM events WHERE userid = '".$_SESSION["userid"]."'";

if ($_GET["yearRange"] == "single"){
    $stmt = $stmt." AND EXTRACT(YEAR FROM timestampunix) = ".$_GET["startYear"];
}
elseif ($_GET["yearRange"] == "multiple") {
    $stmt = $stmt." AND EXTRACT(YEAR FROM timestampunix) BETWEEN ".$_GET["startYear"];
    $stmt = $stmt." AND ".$_GET["endYear"];
}

if ($_GET["monthRange"] == "single"){
    $stmt = $stmt." AND EXTRACT(MONTH FROM timestampunix) = ".$_GET["startMonth"];
}
elseif ($_GET["monthRange"] == "multiple") {
    $stmt = $stmt." AND EXTRACT(MONTH FROM timestampunix) BETWEEN ".$_GET["startMonth"];
    $stmt = $stmt." AND ".$_GET["endMonth"];
}

$result = pg_query($conn, $stmt) or die('communication error');

$locations_arr = array();
while ($row = pg_fetch_row($result)) {
    $locations_arr[] = array((float) $row[0], (float) $row[1]);
}

echo json_encode($locations_arr);

?>