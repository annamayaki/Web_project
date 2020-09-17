<?php

require_once 'db_connect.php';
require __DIR__ . "\\temp";

$conn = dbConnect();

// $_GET["yearRange"] = "single";
// $_GET["startYear"] = 2017;
// $_GET["monthRange"] = "all";
// $_GET["dowRange"] = "all";
// $_GET["hourRange"] = "multiple";
// $_GET["startHour"] = 03;
// $_GET["endHour"] = 22;
// $_GET["activities"] = "all";

// Create file
date_default_timezone_set('Europe/Athens');
$fileName = "db_export_".time().".JSON";

// $filePath = "/temp/".$fileName;
$file = fopen($fileName, "w");
echo $fileName."\n";
echo __DIR__."\n";
echo fwrite($file,"Hello World. Testing!")."\n";

// Build query string
$stmt = "SELECT * FROM events";
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

// $result = pg_query($conn, $stmt) or die('communication error');



// $locations_arr = array();
// while ($row = pg_fetch_assoc($result)) {
//     // $locations_arr[] = array((float) $row[0], (float) $row[1]);

// }
fclose($file);

// header("Content-disposition: attachment; filename=" . $fileName);
// header("Content-type: " . mime_content_type($filePath));
// readfile($filePath);

?>