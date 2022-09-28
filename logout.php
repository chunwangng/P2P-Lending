<?php

    // DO NOT CHANGE THE CODES

    session_start();
    $_SESSION = [];
    
    session_destroy();
 
    header("location:login.php");
    exit();
?>