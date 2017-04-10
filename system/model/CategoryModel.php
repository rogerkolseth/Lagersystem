<?php

class CategoryModel {
    
    private $dbConn;

    const TABLE = "categories";
    
    const SELECT_QUERY_CATID = "SELECT * FROM " . CategoryModel::TABLE . " WHERE categoryID = :givenCategoryID";
    const INSERT_QUERY = "INSERT INTO " . CategoryModel::TABLE . " (categoryName) VALUES (:givenCategoryName)";
    const SELECT_QUERY = "SELECT * FROM " . CategoryModel::TABLE;
    const SEARCH_QUERY = "SELECT * FROM " . CategoryModel::TABLE . " WHERE categoryName LIKE :givenSearchWord ";
    const DELETE_QUERY = "DELETE FROM " . CategoryModel::TABLE . " WHERE categoryID = :givenCategoryID";
    const UPDATE_QUERY = "UPDATE " . CategoryModel::TABLE . " SET categoryName = :givenCategoryName WHERE categoryID = :givenCategoryID"; 

    
    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;
        $this->addStmt = $this->dbConn->prepare(CategoryModel::INSERT_QUERY);
        $this->selStmt = $this->dbConn->prepare(CategoryModel::SELECT_QUERY);
        $this->searchStmt = $this->dbConn->prepare(CategoryModel::SEARCH_QUERY);
        $this->selCatID = $this->dbConn->prepare(CategoryModel::SELECT_QUERY_CATID);
        $this->delStmt = $this->dbConn->prepare(CategoryModel::DELETE_QUERY);
        $this->editStmt = $this->dbConn->prepare(CategoryModel::UPDATE_QUERY);

    }
    
    public function addCategory($givenCategoryName) {
        return $this->addStmt->execute(array("givenCategoryName" =>  $givenCategoryName));
    }    
    
    public function getAllCategoryInfo(){
        $this->selStmt->execute();
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function getSearchResult($givenSearchWord) {
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCategoryByID($givenCategoryID){
       $this->selCatID->execute(array("givenCategoryID" => $givenCategoryID));
       return $this->selCatID->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function deleteCategory($givenCategoryID) {
       return $this->delStmt->execute(array("givenCategoryID" => $givenCategoryID));
    }
    
    public function editCategory($givenCategoryName, $givenCategoryID){
       return $this->editStmt->execute(array("givenCategoryName" => $givenCategoryName, "givenCategoryID" => $givenCategoryID)); 
    }
    
    
}
