<?php

class CategoryModel {
    
    private $dbConn;

    const TABLE = "categories";
    
    const INSERT_QUERY = "INSERT INTO " . CategoryModel::TABLE . " (categoryName) VALUES (:givenCategoryName)";
    const SELECT_QUERY = "SELECT * FROM " . CategoryModel::TABLE;
    
    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;
        $this->addStmt = $this->dbConn->prepare(CategoryModel::INSERT_QUERY);
        $this->selStmt = $this->dbConn->prepare(CategoryModel::SELECT_QUERY);
    }
    
    public function addCategory($givenCategoryName) {
        return $this->addStmt->execute(array("givenCategoryName" =>  $givenCategoryName));
    }    
    
    public function getAllCategoryInfo(){
        $this->selStmt->execute();
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    
}
