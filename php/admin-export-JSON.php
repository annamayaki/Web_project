<?php

require_once 'db_connect.php';

$conn = dbConnect();

// Create file
date_default_timezone_set('Europe/Athens');
$fileName = "db_export_".time().".JSON";
$filePath = __DIR__."\\..\\temp\\".$fileName;
$file = fopen($filePath, "w");
// echo $_SERVER['QUERY_STRING']."\n";

// Build query string
$stmt = "SELECT * FROM events";
$addWhere = true;   // where clause inclusion between attributes

if ($_GET["yearRange"] == "single"){
    $stmt = $stmt." WHERE EXTRACT(YEAR FROM timestampunix) = ".$_GET["startYear"];
    $addWhere = false;
}
elseif ($_GET["yearRange"] == "multiple") {
    $stmt = $stmt." WHERE EXTRACT(YEAR FROM timestampunix) BETWEEN ".$_GET["startYear"];
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
    $stmt = $stmt." EXTRACT(MONTH FROM timestampunix) = ".$_GET["startMonth"];
}
elseif ($_GET["monthRange"] == "multiple") {
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." EXTRACT(MONTH FROM timestampunix) BETWEEN ".$_GET["startMonth"];
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
    $stmt = $stmt." EXTRACT(DOW FROM timestampunix) = ".$_GET["startDow"];
}
elseif ($_GET["dowRange"] == "multiple") {
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." EXTRACT(DOW FROM timestampunix) BETWEEN ".$_GET["startDow"];
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
    $stmt = $stmt." EXTRACT(HOUR FROM timestampunix) = ".$_GET["startHour"];
}
elseif ($_GET["hourRange"] == "multiple") {
    if (!$addWhere){
        $stmt = $stmt." AND";
    }
    else {
        $stmt = $stmt." WHERE";
        $addWhere = false;
    }
    $stmt = $stmt." EXTRACT(HOUR FROM timestampunix) BETWEEN ".$_GET["startHour"];
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

$result = pg_query($conn, $stmt) or die('communication error');

$exp7 = 10000000;
fwrite($file,'{ "events": [');
// First object
$row = pg_fetch_assoc($result);
fwrite($file, '{"latitudeE7":'.($row["latitude"]*$exp7));
fwrite($file, ',"longitudeE7":'.($row["longitude"]*$exp7));
fwrite($file, ',"timestampMs":'.$row["timestampms"]);
if (!is_null($row["activity_type"])){
    fwrite($file, ',"activity":{');
    fwrite($file, '"type":"'.$row["activity_type"].'"');
    fwrite($file, ',"confidence":'.$row["activity_confidence"].'');
    fwrite($file, ',"timestampMs":'.$row["activity_timestampms"].'}');
}
if (!is_null($row["heading"])){
    fwrite($file, ',"heading":'.$row["heading"].'');
}
if (!is_null($row["verticalaccuracy"])){
    fwrite($file, ',"verticalAccuracy":'.$row["verticalaccuracy"].'');
}
if (!is_null($row["velocity"])){
    fwrite($file, ',"velocity":'.$row["velocity"].'');
}
if (!is_null($row["accuracy"])){
    fwrite($file, ',"accuracy":'.$row["accuracy"].'');
}
if (!is_null($row["altitude"])){
    fwrite($file, ',"altitude":'.$row["altitude"].'');
}
fwrite($file, ',"userId":"'.$row["userid"].'"}');

// Other objects
while ($row = pg_fetch_assoc($result)) {
    fwrite($file, ',{"latitudeE7":'.($row["latitude"]*$exp7));
    fwrite($file, ',"longitudeE7":'.($row["longitude"]*$exp7));
    fwrite($file, ',"timestampMs":'.$row["timestampms"]);
    if (!is_null($row["activity_type"])){
        fwrite($file, ',"activity":{');
        fwrite($file, '"type":"'.$row["activity_type"].'"');
        fwrite($file, ',"confidence":'.$row["activity_confidence"].'');
        fwrite($file, ',"timestampMs":'.$row["activity_timestampms"].'}');
    }
    if (!is_null($row["heading"])){
        fwrite($file, ',"heading":'.$row["heading"].'');
    }
    if (!is_null($row["verticalaccuracy"])){
        fwrite($file, ',"verticalAccuracy":'.$row["verticalaccuracy"].'');
    }
    if (!is_null($row["velocity"])){
        fwrite($file, ',"velocity":'.$row["velocity"].'');
    }
    if (!is_null($row["accuracy"])){
        fwrite($file, ',"accuracy":'.$row["accuracy"].'');
    }
    if (!is_null($row["altitude"])){
        fwrite($file, ',"altitude":'.$row["altitude"].'');
    }
    fwrite($file, ',"userId":"'.$row["userid"].'"}');
}
fwrite($file, '] }');
fclose($file);

// Send file to client
header("Content-Description: File Transfer"); 
header("Content-Type: application/JSON"); 
header("Content-Disposition: attachment; filename=\"".$fileName."\""); 
readfile ($filePath);

// Delete file
unlink($filePath);

?>