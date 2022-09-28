<?php

class offer {
    private $id;
    private $requestId;
    private $borrowerId;
    private $lenderId;
    private $offerAmount;

    public function __construct($id, $requestId, $borrowerId, $lenderId, $offerAmount) {
        $this->id = $id;
        $this->requestId = $requestId;
        $this->borrowerId = $borrowerId;
        $this->lenderId = $lenderId;
        $this->offerAmount = $offerAmount;
    }

    public function getId(){
        return $this->id;
    }

    public function getRequestId() {
        return $this->requestId;
    }

    public function getBorrowerId() {
        return $this->borrowerId;
    }

    public function getLenderId() {
        return $this->lenderId;
    }

    public function getOfferAmount() {
        return $this->offerAmount;
    }

}
?>