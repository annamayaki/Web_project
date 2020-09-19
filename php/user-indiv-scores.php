<?php

session_start();

require_once 'db_connect.php';

$conn = dbConnect();

$userid = $_SESSION["userid"];
$months = array();
$years = array();
$gr_months = array('Ιαν', 'Φεβ', 'Μαρ', 'Απρ',
'Μαι', 'Ιουν', 'Ιουλ', 'Αυγ', 'Σεπ', 'Οκτ', 'Νοε', 'Δεκ');
$labels = array();

$j = 0;
for ($i = 11; $i >= 0; $i--) {
    $months[] = date("n", strtotime("-$i months"));
    $years[] = date("Y", strtotime("-$i months"));
    $labels[] = $gr_months[(int)$months[$j] - 1]." ".$years[$j];
    $j++;
}
$scores = array();

$stmt = "SELECT count(*) AS score FROM events WHERE userid = $1 AND".
    " EXTRACT(MONTH FROM timestampunix) = $2 AND EXTRACT(YEAR FROM timestampunix) = $3".
    " AND (activity_type = 'ON_BICYCLE' OR activity_type = 'ON_FOOT' OR".
    " activity_type = 'RUNNING')";
$result = pg_prepare($conn, "indiv_score_count_query", $stmt) or die('communication error');

for ($i = 0; $i < 12; $i++) {
    $result = pg_execute($conn, "indiv_score_count_query", 
    array($userid, $months[$i], $years[$i]));
    $arr = pg_fetch_all($result);
    if ($arr) {
        $scores[] = (int)$arr[0]["score"];
    }
    else {
        $scores[] = 0;
    }
}

// Build query string
$stmt = "SELECT count(*) AS score FROM events WHERE userid = $1 AND".
    " EXTRACT(MONTH FROM timestampunix) = $2 AND EXTRACT(YEAR FROM timestampunix) = $3";

$result = pg_prepare($conn, "indiv_score_denom_query", $stmt) or die('communication error');

for ($i = 0; $i < 12; $i++) {
    $result = pg_execute($conn, "indiv_score_denom_query", 
        array($userid, $months[$i], $years[$i]));
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

header('Content-Type: text/html; charset=utf-8');
echo json_encode(array($labels, $scores));

?>