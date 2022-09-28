<?php

class loan {
    private $id;
    private $lenderId;
    private $requestId;
    private $borrowerId;
    private $dateLoan;
    private $interestRate;
    private $status;

    public function __construct($id, $requestId ,$lenderId, $borrowerId, $dateLoan, $interestRate, $status) {
        $this->id = $id;
        $this->requestId = $requestId;
        $this->lenderId = $lenderId;
        $this->borrowerId = $borrowerId;
        $this->dateLoan = $dateLoan;
        $this->interestRate = $interestRate;
        $this->status = $status;
    }

    public function getId(){
        return $this->id;
    }
    
    public function getLenderId() {
        return $this->lenderId;
    }
    
    public function getRequestId(){
        return $this->requestId;
    }

    public function getBorrowerId() {
        return $this->borrowerId;
    }

    public function getDateLoan() {
        return $this->dateLoan;
    }

    public function getInterestRate() {
        return $this->interestRate;
    }

    public function getStatus() {
        return $this->status;
    }
}
?>