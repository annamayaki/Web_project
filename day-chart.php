<?php

$username = "postgres";
$servername ="localhost";
$password = "anna1234";
$dbname = "supertrouper";

// Create connection
$db_connection = pg_connect("host=localhost dbname=supertrouper user=postgres password=anna1234");
// Check connection
if (!$db_connection) {
    echo "A connection error occurred.\n";
    exit;
}

$prep_stmt = "SELECT count(*) FROM events WHERE EXTRACT(DOW FROM timestampms) = $1";
$days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

$result = pg_prepare($db_connection, "day_query", $prep_stmt);
if (!$result) {
    echo "Houston, we have a problem...\n";
}

$i = 0;
$count = array();
foreach ($days as $day){
    $result = pg_execute($db_connection, "day_query", array($i));
    $arr = pg_fetch_all($result);
    if ($arr) {
        $count[$day] = (int)$arr[0]["count"];
    }
    else {
        echo "Houston, we have a problem...\n";
    }
    $i++;
}

echo json_encode($count);

?>