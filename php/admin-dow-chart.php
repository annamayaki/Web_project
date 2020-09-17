<?php

require_once 'db_connect.php';

$conn = dbConnect();

$prep_stmt = "SELECT count(*) FROM events WHERE EXTRACT(DOW FROM timestampunix) = $1";
$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

$result = pg_prepare($conn, "day_query", $prep_stmt) or die('communication error');

$i = 0;
$count = array();
foreach ($days as $day){
    $result = pg_execute($conn, "day_query", array($i));
    $arr = pg_fetch_all($result) or die('communication error');
    $count[$day] = (int)$arr[0]["count"];
    $i++;
}

echo json_encode($count);

?>