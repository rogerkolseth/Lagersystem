<?php

class GroupModel {
    
    private $dbConn;    //database connection variable

    const TABLE = "user_group"; // table to access
    const TABLE_MEMB = "group_members"; // table to access
    
    // query to run, can include binded variables
    const DELETE_QUERY = "DELETE FROM " . GroupModel::TABLE . " WHERE groupID = :givenGroupID";
    const SELECT_GROUPID = "SELECT * FROM " . GroupModel::TABLE . " WHERE groupID = :givenGroupID";
    const INSERT_QUERY = "INSERT INTO " . GroupModel::TABLE . " (groupName) VALUES (:givenGroupName)";
    const SEARCH_QUERY = "SELECT * FROM " . GroupModel::TABLE . " WHERE groupName LIKE :givenSearchWord ";
    const UPDATE_QUERY = "UPDATE " . GroupModel::TABLE . " SET groupName = :givenGroupName WHERE groupID = :givenGroupID"; 
    const FIND_GROUP_MEMB = "SELECT COUNT(*) FROM " . GroupModel::TABLE_MEMB . " WHERE groupID = :givenGroupID AND userID = :givenUserID";
    const INSERT_GROUP_MEMB = "INSERT INTO " . GroupModel::TABLE_MEMB . " (userID, groupID) VALUES (:givenUserID, :givenGroupID)";
    const SELECT_GROUP_MEMB = "SELECT users.userID, users.username, group_members.groupID, group_members.memberID FROM " . GroupModel::TABLE_MEMB . " INNER JOIN users ON group_members.userID = users.userID WHERE groupID = :givenGroupID";
    const DELETE_GROUP_MEMB = "DELETE FROM " . GroupModel::TABLE_MEMB . " WHERE memberID = :givenMemberID";
    const GROUP_MEMB_FROM_USERID = "SELECT group_members.userID, user_group.groupName, group_members.groupID, group_members.memberID FROM " . GroupModel::TABLE_MEMB . " INNER JOIN user_group ON group_members.groupID = user_group.groupID WHERE userID = :givenUserID";
    const DISABLE_CONS = "SET FOREIGN_KEY_CHECKS=0;";
    const ACTIVATE_CONS = "SET FOREIGN_KEY_CHECKS=1;";
    
    
    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;    // connect to database
        // prepare the statements
        $this->addStmt = $this->dbConn->prepare(GroupModel::INSERT_QUERY);
        $this->searchStmt = $this->dbConn->prepare(GroupModel::SEARCH_QUERY);
        $this->selGroupID = $this->dbConn->prepare(GroupModel::SELECT_GROUPID);
        $this->delStmt = $this->dbConn->prepare(GroupModel::DELETE_QUERY);
        $this->editStmt = $this->dbConn->prepare(GroupModel::UPDATE_QUERY);
        $this->existGroupMemb = $this->dbConn->prepare(GroupModel::FIND_GROUP_MEMB);
        $this->addGroupMemb = $this->dbConn->prepare(GroupModel::INSERT_GROUP_MEMB);
        $this->selGroupMemb = $this->dbConn->prepare(GroupModel::SELECT_GROUP_MEMB);
        $this->delGroupMemb = $this->dbConn->prepare(GroupModel::DELETE_GROUP_MEMB);
        $this->selGroupMembFromUser = $this->dbConn->prepare(GroupModel::GROUP_MEMB_FROM_USERID);
        $this->disabCons = $this->dbConn->prepare(GroupModel::DISABLE_CONS);
        $this->actCons = $this->dbConn->prepare(GroupModel::ACTIVATE_CONS);
    }
    
     /**
     * Add a new group to database
     */ 
    public function addGroup($givenGroupName) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addStmt->execute(array("givenGroupName" =>  $givenGroupName));
    } 
    
     /**
     * Get all group info from search result
     */ 
    public function getSearchResult($givenSearchWord) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->searchStmt->execute(array("givenSearchWord" => $givenSearchWord));
        return $this->searchStmt->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
     /**
     * Get all group info from given groupID
     */ 
    public function getGroupByID($givenGroupID){
        //bind variable to the parameter as strings, and execute SQL statement
       $this->selGroupID->execute(array("givenGroupID" => $givenGroupID));
       return $this->selGroupID->fetchAll(PDO::FETCH_ASSOC);    // return fetched result
    }
    
     /**
     * Delete a group from database
     */ 
    public function deleteGroup($givenGroupID) {
       $this->disabCons->execute(); 
       //bind variable to the parameter as strings, and execute SQL statement
       $this->delStmt->execute(array("givenGroupID" => $givenGroupID));
       $this->actCons->execute();
       return $this->delStmt;
    }
    
     /**
     * Edit a existing group in database
     */ 
    public function editGroup($editGroupName, $editGroupID){
        //bind variable to the parameter as strings, and execute SQL statement
       return $this->editStmt->execute(array("givenGroupName" => $editGroupName, "givenGroupID" => $editGroupID)); 
    }
    
     /**
     * Check if user already are member of group
     */ 
    public function doesMemberExist($givenGroupID, $givenUserID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->existGroupMemb->execute(array("givenUserID" => $givenUserID, "givenGroupID" => $givenGroupID));
        return $this->existGroupMemb->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
     /**
     * Add a new group member to given group
     */ 
    public function addGroupMember($givenGroupID, $givenUserID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->addGroupMemb->execute(array("givenUserID" => $givenUserID, "givenGroupID" => $givenGroupID));
    }
    
     /**
     * Get all members in a given group
     */ 
    public function getGroupMember($givenGroupID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selGroupMemb->execute(array("givenGroupID" => $givenGroupID));
       return $this->selGroupMemb->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
     /**
     * Delete a member from a group
     */ 
    public function deleteGroupMember($memberID){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->delGroupMemb->execute(array("givenMemberID" => $memberID));
    }
    
     /**
     * Gets all group a given user are member of
     */ 
    public function getGroupMembershipFromUserID($givenUserID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selGroupMembFromUser->execute(array("givenUserID" => $givenUserID));
        return $this->selGroupMembFromUser->fetchAll(PDO::FETCH_ASSOC);     // return fetched result
    }
}