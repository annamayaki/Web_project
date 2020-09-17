<?php

session_start();

require_once 'db_connect.php';

$conn = dbConnect();

$userid = $_SESSION["userid"];

// Build query string
$stmt = "SELECT EXTRACT(DOW FROM timestampunix) AS dayofweek, count(*) FROM events".
    " WHERE activity_type LIKE $1 AND userid = $2";

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

$stmt = $stmt." GROUP BY dayofweek ORDER BY count(*) DESC LIMIT 1";

$result = pg_prepare($conn, "activity_dow_query", $stmt) or die('communication error');
$activity_types = array('IN_VEHICLE', 'ON_BICYCLE', 'ON_FOOT', 'RUNNING',
'STILL', 'TILTING', 'UNKNOWN');
$dow_names = array('Κυριακή', 'Δευτέρα', 'Τρίτη', 'Τετάρτη', 'Πέμπτη', 'Παρασκευή', 'Σάββατο');
$count_types = array();

foreach ($activity_types as $type){
    $result = pg_execute($conn, "activity_dow_query", array($type, $userid));
    $arr = pg_fetch_all($result);
    if ($arr) {
        $count_types[$type] = $dow_names[(int)$arr[0]["dayofweek"]];
    }
    else {
        $count_types[$type] = "[Καμία Εγγραφή]";
    }
}

header('Content-Type: text/html; charset=utf-8');
echo json_encode($count_types);

?>