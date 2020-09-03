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

$prep_stmt = "SELECT count(*) FROM events WHERE EXTRACT(MONTH FROM timestampms) = $1";
$months = array('Jan', 'Feb', 'Mar', 'Apr',
'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

$result = pg_prepare($db_connection, "month_query", $prep_stmt);
if (!$result) {
    echo "Houston, we have a problem...\n";
}

$i = 1;
$count = array();
foreach ($months as $mon){
    $result = pg_execute($db_connection, "month_query", array($i));
    $arr = pg_fetch_all($result);
    if ($arr) {
        $count[$mon] = (int)$arr[0]["count"];
    }
    else {
        echo "Houston, we have a problem...\n";
    }
    $i++;
}

echo json_encode($count);

?>