<?php
    require_once "model/common.php";

    if (!isset($_POST["submit"])) {

        $name = $_POST["givenName"];
        $username = $_POST["userName"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $gender = $_POST["gender"];
        $occupation = $_POST["occupation"];
        $dob = $_POST["dob"];
        $phone = $_POST["phone"];
        $income = $_POST["income"];
        $emp_Length = $_POST["emplength"];
        $userid = $_POST["userid"];
        $pin = $_POST["pin"];
        $acc_Id = $_POST["accid"];

        $dao = new usersDAO();
        $status = $dao->addUser($username, $password, $name, $acc_Id, $email, $phone, $dob, $occupation, $gender, $income, $emp_Length, $pin, $userid);

        
        if ($status) {
            $_SESSION["status"] = true;
            header("Location: login.php");
            exit();
        } else {
            $_SESSION["status"] = false;
            header("Location: register.php");
        }
        
    }

   


    //$result = $dao->login($userName, $password);
        
    //if ($result) {

        //header('Location: login.php');
        //exit();
    //} else {

        //header('Location: register.php');
       // exit();
    //}
?>