<?php

require_once 'db_connect.php';

$conn = dbConnect();

$stmt = "SELECT username, count(*) as entries FROM events ".
    "INNER JOIN users ON events.userid = users.userid GROUP BY username";
$result = pg_query($conn, $stmt) or die('communication error');

$user_entries = array();
while ($row = pg_fetch_row($result)) {
    $user_entries[] = array($row[0], (int) $row[1]);
}

echo json_encode($user_entries);

?>