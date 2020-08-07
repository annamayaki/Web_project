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

$prep_stmt = "SELECT user_type From users WHERE 
    username=$1 AND password=$2";
$result = pg_prepare($db_connection, "login_query", $prep_stmt);
if (! $result) {
    echo "Houston, we have a problem...";
}

$result = pg_execute($db_connection, "login_query", array($_POST["username"], $_POST["password"]));
$arr = pg_fetch_all($result);


// unique usernames => {1 row fetched => success, 0 rows fetched => wrong input}
if (!$arr){
    echo "Λανθασμένα στοιχεία. Προσπαθήστε ξανά.";
}
else if ($arr[0]["user_type"] == "user"){
    echo "Redirect to user profile";
}
else if ($arr[0]["user_type"] == "admin"){
    echo "Redirect to admin dashboard";
}

?>