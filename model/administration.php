<?php

class administration {
    private $id;
    private $accountId;
    private $accountType;
    private $pin;
    private $userid;

    public function __construct($id, $accountId, $accountType, $pin, $userid) {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->accountType = $accountType;
        $this->pin = $pin;
        $this->userid = $userid;
    }

    public function getId(){
        return $this->id;
    }

    public function getAccountId() {
        return $this->accountId;
    }

    public function getAccountType() {
        return $this->accountType;
    }

    public function getPin(){
        return $this->pin;
    }

    public function getUserid(){
        return $this->userid;
    }

}
?>