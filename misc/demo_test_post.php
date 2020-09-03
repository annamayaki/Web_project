<?php

// ************* Read as JSON *********************

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

echo "Hello JSON!\n";
var_dump($_POST);
// $sth = $_POST["0"]["timestamp"];
// echo "\n$sth\n";

foreach ($_POST as $row){
    $sth = $row["timestamp"];
    echo "$sth\n";
}

// ************************************************

// $rest_json = fopen("php://input", 'r');
// echo "Stream works!";
// parse & send to db

?>