<?php
require_once 'common.php';

class requestDAO
{
    // Add a new request record
    public function addNewRequest($loan_title, $amount, $currency, $loan_purpose, $loan_term, $uid, $interest_rate)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "insert into request (borrower_id, loan_amount, loan_purpose, loan_term, loan_title, currency, interest_rate) values (:userid, :amount, :purpose, :term, :title, :currency, :interest_rate);";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":userid", $uid);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":purpose", $loan_purpose);
        $stmt->bindParam(":term", $loan_term);
        $stmt->bindParam(":title", $loan_title);
        $stmt->bindParam(":currency", $currency);
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

    // Retrieve all requests 
    public function retrieveAllRequests()
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "select * from request;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $requests = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $requests[] = new request($row["id"], $row["borrower_id"], $row["loan_amount"], $row["loan_purpose"], $row["loan_term"], $row["loan_title"], $row["currency"], $row["interest_rate"]);
        }

        $stmt = null;
        $pdo = null;
        return $requests;
    }

    // Retrieve a specific request
    public function retrieveRequestInfo($requestId)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "select * from request where id =:requestId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":requestId", $requestId);
        $stmt->execute();

        $request = null;
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if ($row = $stmt->fetch()) {
            $request = new request($row["id"], $row["borrower_id"], $row["loan_amount"], $row["loan_purpose"], $row["loan_term"], $row["loan_title"], $row["currency"], $row["interest_rate"]);
        }

        $stmt = null;
        $pdo = null;
        return $request;
    }

    // Retrieve a requests by borrower Id
    public function retrieveRequestInfoByBorrowerId($borrowerId)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "select * from request where borrower_id =:borrower_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":borrower_id", $borrowerId);
        $stmt->execute();

        $requests = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $requests[] = new request($row["id"], $row["borrower_id"], $row["loan_amount"], $row["loan_purpose"], $row["loan_term"], $row["loan_title"], $row["currency"], $row["interest_rate"]);
        } 
        
        $stmt = null;
        $pdo = null;
        return $requests;
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

    public function getMaturityDate($startDate, $loan_term){

        if ($loan_term == 12){
            $maturityDate = date('Y-m-d', strtotime($startDate. ' + 1 year'));
        }
        else if ($loan_term == 24){
            $maturityDate = date('Y-m-d', strtotime($startDate. ' + 1 year'));
            $maturityDate = date('Y-m-d', strtotime($maturityDate. ' + 1 year'));
        }
        else {
            $maturityDate = date('Y-m-d', strtotime($startDate. ' + 1 year'));
            $maturityDate = date('Y-m-d', strtotime($maturityDate. ' + 1 year'));
            $maturityDate = date('Y-m-d', strtotime($maturityDate. ' + 1 year'));
        }
        return $maturityDate;
    }
    
}
