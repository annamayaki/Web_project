<?php

session_start();

require_once 'db_connect.php';

// dummy
$_SESSION["userId"] = "yHHFxIjbHDoylTLjMym6PA==";
// $_GET["yearRange"] = "single";
// $_GET["startYear"] = 2019;
// $_GET["monthRange"] = "multiple";
// $_GET["startMonth"] = 1;
// $_GET["endMonth"] = 12;

$conn = dbConnect();

$months = array();
$years = array();

for ($i = 11; $i >= 0; $i--) {
    $months[] = date("n", strtotime("-$i months"));
    $years[] = date("Y", strtotime("-$i months"));
}
$scores = array();

// $stmt = "SELECT count(*) FROM events WHERE userId LIKE $1 AND".
$stmt = "SELECT count(*) AS score FROM events WHERE username LIKE $1 AND".
    " EXTRACT(MONTH FROM timestampms) = $2 AND EXTRACT(YEAR FROM timestampms) = $3".
    " AND (activity_type = 'ON_BICYCLE' OR activity_type = 'ON_FOOT' OR".
    " activity_type = 'RUNNING')";
$result = pg_prepare($conn, "indiv_score_count_query", $stmt) or die('communication error');

for ($i = 0; $i < 12; $i++) {
    $result = pg_execute($conn, "indiv_score_count_query", 
        // array($_SESSION["userId"], 
        array("anna",
        $months[$i], $years[$i]));
    $arr = pg_fetch_all($result);
    if ($arr) {
        $scores[] = (int)$arr[0]["score"];
    }
    else {
        $scores[] = 0;
    }
}

// Build query string
// $stmt = "SELECT count(*) FROM events WHERE userId LIKE $1 AND".
$stmt = "SELECT count(*) AS score FROM events WHERE username LIKE $1 AND".
    " EXTRACT(MONTH FROM timestampms) = $2 AND EXTRACT(YEAR FROM timestampms) = $3";

$result = pg_prepare($conn, "indiv_score_denom_query", $stmt) or die('communication error');

for ($i = 0; $i < 12; $i++) {
    $result = pg_execute($conn, "indiv_score_denom_query", 
        // array($_SESSION["userId"], 
        array("anna",
        $months[$i], $years[$i]));
    $arr = pg_fetch_all($result);
    if ($arr) {
        $denominator = (int)$arr[0]["score"];
        if ($denominator > 0){
            $scores[$i] /= $denominator;
            $scores[$i] = round($scores[$i] * 100);
        }
        else {
            $scores[$i] = 0;
        }
    }
}

// header('Content-Type: text/html; charset=utf-8');
echo json_encode($scores);



// for ($i = 0; $i < 12; $i++) {
//     print $months[$i]." ".$years[$i]."\n";
// }

?>