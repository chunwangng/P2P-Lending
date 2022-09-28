<!DOCTYPE html>
<script type="text/javascript" src="./asset/js/process_supply_loan.js"></script>

</html>

<?php
require_once 'model/common.php';

$dao = new loanDAO();
$loanRecords = $dao->retrieveAllRepaymentLoan();
$dao2 = new repaymentrecordDAO();
$dao3 = new requestDAO();
$dao4 = new offerDAO();
$dao5 = new usersDAO();
$paymentList = [];

date_default_timezone_set("Asia/Singapore");
foreach ($loanRecords as $loanRecord) {

    $loanDate = $loanRecord->getDateLoan();
    $today = new DateTime('now');
    $today_obj = $today->format('Y-m-d');
    $dateObj = new DateTime($loanRecord->getDateLoan());

    //add every 30 days until it is more or equals to today
    while ($today > $dateObj) {
        $date_Obj = $dateObj->format('Y-m-d');
        if ($date_Obj == $today_obj) {
            //if today is 30 days interval add that loan in paymentlist
            array_push($paymentList, $loanRecord);
        }
        $loanDate = $dateObj->format('Y-m-d');
        $dateObj->add(new DateInterval('P30D'));
    }
}

//Those loan record needed to trigger auto payment
if ($paymentList != null) {
    foreach ($paymentList as $loanRecord) {
        $repaymentRecord = $dao2->retrieveAllRepaymentByBorrowerId($loanRecord->getBorrowerId(), $loanRecord->getId());
        $today = new DateTime('now');
        $request = $dao3->retrieveRequestInfo($loanRecord->getRequestId());
        $baseAutoRepaymentAmt = $request->getLoanAmount() / $request->getLoanTerm();
        $monthlyTotalAutoPaymentAmt = $baseAutoRepaymentAmt * ((100 + $request->getInterestRate() + 0.35) * 1 / 100);


        $borrower = $dao5->getUserDetails($loanRecord->getBorrowerId());
        $borrowerAccountId = $dao5->getBorrowerAccountId($loanRecord->getBorrowerId());

        $repaymentId = "8239";
        $amount = $monthlyTotalAutoPaymentAmt;

        //$borrowerAccount_UserId = 'NgChunWang';
        //$borrower_Phone = '6591148594';
        //$PIN = '578701';

        $borrowerAccount_UserId = $borrower->getUserid();
        $borrower_Phone = $borrower->getPhone();
        $borrower_PIN = $borrower->getPin();

        //need to call credit transfer api and sms api
        echo "<script type='text/javascript'>

            addBeneficiaryStatus = addBeneficiary('$borrowerAccount_UserId', '$borrower_PIN', $repaymentId);
            addDailyStatus = addDailyLimit('$borrowerAccount_UserId', '$borrower_PIN', $borrowerAccountId);
            creditTransferResult = creditTransfer($borrowerAccountId, $repaymentId, $amount, '$borrowerAccount_UserId', '$borrower_PIN');
            
            console.log('first creditransferresult is', creditTransferResult);
            if (creditTransferResult) {
                //smsStatus = sendSMS('$borrowerAccount_UserId', $borrower_PIN, $borrower_Phone);
                //console.log('sms sent?', smsStatus);
                
                sessionStorage.setItem('creditTransferStatus', true);
                
            } else {
                sessionStorage.setItem('creditTransferStatus', false);          
            }
        </script>";
        //Create New Payment Record
        $res = $dao2->addNewRepaymentRecord($loanRecord->getId(), $loanRecord->getBorrowerId(), $monthlyTotalAutoPaymentAmt);

        if ($repaymentRecord != null) {
            $remainLoanTerm = $request->getLoanTerm() - sizeof($repaymentRecord);
            //if this is the last term of loan, update status to CompleteRepayment
            if ($remainLoanTerm == 1) {
                $res2 = $dao->updateLoanStatusToCompleteRepaymentByRequestId($loanRecord->getId());
            }
        }
        //retrieve all lenders that offers the loan
        $offerList = $dao4->retrieveOfferByReqesutId($loanRecord->getRequestId());
        if ($offerList != null) {
            foreach ($offerList as $offer) {
                $loan = $dao->retrieveLoanByRequestId($offer->getRequestId());
                $request = $dao3->retrieveRequestInfo($offer->getRequestId());
                //from repayment account calculate all the interest * base loan amount
                $baseAutoRepaymentAmt = $request->getLoanAmount()/$request->getLoanTerm();
                $totalAutoPaymentToLenderAmt = $baseAutoRepaymentAmt * $loan->getInterestRate();

                $lender = $dao5->getUserDetails($offer->getLenderId());
                $lenderAccountId = $dao5->getLenderAccountId($offer->getLenderId());

                $repaymentId = "8239";
                $admin_userId = "S19234567F";
                $PIN = "000000";

                $amt = $totalAutoPaymentToLenderAmt;

                $lenderAccount_UserId = $lender->getUserid();
                $lender_Phone = $lender->getPhone();
                $lender_PIN = $lender->getPin();

                //need to call credit transfer api and sms api
                //send credit from repayment to lenders + sms
                echo "<script type='text/javascript'>
                addBeneficiaryStatus = addBeneficiary('$admin_userId', '$PIN', $lenderAccountId);
                addDailyStatus = addDailyLimit('$admin_userId', '$PIN', $repaymentId);
                creditTransferResult = creditTransfer($repaymentId,$lenderAccountId, $amt, '$admin_userId', '$PIN');
                
                console.log('first creditransferresult is', creditTransferResult);
                if (creditTransferResult) {
                    //smsStatus = sendSMS('$lenderAccountId', '$lender_PIN', $lender_Phone);
                    //console.log('sms sent?', smsStatus);

                    sessionStorage.setItem('creditTransferStatus', true);

                } else {
                    sessionStorage.setItem('creditTransferStatus', false);          
                }
                </script>";

                //send credit from repayment to platform account
                $platformAccountId = "8240";
                $repaymentId = "8239";
                $admin_userId = "S19234567F";
                $PIN = "000000";

                $servicechargeamount = $monthlyTotalAutoPaymentAmt - $totalAutoPaymentToLenderAmt;

                echo "<script type='text/javascript'>
                addBeneficiaryStatus = addBeneficiary('$admin_userId', '$PIN', $platformAccountId);
                addDailyStatus = addDailyLimit('$admin_userId', '$PIN', $repaymentId);
                creditTransferResult = creditTransfer($repaymentId,$platformAccountId, $servicechargeamount, '$admin_userId', '$PIN');

                console.log('first creditransferresult is', creditTransferResult);
                if (creditTransferResult) {
                    sessionStorage.setItem('creditTransferStatus', true);
                } else {
                    sessionStorage.setItem('creditTransferStatus', false);          
                }
                </script>";
            }
        }
    }
}


exit();
