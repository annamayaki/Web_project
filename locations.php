<?php

$username = "postgres";
$servername ="localhost";
$password = "anna1234";
$dbname = "supertrouper";
$user = "anna";

// print_r($_GET);

// // Create connection
// $db_connection = pg_connect("host=localhost dbname=supertrouper user=postgres password=anna1234");
// // Check connection
// if (!$db_connection) {
//     echo "A connection error occurred.\n";
//     exit;
// }

// Spit activity types in request string and store in array
$actTypes = preg_split("/[,]+/", $_GET["actType"]);

// Build query string
$stmt = "SELECT latitude, longitude FROM events";
$addWhere = true;   // where clause inclusion between attributes
$addOr = false;     // or clause inclusion between activity types

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



echo $stmt;

// $result = pg_prepare($db_connection, "new_file_query", $prep_stmt);
// if ($result) {
//     echo "OK\n";
// }
// else {
//     echo "Houston, we have a problem...\n";
// }

// foreach ($_POST as $row){
//     $arr = array($user, $row["heading"], $row["activity_type"], 
//         $row["activity_confidence"], $row["activity_timestampMs"]/1000,
//         $row["verticalAccuracy"], $row["velocity"], $row["accuracy"],
//         $row["longitude"], $row["latitude"], $row["altitude"],
//         $row["timestamp"]/1000);
//     $result = pg_execute($db_connection, "new_file_query", $arr);
//     if (!$result) {
//         echo "An error occurred.\n";
//         exit;
//     }
// }

// echo "OK\n";

// while ($row = pg_fetch_row($result)) {
//     echo "username: $row[0]  password: $row[1] user_type: $row[2]";
//     echo "<br />\n";
// }

?>