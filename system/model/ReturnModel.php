<?php

class ReturnModel {
    
    private $dbConn;    //database connection variable
    
    // tables to access
    const TABLE = "returns";
    const MAC_TABLE = "returns_macadresse";
    
     // query to run, can include binded variables
    const SELECT_QUERY = "SELECT returnID, customerNr, products.productName, products.macAdresse, DATE_FORMAT(returns.date,'%d %b %Y') AS date, comment, storage.storageName, quantity, returns.deletedStorage, returns.deletedProduct FROM " . ReturnModel::TABLE . 
            " LEFT JOIN products ON returns.productID = products.productID LEFT JOIN storage ON returns.storageID = storage.storageID";
    const SELECT_MY_RETURNS = "SELECT returnID, customerNr, products.productName, products.macAdresse, DATE_FORMAT(returns.date,'%d %b %Y') AS date, comment, storage.storageName, quantity, returns.deletedStorage, returns.deletedProduct FROM " . ReturnModel::TABLE . 
            " LEFT JOIN products ON returns.productID = products.productID LEFT JOIN storage ON returns.storageID = storage.storageID WHERE userID = :givenUserID AND customerNr LIKE :givenProductSearchWord OR userID = :givenUserID AND comment LIKE "
            . ":givenProductSearchWord OR userID = :givenUserID AND productName LIKE :givenProductSearchWord OR userID = :givenUserID AND storageName LIKE :givenProductSearchWord ORDER BY returnID DESC";
    const INSERT_QUERY = "INSERT INTO " . ReturnModel::TABLE . " (productID, date, customerNr, comment, userID, storageID, quantity) VALUES (:givenProductID, NOW(), :givenCustomerNumber, :givenComment, :givenUserID, :givenStorageID, :givenQuantity)";
    const SELECT_FROM_ID = "SELECT * FROM " . ReturnModel::TABLE . " WHERE returnID = :givenReturnID";
    const UPDATE_QUERY = "UPDATE " . ReturnModel::TABLE . " SET customerNr = :editCustomerNr, comment = :editComment  WHERE returnID = :editReturnID" ;
    const INSERT_RETURN_MAC = "INSERT INTO " . ReturnModel::MAC_TABLE . " (returnID, macAdresse) VALUES (:givenReturnID, :givenMacAdresse)";
    const SELECT_RETURN_MAC = "SELECT * FROM " . ReturnModel::MAC_TABLE . " WHERE returnID = :givenReturnsID";
    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;  // connect to database
      // prepare the statements
      $this->selMyReturns = $this->dbConn->prepare(ReturnModel::SELECT_MY_RETURNS);
      $this->addStmt = $this->dbConn->prepare(ReturnModel::INSERT_QUERY);
      $this->selFromID = $this->dbConn->prepare(ReturnModel::SELECT_FROM_ID);
      $this->editStmt = $this->dbConn->prepare(ReturnModel::UPDATE_QUERY);  
      $this->selStmt = $this->dbConn->prepare(ReturnModel::SELECT_QUERY);
      $this->addReturnMac = $this->dbConn->prepare(ReturnModel::INSERT_RETURN_MAC);
      $this->getReturnMac = $this->dbConn->prepare(ReturnModel::SELECT_RETURN_MAC);
      }
    
     /**
     * Get all registered returns in database
     */ 
    public function getAllReturnInfo() {
        $this->selStmt->execute();  // execute SQL statement
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * Get all returns from search
     */ 
    public function getMyReturns($givenUserID, $givenProductSearchWord){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selMyReturns->execute(array("givenUserID" =>  $givenUserID, "givenProductSearchWord" => $givenProductSearchWord));
        return $this->selMyReturns->fetchAll(PDO::FETCH_ASSOC);     // return fetched result
    }
    
    /**
     * add new return to database
     */ 
    public function newReturn($givenStorageID, $givenCustomerNumber, $givenProductID, $givenQuantity, $givenUserID, $givenComment) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->addStmt->execute(array("givenStorageID" =>  $givenStorageID, "givenCustomerNumber" => $givenCustomerNumber, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity, "givenUserID" => $givenUserID, "givenComment" => $givenComment));
        return  $this->dbConn->lastInsertId();
    }
    
    /**
     * Get return information from returnID
     */ 
    public function getReturnFromID($givenReturnID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selFromID->execute(array("givenReturnID" =>  $givenReturnID)); 
        return $this->selFromID->fetchAll(PDO::FETCH_ASSOC);    // return fetched result
    }
    
    /**
     * Edit a existing return
     */ 
    public function editMyReturn($editReturnID, $editCustomerNr, $editComment) {
        //bind variable to the parameter as strings, and execute SQL statement
       return $this->editStmt->execute(array("editReturnID" =>  $editReturnID, "editCustomerNr" => $editCustomerNr, "editComment" => $editComment)); 
    }
    
    /**
     * Get others users returns
     */ 
    public function getSelectedUserReturns($usernameArray){
        // check if array contains value
       if(empty(!$usernameArray)){
        $userID = implode(',', array_fill(0, count($usernameArray), '?'));  // create a '?' for each value
        $usernameQuery = "userID IN ($userID)"; // create part of query for binded value
        } else {$usernameQuery = "";}   // adding this query if array is empty
        
        // query to run
        $sql = "SELECT returnID, customerNr, products.productName, products.macAdresse, DATE_FORMAT(returns.date,'%d %b %Y') AS date, comment, storage.storageName, quantity, returns.deletedStorage, returns.deletedProduct FROM " . ReturnModel::TABLE . 
            " LEFT JOIN products ON returns.productID = products.productID LEFT JOIN storage ON returns.storageID = storage.storageID WHERE $usernameQuery ORDER BY date DESC";
    
        $this->selUserReturn = $this->dbConn->prepare($sql);    // prepare the statement
        
        $this->selUserReturn->execute($usernameArray);  // execute SQL statement

        return $this->selUserReturn->fetchALL(PDO::FETCH_ASSOC);    // return fetched result
    }
    
    /**
     * Add mac adresse from a new return
     */ 
    public function addReturnMac($returnID, $macAdresse){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addReturnMac->execute(array("givenReturnID" =>  $returnID, "givenMacAdresse" => $macAdresse)); 
    }
    
    /**
     * Get mac adresse from a given return
     */ 
    public function getMacFromReturnID($givenReturnsID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->getReturnMac->execute(array("givenReturnsID" =>  $givenReturnsID));
        return $this->getReturnMac->fetchAll(PDO::FETCH_ASSOC);     // return fetched result
    }
}

