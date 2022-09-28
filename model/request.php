<?php

class request {
    private $id;
    private $borrowerId;
    private $loanAmount;
    private $loanPurpose;
    private $loanTerm;
    private $loanTitle;
    private $currency;
    private $interest_rate;

    public function __construct($id, $borrowerId, $loanAmount, $loanPurpose, $loanTerm, $loanTitle, $currency, $interest_rate) {
        $this->id = $id;
        $this->borrowerId = $borrowerId;
        $this->loanAmount = $loanAmount;
        $this->loanPurpose = $loanPurpose;
        $this->loanTerm = $loanTerm;
        $this->loanTitle = $loanTitle;
        $this->currency = $currency;
        $this->interest_rate = $interest_rate;
    }

    public function getId(){
        return $this->id;
    }

    public function getBorrowerId() {
        return $this->borrowerId;
    }

    public function getLoanAmount() {
        return $this->loanAmount;
    }

    public function getLoanPurpose() {
        return $this->loanPurpose;
    }

    public function getLoanTerm() {
        return $this->loanTerm;
    }

    public function getLoanTitle() {
        return $this->loanTitle;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function getInterestRate() {
        return $this->interest_rate;
    }
}
?>