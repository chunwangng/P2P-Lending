
<?php

require_once 'model/common.php';


// No session variable "user" => no login
if (!isset($_SESSION["userid"])) {
    // redirect to login page
    header("Location: login.php");
    exit();
}
?>
