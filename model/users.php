<?php

class users {
    private $id;
    private $username;
    private $password;
    private $name;
    private $acc_Id;
    private $credit_Score;
    private $email;
    private $phone;
    private $dob;
    private $occupation;
    private $gender;
    private $income;
    private $emp_Length;
    private $pin;
    private $userid;
    private $interest_rate;

    public function __construct($id, $username, $password, $name, $acc_Id, $credit_Score, $email, $phone, $dob, $occupation, $gender, $income, $emp_Length, $pin, $userid, $interest_rate) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->acc_Id = $acc_Id;
        $this->credit_Score = $credit_Score;
        $this->email = $email;
        $this->phone = $phone;
        $this->dob = $dob;
        $this->occupation = $occupation;
        $this->gender = $gender;
        $this->income = $income;
        $this->emp_Length = $emp_Length;
        $this->pin = $pin;
        $this->userid = $userid;
        $this->interest_rate = $interest_rate;
    }

    public function getId(){
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getName(){
        return $this->name;
    }

    public function getAccId() {
        return $this->acc_Id;
    }

    public function getCreditScore() {
        return $this->credit_Score;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }
    
    public function getDOB() {
        return $this->dob;
    }

    public function getOccupation() {
        return $this->occupation;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getIncome() {
        return $this->income;
    }
    
    public function getEmpLength() {
        return $this->emp_Length;
    }

    public function getPin() {
        return $this->pin;
    }

    public function getUserid() {
        return $this->userid;
    }

    public function getInterestRate() {
        return $this->interest_rate;
    }
}
?>