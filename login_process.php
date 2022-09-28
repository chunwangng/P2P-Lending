<?php
    require_once "model/common.php";

    if (isset($_POST['inputUsername'])) {
        $inputUsername = $_POST['inputUsername'];
    }

    if (isset($_POST['inputPassword'])) {
        $inputPassword = $_POST['inputPassword'];
    }

    $dao = new usersDAO();

    $result = $dao->login($inputUsername, $inputPassword);
    
    if ($result) {

        $usr = $dao->getUserId($inputUsername);
        $_SESSION['userid'] = $usr;
        $_SESSION['username'] = $inputUsername;

        header('Location: home.php');
        exit();
    } else {

        header('Location: login.php');
        exit();
    }
?>