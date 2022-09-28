<?php
require_once 'common.php';

class offerDAO
{
    // Add a new offer record
    public function addNewOffer($requestId, $borrowerId, $uid, $amount)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();
          
        $sql = "insert into offer (request_Id, borrower_id, lender_id, offer_amt) values (:requestId, :borrowerId, :userid, :amount);";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":userid", $uid);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":requestId", $requestId);
        $stmt->bindParam(":borrowerId", $borrowerId);
        if ($stmt->execute()) {
            $stmt = null;
            $pdo = null;
            return true;
        }

        $stmt = null;
        $pdo = null;
        return false;
    }

    // Check the total offered amount for a request
    public function retrieveTotalOfferedAmount($requestId)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();
          
        $sql = "select * from offer where request_Id =:requestId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":requestId", $requestId);
        $stmt->execute();

        $amount = 0;
        while ($row = $stmt->fetch()) {
            $amount += $row["offer_amt"];
        }
            
        $stmt = null;
        $pdo = null;
        return $amount;
    }

    // Retrieve offers by lender id
    public function retrieveOfferInfoByLenderId($uid)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();
          
        $sql = "select * from offer where lender_id =:lender_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":lender_id", $uid);
        $stmt->execute();

        $offers = [];
        while ($row = $stmt->fetch()) {
            $offers[] = new offer($row["id"], $row["request_Id"], $row["borrower_id"], $row["lender_id"], $row["offer_amt"]);
        }
            
        $stmt = null;
        $pdo = null;
        return $offers;
    }

    // Check the total offered amount for a request
    public function retrieveOfferByReqesutId($requestId)
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();

        $sql = "select * from offer where request_Id =:requestId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":requestId", $requestId);
        $stmt->execute();
        $offers = [];
        while ($row = $stmt->fetch()) {
            $offers[] = new offer($row["id"], $row["request_Id"], $row["borrower_id"], $row["lender_id"], $row["offer_amt"]);
        }

        $stmt = null;
        $pdo = null;
        return $offers;
    }
}
?>