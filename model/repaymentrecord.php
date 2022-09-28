<?php

class repaymentrecord {
    private $id;
    private $loanId;
    private $borrowerId;
    private $paymentAmt;
    private $paymentDate;
    
    public function __construct($id, $loanId ,$borrowerId, $paymentAmt, $paymentDate) {
        $this->id = $id;
        $this->loanId = $loanId;
        $this->borrowerId = $borrowerId;
        $this->paymentAmt = $paymentAmt;
        $this->paymentDate = $paymentDate;
    }

    public function getId(){
        return $this->id;
    }
    
    public function getLoanId() {
        return $this->loanId;
    }

    public function getBorrowerId() {
        return $this->borrowerId;
    }

    public function getPaymentAmt() {
        return $this->paymentAmt;
    }
    public function getPaymentDate(){
        return $this->paymentDate;
    }
}
?>