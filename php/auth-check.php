<?php

session_start();

$_SESSION["userid"] = "1234";
$_SESSION["type"] = "user";

if (isset($_SESSION["userid"]) && ($_SESSION["type"] == $_GET["type"])) {
    header('HTTP/2 200 OK');
}
else {
    header('HTTP/2 401 Unauthorized');
}

?>