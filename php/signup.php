<?php

session_start();

// if (isset($_SESSION["userId"])) {
//     echo "Session set";
//     return;
// }

require_once 'db_connect.php';
$conn = dbConnect();

// $user_info = array("firstname"=>"Άννα", "lastname"=>"Μαγιάκη",
// "username"=>"anna", "email"=>"anna@me.com", "password"=>"anna1234");
// $_POST = $user_info;

// $user_info = array("firstname"=>"Κλέλια", "lastname"=>"Λ",
// "username"=>"klelia", "email"=>"klelia@icloud.com", "password"=>"klelia1998");
// $_POST = $user_info;

// Encryption & hashing
$cipher = "aes-128-cbc";
$ciphertext = openssl_encrypt($_POST["email"], $cipher, $_POST["password"]);
$hash = password_hash($_POST["password"], PASSWORD_BCRYPT);

$prep_stmt = "SELECT user_type FROM users WHERE userid = $1";
$result = pg_prepare($conn, "id_exists_query", $prep_stmt) or die('communication error');
$result = pg_execute($conn, "id_exists_query", array($ciphertext)) or die('communication error');
$arr = pg_fetch_all($result);

// unique usernames => {1 row fetched => ID already exists, 0 rows fetched => success }
if (!$arr){
    $prep_stmt = "INSERT INTO users VALUES ($1, $2, $3, $4, $5, $6)";
    $result = pg_prepare($conn, "user_signup_query", $prep_stmt) or die('communication error');
    $arr = array($ciphertext, $_POST["firstname"], $_POST["lastname"], $_POST["username"], 
        $_POST["email"], $hash);
    $result = pg_execute($conn, "user_signup_query", $arr) or die('communication error');
    $_SESSION["userId"] = $ciphertext;
    $_SESSION["type"] = "user";
    echo "OK redirect";
}
else {
    echo "ID taken";
}

?>
