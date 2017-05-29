<?php

class SaleModel {
    
    private $dbConn;     //database connection variable
    
    // tables to access
    const TABLE = "sales";
    const MAC_TABEL = "sales_macadresse";
    
    // query to run, can include binded variables
    const SELECT_QUERY = "SELECT salesID, customerNr, products.productName, products.macAdresse, DATE_FORMAT(sales.date,'%d %b %Y') AS date, comment, storage.storageName, quantity, sales.deletedStorage, sales.deletedProduct  FROM " . SaleModel::TABLE . 
            " LEFT JOIN products ON sales.productID = products.productID LEFT JOIN storage ON sales.storageID = storage.storageID";
    const SELECT_MY_SALES = "SELECT salesID, customerNr, products.productName, products.macAdresse, DATE_FORMAT(sales.date,'%d %b %Y') AS date, comment, storage.storageName, quantity, sales.deletedStorage, sales.deletedProduct FROM " . SaleModel::TABLE . 
            " LEFT JOIN products ON sales.productID = products.productID LEFT JOIN storage ON sales.storageID = storage.storageID WHERE userID = :givenUserID AND customerNr LIKE :givenProductSearchWord OR userID = :givenUserID AND comment LIKE "
            . ":givenProductSearchWord OR userID = :givenUserID AND productName LIKE :givenProductSearchWord OR userID = :givenUserID AND storageName LIKE :givenProductSearchWord ORDER BY salesID DESC";
    const SELECT_STORAGE = "SELECT * FROM " . SaleModel::TABLE . " WHERE storageID = :givenStorageID";
    const UPDATE_QUERY = "UPDATE " . SaleModel::TABLE . " SET customerNr = :editCustomerNr, comment = :editComment  WHERE salesID = :editSaleID" ;
    const INSERT_QUERY = "INSERT INTO " . SaleModel::TABLE . " (productID, date, customerNr, comment, userID, storageID, quantity) VALUES (:givenProductID, NOW(), :givenCustomerNumber, :givenComment, :givenUserID, :givenStorageID, :givenQuantity)";
    const SELECT_FROM_ID = "SELECT * FROM " . SaleModel::TABLE . " WHERE salesID = :givenSalesID";
    const SELECT_LAST_QUERY =  "SELECT salesID, customerNr, products.productName, DATE_FORMAT(sales.date,'%d %b %Y') AS date, comment, storage.storageName, quantity FROM " . SaleModel::TABLE . 
            " INNER JOIN products ON sales.productID = products.productID INNER JOIN storage ON sales.storageID = storage.storageID WHERE userID = :givenUserID ORDER BY salesID DESC LIMIT 10";
    const SELECT_ALL_LAST_QUERY =  "SELECT salesID, customerNr, products.productName, DATE_FORMAT(sales.date,'%d %b %Y') AS date, users.username, comment, storage.storageName, quantity FROM " . SaleModel::TABLE . 
            " INNER JOIN products ON sales.productID = products.productID INNER JOIN storage ON sales.storageID = storage.storageID INNER JOIN users ON sales.userID = users.userID ORDER BY salesID DESC LIMIT 10";
    const INSERT_SALE_MAC = "INSERT INTO " . SaleModel::MAC_TABEL . " (salesID, macAdresse) VALUES (:givenSalesID, :givenMacAdresse)";
    const SELECT_SALE_MAC = "SELECT * FROM " . SaleModel::MAC_TABEL . " WHERE salesID = :givenSalesID";

    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;  // connect to database
      // prepare the statements
      $this->editStmt = $this->dbConn->prepare(SaleModel::UPDATE_QUERY);  
      $this->addStmt = $this->dbConn->prepare(SaleModel::INSERT_QUERY);
      $this->selStmt = $this->dbConn->prepare(SaleModel::SELECT_QUERY);
      $this->selStorage = $this->dbConn->prepare(SaleModel::SELECT_STORAGE);
      $this->mySales = $this->dbConn->prepare(SaleModel::SELECT_MY_SALES);
      $this->selFromID = $this->dbConn->prepare(SaleModel::SELECT_FROM_ID);
      $this->selLast = $this->dbConn->prepare(SaleModel::SELECT_LAST_QUERY);
      $this->selAllLast = $this->dbConn->prepare(SaleModel::SELECT_ALL_LAST_QUERY);
      $this->addSaleMac = $this->dbConn->prepare(SaleModel::INSERT_SALE_MAC);
      $this->getSaleMac = $this->dbConn->prepare(SaleModel::SELECT_SALE_MAC);
    }
    
    /**
     * Get 10 last sales from database
     */
    public function getAllLastSaleInfo() {
        $this->selAllLast->execute();   // execute SQL statement
        return $this->selAllLast->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
    /**
     * Get given users last 10 sales
     */
    public function getLastSaleInfo($givenUserID) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selLast->execute(array("givenUserID" =>  $givenUserID));
        return $this->selLast->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }

    /**
     * Get all sales registered in database
     */
    public function getAllSaleInfo() {
        $this->selStmt->execute();  // execute SQL statement
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * Get sales from a given given storage
     */
    public function getSaleFromStorageID($givenStorageID){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->selStorage->execute(array("givenStorageID" =>  $givenStorageID)); 
    }
    
    /**
     * Update an existing sale
     */
    public function editMySale($editSaleID, $editCustomerNr, $editComment) {
        //bind variable to the parameter as strings, and execute SQL statement
       return $this->editStmt->execute(array("editSaleID" =>  $editSaleID, "editCustomerNr" => $editCustomerNr, "editComment" => $editComment)); 
    }
    
    /**
     * Add new sale to databasae
     */
    public function newSale($givenStorageID, $givenCustomerNumber, $givenProductID, $givenQuantity, $givenUserID, $givenComment) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->addStmt->execute(array("givenStorageID" =>  $givenStorageID, "givenCustomerNumber" => $givenCustomerNumber, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity, "givenUserID" => $givenUserID, "givenComment" => $givenComment));
        return  $this->dbConn->lastInsertId(); // get id of last inserted row
    }
    
    /**
     * Get all sales from search and given userID
     */
    public function getMySales($givenUserID, $givenProductSearchWord){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->mySales->execute(array("givenUserID" =>  $givenUserID, "givenProductSearchWord" => $givenProductSearchWord));
        return $this->mySales->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * Get sales info from saleID
     */
    public function getSaleFromID($givenSalesID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selFromID->execute(array("givenSalesID" =>  $givenSalesID)); 
        return $this->selFromID->fetchAll(PDO::FETCH_ASSOC);    // return fetched result 
    }
    
    /**
     * Get others users sales
     */
    public function getSelectedUserSale($usernameArray){
        // check if array contains value
       if(empty(!$usernameArray)){
        $userID = implode(',', array_fill(0, count($usernameArray), '?'));  // create a '?' for each value
        $usernameQuery = "userID IN ($userID)";     // create part of query for binded value
        } else {$usernameQuery = "";}   // adding this query if array is empty
        
        
        $sql = "SELECT salesID, customerNr, products.productName, products.macAdresse, DATE_FORMAT(sales.date,'%d %b %Y') AS date, comment, storage.storageName, quantity, sales.deletedStorage, sales.deletedProduct FROM " . SaleModel::TABLE . 
            " LEFT JOIN products ON sales.productID = products.productID LEFT JOIN storage ON sales.storageID = storage.storageID WHERE $usernameQuery ORDER BY salesID DESC";
    
        $this->selUserSale = $this->dbConn->prepare($sql);  // prepare the statement
        
        $this->selUserSale->execute($usernameArray);    // execute SQL statement

        return $this->selUserSale->fetchALL(PDO::FETCH_ASSOC);  // return fetched result 
    }
    
    /**
     * Add mac adresse from a new sale
     */
    public function addSalesMac($salesID, $macAdresse){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addSaleMac->execute(array("givenSalesID" =>  $salesID, "givenMacAdresse" => $macAdresse)); 
    }
    
    /**
     * Get mac adresse from a given sale
     */
    public function getMacFromSaleID($givenSalesID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->getSaleMac->execute(array("givenSalesID" =>  $givenSalesID));
        return $this->getSaleMac->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
}