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

}

?>