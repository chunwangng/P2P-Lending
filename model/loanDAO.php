<?php
require_once 'common.php';

class loanDAO
{
    // Add a new loan record
    public function addNewLoan($requestId, $borrowerId, $uid, $amount, $interestrate)
    {
        date_default_timezone_set('Asia/Singapore');
        $today = date("Y-m-d H:i:s");
        $status = "Repayment";
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "insert into loan (request_id, lender_id, borrower_id, date_of_loan, status, interest_rate) values (:requestId, :userid, :borrowerId, :date, :status, :interestrate);";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":userid", $uid);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":requestId", $requestId);
        $stmt->bindParam(":borrowerId", $borrowerId);
        $stmt->bindParam(":date", $today);
        $stmt->bindParam(":interestrate", $interestrate);
        if ($stmt->execute()) {
            $stmt = null;
            $pdo = null;
            return true;
        }

        $stmt = null;
        $pdo = null;
        return false;
    }

    // Add a new loan record
    public function retrieveAllRequestIds()
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "select request_id from loan;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $requestIds = [];
        while ($row = $stmt->fetch()) {
            $requestIds[] = $row["request_id"];
        }

        $stmt = null;
        $pdo = null;
        return $requestIds;
    }

    // Retrieve loan record by user id
    public function retrieveAllLoanById($uid)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "select * from loan where borrower_id = :uid and status='repayment';";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":uid", $uid);
        $stmt->execute();

        $loanRecords = [];
        while ($row = $stmt->fetch()) {
            $loanRecords[] = new loan($row["id"],$row["request_id"],$row["lender_id"], $row["borrower_id"], $row["date_of_loan"], $row["interest_rate"], $row["status"]);
        }

        $stmt = null;
        $pdo = null;
        return $loanRecords;
    }

    // Retrieve loan record by request id
    public function retrieveLoanByRequestId($requestId)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();
        $sql = "select * from loan where request_id = :requestId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":requestId", $requestId);
        $stmt->execute();
        $loanRecord = null;
        while ($row = $stmt->fetch()) {
            $loanRecord = new loan($row["id"],$row["request_id"],$row["lender_id"], $row["borrower_id"], $row["date_of_loan"], $row["interest_rate"], $row["status"]);
        }
        $stmt = null;
        $pdo = null;
        return $loanRecord;
    }

     // Update loan record status
    public function updateLoanStatusToCompleteRepaymentByRequestId($requestId)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();
        $sql = "update loan set status = 'CompleteRepayment' where request_id = :requestId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":requestId", $requestId);
        $res = $stmt->execute();
        return $res;
    }

    public function retrieveAll($uid)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "select * from loan where lender_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":uid", $uid);
        $stmt->execute();

        $loanRecords = [];
        while ($row = $stmt->fetch()) {
            $loanRecords[] = new loan($row["id"],$row["request_id"],$row["lender_id"], $row["borrower_id"], $row["date_of_loan"], $row["interest_rate"], $row["status"]);
        }

        $stmt = null;
        $pdo = null;
        return $loanRecords;
    }

    public function retrieveAllRepaymentLoan()
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "select * from loan where status = 'Repayment' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $loanRecords = [];
        while ($row = $stmt->fetch()) {
            $loanRecords[] = new loan($row["id"],$row["request_id"],$row["lender_id"], $row["borrower_id"], $row["date_of_loan"], $row["interest_rate"], $row["status"]);
        }

        $stmt = null;
        $pdo = null;
        return $loanRecords;
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

}
