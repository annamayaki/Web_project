<?php

function authCheck($type){

    session_start();

    if (isset($_SESSION["userid"])) {            // possibly logged in
        if (isset($_SESSION["type"])){           // probably logged in 
            if ($_SESSION["type"] == $type) {    // everything alright
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
            header("Location: /login.html");
        }
    }
    else {                                       // not logged in
        // in case session has somehow been corrupted
        session_unset();
        session_destroy();
        header("Location: /login.html");
    }

}

?>