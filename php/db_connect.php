<?php


function dbConnect(){
    $user = "postgres";
    $host ="localhost";
    $port = 5432;
    $dbname = "supertrouper";
    $password = "anna1234";
    $conn_string = "host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password;

    $db_connection = pg_connect($conn_string) or die('connection failed');
    return $db_connection;

    // $result = pg_query($db_connection, "SELECT * FROM users");
    // if (!$result) {
    //     echo "An error occurred.\n";
    //     exit;
    // }

    // while ($row = pg_fetch_row($result)) {
    //     echo "username: $row[0]  password: $row[1] user_type: $row[2]\n";
    // }

}

?>