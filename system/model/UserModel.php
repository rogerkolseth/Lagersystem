<?php

class UserModel {

    private $dbConn;

    const TABLE = "users";
    const UPDATE_QUERY = "UPDATE " . UserModel::TABLE . " SET name = :editName, username = :editUsername, password = :editPassword, userLevel = :editUserLevel, email = :editEmail, mediaID = :editMediaID WHERE userID = :editUserID";
    const SELECT_QUERY = "SELECT * FROM " . UserModel::TABLE . " INNER JOIN media ON users.mediaID = media.mediaID";
    const SELECT_QUERY_USERID = "SELECT * FROM " . UserModel::TABLE . " INNER JOIN media ON users.mediaID = media.mediaID WHERE userID = :givenUserID";
    const SEARCH_QUERY = "SELECT * FROM " . UserModel::TABLE . " WHERE name LIKE :givenSearchWord OR username LIKE :givenSearchWord";
    const INSERT_QUERY = "INSERT INTO " . UserModel::TABLE . " (name, username, password, userLevel, email, mediaID) VALUES (:givenName, :givenUsername, :givenPassword, :givenUserLevel, :givenEmail, :givenMediaID)";
    const DELETE_QUERY = "DELETE FROM " . UserModel::TABLE . " WHERE userID = :removeUserID";
    const UPDATE_LOGINDATE = "UPDATE " . UserModel::TABLE . " SET lastLogin = :givenLastLogin WHERE username = :givenUsername";
    const SET_SESSION_VAR = "SET @sessionUserID := :sessionUserID";
    const DISABLE_CONS = "SET FOREIGN_KEY_CHECKS=0;";
    const ACTIVATE_CONS = "SET FOREIGN_KEY_CHECKS=1;";

    
    private $selStmt;
    private $addStmt;
    private $delStmt;
    private $editStmt;
    private $searchStmt;

    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;
        $this->addStmt = $this->dbConn->prepare(UserModel::INSERT_QUERY);
        $this->selStmt = $this->dbConn->prepare(UserModel::SELECT_QUERY);
        $this->searchStmt = $this->dbConn->prepare(UserModel::SEARCH_QUERY);
        $this->delStmt = $this->dbConn->prepare(UserModel::DELETE_QUERY);
        $this->editStmt = $this->dbConn->prepare(UserModel::UPDATE_QUERY);
        $this->selUserID = $this->dbConn->prepare(UserModel::SELECT_QUERY_USERID);
        $this->disabCons = $this->dbConn->prepare(UserModel::DISABLE_CONS);
        $this->actCons = $this->dbConn->prepare(UserModel::ACTIVATE_CONS);
        $this->lastLogin = $this->dbConn->prepare(UserModel::UPDATE_LOGINDATE);
        $this->sessionVar = $this->dbConn->prepare(UserModel::SET_SESSION_VAR);
    }

    public function getSearchResult($givenSearchWord) {
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUserInfo() {
        $this->selStmt->execute();
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    

    public function getAllUserInfoFromID($givenUserID) {
        $this->selUserID->execute(array("givenUserID" => $givenUserID));
        return $this->selUserID->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editUser($editName, $editUsername, $editPassword, $editUserLevel, $editEmail, $editUserID, $editMediaID) {
        return $this->editStmt->execute(array("editName" => $editName, "editUsername" => $editUsername, "editPassword" => $editPassword, "editUserLevel" => $editUserLevel, "editEmail" => $editEmail, "editUserID" => $editUserID, "editMediaID" => $editMediaID));
    }

    // kommer tilbake til, ved oppretting av bruker
    public function addUser($givenName, $givenUsername, $givenPassword, $givenUserLevel, $givenEmail, $givenMediaID, $sessionID) {
        $this->setSession($sessionID);
        $this->addStmt->execute(array("givenName" => $givenName, "givenUsername" => $givenUsername, "givenPassword" => $givenPassword, "givenUserLevel" => $givenUserLevel, "givenEmail" => $givenEmail, "givenMediaID" => $givenMediaID));
        $lastAdded = $this->dbConn->lastInsertId('users');
        return $lastAdded;
    }

    // kommer tilbake til, ved sletting av bruker
    public function removeUser($removeUserID) {
       $this->disabCons->execute();
       $this->delStmt->execute(array("removeUserID" => $removeUserID));
       $this->actCons->execute();
       return $this->delStmt;
    }
    
    public function updateLastLogin($givenLastLogin, $givenUsername){
        return $this->lastLogin->execute(array("givenLastLogin" => $givenLastLogin, "givenUsername" => $givenUsername));
    }
    
    public function setSession($sessionID){
        $this->sessionVar->execute(array("sessionUserID" => $sessionID));
    }

}
