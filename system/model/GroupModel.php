<?php

class GroupModel {
    
    private $dbConn;

    const TABLE = "user_group";
    
    const INSERT_QUERY = "INSERT INTO " . GroupModel::TABLE . " (groupName) VALUES (:givenGroupName)";
    const SEARCH_QUERY = "SELECT * FROM " . GroupModel::TABLE . " WHERE groupName LIKE :givenSearchWord ";
    const SELECT_GROUPID = "SELECT * FROM " . GroupModel::TABLE . " WHERE groupID = :givenGroupID";
    const DELETE_QUERY = "DELETE FROM " . GroupModel::TABLE . " WHERE groupID = :givenGroupID";
    const UPDATE_QUERY = "UPDATE " . GroupModel::TABLE . " SET groupName = :givenGroupName WHERE groupID = :givenGroupID"; 

    
    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;
        $this->addStmt = $this->dbConn->prepare(GroupModel::INSERT_QUERY);
        $this->searchStmt = $this->dbConn->prepare(GroupModel::SEARCH_QUERY);
        $this->selGroupID = $this->dbConn->prepare(GroupModel::SELECT_GROUPID);
        $this->delStmt = $this->dbConn->prepare(GroupModel::DELETE_QUERY);
        $this->editStmt = $this->dbConn->prepare(GroupModel::UPDATE_QUERY);
    }
    
    public function addGroup($givenGroupName) {
        return $this->addStmt->execute(array("givenGroupName" =>  $givenGroupName));
    } 
    
    public function getSearchResult($givenSearchWord) {
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getGroupByID($givenGroupID){
       $this->selGroupID->execute(array("givenGroupID" => $givenGroupID));
       return $this->selGroupID->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function deleteGroup($givenGroupID) {
       return $this->delStmt->execute(array("givenGroupID" => $givenGroupID));
    }
    
    public function editGroup($editGroupName, $editGroupID){
       return $this->editStmt->execute(array("givenGroupName" => $editGroupName, "givenGroupID" => $editGroupID)); 
    }
}