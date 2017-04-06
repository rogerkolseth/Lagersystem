<?php



class RestritionModel{
    
    private $dbConn;
    
    
    const TABLE = "restrictions";
    const SELECT_FROM_STORAGEID = "SELECT users.name, restrictions.storageID, restrictions.userID FROM users INNER JOIN " . RestritionModel::TABLE . " ON users.userID = restrictions.userID WHERE storageID = :givenStorageID";
    const SELECT_FROM_USERID = "SELECT storage.storageName, restrictions.storageID, restrictions.userID FROM storage INNER JOIN " . RestritionModel::TABLE . " ON storage.storageID = restrictions.storageID WHERE userID = :givenUserID";
    const FIND_QUERY = "SELECT COUNT(*) FROM " . RestritionModel::TABLE . " WHERE storageID = :givenStorageID AND userID = :givenUserID";
    const COUNT_QUERY = "SELECT COUNT(*) FROM " . RestritionModel::TABLE . " WHERE userID = :givenUserID";
    const DELETE_STO_ID_QUERY = "DELETE FROM " . RestritionModel::TABLE . " WHERE storageID = :givenStorageID";

    const SELECT_STORAGE_QUERY = "SELECT storage.storageName, restrictions.storageID, restrictions.userID FROM storage INNER JOIN " . RestritionModel::TABLE . " ON storage.storageID = restrictions.storageID";
    const SELECT_USER_QUERY = "SELECT users.name, restrictions.storageID, restrictions.userID FROM users INNER JOIN " . RestritionModel::TABLE . " ON users.userID = restrictions.userID";
    const INSERT_QUERY = "INSERT INTO " . RestritionModel::TABLE . " (userID, storageID) VALUES (:givenUserID, :givenStorageID)";
    const DELETE_QUERY = "DELETE FROM " . RestritionModel::TABLE . " WHERE userID = :removeUserID";
    const DELETE_SINGLE_QUERY = "DELETE FROM " . RestritionModel::TABLE . " WHERE userID = :givenUserID AND storageID = :givenStorageID";
    
    /** @var PDOStatement Statement for selecting all entries */

    /** @var PDOStatement Statement for adding new entries */
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
    
}
