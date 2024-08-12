<?php
    session_start();

    //checking if session id exists
    if(isset($_SESSION['id'])){
        session_unset();
        session_destroy();
        header("Location: ./index.php?action=logged_out");
    }
    else{
        //redirect to home if no session id was found
        header("Location: ./index.php");
    }
?>