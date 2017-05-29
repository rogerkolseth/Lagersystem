<?php

class CategoryModel {
    
    private $dbConn;    //database connection variable

    const TABLE = "categories"; // table to access
    
    // query to run, can include binded variables
    const SELECT_QUERY_CATID = "SELECT * FROM " . CategoryModel::TABLE . " WHERE categoryID = :givenCategoryID";
    const INSERT_QUERY = "INSERT INTO " . CategoryModel::TABLE . " (categoryName) VALUES (:givenCategoryName)";
    const SELECT_QUERY = "SELECT * FROM " . CategoryModel::TABLE;
    const SEARCH_QUERY = "SELECT * FROM " . CategoryModel::TABLE . " WHERE categoryName LIKE :givenSearchWord ";
    const DELETE_QUERY = "DELETE FROM " . CategoryModel::TABLE . " WHERE categoryID = :givenCategoryID";
    const UPDATE_QUERY = "UPDATE " . CategoryModel::TABLE . " SET categoryName = :givenCategoryName WHERE categoryID = :givenCategoryID"; 
    const SELECT_CAT_PROD = "SELECT categories.categoryID, categories.categoryName FROM products INNER JOIN categories ON products.categoryID = categories.categoryID GROUP BY categories.categoryName";
    const SELECT_CAT_MEDIA = "SELECT categories.categoryID, categories.categoryName FROM media INNER JOIN categories ON media.categoryID = categories.categoryID GROUP BY categories.categoryName";
    const SELECT_CAT_PROD_STO = "SELECT categories.categoryID, categories.categoryName FROM products INNER JOIN categories ON products.categoryID = categories.categoryID INNER JOIN inventory ON inventory.productID = products.productID WHERE inventory.storageID = :givenStorageID GROUP BY categories.categoryName";

    
    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;    // connect to database
        // prepare the statements
        $this->addStmt = $this->dbConn->prepare(CategoryModel::INSERT_QUERY);
        $this->selStmt = $this->dbConn->prepare(CategoryModel::SELECT_QUERY);
        $this->searchStmt = $this->dbConn->prepare(CategoryModel::SEARCH_QUERY);
        $this->selCatID = $this->dbConn->prepare(CategoryModel::SELECT_QUERY_CATID);
        $this->delStmt = $this->dbConn->prepare(CategoryModel::DELETE_QUERY);
        $this->editStmt = $this->dbConn->prepare(CategoryModel::UPDATE_QUERY);
        $this->catProd = $this->dbConn->prepare(CategoryModel::SELECT_CAT_PROD);
        $this->catMedia = $this->dbConn->prepare(CategoryModel::SELECT_CAT_MEDIA);
        $this->catProdSto = $this->dbConn->prepare(CategoryModel::SELECT_CAT_PROD_STO);

    }
    
     /**
     * add new categorie to database
     */ 
    public function addCategory($givenCategoryName) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addStmt->execute(array("givenCategoryName" =>  $givenCategoryName));
    }    
    
     /**
     * Get all category information from database
     */ 
    public function getAllCategoryInfo(){
        $this->selStmt->execute();  // execute SQL statement
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
     /**
     * Get all category search result from database
     */ 
    public function getSearchResult($givenSearchWord) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }
    
     /**
     * Get category information from categoryID
     */ 
    public function getCategoryByID($givenCategoryID){
        //bind variable to the parameter as strings, and execute SQL statement
       $this->selCatID->execute(array("givenCategoryID" => $givenCategoryID));
       return $this->selCatID->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }
    
     /**
     * Delete a category from database 
     */ 
    public function deleteCategory($givenCategoryID) {
        //bind variable to the parameter as strings, and execute SQL statement
       return $this->delStmt->execute(array("givenCategoryID" => $givenCategoryID));
    }
    
     /**
     * Edit a existing category
     */ 
    public function editCategory($givenCategoryName, $givenCategoryID){
        //bind variable to the parameter as strings, and execute SQL statement
       return $this->editStmt->execute(array("givenCategoryName" => $givenCategoryName, "givenCategoryID" => $givenCategoryID)); 
    }
    
     /**
     * Get categories containing a product
     */ 
    public function getCatWithProd(){
        $this->catProd->execute(); // execute SQL statement
        return $this->catProd->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }
    
     /**
     * Get categories containing media
     */ 
    public function getCatWithMedia(){
        $this->catMedia->execute(); // execute SQL statement
        return $this->catMedia->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }
    
     /**
     * Get categories containing a product within a given storage
     */ 
    public function getCatWithProdAndSto($givenStorageID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->catProdSto->execute(array("givenStorageID" => $givenStorageID));
        return $this->catProdSto->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
}
