<?php



class RestritionModel{
    
    private $dbConn;    //database connection variable
    
    // tables to access
    const TABLE = "restrictions";
    const GROUP_TABLE = "group_members";
    
    // query to run, can include binded variables
    const SELECT_USER_GROUP_RES = "SELECT storage.storageName, restrictions.storageID, restrictions.userID, restrictions.groupID FROM " . RestritionModel::TABLE . " INNER JOIN storage ON storage.storageID = restrictions.storageID LEFT jOIN group_members ON restrictions.groupID = group_members.groupID WHERE restrictions.userID = :givenUserID OR group_members.userID = :givenUserID GROUP BY storage.storageName";
    const SELECT_FROM_STORAGEID = "SELECT users.name, restrictions.storageID, restrictions.userID FROM users INNER JOIN " . RestritionModel::TABLE . " ON users.userID = restrictions.userID WHERE storageID = :givenStorageID";
    const SELECT_FROM_USERID = "SELECT storage.storageName, restrictions.storageID, restrictions.userID FROM storage INNER JOIN " . RestritionModel::TABLE . " ON storage.storageID = restrictions.storageID WHERE userID = :givenUserID";
    const FIND_QUERY = "SELECT COUNT(*) FROM " . RestritionModel::TABLE . " WHERE storageID = :givenStorageID AND userID = :givenUserID";
    const COUNT_QUERY = "SELECT COUNT(*) FROM " . RestritionModel::TABLE . " WHERE userID = :givenUserID";
    const DELETE_STO_ID_QUERY = "DELETE FROM " . RestritionModel::TABLE . " WHERE storageID = :givenStorageID";
    const INSERT_GROUP_RES = "INSERT INTO " . RestritionModel::TABLE . " (storageID, groupID) VALUES (:givenStorageID, :givenGroupID)";
    const SELECT_STORAGE_QUERY = "SELECT storage.storageName, restrictions.storageID, restrictions.userID FROM storage INNER JOIN " . RestritionModel::TABLE . " ON storage.storageID = restrictions.storageID";
    const SELECT_USER_QUERY = "SELECT users.name, restrictions.storageID, restrictions.userID FROM users INNER JOIN " . RestritionModel::TABLE . " ON users.userID = restrictions.userID";
    const INSERT_QUERY = "INSERT INTO " . RestritionModel::TABLE . " (userID, storageID) VALUES (:givenUserID, :givenStorageID)";
    const DELETE_QUERY = "DELETE FROM " . RestritionModel::TABLE . " WHERE userID = :removeUserID";
    const DELETE_SINGLE_QUERY = "DELETE FROM " . RestritionModel::TABLE . " WHERE userID = :givenUserID AND storageID = :givenStorageID";
    const FIND_GROUP_EXIST = "SELECT COUNT(*) FROM " . RestritionModel::GROUP_TABLE . " WHERE groupID = :givenGroupID AND userID = :givenUserID";
    const SELECT_GROUP_RES = "SELECT restrictions.resID, storage.storageName, restrictions.groupID, restrictions.storageID FROM " . RestritionModel::TABLE . " INNER JOIN storage ON restrictions.storageID = storage.storageID WHERE groupID = :givenGroupID";
    const DELETE_GROUP_RES = "DELETE FROM " . RestritionModel::TABLE . " WHERE resID = :restrictionID";
    const GROUP_RES_FROM_STO = "SELECT restrictions.resID, restrictions.groupID, user_group.groupName, restrictions.storageID FROM " . RestritionModel::TABLE . " INNER JOIN user_group ON restrictions.groupID = user_group.groupID WHERE storageID = :givenStorageID";

    
    public function __construct(PDO $dbConn) {
    $this->dbConn = $dbConn;    // connect to database
    // prepare the statements
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
     * Get all storage restricitons from databsae
     */ 
    public function getAllStorageRestrictionInfo() {
        $this->selStoStmt->execute();   // execute SQL statement
        return $this->selStoStmt->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }   
    
    /**
     * Get all user restrictions from database
     */ 
    public function getAllUserRestrictionInfo() {
        $this->selUserStmt->execute();  // execute SQL statement
        return $this->selUserStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    } 
    
    /**
     * Get all storage restrictions a given user have
     */ 
    public function getAllRestrictionInfoFromUserID($givenUserID) {
         //bind variable to the parameter as strings, and execute SQL statement
        $this->SelFromUserID->execute(array("givenUserID" => $givenUserID));
        return $this->SelFromUserID->fetchAll(PDO::FETCH_ASSOC);    // return fetched result
    } 
    
    /**
     * Get all users with restrictions to a given storage
     */ 
    public function getAllRestrictionInfoFromStorageID($givenStorageID){
         //bind variable to the parameter as strings, and execute SQL statement
        $this->SelFromStorageID->execute(array("givenStorageID" => $givenStorageID));
        return $this->SelFromStorageID->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }
    
    /**
     * Delete all restrictions a given user have
     */ 
    public function deleteUserRestriction($removeUserID){
         //bind variable to the parameter as strings, and execute SQL statement
        return $this->delStmt->execute(array("removeUserID" => $removeUserID));  
    }
    
    /**
     * Delete one spesific restriction from given userID and storageID
     */ 
    public function deleteSingleRestriction($givenUserID, $givenStorageID){
         //bind variable to the parameter as strings, and execute SQL statement
        return $this->delSingleStmt->execute(array("givenUserID" => $givenUserID, "givenStorageID" => $givenStorageID));  
    }
    
    /**
     * Check if given user allready have restriction to given storage
     */ 
    public function doesRestrictionExist($givenUserID, $givenStorageID){
         //bind variable to the parameter as strings, and execute SQL statement
        $this->findStm->execute(array("givenUserID" => $givenUserID, "givenStorageID" => $givenStorageID));
        return $this->findStm->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }
    
    /**
     * Giev a user restriction to a storage
     */ 
    public function addRestriction($givenUserID, $givenStorageID){
         //bind variable to the parameter as strings, and execute SQL statement
        $this->addStmt->execute(array("givenUserID" => $givenUserID, "givenStorageID" => $givenStorageID));
    }
    
    /**
     * check how many storage user have restriction to 
     */ 
    public function resCount($givenUserID){
         //bind variable to the parameter as strings, and execute SQL statement
        $this->resCount->execute(array("givenUserID" => $givenUserID));  
        return $this->resCount->fetchAll(PDO::FETCH_ASSOC);     // return fetched result
    }
    
    /**
     * Delete all restrictions to a given storage
     */ 
    public function deleteResStorageID($givenStorageID){
         //bind variable to the parameter as strings, and execute SQL statement
        return $this->delResFromSto->execute(array("givenStorageID" => $givenStorageID));   
    }
    
    /**
     * Give a group restriction to a given storage
     */ 
    public function addGroupRestriction($givenGroupID, $givenStorageID){
         //bind variable to the parameter as strings, and execute SQL statement
        $this->addGroupRes->execute(array("givenStorageID" => $givenStorageID, "givenGroupID" => $givenGroupID));
    }
    
    /**
     * Get all restrictions a user have, including restrictions from group memberships
     */ 
    public function getUserAndGroupRes($givenUserID){
         //bind variable to the parameter as strings, and execute SQL statement
        $this->selUserAndGroupRes->execute(array("givenUserID" => $givenUserID));
        return $this->selUserAndGroupRes->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
    /**
     * Check if group allready have restriction to given storage
     */ 
    public function doesGroupRestrictionExist($givenUserID, $givenGroupID){
         //bind variable to the parameter as strings, and execute SQL statement
        $this->existGroupStm->execute(array("givenUserID" => $givenUserID, "givenGroupID" => $givenGroupID));
        return $this->existGroupStm->fetchAll(PDO::FETCH_ASSOC);    // return fetched result
    }
    
    /**
     * Get all retriction a given group have
     */ 
    public function getGroupRestriction($givenGroupID){
         //bind variable to the parameter as strings, and execute SQL statement
        $this->ResFromGroupID->execute(array("givenGroupID" => $givenGroupID));
        return $this->ResFromGroupID->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
    /**
     * Delete a single group restriction
     */ 
    public function deleteGroupRestriction($restrictionID){
         //bind variable to the parameter as strings, and execute SQL statement
        return $this->delGroupRes->execute(array("restrictionID" => $restrictionID));  
    }
    
    /**
     * Get all groups with restrictions to a given storage
     */ 
    public function getGroupRestrictionFromSto($givenStorageID){
         //bind variable to the parameter as strings, and execute SQL statement
        $this->ResFromStoID->execute(array("givenStorageID" => $givenStorageID));
        return $this->ResFromStoID->fetchAll(PDO::FETCH_ASSOC); // return fetched result
    }
    
    
    
}
