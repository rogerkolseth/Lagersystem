<?php



class RestritionModel{
    
    private $dbConn;
    
    
    const TABLE = "restrictions";
    const GROUP_TABLE = "group_members";
    const SELECT_USER_GROUP_RES = "SELECT storage.storageName, restrictions.storageID, restrictions.userID, restrictions.groupID FROM " . RestritionModel::TABLE . " INNER JOIN storage ON storage.storageID = restrictions.storageID LEFT jOIN group_members ON restrictions.groupID = group_members.groupID WHERE restrictions.userID = :givenUserID OR group_members.userID = :givenUserID GROUP BY storage.storageName";
    const SELECT_FROM_STORAGEID = "SELECT users.name, restrictions.storageID, restrictions.userID FROM users INNER JOIN " . RestritionModel::TABLE . " ON users.userID = restrictions.userID WHERE storageID = :givenStorageID";
    const SELECT_FROM_USERID = "SELECT storage.storageName, restrictions.storageID, restrictions.userID FROM storage INNER JOIN " . RestritionModel::TABLE . " ON storage.storageID = restrictions.storageID WHERE userID = :givenUserID";
    const FIND_QUERY = "SELECT COUNT(*) FROM " . RestritionModel::TABLE . " WHERE storageID = :givenStorageID AND userID = :givenUserID";
    const COUNT_QUERY = "SELECT COUNT(*) FROM " . RestritionModel::TABLE . " WHERE userID = :givenUserID";
    const DELETE_STO_ID_QUERY = "DELETE FROM " . RestritionModel::TABLE . " WHERE storageID = :givenStorageID";
    const INSERT_GROUP_RES = "INSERT INTO " . RestritionModel::TABLE . " (storageID, groupID) VALUES (:givenStorageID, :givenGroupID)";
    const SELECT_STORAGE_QUERY = "SELECT storage.storageName, restrictions.storageID, restrictions.userID FROM storage INNER JOIN " . RestritionModel::TABLE . " ON storage.storageID = restrictions.storageID";
    const SELECT_USER_QUERY = "SELECT users.name, restrictions.storageID, restrictions.userID FROM users INNER JOIN " . RestritionModel::TABLE . " ON users.userID = restrictions.userID";
    const INSERT_QUERY = "INSERT INTO " . RestritionModel::TABLE . " (groupID, storageID) VALUES (:givenGroupID, :givenStorageID)";
    const DELETE_QUERY = "DELETE FROM " . RestritionModel::TABLE . " WHERE userID = :removeUserID";
    const DELETE_SINGLE_QUERY = "DELETE FROM " . RestritionModel::TABLE . " WHERE userID = :givenUserID AND storageID = :givenStorageID";
    const FIND_GROUP_EXIST = "SELECT COUNT(*) FROM " . RestritionModel::GROUP_TABLE . " WHERE groupID = :givenGroupID AND userID = :givenUserID";
    const SELECT_GROUP_RES = "SELECT restrictions.resID, storage.storageName, restrictions.groupID, restrictions.storageID FROM " . RestritionModel::TABLE . " INNER JOIN storage ON restrictions.storageID = storage.storageID WHERE groupID = :givenGroupID";
    const DELETE_GROUP_RES = "DELETE FROM " . RestritionModel::TABLE . " WHERE resID = :restrictionID";
    const GROUP_RES_FROM_STO = "SELECT restrictions.resID, restrictions.groupID, user_group.groupName, restrictions.storageID FROM " . RestritionModel::TABLE . " INNER JOIN user_group ON restrictions.groupID = user_group.groupID WHERE storageID = :givenStorageID";
    
    private $addStmt;
    
    public function __construct(PDO $dbConn) {
    $this->dbConn = $dbConn;
    $this->addStmt = $this->dbConn->prepare(RestritionModel::INSERT_QUERY);
    $this->selStoStmt = $this->dbConn->prepare(RestritionModel::SELECT_STORAGE_QUERY);
    $this->selUserStmt = $this->dbConn->prepare(RestritionModel::SELECT_USER_QUERY);
    $this->SelFromUserID = $this->dbConn->prepare(RestritionModel::SELECT_FROM_USERID);
    $this->SelFromStorageID = $this->dbConn->prepare(RestritionModel::SELECT_FROM_STORAGEID);
    $this->delStmt = $this->dbConn->prepare(RestritionModel::DELETE_QUERY);
    $this->delSingleStmt = $this->dbConn->prepare(RestritionModel::DELETE_SINGLE_QUERY);
    $this->findStm = $this->dbConn->prepare(RestritionModel::FIND_QUERY);
    $this->resCount = $this->dbConn->prepare(RestritionModel::COUNT_QUERY);
    $this->delResFromSto = $this->dbConn->prepare(RestritionModel::DELETE_STO_ID_QUERY);
    $this->addGroupRes = $this->dbConn->prepare(RestritionModel::INSERT_GROUP_RES);
    $this->selUserAndGroupRes = $this->dbConn->prepare(RestritionModel::SELECT_USER_GROUP_RES);
    $this->existGroupStm = $this->dbConn->prepare(RestritionModel::FIND_GROUP_EXIST);
    $this->ResFromGroupID = $this->dbConn->prepare(RestritionModel::SELECT_GROUP_RES);
    $this->delGroupRes = $this->dbConn->prepare(RestritionModel::DELETE_GROUP_RES);
    $this->ResFromStoID = $this->dbConn->prepare(RestritionModel::GROUP_RES_FROM_STO);

    }
    
    /**
     * Get all info stored in the DB
     * @return array in associative form
     */
    public function getAllStorageRestrictionInfo() {
        // Fetch all customers as associative arrays
        $this->selStoStmt->execute();
        return $this->selStoStmt->fetchAll(PDO::FETCH_ASSOC);
    }   
    
    public function getAllUserRestrictionInfo() {
        // Fetch all customers as associative arrays
        $this->selUserStmt->execute();
        return $this->selUserStmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    
    public function getAllRestrictionInfoFromUserID($givenUserID) {
        $this->SelFromUserID->execute(array("givenUserID" => $givenUserID));
        return $this->SelFromUserID->fetchAll(PDO::FETCH_ASSOC);
    } 
    
    public function getAllRestrictionInfoFromStorageID($givenStorageID){
        $this->SelFromStorageID->execute(array("givenStorageID" => $givenStorageID));
        return $this->SelFromStorageID->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteUserRestriction($removeUserID){
        return $this->delStmt->execute(array("removeUserID" => $removeUserID));  
    }
    
    public function deleteSingleRestriction($givenUserID, $givenStorageID){
        return $this->delSingleStmt->execute(array("givenUserID" => $givenUserID, "givenStorageID" => $givenStorageID));  
    }
    
    public function doesRestrictionExist($givenUserID, $givenStorageID){
        $this->findStm->execute(array("givenUserID" => $givenUserID, "givenStorageID" => $givenStorageID));
        return $this->findStm->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function addRestriction($givenUserID, $givenStorageID){
        $this->addStmt->execute(array("givenUserID" => $givenUserID, "givenStorageID" => $givenStorageID));
    }
    
    
    public function resCount($givenUserID){
        $this->resCount->execute(array("givenUserID" => $givenUserID));  
        return $this->resCount->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function deleteResStorageID($givenStorageID){
        return $this->delResFromSto->execute(array("givenStorageID" => $givenStorageID));   
    }
    
    public function addGroupRestriction($givenGroupID, $givenStorageID){
        $this->addGroupRes->execute(array("givenStorageID" => $givenStorageID, "givenGroupID" => $givenGroupID));
    }
    
    public function getUserAndGroupRes($givenUserID){
        $this->selUserAndGroupRes->execute(array("givenUserID" => $givenUserID));
        return $this->selUserAndGroupRes->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function doesGroupRestrictionExist($givenUserID, $givenGroupID){
        $this->existGroupStm->execute(array("givenUserID" => $givenUserID, "givenGroupID" => $givenGroupID));
        return $this->existGroupStm->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function getGroupRestriction($givenGroupID){
        $this->ResFromGroupID->execute(array("givenGroupID" => $givenGroupID));
        return $this->ResFromGroupID->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteGroupRestriction($restrictionID){
        return $this->delGroupRes->execute(array("restrictionID" => $restrictionID));  
    }
    
    public function getGroupRestrictionFromSto($givenStorageID){
        $this->ResFromStoID->execute(array("givenStorageID" => $givenStorageID));
        return $this->ResFromStoID->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
}
