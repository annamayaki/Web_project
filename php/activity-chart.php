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

// $prep_stmt = "SELECT count(*) FROM events WHERE 
//     username=$1 AND activity_type LIKE $2";
$prep_stmt = "SELECT count(*) FROM events WHERE activity_type LIKE $1";
$activity_types = array('IN_VEHICLE', 'ON_BICYCLE', 'ON_FOOT', 'RUNNING',
'STILL', 'TILTING', 'UNKNOWN');

$result = pg_prepare($db_connection, "activity_query", $prep_stmt);
if (!$result) {
    echo "Houston, we have a problem...\n";
}

$count_types = array();
foreach ($activity_types as $type){
    $result = pg_execute($db_connection, "activity_query", 
        // array($_POST["username"], $type));
        array($type));
    $arr = pg_fetch_all($result);
    if ($arr) {
        $count_types[$type] = (int)$arr[0]["count"];
    }
    else {
        echo "Houston, we have a problem...2";
    }
}

// $prep_stmt = "SELECT count(*) FROM events WHERE 
//     username=$1 AND activity_type IS NULL";
// $result = pg_prepare($db_connection, "activity_null_query", $prep_stmt);
// if (!$result) {
//     echo "Houston, we have a problem...";
// }
// $result = pg_execute($db_connection, "activity_null_query", 
//         // array($_POST["username"]));
//         array("anna"));
// $arr = pg_fetch_all($result);
// if ($arr){
//     $count_types['NULL'] = $arr[0]["count"];
// }
// else {
//     echo "Houston, we have a problem...";
// }

echo json_encode($count_types);

?>