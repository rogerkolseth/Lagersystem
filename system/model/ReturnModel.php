<?php

class ReturnModel {
    
    private $dbConn;
    
    const TABLE = "returns";
    const SELECT_QUERY = "SELECT returnID, customerNr, products.productName, DATE_FORMAT(returns.date,'%d %b %Y') AS date, comment, storage.storageName, quantity, returns.deletedStorage, returns.deletedProduct FROM " . ReturnModel::TABLE . 
            " LEFT JOIN products ON returns.productID = products.productID LEFT JOIN storage ON returns.storageID = storage.storageID";
    const SELECT_MY_RETURNS = "SELECT returnID, customerNr, products.productName, DATE_FORMAT(returns.date,'%d %b %Y') AS date, comment, storage.storageName, quantity, returns.deletedStorage, returns.deletedProduct FROM " . ReturnModel::TABLE . 
            " LEFT JOIN products ON returns.productID = products.productID LEFT JOIN storage ON returns.storageID = storage.storageID WHERE userID = :givenUserID AND customerNr LIKE :givenProductSearchWord OR userID = :givenUserID AND comment LIKE "
            . ":givenProductSearchWord OR userID = :givenUserID AND productName LIKE :givenProductSearchWord OR userID = :givenUserID AND storageName LIKE :givenProductSearchWord ORDER BY date DESC";
    const INSERT_QUERY = "INSERT INTO " . ReturnModel::TABLE . " (productID, date, customerNr, comment, userID, storageID, quantity) VALUES (:givenProductID, :givenDate, :givenCustomerNumber, :givenComment, :givenUserID, :givenStorageID, :givenQuantity)";
    const SELECT_FROM_ID = "SELECT * FROM " . ReturnModel::TABLE . " WHERE returnID = :givenReturnID";
    const UPDATE_QUERY = "UPDATE " . ReturnModel::TABLE . " SET customerNr = :editCustomerNr, comment = :editComment  WHERE returnID = :editReturnID" ;
   
    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;
      $this->selStmt = $this->dbConn->prepare(ReturnModel::SELECT_MY_RETURNS);
      $this->addStmt = $this->dbConn->prepare(ReturnModel::INSERT_QUERY);
      $this->selFromID = $this->dbConn->prepare(ReturnModel::SELECT_FROM_ID);
      $this->editStmt = $this->dbConn->prepare(ReturnModel::UPDATE_QUERY);  
      $this->selStmt = $this->dbConn->prepare(ReturnModel::SELECT_QUERY);
    }
    
    public function getAllReturnInfo() {
        $this->selStmt->execute();
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getMyReturns($givenUserID, $givenProductSearchWord){
        $this->selStmt->execute(array("givenUserID" =>  $givenUserID, "givenProductSearchWord" => $givenProductSearchWord));
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function newReturn($givenStorageID, $givenCustomerNumber, $givenProductID, $givenQuantity, $givenUserID, $givenComment, $givenDate) {
        return $this->addStmt->execute(array("givenStorageID" =>  $givenStorageID, "givenCustomerNumber" => $givenCustomerNumber, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity, "givenUserID" => $givenUserID, "givenComment" => $givenComment, "givenDate" => $givenDate));
    }
    
    public function getReturnFromID($givenReturnID){
        $this->selFromID->execute(array("givenReturnID" =>  $givenReturnID)); 
        return $this->selFromID->fetchAll(PDO::FETCH_ASSOC);  
    }
    
    public function editMyReturn($editReturnID, $editCustomerNr, $editComment) {
       return $this->editStmt->execute(array("editReturnID" =>  $editReturnID, "editCustomerNr" => $editCustomerNr, "editComment" => $editComment)); 
    }
    
    public function getSelectedUserReturns($usernameArray){
       if(empty(!$usernameArray)){
        $userID = implode(',', array_fill(0, count($usernameArray), '?'));
        $usernameQuery = "userID IN ($userID)";
        } else {$usernameQuery = "";}
        
        
        $sql = "SELECT returnID, customerNr, products.productName, DATE_FORMAT(returns.date,'%d %b %Y') AS date, comment, storage.storageName, quantity, returns.deletedStorage, returns.deletedProduct FROM " . ReturnModel::TABLE . 
            " LEFT JOIN products ON returns.productID = products.productID LEFT JOIN storage ON returns.storageID = storage.storageID WHERE $usernameQuery ORDER BY date DESC";
    
        $this->selUserReturn = $this->dbConn->prepare($sql);
        
        $this->selUserReturn->execute($usernameArray);

        return $this->selUserReturn->fetchALL(PDO::FETCH_ASSOC);  
    }
}

