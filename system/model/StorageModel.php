<?php

class StorageModel {
    
    private $dbConn;

    const TABLE = "storage";
    const SELECT_QUERY_STORAGEID = "SELECT * FROM " . StorageModel::TABLE . " WHERE storageID = :givenStorageID";
    const UPDATE_QUERY = "UPDATE " . StorageModel::TABLE . " SET storageName = :editStorageName, negativeSupport = :editNegativeSupport WHERE storageID = :editStorageID"; 
    const SELECT_QUERY = "SELECT * FROM " . StorageModel::TABLE;
    const SEARCH_QUERY = "SELECT * FROM " . StorageModel::TABLE . " WHERE storageName LIKE :givenSearchWord ";
    const INSERT_QUERY = "INSERT INTO " . StorageModel::TABLE . " (storageName, negativeSupport) VALUES (:givenStorageName, :givenNegativeSupport)";
    const DELETE_QUERY = "DELETE FROM " . StorageModel::TABLE . " WHERE storageID = :removeStorageID";
    const NEGATIVE_SUPP = "SELECT negativeSupport FROM " . StorageModel::TABLE . " WHERE storageID = :givenStorageID";
    const DISABLE_CONS = "SET FOREIGN_KEY_CHECKS=0;";
    const ACTIVATE_CONS = "SET FOREIGN_KEY_CHECKS=1;";
    
    
    private $selStmt;
    private $addStmt;

    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;
        $this->addStmt = $this->dbConn->prepare(StorageModel::INSERT_QUERY);
        $this->selStmt = $this->dbConn->prepare(StorageModel::SELECT_QUERY);
        $this->delStmt = $this->dbConn->prepare(StorageModel::DELETE_QUERY);
        $this->searchStmt = $this->dbConn->prepare(StorageModel::SEARCH_QUERY);
        $this->editStmt = $this->dbConn->prepare(StorageModel::UPDATE_QUERY);
        $this->selStorageID = $this->dbConn->prepare(StorageModel::SELECT_QUERY_STORAGEID);
        $this->disabCons = $this->dbConn->prepare(StorageModel::DISABLE_CONS);
        $this->actCons = $this->dbConn->prepare(StorageModel::ACTIVATE_CONS);
        $this->negSupp = $this->dbConn->prepare(StorageModel::NEGATIVE_SUPP);
        
    }

    public function getSearchResult($givenSearchWord) {
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $this->selStmt->execute();
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

    public function addStorage($givenStorageName, $givenNegativeSupport) {
        return $this->addStmt->execute(array("givenStorageName" =>  $givenStorageName, "givenNegativeSupport" => $givenNegativeSupport));
    }    
    
    public function removeStorage($removeStorageID)    {
       $this->disabCons->execute(); 
       $this->delStmt->execute(array("removeStorageID" => $removeStorageID));
       $this->actCons->execute();
       return $this->delStmt;
    }
    
    public function editStorage($editStorageName, $editStorageID, $editNegativeSupport){
       return $this->editStmt->execute(array("editStorageName" => $editStorageName, "editStorageID" => $editStorageID, "editNegativeSupport" => $editNegativeSupport)); 
    }
    
    public function getAllStorageInfoFromID($givenStorageID) {
        $this->selStorageID->execute(array("givenStorageID" => $givenStorageID));
        return $this->selStorageID->fetchAll(PDO::FETCH_ASSOC);
    }    
    
    public function getNegativeSupportStatus($givenStorageID){
       $this->negSupp->execute(array("givenStorageID" => $givenStorageID));
        return $this->negSupp->fetchAll(PDO::FETCH_ASSOC); 
    }

}