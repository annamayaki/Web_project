<?php

require_once 'db_connect.php';

$conn = dbConnect();

$prep_stmt = "SELECT count(*) FROM events WHERE EXTRACT(MONTH FROM timestampunix) = $1";
$months = array('Jan', 'Feb', 'Mar', 'Apr',
'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

$result = pg_prepare($conn, "month_query", $prep_stmt) or die('communication error');

$i = 1;
$count = array();
foreach ($months as $mon){
    $result = pg_execute($conn, "month_query", array($i));
    $arr = pg_fetch_all($result) or die('communication error');
    $count[$mon] = (int)$arr[0]["count"];
    $i++;
}

echo json_encode($count);

?>