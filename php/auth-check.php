<?php

function authCheck($type){

    session_start();

    if (isset($_SESSION["userid"])) {            // possibly logged in
        if (isset($_SESSION["type"])){           // probably logged in 
            if ($_SESSION["type"] == $type) {    // everything alright
                // there is no need to explicitly set this header,
                // but we do it for readability
                header('HTTP/2 200 OK');
            }
            else {                               // unauthorised access
                header("Location: /unauthorised401.html");
            }
        }
        else {                                   // something is wrong
            // in case session has somehow been corrupted
            session_unset();
            session_destroy();
            header("Location: /login.php");
        }
    }
    else {                                       // not logged in
        // in case session has somehow been corrupted
        session_unset();
        session_destroy();
        header("Location: /login.php");
    }

}

?>