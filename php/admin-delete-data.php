<?php

require_once 'db_connect.php';
$conn = dbConnect();

$result = pg_query($conn, "DELETE FROM events") or die('communication error');
echo "Done";

?>