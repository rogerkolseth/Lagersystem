<?php

class MediaModel {
    
    private $dbConn;
    
    const TABLE = "media";
    
    const SEARCH_QUERY = "SELECT * FROM " . MediaModel::TABLE . " WHERE mediaName LIKE :givenSearchWord";
    const INSERT_QUERY = "INSERT INTO " . MediaModel::TABLE . " (mediaName, categoryID) VALUES (:givenFileName, :givenCaterogy)";
    const ID_QUERY = "SELECT * FROM " . MediaModel::TABLE . " INNER JOIN categories ON media.categoryID = categories.categoryID WHERE mediaID LIKE :givenMediaID";
    const UPDATE_QUERY = "UPDATE " . MediaModel::TABLE . " SET mediaName = :editMediaName, categoryID = :editCategory WHERE mediaID = :editMediaID"; 
    const DELETE_QUERY = "DELETE FROM " . MediaModel::TABLE . " WHERE mediaID = :deleteMediaID";
    const SELECT_QUERY = "SELECT * FROM " . MediaModel::TABLE;
    const DISABLE_CONS = "SET FOREIGN_KEY_CHECKS=0;";
    const ACTIVATE_CONS = "SET FOREIGN_KEY_CHECKS=1;";
    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;
      $this->searchStmt = $this->dbConn->prepare(MediaModel::SEARCH_QUERY);
      $this->addStmt = $this->dbConn->prepare(MediaModel::INSERT_QUERY);
      $this->byIdStmt = $this->dbConn->prepare(MediaModel::ID_QUERY);
      $this->editStmt = $this->dbConn->prepare(MediaModel::UPDATE_QUERY);
      $this->delStmt = $this->dbConn->prepare(MediaModel::DELETE_QUERY);
      $this->selStmt = $this->dbConn->prepare(MediaModel::SELECT_QUERY);
      $this->disabCons = $this->dbConn->prepare(MediaModel::DISABLE_CONS);
      $this->actCons = $this->dbConn->prepare(MediaModel::ACTIVATE_CONS);

    }
    
    public function getMediaSearchResult($givenSearchWord){
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);

       
    }
    
    public function addMedia($fileName, $givenCaterogy) {
        return $this->addStmt->execute(array("givenFileName" => $fileName, "givenCaterogy" => $givenCaterogy));
    }
    
    public function getMediaByID($givenMediaID){
        $this->byIdStmt->execute(array("givenMediaID" => $givenMediaID));
        return $this->byIdStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function editMedia($editMediaID, $editMediaName, $editCategory){
       return $this->editStmt->execute(array("editMediaID" => $editMediaID, "editMediaName" => $editMediaName, "editCategory" => $editCategory)); 
    }
    
    public function deletetMediaByID($deleteMediaID)    {
       $this->disabCons->execute();
       $this->delStmt->execute(array("deleteMediaID" => $deleteMediaID));
       $this->actCons->execute();
       return $this->delStmt;
    }
    
    public function getAllMediaInfo(){
        $this->selStmt->execute();
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC); 
    }
}

