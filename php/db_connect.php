<?php

$username = "postgres";
$servername ="localhost";
$password = "anna1234";
$dbname = "supertrouper";

// Create connection
$db_connection = pg_connect("host=localhost dbname=supertrouper user=postgres password=anna1234");
// Check connection
if (!$db_connection) {
    echo "An error occurred.\n";
    exit;
}

$result = pg_query($db_connection, "SELECT * FROM users");
if (!$result) {
    echo "An error occurred.\n";
    exit;
}

while ($row = pg_fetch_row($result)) {
    echo "username: $row[0]  password: $row[1] user_type: $row[2]";
    echo "<br />\n";
}
?>