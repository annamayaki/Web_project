<?php

require_once 'db_connect.php';
$conn = dbConnect();

$prep_stmt = "SELECT count(*) FROM events WHERE activity_type LIKE $1";
$activity_types = array('IN_VEHICLE', 'ON_BICYCLE', 'ON_FOOT', 'RUNNING',
'STILL', 'TILTING', 'UNKNOWN');

$result = pg_prepare($conn, "activity_query", $prep_stmt) or die('communication error');

$count_types = array();
foreach ($activity_types as $type){
    $result = pg_execute($conn, "activity_query", array($type));
    $arr = pg_fetch_all($result) or die('communication error');
    $count_types[$type] = (int)$arr[0]["count"];
}

echo json_encode($count_types);

?>