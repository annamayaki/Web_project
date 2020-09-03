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

$prep_stmt = "SELECT count(*) FROM events WHERE EXTRACT(YEAR FROM timestampms) = $1";
$years = array('2019', '2020');

$result = pg_prepare($db_connection, "year_query", $prep_stmt);
if (!$result) {
    echo "Houston, we have a problem...\n";
}

$i = 0;
$count = array();
foreach ($years as $yr){
    $result = pg_execute($db_connection, "year_query", array($years[$i]));
    $arr = pg_fetch_all($result);
    if ($arr) {
        $count[$yr] = (int)$arr[0]["count"];
    }
    else {
        echo "Houston, we have a problem...\n";
    }
    $i++;
}

echo json_encode($count);

?>