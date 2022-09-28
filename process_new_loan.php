<?php
    require_once "model/common.php";
    require_once "protect.php";

    if (isset($_POST["submit"])) {
        $loan_title = $_POST["loan_title"];
        $amount = $_POST["amount"];
        $currency = $_POST["currency"];
        $loan_term = $_POST["loan_term"];
        $loan_purpose = $_POST["loan_purpose"];
        $uid = $_SESSION["userid"];

        $dao2 = new usersDAO();
        $userinfo = $dao2->getUserDetails($uid);
        $interest_rate = $userinfo->getInterestRate();

        $dao = new requestDAO();
        $status = $dao->addNewRequest($loan_title, $amount, $currency, $loan_purpose, $loan_term, $uid, $interest_rate);

        if ($status) {
            $_SESSION["status"] = true;
        } else {
            $_SESSION["status"] = false;
        }

        header("Location: request_confirmation.php");
        exit();
    }
?>