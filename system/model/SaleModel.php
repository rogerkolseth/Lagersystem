<?php

class SaleModel {
    
    private $dbConn;
    
    const TABLE = "sales";
    
    const SELECT_QUERY = "SELECT * FROM " . SaleModel::TABLE;
    const SELECT_MY_SALES = "SELECT salesID, customerNr, products.productName, DATE_FORMAT(sales.date,'%d %b %Y') AS date, comment, storage.storageName, quantity FROM " . SaleModel::TABLE . 
            " INNER JOIN products ON sales.productID = products.productID INNER JOIN storage ON sales.storageID = storage.storageID WHERE userID = :givenUserID AND customerNr LIKE :givenProductSearchWord OR userID = :givenUserID AND comment LIKE "
            . ":givenProductSearchWord OR userID = :givenUserID AND productName LIKE :givenProductSearchWord OR userID = :givenUserID AND storageName LIKE :givenProductSearchWord ORDER BY date DESC";
    const SELECT_STORAGE = "SELECT * FROM " . SaleModel::TABLE . " WHERE storageID = :givenStorageID";
    const UPDATE_QUERY = "UPDATE " . SaleModel::TABLE . " SET customerNr = :editCustomerNr, comment = :editComment  WHERE salesID = :editSaleID" ;
    const INSERT_QUERY = "INSERT INTO " . SaleModel::TABLE . " (productID, date, customerNr, comment, userID, storageID, quantity) VALUES (:givenProductID, :givenDate, :givenCustomerNumber, :givenComment, :givenUserID, :givenStorageID, :givenQuantity)";
    const SELECT_FROM_ID = "SELECT * FROM " . SaleModel::TABLE . " WHERE salesID = :givenSalesID";
    const SELECT_LAST_QUERY =  "SELECT salesID, customerNr, products.productName, DATE_FORMAT(sales.date,'%d %b %Y') AS date, comment, storage.storageName, quantity FROM " . SaleModel::TABLE . 

            " INNER JOIN products ON sales.productID = products.productID INNER JOIN storage ON sales.storageID = storage.storageID WHERE userID = :givenUserID LIMIT 10";
    
    const SELECT_ALL_LAST_QUERY =  "SELECT salesID, customerNr, products.productName, DATE_FORMAT(sales.date,'%d %b %Y') AS date, users.username, comment, storage.storageName, quantity FROM " . SaleModel::TABLE . 
            " INNER JOIN products ON sales.productID = products.productID INNER JOIN storage ON sales.storageID = storage.storageID INNER JOIN users ON sales.userID = users.userID ORDER BY date DESC LIMIT 10";

    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;
      $this->editStmt = $this->dbConn->prepare(SaleModel::UPDATE_QUERY);  
      $this->addStmt = $this->dbConn->prepare(SaleModel::INSERT_QUERY);
      $this->selStmt = $this->dbConn->prepare(SaleModel::SELECT_QUERY);
      $this->selStorage = $this->dbConn->prepare(SaleModel::SELECT_STORAGE);
      $this->mySales = $this->dbConn->prepare(SaleModel::SELECT_MY_SALES);
      $this->selFromID = $this->dbConn->prepare(SaleModel::SELECT_FROM_ID);
      $this->selLast = $this->dbConn->prepare(SaleModel::SELECT_LAST_QUERY);
      $this->selAllLast = $this->dbConn->prepare(SaleModel::SELECT_ALL_LAST_QUERY);
    }
    
    public function getAllLastSaleInfo() {
        $this->selAllLast->execute();
        return $this->selAllLast->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getLastSaleInfo($givenUserID) {
        $this->selLast->execute(array("givenUserID" =>  $givenUserID));
        return $this->selLast->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSaleInfo() {
        $this->selStmt->execute();
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getSaleFromStorageID($givenStorageID){
        return $this->selStorage->execute(array("givenStorageID" =>  $givenStorageID)); 
    }
    
    public function editMySale($editSaleID, $editCustomerNr, $editComment) {
       return $this->editStmt->execute(array("editSaleID" =>  $editSaleID, "editCustomerNr" => $editCustomerNr, "editComment" => $editComment)); 
    }
    
    public function newSale($givenStorageID, $givenCustomerNumber, $givenProductID, $givenQuantity, $givenUserID, $givenComment, $givenDate) {
        return $this->addStmt->execute(array("givenStorageID" =>  $givenStorageID, "givenCustomerNumber" => $givenCustomerNumber, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity, "givenUserID" => $givenUserID, "givenComment" => $givenComment, "givenDate" => $givenDate));
    }
    
    public function getMySales($givenUserID, $givenProductSearchWord){
        $this->mySales->execute(array("givenUserID" =>  $givenUserID, "givenProductSearchWord" => $givenProductSearchWord));
        return $this->mySales->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getSaleFromID($givenSalesID){
        $this->selFromID->execute(array("givenSalesID" =>  $givenSalesID)); 
        return $this->selFromID->fetchAll(PDO::FETCH_ASSOC);  
    }
    
}