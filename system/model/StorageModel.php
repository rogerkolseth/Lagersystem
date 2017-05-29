<?php

class StorageModel {
    
    private $dbConn;    //database connection variable

    const TABLE = "storage";     // table to access
    
    // query to run, can include binded variables
    const SELECT_QUERY_STORAGEID = "SELECT * FROM " . StorageModel::TABLE . " WHERE storageID = :givenStorageID";
    const UPDATE_QUERY = "UPDATE " . StorageModel::TABLE . " SET storageName = :editStorageName, negativeSupport = :editNegativeSupport WHERE storageID = :editStorageID"; 
    const SELECT_QUERY = "SELECT * FROM " . StorageModel::TABLE;
    const SEARCH_QUERY = "SELECT * FROM " . StorageModel::TABLE . " WHERE storageName LIKE :givenSearchWord ";
    const INSERT_QUERY = "INSERT INTO " . StorageModel::TABLE . " (storageName, negativeSupport) VALUES (:givenStorageName, :givenNegativeSupport)";
    const DELETE_QUERY = "DELETE FROM " . StorageModel::TABLE . " WHERE storageID = :removeStorageID";
    const NEGATIVE_SUPP = "SELECT negativeSupport FROM " . StorageModel::TABLE . " WHERE storageID = :givenStorageID";
    const DISABLE_CONS = "SET FOREIGN_KEY_CHECKS=0;";
    const ACTIVATE_CONS = "SET FOREIGN_KEY_CHECKS=1;";

    
    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;    // connect to database
        // prepare the statements
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

    /**
     * Search for storage from given search word
     */ 
    public function getSearchResult($givenSearchWord) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
    /**
     * Get all registered storages in database
     */ 
    public function getAll() {
        $this->selStmt->execute();  // execute SQL statement
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }

    /**
     * Add new storage to database
     */ 
    public function addStorage($givenStorageName, $givenNegativeSupport) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addStmt->execute(array("givenStorageName" =>  $givenStorageName, "givenNegativeSupport" => $givenNegativeSupport));
    }    
    
    /**
     * Remove a existing storage fromdatabase
     */ 
    public function removeStorage($removeStorageID)    {
       $this->disabCons->execute();     // execute SQL statement
       //bind variable to the parameter as strings, and execute SQL statement
       $this->delStmt->execute(array("removeStorageID" => $removeStorageID));
       $this->actCons->execute();   // execute SQL statement
       return $this->delStmt;
    }
    
    /**
     * Update an existing storage in database
     */ 
    public function editStorage($editStorageName, $editStorageID, $editNegativeSupport){
        //bind variable to the parameter as strings, and execute SQL statement
       return $this->editStmt->execute(array("editStorageName" => $editStorageName, "editStorageID" => $editStorageID, "editNegativeSupport" => $editNegativeSupport)); 
    }
    
    /**
     * Get all storageinfo from given storageID
     */ 
    public function getAllStorageInfoFromID($givenStorageID) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selStorageID->execute(array("givenStorageID" => $givenStorageID));
        return $this->selStorageID->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }    
    
    /**
     * Check if storage can have nagive inventarstatus, 1 = true, 0 = false
     */ 
    public function getNegativeSupportStatus($givenStorageID){
        //bind variable to the parameter as strings, and execute SQL statement
       $this->negSupp->execute(array("givenStorageID" => $givenStorageID));
        return $this->negSupp->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }   

}