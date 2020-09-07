<?php

require_once 'db_connect.php';

$conn = dbConnect();

// dummy post data
$user_info = array("firstname"=>"Ιωάννης", "lastname"=>"Μηλόσπορος",
"username"=>"jAppleseed", "email"=>"jAppleseed@icloud.com", "password"=>"@ppleSeed2");
$_POST = $user_info;

// check if username is taken??

// Encryption & hashing
$cipher = "aes-128-cbc";
$ciphertext = openssl_encrypt($_POST["email"], $cipher, $_POST["password"]);
$original_plaintext = openssl_decrypt($ciphertext, $cipher, $_POST["password"]);
$hash = password_hash($_POST["password"], PASSWORD_BCRYPT);

$prep_stmt = "INSERT INTO users 
    (userId, username, firstname, lastname, email, password, user_type)
    VALUES ($1, $2, $3, $4, $5, $6, $7)";
$result = pg_prepare($conn, "user_signup_query", $prep_stmt) or die('communication error');
$arr = array($ciphertext, $_POST["username"], $_POST["firstname"], $_POST["lastname"], 
    $_POST["email"], $hash, "user");
$result = pg_execute($conn, "user_signup_query", $arr);

// echo "Registered Users:\n";
// $result = pg_query($conn, "SELECT * FROM users") or die('communication error');
// while ($row = pg_fetch_row($result)) {
//     print_r($row);
// }

// ++ return OK

?>
