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

$prep_stmt = "SELECT count(*) FROM events WHERE EXTRACT(HOUR FROM timestampms) = $1";
$hours = array('0', '1', '2', '3',
'4', '5', '6', '7', '8', '9', '10', '11', '12', '13',
'14', '15', '16', '17', '18', '19', '20', '21', '22', '23');

$result = pg_prepare($db_connection, "hour_query", $prep_stmt);
if (!$result) {
    echo "Houston, we have a problem...\n";
}

$i = 0;
$count = array();
foreach ($hours as $hr){
    $result = pg_execute($db_connection, "hour_query", array($i));
    $arr = pg_fetch_all($result);
    if ($arr) {
        $count[$hr] = (int)$arr[0]["count"];
    }
    else {
        echo "Houston, we have a problem...\n";
    }
    $i++;
}

echo json_encode($count);

?>