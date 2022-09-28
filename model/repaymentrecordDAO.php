<?php
require_once 'common.php';

class repaymentrecordDAO
{
    // Add a new repayment record
    public function addNewRepaymentRecord($loanId, $borrowerId, $paymentAmt)
    {
        date_default_timezone_set('Asia/Singapore');
        $paymentDate = date("Y-m-d");
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "insert into repaymentrecord (loan_id,borrower_id,payment_amt, payment_date) values (:loanId,:borrowerId,:paymentAmt,:paymentDate);";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":loanId", $loanId);
        $stmt->bindParam(":borrowerId", $borrowerId);
        $stmt->bindParam(":paymentAmt", $paymentAmt);
        $stmt->bindParam(":paymentDate", $paymentDate);

        if ($stmt->execute()) {
            $stmt = null;
            $pdo = null;
            return true;
        }

        $stmt = null;
        $pdo = null;
        return false;
    }

    // Retrieve All Repayment Record By Borrower ID
    public function retrieveAllRepaymentByBorrowerId($borrowerId, $loanId)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "select * from repaymentrecord where borrower_id = :borrowerId and loan_id = :loanId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":borrowerId", $borrowerId);
        $stmt->bindParam(":loanId", $loanId);
        $stmt->execute();

        $paymentrecords = [];
        while ($row = $stmt->fetch()) {
            $paymentrecords[] = new repaymentrecord($row["id"], $row["loan_id"], $row["borrower_id"], $row["payment_amt"], $row["payment_date"]);
        }
        $stmt = null;
        $pdo = null;
        return $paymentrecords;
    }

    
}
