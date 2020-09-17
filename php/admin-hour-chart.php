<?php

require_once 'db_connect.php';

$conn = dbConnect();

$prep_stmt = "SELECT count(*) FROM events WHERE EXTRACT(HOUR FROM timestampunix) = $1";
$hours = array('0', '1', '2', '3',
'4', '5', '6', '7', '8', '9', '10', '11', '12', '13',
'14', '15', '16', '17', '18', '19', '20', '21', '22', '23');

$result = pg_prepare($conn, "hour_query", $prep_stmt) or die('communication error');

$i = 0;
$count = array();
foreach ($hours as $hr){
    $result = pg_execute($conn, "hour_query", array($i));
    $arr = pg_fetch_all($result) or die('communication error');
    $count[$hr] = (int)$arr[0]["count"];
    $i++;
}

echo json_encode($count);

?>