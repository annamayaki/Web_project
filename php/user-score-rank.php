<?php

session_start();

require_once 'db_connect.php';

$conn = dbConnect();

$userid = $_SESSION["userid"];

$names = array();
$scores = array();

$stmt = "SELECT calc_eco_score_rank() as ranks";
$result = pg_query($conn, $stmt) or die('communication error');
while ($row = pg_fetch_row($result)) {
    // result is one string
    $row[0] = trim($row[0], "()");   // strip parentheses
    $arr = explode(",", $row[0]);    // split values separated by commas
    $score_val = (float) $arr[2];           // score is float val
    $scores[] = round($score_val * 100);    // score as percentage
    $names[] = $arr[0]." ".mb_substr($arr[1], 0, 1).".";  // merge first, last names
}

$stmt = "SELECT count(*) AS score FROM events WHERE userid = $1 AND".
    " age(now(), timestampunix) < interval '1 month' AND (activity_type = 'ON_BICYCLE'".
    " OR activity_type = 'ON_FOOT' OR activity_type = 'RUNNING')";
$result = pg_prepare($conn, "indiv_score_count_query", $stmt) or die('communication error');
$result = pg_execute($conn, "indiv_score_count_query", array($userid));
$arr = pg_fetch_row($result) or die('communication error');
$score_val = (float) $arr[0];


// Build query string
$stmt = "SELECT count(*) AS score FROM events WHERE userid = $1 AND".
    " age(now(), timestampunix) < interval '1 month'";
$result = pg_prepare($conn, "indiv_score_denom_query", $stmt) or die('communication error');
$result = pg_execute($conn, "indiv_score_denom_query", array($userid));
$arr = pg_fetch_row($result) or die('communication error');
$denominator = (float) $arr[0];
if ($denominator > 0) {
    $score_val /= $denominator;
    $scores[] = round($score_val * 100);
}

// Build query string
$stmt = "SELECT firstname, lastname FROM users WHERE userid = $1";
$result = pg_prepare($conn, "indiv_name_query", $stmt) or die('communication error');
$result = pg_execute($conn, "indiv_name_query", array($userid));
$arr = pg_fetch_row($result) or die('communication error');
$names[] = $arr[0]." ".mb_substr($arr[1], 0, 1).".";

header('Content-Type: text/html; charset=utf-8');
echo json_encode(array($names, $scores));

?>