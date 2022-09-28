<!DOCTYPE html>
<script type="text/javascript" src="./asset/js/process_supply_loan.js"></script>

</html>
<?php
    require_once "model/common.php";
    require_once "protect.php";

    if (isset($_POST["submit"])) {

        //Get details
        $amount = $_POST["amount"];
        $choice = $_POST["radio"];
        $requestId = $_POST["requestid"];
        $borrowerId = $_POST["borrowerid"];
        $loan_amount = $_POST["loan_amount"];
        $act_loan_amount = $_POST["act_loan_amount"];
        $interestRate = $_POST["interest_rate"];
        $uid = $_SESSION["userid"];

        if ($choice == "partial") {
            $dao = new offerDAO();
            $status = $dao->addNewOffer($requestId, $borrowerId, $uid, $amount);
        
            $dao2 = new administrationDAO();
            $disbursement = $dao2->getDisbursementAccount();
            $platform = $dao2->getPlatformAccount();
            $disbursementId = $disbursement->getAccountId();

            $platformPin = $platform->getPin();
            $platformUserid = $platform->getUserid();
            $platformId = $platform->getAccountId();

            $dao3 = new usersDAO();
            $lenderAccountId = $dao3->getLenderAccountId($uid);
            $lenderAccountId_UserId = $dao3->getLenderUserID($uid);
            $lenderAccountId_PIN = $dao3->getLenderPIN($uid);

            $f = $amount * 0.002;
            $tamount = $amount + $f;

            if ($status) {
                echo "<script type='text/javascript'>
                        addBeneficiaryStatus = addBeneficiary('$lenderAccountId_UserId', '$lenderAccountId_PIN', $disbursementId);
                        addDailyStatus = addDailyLimit('$lenderAccountId_UserId', '$lenderAccountId_PIN', $lenderAccountId);
                        creditTransferResult = creditTransfer($lenderAccountId, $disbursementId, $tamount, '$lenderAccountId_UserId', '$lenderAccountId_PIN');
                        
                        console.log('first creditransferresult is', creditTransferResult);
                        console.log('first addBeneficiaryStatus is', addBeneficiaryStatus);
                        console.log('first addDailyStatus is', addDailyStatus);

                        if (creditTransferResult) {
                            //smsStatus = sendSMS('S9711111A', '111111', '6597591930');
                            //console.log('sms sent?', smsStatus);
                            
                            sessionStorage.setItem('creditTransferStatus', true);
                            //window.location.href = 'supply_confirmation.php';
                        } else {
                            sessionStorage.setItem('creditTransferStatus', false);
                            window.location.href = 'supply_confirmation.php';
                        }
                    </script>";
            } else {
                $_SESSION["creditTransferStatus"] = [false, "Failed! Please try again."];
                header("Location: supply_confirmation.php");
                exit();
            }

            $total_offered = $dao->retrieveTotalOfferedAmount($requestId);
            if ($total_offered == (int)$act_loan_amount) {

                $ooff= $dao->retrieveOfferByReqesutId($requestId);
                var_dump($ooff);
                $dao4 = new loanDAO();
                foreach ($ooff as $o) {
                    $dao4->addNewLoan($requestId, $borrowerId, $o->getLenderId(), $amount, $interestRate);
                }
                //$status2 = $dao4->addNewLoan($requestId, $borrowerId, $uid, $amount, $interestRate);
                $status2 = true;

                //$fee = $total_offered * 0.004;
                $borrowerFee = $act_loan_amount * 0.002;
                $remaining = $act_loan_amount - $borrowerFee;

                $platFee = $total_offered * 0.004;

                $borrowerAccountId = $dao3->getBorrowerAccountId($borrowerId);
                $borrower = $dao3->getLenderDetailsById($borrowerId);
                $borrowerUserid = $borrower->getUserid();
                $borrowerPin = $borrower->getPin();
                $borrowerAccId = $borrower->getAccId();
                $borrowerPhone = $borrower->getPhone();

                if ($status2) {

                    echo "<script type='text/javascript'>
                        addBeneficiaryStatus = addBeneficiary('$platformUserid', '$platformPin', $borrowerAccountId);
                        addDailyStatus = addDailyLimit('$platformUserid', '$platformPin', $disbursementId);
                        creditTransferResult = creditTransfer($disbursementId, $borrowerAccountId, $remaining, '$platformUserid', '$platformPin');
                        creditTransferResult2 = creditTransfer($disbursementId, $platformId, $platFee, '$platformUserid', '$platformPin');

                        if (creditTransferResult) {
                            //smsStatus = sendSMS('$borrowerUserid', '$borrowerPin', $borrowerPhone);
                            //console.log('sms sent?', smsStatus);            
                            
                            sessionStorage.setItem('creditTransferStatus', true);
                            window.location.href = 'supply_confirmation.php';
                        }
                        else {
                            sessionStorage.setItem('creditTransferStatus', false);
                            window.location.href = 'supply_confirmation.php';
                        }
                    </script>";

                } else {
                    $_SESSION["creditTransferStatus"] = [false, "Failed! Please try again."];
                    header("Location: supply_confirmation.php");
                    exit();
                }
            } else {
                echo "<script>window.location.href = 'supply_confirmation.php';</script>";
                // header("Location: supply_confirmation.php");
                // exit(); 
            }
        } else {
    
            $dao2 = new administrationDAO();
            $disbursement = $dao2->getDisbursementAccount();
            $platform = $dao2->getPlatformAccount();

            $dao3 = new usersDAO();
            $borrower = $dao3->getLenderDetailsById($borrowerId);
            $lender = $dao3->getLenderDetailsById($uid);
            $loan_amount = strval($loan_amount);

            $lenderUserid = $lender->getUserid();
            $lenderPin = $lender->getPin();
            $lenderAccId = $lender->getAccId();
            $borrowerUserid = $borrower->getUserid();
            $borrowerPin = $borrower->getPin();
            $borrowerAccId = $borrower->getAccId();
            $borrowerPhone = $borrower->getPhone();
            $disbursementPin = $disbursement->getPin();
            $disbursementUserid = $disbursement->getUserid();
            $disbursementId = $disbursement->getAccountId();
            $platformPin = $platform->getPin();
            $platformUserid = $platform->getUserid();
            $platformId = $platform->getAccountId();

            $lenderFee = $loan_amount * 0.002;
            $borrowerFee = $act_loan_amount * 0.002;
            $remaining = $act_loan_amount - $borrowerFee;

            $lenderLoan = $loan_amount + $lenderFee;
            $platFee = $lenderFee + $borrowerFee;

            echo "<script type='text/javascript'>
                    addBeneficiaryStatus = addBeneficiary('$lenderUserid', '$lenderPin', $disbursementId);
                    addDailyStatus = addDailyLimit('$lenderUserid', '$lenderPin', $lenderAccId);
                    creditTransferResult = creditTransfer($lenderAccId, $disbursementId, $lenderLoan, '$lenderUserid', '$lenderPin');
                    
                    console.log('first creditransferresult is', creditTransferResult);
                    console.log('first addBeneficiaryStatus is', addBeneficiaryStatus);
                    console.log('first addDailyStatus is', addDailyStatus);

                    if (creditTransferResult) {
                        addBeneficiaryStatus2 = addBeneficiary('$platformUserid', '$platformPin', $borrowerAccId);
                        addDailyStatus2 = addDailyLimit('$platformUserid', '$platformPin', $disbursementId);
                        creditTransferResult2 = creditTransfer($disbursementId, $borrowerAccId, $remaining, '$disbursementUserid', '$disbursementPin');
                        creditTransferResult3 = creditTransfer($disbursementId, $platformId, $platFee, '$disbursementUserid', '$disbursementPin');

                        console.log('second creditransferresult is', creditTransferResult2);
                        console.log('second addBeneficiaryStatus is', addBeneficiaryStatus2);
                        console.log('second addDailyStatus is', addDailyStatus2);

                        if (!creditTransferResult2 || !creditTransferResult3) {
                            sessionStorage.setItem('creditTransferStatus', false);
                            console.log('FAIL!');
                            window.location.href = 'supply_confirmation.php';
                        }
                    }
                    
                    smsStatus = sendSMS('$borrowerUserid', '$borrowerPin', $borrowerPhone);
                    console.log('sms sent?', smsStatus);                    
                </script>";


            $dao = new loanDAO();
            $status = $dao->addNewLoan($requestId, $borrowerId, $uid, $loan_amount, $interestRate);

            if ($status) {
                echo "<script type='text/javascript'>
                        sessionStorage.setItem('creditTransferStatus', true);
                        console.log('PASS!');
                        window.location.href = 'supply_confirmation.php';
                </script>";
            } else {
                echo "<script type='text/javascript'>
                        sessionStorage.setItem('creditTransferStatus', false);
                        console.log('FAIL!');
                        window.location.href = 'supply_confirmation.php';
                </script>";;
            }
        }
    }
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<script>

</script>