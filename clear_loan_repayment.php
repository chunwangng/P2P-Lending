<?php
    require_once "model/common.php";
    require_once "protect.php";

    $requestId = $_POST['reqId'];
    $loanId = $_POST['loanId'];
    $paymentAmt = $_POST['paymentAmt'];
    $borrowerId = $_POST['borrowerId'];

    $dao = new loanDAO();
    $res = $dao->updateLoanStatusToCompleteRepaymentByRequestId($requestId);

    $dao2 = new repaymentrecordDAO();
    $res2 = $dao2->addNewRepaymentRecord($loanId,$borrowerId,$paymentAmt);

    session_start();
    
    $_SESSION['res'] = $res;
    $_SESSION['res2'] = $res2;
    $_SESSION['loanId'] = $loanId;
    $_SESSION['borrowerId'] = $borrowerId;
    $_SESSION['paymentAmt'] = $paymentAmt;
    $_SESSION['reqId'] = $requestId;
    
    header('Location: repayment_confirmation.php');
    exit();
?>