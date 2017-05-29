<?php

class ProductModel {
    
    private $dbConn;    //database connection variable
    
    const TABLE = "products";   // table to access
    
    // query to run, can include binded variables
    const SELECT_QUERY_PRODUCTID = "SELECT productID, productName, price, products.categoryID, categories.categoryName, products.mediaID, date, macAdresse, media.mediaName FROM " . ProductModel::TABLE . " INNER JOIN media ON products.mediaID = media.mediaID INNER JOIN categories ON products.categoryID = categories.categoryID WHERE productID = :givenProductID";
    const SELECT_QUERY = "SELECT * FROM " . ProductModel::TABLE . " INNER JOIN categories ON products.categoryID = categories.categoryID" ;
    const UPDATE_QUERY = "UPDATE " . ProductModel::TABLE . " SET productName = :editProductName, price = :editPrice, categoryID = :editCategoryID, mediaID = :editMediaID WHERE productID = :editProductID" ;
    const SEARCH_QUERY = "SELECT * FROM " . ProductModel::TABLE . " INNER JOIN categories ON products.categoryID = categories.categoryID WHERE productName LIKE :givenSearchWord";
    const PROD_FROM_CATID = "SELECT * FROM " . ProductModel::TABLE . " INNER JOIN categories ON products.categoryID = categories.categoryID WHERE categories.categoryID = :givenCategoryID";
    const INSERT_QUERY = "INSERT INTO " . ProductModel::TABLE . " (productName, price, CategoryID, MediaID, date, macAdresse) VALUES (:givenProductName, :givenPrice, :givenCategoryID, :givenMediaID, NOW(), :givenMacAdresse)";
    const DELETE_QUERY = "DELETE FROM " . ProductModel::TABLE . " WHERE productID = :removeProductID";
    const DISABLE_CONS = "SET FOREIGN_KEY_CHECKS=0;";
    const ACTIVATE_CONS = "SET FOREIGN_KEY_CHECKS=1;";
    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;  // connect to database
      // prepare the statements
      $this->editStmt = $this->dbConn->prepare(ProductModel::UPDATE_QUERY);  
      $this->searchStmt = $this->dbConn->prepare(ProductModel::SEARCH_QUERY);
      $this->addStmt = $this->dbConn->prepare(ProductModel::INSERT_QUERY);
      $this->selStmt = $this->dbConn->prepare(ProductModel::SELECT_QUERY);
      $this->delStmt = $this->dbConn->prepare(ProductModel::DELETE_QUERY);
      $this->selProductID = $this->dbConn->prepare(ProductModel::SELECT_QUERY_PRODUCTID);
      $this->disabCons = $this->dbConn->prepare(ProductModel::DISABLE_CONS);
      $this->actCons = $this->dbConn->prepare(ProductModel::ACTIVATE_CONS);
      $this->prodFromCat = $this->dbConn->prepare(ProductModel::PROD_FROM_CATID);
    }
    
    /**
     * Get all products info from given search criteria
     */ 
    public function getSearchResult($givenSearchWord) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
    /**
     * Get all productsinformation from database
     */ 
    public function getAllProductInfo() {
        $this->selStmt->execute();  // execute SQL statement
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * Update existing product information
     */ 
    public function editProduct($editProductName, $editProductID, $editPrice, $editCategoryID, $editMediaID) {
       //bind variable to the parameter as strings, and execute SQL statement
        return $this->editStmt->execute(array("editProductName" =>  $editProductName, "editProductID" => $editProductID, "editPrice" => $editPrice, "editCategoryID" => $editCategoryID, "editMediaID" => $editMediaID)); 
    }
    
    /**
     * Add a new product to database
     */ 
    public function addProduct($givenProductName, $givenPrice, $givenCategoryID, $givenMediaID, $givenMacAdresse) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addStmt->execute(array("givenProductName" =>  $givenProductName, "givenPrice" => $givenPrice, "givenCategoryID" => $givenCategoryID, "givenMediaID" => $givenMediaID, "givenMacAdresse" => $givenMacAdresse));
    }
    
    /**
     * remove a existing product from database
     */ 
    public function removeProduct($removeProductID){
       $this->disabCons->execute(); 
       //bind variable to the parameter as strings, and execute SQL statement
       $this->delStmt->execute(array("removeProductID" => $removeProductID));
       $this->actCons->execute();
       return $this->delStmt;
    }
    
    /**
     * Get all product information from a given productID
     */ 
    public function getAllProductInfoFromID($givenProductID) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selProductID->execute(array("givenProductID" => $givenProductID));
        return $this->selProductID->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    } 
    
    /**
     * Get all products within a given category
     */ 
    public function getProductFromCategory($givenCategoryID) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->prodFromCat->execute(array("givenCategoryID" => $givenCategoryID));
        return $this->prodFromCat->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    } 
    
    
}