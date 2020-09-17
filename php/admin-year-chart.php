<?php

require_once 'db_connect.php';

$conn = dbConnect();

$prep_stmt = "SELECT count(*) FROM events WHERE EXTRACT(YEAR FROM timestampunix) = $1";
$years = array('2015', '2016', '2017', '2018', '2019', '2020');

$result = pg_prepare($conn, "year_query", $prep_stmt) or die('communication error');

$i = 0;
$count = array();
foreach ($years as $yr){
    $result = pg_execute($conn, "year_query", array($years[$i]));
    $arr = pg_fetch_all($result) or die('communication error');
    $count[$yr] = (int)$arr[0]["count"];
    $i++;
}

echo json_encode($count);

?>