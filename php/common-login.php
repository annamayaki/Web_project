<?php

session_start();

require_once 'db_connect.php';
$conn = dbConnect();

// Encryption & hashing
$cipher = "aes-128-cbc";
// Silence empty IV warning
$ciphertext = @openssl_encrypt($_POST["email"], $cipher, $_POST["password"]);

$prep_stmt = "SELECT password, user_type FROM users WHERE userid=$1";
$result = pg_prepare($conn, "login_query", $prep_stmt) or die('communication error');

$result = pg_execute($conn, "login_query", array($ciphertext)) or die('communication error');
$arr = pg_fetch_all($result);

// unique usernames => {1 row fetched => success, 0 rows fetched => wrong input}
if (!$arr){
    header('HTTP/2 401 Unauthorized');
}
else if (password_verify($_POST["password"], $arr[0]["password"])) {
    header('HTTP/2 200 OK');
    if ($arr[0]["user_type"] == "user"){
        echo "user";
        $_SESSION["userid"] = $ciphertext;
        $_SESSION["type"] = "user";
    }
    else if ($arr[0]["user_type"] == "admin"){
        echo "admin";
        $_SESSION["userid"] = $ciphertext;
        $_SESSION["type"] = "admin";
    }
}
else {
    header('HTTP/2 401 Unauthorized');
}

?>