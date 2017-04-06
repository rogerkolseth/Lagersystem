<?php

class ProductModel {
    
    private $dbConn;
    
    const TABLE = "products";
    
    const SELECT_QUERY_PRODUCTID = "SELECT productID, productName, price, products.categoryID, categories.categoryName, products.mediaID, date, macAdresse, media.mediaName FROM " . ProductModel::TABLE . " INNER JOIN media ON products.mediaID = media.mediaID INNER JOIN categories ON products.categoryID = categories.categoryID WHERE productID = :givenProductID";
    const SELECT_QUERY = "SELECT * FROM " . ProductModel::TABLE . " INNER JOIN categories ON products.categoryID = categories.categoryID" ;
    const UPDATE_QUERY = "UPDATE " . ProductModel::TABLE . " SET productName = :editProductName, price = :editPrice, categoryID = :editCategoryID, mediaID = :editMediaID WHERE productID = :editProductID" ;
    const SEARCH_QUERY = "SELECT * FROM " . ProductModel::TABLE . " INNER JOIN categories ON products.categoryID = categories.categoryID WHERE productName LIKE :givenSearchWord";
    const INSERT_QUERY = "INSERT INTO " . ProductModel::TABLE . " (productName, price, CategoryID, MediaID, date, macAdresse) VALUES (:givenProductName, :givenPrice, :givenCategoryID, :givenMediaID, :givenProductDate, :givenMacAdresse)";
    const DELETE_QUERY = "DELETE FROM " . ProductModel::TABLE . " WHERE productID = :removeProductID";
    const DISABLE_CONS = "SET FOREIGN_KEY_CHECKS=0;";
    const ACTIVATE_CONS = "SET FOREIGN_KEY_CHECKS=1;";
    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;
      $this->editStmt = $this->dbConn->prepare(ProductModel::UPDATE_QUERY);  
      $this->searchStmt = $this->dbConn->prepare(ProductModel::SEARCH_QUERY);
      $this->addStmt = $this->dbConn->prepare(ProductModel::INSERT_QUERY);
      $this->selStmt = $this->dbConn->prepare(ProductModel::SELECT_QUERY);
      $this->delStmt = $this->dbConn->prepare(ProductModel::DELETE_QUERY);
      $this->selProductID = $this->dbConn->prepare(ProductModel::SELECT_QUERY_PRODUCTID);
      $this->disabCons = $this->dbConn->prepare(ProductModel::DISABLE_CONS);
      $this->actCons = $this->dbConn->prepare(ProductModel::ACTIVATE_CONS);
    }
    
    
    public function getSearchResult($givenSearchWord) {
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllProductInfo() {
        $this->selStmt->execute();
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function editProduct($editProductName, $editProductID, $editPrice, $editCategoryID, $editMediaID) {
       return $this->editStmt->execute(array("editProductName" =>  $editProductName, "editProductID" => $editProductID, "editPrice" => $editPrice, "editCategoryID" => $editCategoryID, "editMediaID" => $editMediaID)); 
    }
    
    public function addProduct($givenProductName, $givenPrice, $givenCategoryID, $givenMediaID, $givenProductDate, $givenMacAdresse) {
        return $this->addStmt->execute(array("givenProductName" =>  $givenProductName, "givenPrice" => $givenPrice, "givenCategoryID" => $givenCategoryID, "givenMediaID" => $givenMediaID, "givenProductDate" => $givenProductDate, "givenMacAdresse" => $givenMacAdresse));
    }
    
    public function removeProduct($removeProductID)
    {
       $this->disabCons->execute(); 
       $this->delStmt->execute(array("removeProductID" => $removeProductID));
       $this->actCons->execute();
       return $this->delStmt;
    }
    
    public function getAllProductInfoFromID($givenProductID) {
        $this->selProductID->execute(array("givenProductID" => $givenProductID));
        return $this->selProductID->fetchAll(PDO::FETCH_ASSOC);
    } 
    
}