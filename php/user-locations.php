<?php

session_start();

require_once 'db_connect.php';

// dummy
$_SESSION["userId"] = "yHHFxIjbHDoylTLjMym6PA==";

$conn = dbConnect();

// Build query string
$stmt = "SELECT latitude, longitude FROM events WHERE userId = '".$_SESSION["userId"]."'";

if ($_GET["yearRange"] == "single"){
    $stmt = $stmt." AND EXTRACT(YEAR FROM timestampms) = ".$_GET["startYear"];
}
elseif ($_GET["yearRange"] == "multiple") {
    $stmt = $stmt." AND EXTRACT(YEAR FROM timestampms) BETWEEN ".$_GET["startYear"];
    $stmt = $stmt." AND ".$_GET["endYear"];
}

if ($_GET["monthRange"] == "single"){
    $stmt = $stmt." AND EXTRACT(MONTH FROM timestampms) = ".$_GET["startMonth"];
}
elseif ($_GET["monthRange"] == "multiple") {
    $stmt = $stmt." AND EXTRACT(MONTH FROM timestampms) BETWEEN ".$_GET["startMonth"];
    $stmt = $stmt." AND ".$_GET["endMonth"];
}

$result = pg_query($conn, $stmt) or die('communication error');

$locations_arr = array();
while ($row = pg_fetch_row($result)) {
    $locations_arr[] = array((float) $row[0], (float) $row[1]);
}

echo json_encode($locations_arr);

?>