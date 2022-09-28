<?php

require_once 'common.php';

class usersDAO
{

  public function addUser($username, $password, $name, $acc_Id, $email, $phone, $dob, $occupation, $gender, $income, $emp_Length, $pin, $userid)
  {
    $dao = new usersDAO;
    $credit_Score = $dao->getCreditDetails($gender,$income,$emp_Length,$occupation);
    $interest_rate = $dao->getInterestRate($credit_Score);

    $conn_manager = new ConnectionManager();
    $pdo = $conn_manager->getConnection();
      
    $password = password_hash($password, PASSWORD_DEFAULT);

    //need to get these two values

      $sql = "insert into users (given_name, account_id, credit_score, email, phoneNo, DOB, occupation, gender, income, emp_length, username, pword, pin, userid, interest_rate) 
      values (:name, :accid, :credit_score, :email, :phone, :dob, :occupation, :gender, :income, :emp_length, :username, :pword, :pin, :userid, :interest_rate);";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(":name", $name);
      $stmt->bindParam(":accid", $acc_Id);
      $stmt->bindParam(":credit_score", $credit_Score);
      $stmt->bindParam(":email", $email);
      $stmt->bindParam(":phone", $phone);
      $stmt->bindParam(":dob", $dob);
      $stmt->bindParam(":occupation", $occupation);
      $stmt->bindParam(":gender", $gender);
      $stmt->bindParam(":income", $income);
      $stmt->bindParam(":emp_length", $emp_Length);
      $stmt->bindParam(":username", $username);
      $stmt->bindParam(":pword", $password);
      $stmt->bindParam(":pin", $pin);
      $stmt->bindParam(":userid", $userid);
      $stmt->bindParam(":interest_rate", $interest_rate);
      
      if ($stmt->execute()) {
          $stmt = null;
          $pdo = null;
          return true;
      }

      $stmt = null;
      $pdo = null;
      return false;
  }

  public function getRiskGrade($interest_rate) {
    $return = '';

    if ($interest_rate == 3.5) {
      $return = 'AA';
    }
    else if ($interest_rate == 3.8) {
      $return = 'A';
    }
    else if ($interest_rate == 4.2) {
      $return = 'BB';
    }
    else if ($interest_rate == 4.5) {
      $return = 'B';
    }
    else if ($interest_rate == 4.8) {
      $return = 'CC';
    }
    else if ($interest_rate == 5.2) {
      $return = 'C';
    }
    else {
      $return = 'Not Recommended';
    }
    return $return;
  }

  public function getInterestRate($score) {
    $return = 0;

    if ($score >= 900 && $score <= 950) {
      $return = 3.5;
    }
    else if ($score >= 850 && $score <= 899) {
      $return = 3.8;
    }
    else if ($score >= 800 && $score <= 849) {
      $return = 4.2;
    }
    else if ($score >= 750 && $score <= 799) {
      $return = 4.5;
    }
    else if ($score >= 700 && $score <= 749) {
      $return = 4.8;
    }
    else if ($score >= 650 && $score <= 699) {
      $return = 5.2;
    }
    else {
      $return = 0;
    }
    return $return;
  }

  public function getCreditDetails($gender, $income, $emp_length, $occupation){
    $total_credit = 0;

    $dao = new usersDAO;

    $total_credit += $dao->getScoreGender($gender);
    $total_credit += $dao->getScoreIncome($income);
    $total_credit += $dao->getScoreOcc($occupation);
    $total_credit += $dao->getScoreEmpLength($emp_length);

    return $total_credit;
  }

  public function getScoreGender($gender) {
    $score = 0;

    if ($gender == "Male") {
      $score = 130;
    }
    else {
      $score = 150;
    }
    return $score;
  }

  public function getScoreIncome($income) {
    $score = 0;

    if ($income <= 0) {
      $score = 50;
    }
    else if ($income < 50000){
      $score = 230;
    }
    else if ($income < 100000){
      $score = 290;
    }
    else if ($income < 200000){
      $score = 310;
    }
    else if ($income < 500000){
      $score = 330;
    }
    else {
      $score = 350;
    }
    return $score;
  }

  public function getScoreOcc($occupation) {
    $score = 0;

    if ($occupation == "Doctor") {
      $score = 250;
    }
    else if ($occupation == "Lawyer" || $occupation = "Manager" || $occupation == "Consultant" || $occupation = "Professor"){
      $score = 240;
    }
    else if ($occupation == "Accountant" || $occupation = "Developer"){
      $score = 230;
    }
    else if ($occupation == "AdministrativeExec" || $occupation = "Teacher" || $occupation == "Driver" || $occupation = "Lecturer"){
      $score = 210;
    }
    else if ($occupation == "Student" || $occupation = "Unemployed" || $occupation == "SelfEmployed"){
      $score = 210;
    }
    else {
      $score = 180;
    }
    return $score;
  }

  public function getScoreEmpLength($emp_length) {
    $score = 0;

    if ($emp_length < 1) {
      $score = 120;
    }
    else if ($emp_length < 4){
      $score = 160;
    }
    else if ($emp_length < 7){
      $score = 170;
    }
    else if ($emp_length < 10){
      $score = 180;
    }
    else {
      $score = 200;
    }
    return $score;
  }

  public function getUserId($username)
  {
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    $sql = "SELECT * FROM users where username = :username";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->execute();

    $obj = null;
    $usrId = "";

    while ($row = $stmt->fetch()) {
      $obj = new users($row['id'], $row['username'], $row['pword'], $row['given_name'], $row['account_id'], $row['credit_score'], $row['email'], $row['phoneNo'], $row['DOB'], $row['occupation'], $row['gender'], $row['income'], $row['emp_length'], $row['pin'], $row['userid'], $row['interest_rate']);
    }

    if (!empty($obj)) {
      $usrId = $obj->getId();
    }

    $stmt = null;
    $conn = null;

    return $usrId;
  }

  public function getPassword($id)
  {
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    $sql = "SELECT * FROM users where id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->execute();

    $obj = null;
    $usrId = "";

    while ($row = $stmt->fetch()) {
      $obj = new users($row['id'], $row['username'], $row['pword'], $row['given_name'], $row['account_id'], $row['credit_score'], $row['email'], $row['phoneNo'], $row['DOB'], $row['occupation'], $row['gender'], $row['income'], $row['emp_length'], $row['pin'], $row['userid'], $row['interest_rate']);
    }

    if (!empty($obj)) {
      $usrId = $obj->getPassword();
    }

    $stmt = null;
    $conn = null;

    return $usrId;
  }

  public function login($username, $password)
  {
    $isValid = False;
    $dao = new usersDAO();
    $uId = $dao->getUserId($username);
    
    if ($uId !== "") {
      $hPass = $dao->getPassword($uId);
    }

    if ($hPass !== "") {
      if (password_verify($password, $hPass)) {
        $isValid = True;
      }
    }
    
    return $isValid;
  }

  public function getBorrowerAccountId($uid)
  {
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    $sql = "select * from users where id =:id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $uid);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $accountID = "";
    if ($row = $stmt->fetch()) {
      $accountID = $row['account_id'];
    }

    $stmt = null;
    $conn = null;
    return $accountID;
  }

  public function getLenderAccountId($uid)
  {
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    $sql = "select * from users where id =:id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $uid);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $accountID = "";
    if ($row = $stmt->fetch()) {
      $accountID = $row['account_id'];
    }

    $stmt = null;
    $conn = null;
    return $accountID;
  }

  public function getLenderUserID($uid)
  {
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    $sql = "select * from users where id =:id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $uid);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $userid = "";
    if ($row = $stmt->fetch()) {
      $userid = $row['userid'];
    }

    $stmt = null;
    $conn = null;
    return $userid;
  }

  public function getLenderPIN($uid)
  {
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    $sql = "select * from users where id =:id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $uid);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $pin = "";
    if ($row = $stmt->fetch()) {
      $pin = $row['pin'];
    }

    $stmt = null;
    $conn = null;
    return $pin;
  }

  public function getUserDetails($uid)
  {
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();
    $sql = "select * from users where id =:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $uid);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $user = "";
    if ($row = $stmt->fetch()) {
      $user = new users($row['id'],$row['username'],$row['pword'],$row['given_name'],$row['account_id'],$row['credit_score'],$row['email'],$row['phoneNo'],$row['DOB'],$row['occupation'],$row['gender'],$row['income'],$row['emp_length'], $row['pin'], $row['userid'], $row['interest_rate']);
    }
    $stmt = null;
    $conn = null;
    return $user;
  }

  #get lender's detail by user id
  public function getLenderDetailsById($uid)
  {
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();
    $sql = "select * from users where id =:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $uid);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $user = "";
    if ($row = $stmt->fetch()) {
      $user = new users($row['id'],$row['username'],$row['pword'],$row['given_name'],$row['account_id'],$row['credit_score'],$row['email'],$row['phoneNo'],$row['DOB'],$row['occupation'],$row['gender'],$row['income'],$row['emp_length'], $row['pin'], $row['userid'], $row['interest_rate']);
    }
    $stmt = null;
    $conn = null;
    return $user;
  }

  # Get borrower's interest rate
  public function retrieveInterestRate($uid)
  {
    $connMgr = new ConnectionManager();
    $conn = $connMgr->getConnection();

    $sql = "select * from users where id =:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $uid);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $interest_rate = "";
    if ($row = $stmt->fetch()) {
      $interest_rate = $row['interest_rate'];
    }
    $stmt = null;
    $conn = null;
    return $interest_rate;
  }
}

?>


