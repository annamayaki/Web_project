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

$result = pg_query($db_connection, "DELETE FROM events");
if ($result){
    echo "Deleted, probably";
}
else {
    echo "Houston, we have a problem...\n";
}


?>