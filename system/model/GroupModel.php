<?php

class GroupModel {
    
    private $dbConn;

    const TABLE = "user_group";
    
    const INSERT_QUERY = "INSERT INTO " . GroupModel::TABLE . " (groupName) VALUES (:givenGroupName)";
    const SEARCH_QUERY = "SELECT * FROM " . GroupModel::TABLE . " WHERE groupName LIKE :givenSearchWord ";

    
    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;
        $this->addStmt = $this->dbConn->prepare(GroupModel::INSERT_QUERY);
        $this->searchStmt = $this->dbConn->prepare(GroupModel::SEARCH_QUERY);

    }
    
    public function addGroup($givenGroupName) {
        return $this->addStmt->execute(array("givenGroupName" =>  $givenGroupName));
    } 
    
    public function getSearchResult($givenSearchWord) {
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}