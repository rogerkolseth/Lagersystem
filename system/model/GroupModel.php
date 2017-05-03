<?php

class GroupModel {
    
    private $dbConn;

    const TABLE = "user_group";
    const TABLE_MEMB = "group_members";
    const INSERT_QUERY = "INSERT INTO " . GroupModel::TABLE . " (groupName) VALUES (:givenGroupName)";
    const SEARCH_QUERY = "SELECT * FROM " . GroupModel::TABLE . " WHERE groupName LIKE :givenSearchWord ";
    const SELECT_GROUPID = "SELECT * FROM " . GroupModel::TABLE . " WHERE groupID = :givenGroupID";
    const DELETE_QUERY = "DELETE FROM " . GroupModel::TABLE . " WHERE groupID = :givenGroupID";
    const UPDATE_QUERY = "UPDATE " . GroupModel::TABLE . " SET groupName = :givenGroupName WHERE groupID = :givenGroupID"; 
    const FIND_GROUP_MEMB = "SELECT COUNT(*) FROM " . GroupModel::TABLE_MEMB . " WHERE groupID = :givenGroupID AND userID = :givenUserID";
    const INSERT_GROUP_MEMB = "INSERT INTO " . GroupModel::TABLE_MEMB . " (userID, groupID) VALUES (:givenUserID, :givenGroupID)";
    const SELECT_GROUP_MEMB = "SELECT users.userID, users.username, group_members.groupID, group_members.memberID FROM " . GroupModel::TABLE_MEMB . " INNER JOIN users ON group_members.userID = users.userID WHERE groupID = :givenGroupID";
    const DELETE_GROUP_MEMB = "DELETE FROM " . GroupModel::TABLE_MEMB . " WHERE memberID = :givenMemberID";
    const GROUP_MEMB_FROM_USERID = "SELECT group_members.userID, user_group.groupName, group_members.groupID, group_members.memberID FROM " . GroupModel::TABLE_MEMB . " INNER JOIN user_group ON group_members.groupID = user_group.groupID WHERE userID = 68";
    
    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;
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
    
    public function doesMemberExist($givenGroupID, $givenUserID){
        $this->existGroupMemb->execute(array("givenUserID" => $givenUserID, "givenGroupID" => $givenGroupID));
        return $this->existGroupMemb->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function addGroupMember($givenGroupID, $givenUserID){
        $this->addGroupMemb->execute(array("givenUserID" => $givenUserID, "givenGroupID" => $givenGroupID));
    }
    
    public function getGroupMember($givenGroupID){
        $this->selGroupMemb->execute(array("givenGroupID" => $givenGroupID));
       return $this->selGroupMemb->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function deleteGroupMember($memberID){
        return $this->delGroupMemb->execute(array("givenMemberID" => $memberID));
    }
    
    public function getGroupMembershipFromUserID($givenUserID){
        $this->selGroupMembFromUser->execute(array("givenUserID" => $givenUserID));
        return $this->selGroupMembFromUser->fetchAll(PDO::FETCH_ASSOC); 
    }
}