<?php

session_start();

// $_SESSION["userId"] = "1234";
// $_SESSION["type"] = "user";

session_unset();
session_destroy();

if (isset($_SESSION["userId"]) && ($_SESSION["type"] == $_GET["type"])) {
    header('HTTP/2 200 OK');
}
else {
    header('HTTP/2 401 Unauthorized');
}

?>