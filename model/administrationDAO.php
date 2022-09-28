<?php
require_once 'common.php';

class administrationDAO
{
    // Add a new loan record
    public function retrieveDisbursementAccount()
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();
          
        $sql = "select * from administration where AccountType = 'Disbursement';";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(); 
        
        $id = "";
        if ($row = $stmt->fetch()) {
            $id = $row["AccountID"];
        }

        $stmt = null;
        $pdo = null;
        return $id;
    }

    public function retrievePlatformAccount()
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();
          
        $sql = "select * from administration where AccountType = 'Platform Fees';";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(); 
        
        $id = "";
        if ($row = $stmt->fetch()) {
            $id = $row["AccountID"];
        }

        $stmt = null;
        $pdo = null;
        return $id;
    }

    public function getPlatformAccount()
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();
          
        $sql = "select * from administration where AccountType = 'Platform Fees';";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(); 
        
        $platformAccount = null;
        if ($row = $stmt->fetch()) {
            $platformAccount = new administration($row["id"], $row["AccountID"], $row["AccountType"], $row["pin"], $row["userid"]);
        }

        $stmt = null;
        $pdo = null;
        return $platformAccount;
    }

    public function getDisbursementAccount()
    {
        $conn_manager = new ConnectionManager();
        $pdo = $conn_manager->getConnection();
          
        $sql = "select * from administration where AccountType = 'Disbursement';";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(); 
        
        $platformAccount = null;
        if ($row = $stmt->fetch()) {
            $platformAccount = new administration($row["id"], $row["AccountID"], $row["AccountType"], $row["pin"], $row["userid"]);
        }

        $stmt = null;
        $pdo = null;
        return $platformAccount;
    }
}
?>