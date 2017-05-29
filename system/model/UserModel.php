<?php

class UserModel {

    private $dbConn;    //database connection variable

    const TABLE = "users";  // table to access
    
    // query to run, can include binded variables
    const UPDATE_QUERY = "UPDATE " . UserModel::TABLE . " SET name = :editName, username = :editUsername, password = :editPassword, userLevel = :editUserLevel, email = :editEmail, mediaID = :editMediaID WHERE userID = :editUserID";
    const UPDATE_ACTIVE_USER = "UPDATE " . UserModel::TABLE . " SET name = :editName, password = :editPassword, email = :editEmail, mediaID = :editMediaID WHERE userID = :editUserID";
    const SELECT_QUERY = "SELECT * FROM " . UserModel::TABLE . " INNER JOIN media ON users.mediaID = media.mediaID";
    const SELECT_QUERY_USERID = "SELECT * FROM " . UserModel::TABLE . " INNER JOIN media ON users.mediaID = media.mediaID WHERE userID = :givenUserID";
    const SEARCH_QUERY = "SELECT * FROM " . UserModel::TABLE . " WHERE name LIKE :givenSearchWord OR username LIKE :givenSearchWord";
    const INSERT_QUERY = "INSERT INTO " . UserModel::TABLE . " (name, username, password, userLevel, email, mediaID) VALUES (:givenName, :givenUsername, :givenPassword, :givenUserLevel, :givenEmail, :givenMediaID)";
    const DELETE_QUERY = "DELETE FROM " . UserModel::TABLE . " WHERE userID = :removeUserID";
    const UPDATE_LOGINDATE = "UPDATE " . UserModel::TABLE . " SET lastLogin = NOW() WHERE username = :givenUsername";
    const SELECT_USERNAMES = "SELECT userID, username FROM " . UserModel::TABLE;
    const SELECT_EMAIL = "SELECT users.email FROM users WHERE users.userLevel = 'Administrator'";
    const FIND_USER = "SELECT userID FROM " . UserModel::TABLE . " WHERE username = :givenUsername AND email =:givenEmail";
    const NEW_PASSWORD = "UPDATE " . UserModel::TABLE . " SET password = :newPassword WHERE userID = :userID";
    const SET_SESSION_VAR = "SET @sessionUserID := :sessionUserID";
    const DISABLE_CONS = "SET FOREIGN_KEY_CHECKS=0;";
    const ACTIVATE_CONS = "SET FOREIGN_KEY_CHECKS=1;";


    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;    // connect to database
        // prepare the statements
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
        $this->selUsername = $this->dbConn->prepare(UserModel::SELECT_USERNAMES);
        $this->getAdminEmail = $this->dbConn->prepare(UserModel::SELECT_EMAIL);
        $this->findUser = $this->dbConn->prepare(UserModel::FIND_USER);
        $this->newPass = $this->dbConn->prepare(UserModel::NEW_PASSWORD);
        $this->editActiveUser = $this->dbConn->prepare(UserModel::UPDATE_ACTIVE_USER);
    }

    /**
     * Search for user from given search word
     */ 
    public function getSearchResult($givenSearchWord) {
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }

    /**
     * Get all user information in database
     */ 
    public function getAllUserInfo() {
        $this->selStmt->execute();  // execute SQL statement
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * Get all usernames
     */ 
    public function getUsername(){
        $this->selUsername->execute();  // execute SQL statement
        return $this->selUsername->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * Get all userinfo from given userID
     */ 
    public function getAllUserInfoFromID($givenUserID) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selUserID->execute(array("givenUserID" => $givenUserID));
        return $this->selUserID->fetchAll(PDO::FETCH_ASSOC);    // return fetched result
    }
    
    /**
     * Edit user information (NOT administration restrictions)
     */ 
    public function editActiveUser($editName, $editPassword, $editEmail, $editUserID, $editMediaID) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->editActiveUser->execute(array("editName" => $editName, "editPassword" => $editPassword, "editEmail" => $editEmail, "editUserID" => $editUserID, "editMediaID" => $editMediaID));
    }
    
    /**
     * Edit all userinfo (Admin restriction)
     */ 
    public function editUser($editName, $editUsername, $editPassword, $editUserLevel, $editEmail, $editUserID, $editMediaID) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->editStmt->execute(array("editName" => $editName, "editUsername" => $editUsername, "editPassword" => $editPassword, "editUserLevel" => $editUserLevel, "editEmail" => $editEmail, "editUserID" => $editUserID, "editMediaID" => $editMediaID));
    }

    /**
     * Add new user to database
     */ 
    public function addUser($givenName, $givenUsername, $givenPassword, $givenUserLevel, $givenEmail, $givenMediaID, $sessionID) {
        $this->setSession($sessionID);  
        //bind variable to the parameter as strings, and execute SQL statement
        $this->addStmt->execute(array("givenName" => $givenName, "givenUsername" => $givenUsername, "givenPassword" => $givenPassword, "givenUserLevel" => $givenUserLevel, "givenEmail" => $givenEmail, "givenMediaID" => $givenMediaID));
        $lastAdded = $this->dbConn->lastInsertId('users');
        return $lastAdded;
    }

    /**
     * Delete given user from database
     */ 
    public function removeUser($removeUserID) {
       $this->disabCons->execute(); // execute SQL statement
       //bind variable to the parameter as strings, and execute SQL statement
       $this->delStmt->execute(array("removeUserID" => $removeUserID));
       $this->actCons->execute();   // execute SQL statement
       return $this->delStmt;
    }
    
    /**
     * Update lat loggen in from given username
     */ 
    public function updateLastLogin($givenUsername){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->lastLogin->execute(array("givenUsername" => $givenUsername));
    }
    
    /**
     * Set given ID as global variable in database
     */ 
    public function setSession($sessionID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->sessionVar->execute(array("sessionUserID" => $sessionID));
    }
    
    /**
     * Get email adresse from all administrators
     */ 
    public function getAdminEmail(){
        $this->getAdminEmail->execute();    // execute SQL statement
        return $this->getAdminEmail->fetchAll(PDO::FETCH_ASSOC);    // return fetched result
    }
    
    /**
     * check if user exist from username and email adresse
     */ 
    public function forgottenPassword($givenUsername, $givenEmail){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->findUser->execute(array("givenUsername" => $givenUsername, "givenEmail" => $givenEmail));
        return $this->findUser->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }
    
    /**
     * update new password
     */ 
    public function newPassword($newPassword, $userID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->newPass->execute(array("newPassword" => $newPassword, "userID" => $userID));
    }

}
