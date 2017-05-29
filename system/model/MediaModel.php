<?php

class MediaModel {
    
    private $dbConn;    //database connection variable
    
    const TABLE = "media";  // table to access
    
    // query to run, can include binded variables
    const SEARCH_QUERY = "SELECT * FROM " . MediaModel::TABLE . " WHERE mediaName LIKE :givenSearchWord";
    const INSERT_QUERY = "INSERT INTO " . MediaModel::TABLE . " (mediaName, categoryID) VALUES (:givenFileName, :givenCaterogy)";
    const ID_QUERY = "SELECT * FROM " . MediaModel::TABLE . " INNER JOIN categories ON media.categoryID = categories.categoryID WHERE mediaID LIKE :givenMediaID";
    const UPDATE_QUERY = "UPDATE " . MediaModel::TABLE . " SET mediaName = :editMediaName, categoryID = :editCategory WHERE mediaID = :editMediaID"; 
    const DELETE_QUERY = "DELETE FROM " . MediaModel::TABLE . " WHERE mediaID = :deleteMediaID";
    const SELECT_QUERY = "SELECT * FROM " . MediaModel::TABLE;
    const DISABLE_CONS = "SET FOREIGN_KEY_CHECKS=0;";
    const ACTIVATE_CONS = "SET FOREIGN_KEY_CHECKS=1;";
    const MEDIA_FROM_CATID = "SELECT * FROM " . MediaModel::TABLE . " INNER JOIN categories ON media.categoryID = categories.categoryID WHERE categories.categoryID = :givenCategoryID";
    
    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;  // connect to database
      // prepare the statements
      $this->searchStmt = $this->dbConn->prepare(MediaModel::SEARCH_QUERY);
      $this->addStmt = $this->dbConn->prepare(MediaModel::INSERT_QUERY);
      $this->byIdStmt = $this->dbConn->prepare(MediaModel::ID_QUERY);
      $this->editStmt = $this->dbConn->prepare(MediaModel::UPDATE_QUERY);
      $this->delStmt = $this->dbConn->prepare(MediaModel::DELETE_QUERY);
      $this->selStmt = $this->dbConn->prepare(MediaModel::SELECT_QUERY);
      $this->disabCons = $this->dbConn->prepare(MediaModel::DISABLE_CONS);
      $this->actCons = $this->dbConn->prepare(MediaModel::ACTIVATE_CONS);
      $this->mediaFromCat = $this->dbConn->prepare(MediaModel::MEDIA_FROM_CATID);
    }
    
    /**
     * Get media search result
     */ 
    public function getMediaSearchResult($givenSearchWord){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
    /**
     * add new media info to database
     */ 
    public function addMedia($fileName, $givenCaterogy) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addStmt->execute(array("givenFileName" => $fileName, "givenCaterogy" => $givenCaterogy));
    }
    
    /**
     * Get media info grom mediaID
     */ 
    public function getMediaByID($givenMediaID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->byIdStmt->execute(array("givenMediaID" => $givenMediaID));
        return $this->byIdStmt->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }
    
    /**
     * Edit a registered media 
     */ 
    public function editMedia($editMediaID, $editMediaName, $editCategory){
        //bind variable to the parameter as strings, and execute SQL statement
       return $this->editStmt->execute(array("editMediaID" => $editMediaID, "editMediaName" => $editMediaName, "editCategory" => $editCategory)); 
    }
    
    /**
     * Delete a existing media
     */ 
    public function deletetMediaByID($deleteMediaID){
        //bind variable to the parameter as strings, and execute SQL statement
       return $this->delStmt->execute(array("deleteMediaID" => $deleteMediaID));    
    }
    
    /**
     * Get all media information
     */ 
    public function getAllMediaInfo(){
        $this->selStmt->execute();  // execute SQL statement
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * Get media within a given category
     */ 
    public function getMediaFromCategory($givenCategoryID) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->mediaFromCat->execute(array("givenCategoryID" => $givenCategoryID));
        return $this->mediaFromCat->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    } 
}

