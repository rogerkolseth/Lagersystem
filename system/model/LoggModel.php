<?php

class LoggModel {
    
    private $dbConn;
    
    const TABLE = "logg";
    const SELECT_QUERY = 
         "SELECT l.type, l.desc, s1.storageName, s2.storageName AS fromStorage, s3.storageName AS toStorage, l.quantity, l.oldQuantity, l.newQuantity, l.differential, u1.username, u2.username AS onUsername, p.productName, l.customerNr, DATE_FORMAT(l.date,'%d %b %Y %T') AS date FROM " . LoggModel::TABLE . " AS l "
        ."LEFT JOIN storage as s1 ON l.storageID = s1.storageID "
        ."LEFT JOIN storage as s2 ON l.fromStorageID = s2.storageID "
        ."LEFT JOIN storage as s3 ON l.toStorageID = s3.storageID "
        ."LEFT JOIN users as u1 ON l.userID = u1.userID "
        ."LEFT JOIN users as u2 ON l.onUserID = u2.userID "
        ."LEFT JOIN products as p ON l.productID = p.productID WHERE l.type LIKE :givenSearchWord OR l.desc LIKE :givenSearchWord OR s1.storageName LIKE :givenSearchWord OR s2.storageName LIKE :givenSearchWord OR s3.storageName LIKE :givenSearchWord OR l.quantity LIKE :givenSearchWord "
            . "OR l.oldQuantity LIKE :givenSearchWord OR l.newQuantity LIKE :givenSearchWord OR l.differential LIKE :givenSearchWord OR u1.username LIKE :givenSearchWord OR u2.username OR p.productName LIKE :givenSearchWord OR customerNr LIKE :givenSearchWord ORDER BY date DESC";
    
    const SELECT_LATEST_QUERY = 
         "SELECT l.type, l.desc, s1.storageName, s2.storageName AS fromStorage, s3.storageName AS toStorage, l.quantity, l.oldQuantity, l.newQuantity, l.differential, u1.username, u2.username AS onUsername, p.productName, l.customerNr, DATE_FORMAT(l.date,'%d %b %Y %T') AS date FROM " . LoggModel::TABLE . " AS l "
        ."LEFT JOIN storage as s1 ON l.storageID = s1.storageID "
        ."LEFT JOIN storage as s2 ON l.fromStorageID = s2.storageID "
        ."LEFT JOIN storage as s3 ON l.toStorageID = s3.storageID "
        ."LEFT JOIN users as u1 ON l.userID = u1.userID "
        ."LEFT JOIN users as u2 ON l.onUserID = u2.userID "
        ."LEFT JOIN products as p ON l.productID = p.productID ORDER BY date DESC LIMIT 10 ";
    
    const INSERT_QUERY = "INSERT INTO " . LoggModel::TABLE . " (type, desc, storageID, fromStorageID, toStorageID, quantity, oldQuantity, newQuantity, differential, userID, onUserID, productID, date, customerNr) "
            . "VALUES (:type, :desc, :storageID, :fromStorageID, :toStorageID, :quantity, :oldQuantity, :newQuantity, :differential, :userID, :onUserID, :productID, :date, :customerNr)";

    const INSERT_TRANS_LOGG = "INSERT INTO " . LoggModel::TABLE . " (logg.type, logg.desc, logg.fromStorageID, logg.toStorageID, logg.quantity, logg.userID, logg.productID, logg.date) VALUES "
            . "(:givenType, :givenDesc, :givenFromStorageID, :givenToStorageID, :givenQuantity, :givenSessionID, :givenProductID, NOW())";
    
    const INSERT_DELIV_LOGG = "INSERT INTO " . LoggModel::TABLE . " (logg.type, logg.desc, logg.toStorageID, logg.quantity, logg.userID, logg.productID, logg.date) VALUES "
            . "(:givenType, :givenDesc, :givenToStorageID, :givenQuantity, :givenSessionID, :givenProductID, NOW())";
    
    const INSERT_StOCKTAKE_LOGG = "INSERT INTO " . LoggModel::TABLE . " (logg.type, logg.desc, logg.storageID, logg.newQuantity, logg.oldQuantity, logg.differential, logg.productID, logg.userID, logg.date) VALUES "
            . "(:givenType, :givenDesc, :givenStorageID, :givenQuantity, :givenOldQuantity, :givenDifferanse, :givenProductID, :givenSessionID, NOW())";
    
    const INSERT_LOGIN_LOGG = "INSERT INTO " . LoggModel::TABLE . " (logg.type, logg.desc, logg.userID, logg.date) VALUES (:givenType, :givenDesc, :givenUserID, NOW())";
    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;
      $this->selStmt = $this->dbConn->prepare(LoggModel::SELECT_QUERY);
      $this->addStmt = $this->dbConn->prepare(LoggModel::INSERT_QUERY);
      $this->addTransLogg = $this->dbConn->prepare(LoggModel::INSERT_TRANS_LOGG);
      $this->selLateStmt = $this->dbConn->prepare(LoggModel::SELECT_LATEST_QUERY);
      $this->addDeliveryLogg = $this->dbConn->prepare(LoggModel::INSERT_DELIV_LOGG);
      $this->stocktakeLogg = $this->dbConn->prepare(LoggModel::INSERT_StOCKTAKE_LOGG);
      $this->loginLogg = $this->dbConn->prepare(LoggModel::INSERT_LOGIN_LOGG);
    }   
    
    public function getAllLoggInfo($givenLogSearchWord){
        $this->selStmt->execute(array("givenSearchWord" => $givenLogSearchWord));
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function transferLogg($type, $descript, $sessionID, $fromStorageID, $toStorageID, $transferProductID, $transferQuantity) {
        return $this->addTransLogg->execute(array("givenType" => $type, "givenDesc" => $descript, "givenSessionID" => $sessionID, "givenFromStorageID" => $fromStorageID, "givenToStorageID" => $toStorageID, "givenProductID" => $transferProductID, "givenQuantity" => $transferQuantity));
    }
    
    public function stockdelivery($type, $descript, $sessionID, $toStorageID, $transferProductID, $transferQuantity) {
        return $this->addDeliveryLogg->execute(array("givenType" => $type, "givenDesc" => $descript, "givenSessionID" => $sessionID, "givenToStorageID" => $toStorageID, "givenProductID" => $transferProductID, "givenQuantity" => $transferQuantity));
    }
    
    public function stocktaking($type, $descript, $sessionID, $givenStorageID, $givenProductID, $givenQuantity, $oldQuantity, $differance){
        return $this->stocktakeLogg->execute(array("givenType" => $type, "givenDesc" => $descript, "givenSessionID" => $sessionID, "givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity, "givenOldQuantity" => $oldQuantity, "givenDifferanse" => $differance));
    }
    
    public function getLatestLoggInfo() {
        $this->selLateStmt->execute();
        return $this->selLateStmt->fetchALL(PDO::FETCH_ASSOC);    
    }
    
    public function loginLog($type, $desc, $givenUserID){
        return $this->loginLogg->execute(array("givenType" => $type, "givenDesc" => $desc, "givenUserID" => $givenUserID));
    }
}