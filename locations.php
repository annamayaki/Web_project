<?php

$username = "postgres";
$servername ="localhost";
$password = "anna1234";
$dbname = "supertrouper";
$user = "anna";

// print_r($_GET);

// Create connection
$db_connection = pg_connect("host=localhost dbname=supertrouper user=postgres password=anna1234");
// Check connection
if (!$db_connection) {
    echo "A connection error occurred.\n";
    exit;
}

// Build query string
$stmt = "SELECT latitude, longitude FROM events";
$addWhere = true;   // where clause inclusion between attributes

if ($_GET["yearRange"] == "single"){
    $stmt = $stmt." WHERE EXTRACT(YEAR FROM timestampms) = ".$_GET["startYear"];
    $addWhere = false;
}
elseif ($_GET["yearRange"] == "multiple") {
    $stmt = $stmt." WHERE EXTRACT(YEAR FROM timestampms) BETWEEN ".$_GET["startYear"];
    $stmt = $stmt." AND ".$_GET["endYear"];
    $addWhere = false;
}

if ($_GET["monthRange"] == "single"){
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." EXTRACT(MONTH FROM timestampms) = ".$_GET["startMonth"];
}
elseif ($_GET["monthRange"] == "multiple") {
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." EXTRACT(MONTH FROM timestampms) BETWEEN ".$_GET["startMonth"];
    $stmt = $stmt." AND ".$_GET["endMonth"];
}

if ($_GET["dowRange"] == "single"){
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." EXTRACT(DOW FROM timestampms) = ".$_GET["startDow"];
}
elseif ($_GET["dowRange"] == "multiple") {
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." EXTRACT(DOW FROM timestampms) BETWEEN ".$_GET["startDow"];
    $stmt = $stmt." AND ".$_GET["endDow"];
}

if ($_GET["hourRange"] == "single"){
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." EXTRACT(HOUR FROM timestampms) = ".$_GET["startHour"];
}
elseif ($_GET["hourRange"] == "multiple") {
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." EXTRACT(HOUR FROM timestampms) BETWEEN ".$_GET["startHour"];
    $stmt = $stmt." AND ".$_GET["endHour"];
}

if ($_GET["activities"] == "single"){
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." activity_type LIKE ".$_GET["actTypes"];
}
elseif ($_GET["activities"] == "multiple") {
    // Spit activity types in request string and store in array
    $actTypes = preg_split("/[,]+/", $_GET["actType"]);
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
    }
    $stmt = $stmt." (activity_type LIKE '".$actTypes[0]."'";
    for ($i = 1; $i < count($actTypes); $i++) {
        $stmt = $stmt." OR activity_type LIKE '".$actTypes[$i]."'";
    }
    $stmt = $stmt.")";
}

// echo $stmt."\n";

$result = pg_query($db_connection, $stmt);
if (!$result) {
    echo "Houston, we have a problem...\n";
    exit;
}

// $row = pg_fetch_row($result);
// var_dump($row);
// echo "PHP done.\n";

$locations_arr = array();
while ($row = pg_fetch_row($result)) {
    $locations_arr[] = array((float) $row[0], (float) $row[1]);
}

echo json_encode($locations_arr);

?>