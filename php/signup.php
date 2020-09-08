<?php

session_start();

if (isset($_SESSION["userId"])) {
    echo "Session set";
    return;
}

require_once 'db_connect.php';
$conn = dbConnect();

// dummy post data
$user_info = array("firstname"=>"Ιωάννης", "lastname"=>"Μηλόσπορος",
"username"=>"jAppleseed", "email"=>"jAppleseed@icloud.com", "password"=>"@ppleSeed2");
$_POST = $user_info;

// Encryption & hashing
$cipher = "aes-128-cbc";
$ciphertext = openssl_encrypt($_POST["email"], $cipher, $_POST["password"]);
$hash = password_hash($_POST["password"], PASSWORD_BCRYPT);

$prep_stmt = "SELECT user_type FROM users WHERE 
    userId=$1 AND password=$2";
$result = pg_prepare($conn, "id_exists_query", $prep_stmt) or die('communication error');
$result = pg_execute($conn, "id_exists_query", array($ciphertext, $hash)) or die('communication error');
$arr = pg_fetch_all($result);

// unique usernames => {1 row fetched => ID already exists, 0 rows fetched => success }
if (!$arr){
    $prep_stmt = "INSERT INTO users 
        (userId, username, firstname, lastname, email, password, user_type)
        VALUES ($1, $2, $3, $4, $5, $6, $7)";
    $result = pg_prepare($conn, "user_signup_query", $prep_stmt) or die('communication error');
    $arr = array($ciphertext, $_POST["username"], $_POST["firstname"], $_POST["lastname"], 
        $_POST["email"], $hash, "user");
    $result = pg_execute($conn, "user_signup_query", $arr) or die('communication error');
    $_SESSION["userId"] = $ciphertext;
    $_SESSION["type"] = "user";
    echo "OK redirect";
    // echo "Registered Users:\n";
    // $result = pg_query($conn, "SELECT * FROM users") or die('communication error');
    // while ($row = pg_fetch_row($result)) {
    //     print_r($row);
    // }
}
else {
    echo "ID taken";
}

?>
