<?php

class LoggModel {
    
    private $dbConn;
    
    const TABLE = "logg";
    const CHECK_TABLE = "loggtype";
    
    const SELECT_QUERY = 
         "SELECT lt.typeName, l.desc, s1.storageName, s2.storageName AS fromStorage, s3.storageName AS toStorage, l.quantity, l.oldQuantity, l.newQuantity, l.differential, u1.username, u2.username AS onUsername, p.productName, l.customerNr, DATE_FORMAT(l.date,'%d %b %Y %T') AS date FROM " . LoggModel::TABLE . " AS l "
        ."LEFT JOIN storage as s1 ON l.storageID = s1.storageID "
        ."LEFT JOIN storage as s2 ON l.fromStorageID = s2.storageID "
        ."LEFT JOIN storage as s3 ON l.toStorageID = s3.storageID "
        ."LEFT JOIN users as u1 ON l.userID = u1.userID "
        ."LEFT JOIN users as u2 ON l.onUserID = u2.userID "
        ."LEFT JOIN loggType as lt ON l.typeID = lt.typeID "    
        ."LEFT JOIN products as p ON l.productID = p.productID WHERE lt.typeName LIKE :givenSearchWord OR l.desc LIKE :givenSearchWord OR s1.storageName LIKE :givenSearchWord OR s2.storageName LIKE :givenSearchWord OR s3.storageName LIKE :givenSearchWord OR l.quantity LIKE :givenSearchWord "
            . "OR l.oldQuantity LIKE :givenSearchWord OR l.newQuantity LIKE :givenSearchWord OR l.differential LIKE :givenSearchWord OR u1.username LIKE :givenSearchWord OR u2.username OR p.productName LIKE :givenSearchWord OR customerNr LIKE :givenSearchWord ORDER BY date DESC LIMIT 100";
    
    const SELECT_LATEST_QUERY = 
         "SELECT lt.typeName, l.desc, s1.storageName, s2.storageName AS fromStorage, s3.storageName AS toStorage, l.quantity, l.oldQuantity, l.newQuantity, l.differential, u1.username, u2.username AS onUsername, p.productName, l.customerNr, DATE_FORMAT(l.date,'%d %b %Y %T') AS date FROM " . LoggModel::TABLE . " AS l "
        ."LEFT JOIN storage as s1 ON l.storageID = s1.storageID "
        ."LEFT JOIN storage as s2 ON l.fromStorageID = s2.storageID "
        ."LEFT JOIN storage as s3 ON l.toStorageID = s3.storageID "
        ."LEFT JOIN users as u1 ON l.userID = u1.userID "
        ."LEFT JOIN users as u2 ON l.onUserID = u2.userID "
        ."LEFT JOIN loggType as lt ON l.typeID = lt.typeID "     
        ."LEFT JOIN products as p ON l.productID = p.productID ORDER BY date DESC LIMIT 10 ";
    
    const INSERT_QUERY = "INSERT INTO " . LoggModel::TABLE . " (typeID, desc, storageID, fromStorageID, toStorageID, quantity, oldQuantity, newQuantity, differential, userID, onUserID, productID, date, customerNr) "
            . "VALUES (:type, :desc, :storageID, :fromStorageID, :toStorageID, :quantity, :oldQuantity, :newQuantity, :differential, :userID, :onUserID, :productID, :date, :customerNr)";

    const INSERT_TRANS_LOGG = "INSERT INTO " . LoggModel::TABLE . " (logg.typeID, logg.desc, logg.fromStorageID, logg.toStorageID, logg.quantity, logg.userID, logg.productID, logg.date) VALUES "
            . "(:givenType, :givenDesc, :givenFromStorageID, :givenToStorageID, :givenQuantity, :givenSessionID, :givenProductID, NOW())";
    
    const INSERT_DELIV_LOGG = "INSERT INTO " . LoggModel::TABLE . " (logg.typeID, logg.desc, logg.toStorageID, logg.quantity, logg.userID, logg.productID, logg.date) VALUES "
            . "(:givenType, :givenDesc, :givenToStorageID, :givenQuantity, :givenSessionID, :givenProductID, NOW())";
    
    const INSERT_StOCKTAKE_LOGG = "INSERT INTO " . LoggModel::TABLE . " (logg.typeID, logg.desc, logg.storageID, logg.newQuantity, logg.oldQuantity, logg.differential, logg.productID, logg.userID, logg.date) VALUES "
            . "(:givenType, :givenDesc, :givenStorageID, :givenQuantity, :givenOldQuantity, :givenDifferanse, :givenProductID, :givenSessionID, NOW())";
    
    const INSERT_LOGIN_LOGG = "INSERT INTO " . LoggModel::TABLE . " (logg.typeID, logg.desc, logg.userID, logg.date) VALUES (:givenType, :givenDesc, :givenUserID, NOW())";
    
    const CHECK_IF_LOGG = "SELECT loggtype.typeCheck FROM " . LoggModel::CHECK_TABLE . " WHERE loggtype.typeID = :givenTypeID";
    
    const EDIT_LOGG_CHECK = "UPDATE " . LoggModel::CHECK_TABLE . " SET typeCheck = :givenLoggCheck WHERE typeID = :givenTypeID";
    const SELECT_CHECHSTATUS = "SELECT * FROM " . LoggModel::CHECK_TABLE;
    
    public function __construct(PDO $dbConn) { 
      $this->dbConn = $dbConn;
      $this->selStmt = $this->dbConn->prepare(LoggModel::SELECT_QUERY);
      $this->addStmt = $this->dbConn->prepare(LoggModel::INSERT_QUERY);
      $this->addTransLogg = $this->dbConn->prepare(LoggModel::INSERT_TRANS_LOGG);
      $this->selLateStmt = $this->dbConn->prepare(LoggModel::SELECT_LATEST_QUERY);
      $this->addDeliveryLogg = $this->dbConn->prepare(LoggModel::INSERT_DELIV_LOGG);
      $this->stocktakeLogg = $this->dbConn->prepare(LoggModel::INSERT_StOCKTAKE_LOGG);
      $this->loginLogg = $this->dbConn->prepare(LoggModel::INSERT_LOGIN_LOGG);
      $this->checkStmt = $this->dbConn->prepare(LoggModel::CHECK_IF_LOGG);
      $this->editLoggCheck = $this->dbConn->prepare(LoggModel::EDIT_LOGG_CHECK);
      $this->chechStatus = $this->dbConn->prepare(LoggModel::SELECT_CHECHSTATUS);
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
    
    public function loggCheck($givenTypeID) {
        $this->checkStmt->execute(array("givenTypeID" => $givenTypeID));
        return $this->checkStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function editLoggCheck($typeID, $loggCheck) {
        return $this->editLoggCheck->execute(array("givenTypeID" => $typeID, "givenLoggCheck" => $loggCheck));
    }
    
    public function getLoggCheckStatus() {
        $this->chechStatus->execute();
        return $this->chechStatus->fetchALL(PDO::FETCH_ASSOC);    
    }
    
    public function advanceSearch($loggTypeArray, $storageArray, $toStorageArray, $fromStorageArray, $usernameArray, $onUserArray, $productArray, $fromDateArray, $toDateArray){
        if(empty(!$loggTypeArray)){
        $type = implode(',', array_fill(0, count($loggTypeArray), '?'));
        $typeQuery = "l.typeID IN ($type)";
        } else {$typeQuery = "l.typeID IN (SELECT l.typeID)";}
        
        if(empty(!$storageArray)){
        $storage = implode(',', array_fill(0, count($storageArray), '?'));
        $storageQuery = "AND l.storageID IN ($storage)";
        } else {$storageQuery = "";}
        
        if(empty(!$toStorageArray)){
        $toStorage = implode(',', array_fill(0, count($toStorageArray), '?'));
        $toStorageQuery = "AND l.toStorageID IN ($toStorage)";
        } else {$toStorageQuery = "";}
        
        if(empty(!$fromStorageArray)){
        $fromStorage = implode(',', array_fill(0, count($fromStorageArray), '?'));
        $fromStorageQuery = "AND l.fromStorageID IN ($fromStorage)";
        } else {$fromStorageQuery = "";}
        
        if(empty(!$usernameArray)){
        $username = implode(',', array_fill(0, count($usernameArray), '?'));
        $usernameQuery = "AND l.userID IN ($username)";
        } else {$usernameQuery = "";}
        
        if(empty(!$onUserArray)){
        $onUser = implode(',', array_fill(0, count($onUserArray), '?'));
        $onUserQuery = "AND l.onUserID IN ($onUser)";
        } else {$onUserQuery = "";}
        
        if(empty(!$productArray)){
        $product = implode(',', array_fill(0, count($productArray), '?'));
        $productQuery = "AND l.productID IN ($product)";
        } else {$productQuery = "";}
        
        if(empty(!$fromDateArray) && empty(!$toDateArray)){
        $fromDate = implode(',', array_fill(0, count($fromDateArray), '?'));
        $toDate = implode(',', array_fill(0, count($toDateArray), '?'));
        $dateQuery = "AND (l.date BETWEEN $fromDate AND $toDate)";
        } else {$dateQuery = "";}
        
        
        $sql = "SELECT lt.typeName, l.desc, s1.storageName, s2.storageName AS fromStorage, s3.storageName AS toStorage, l.quantity, l.oldQuantity, l.newQuantity, l.differential, u1.username, u2.username AS onUsername, p.productName, l.customerNr, DATE_FORMAT(l.date,'%d %b %Y %T') AS date FROM " . LoggModel::TABLE . " AS l "
        ."LEFT JOIN storage as s1 ON l.storageID = s1.storageID "
        ."LEFT JOIN storage as s2 ON l.fromStorageID = s2.storageID "
        ."LEFT JOIN storage as s3 ON l.toStorageID = s3.storageID "
        ."LEFT JOIN users as u1 ON l.userID = u1.userID "
        ."LEFT JOIN users as u2 ON l.onUserID = u2.userID "
        ."LEFT JOIN loggType as lt ON l.typeID = lt.typeID "    
        ."LEFT JOIN products as p ON l.productID = p.productID WHERE $typeQuery $storageQuery $toStorageQuery $fromStorageQuery $usernameQuery $onUserQuery $productQuery $dateQuery ORDER BY date DESC";
        
        $this->advSearch = $this->dbConn->prepare($sql);
        $params = array_merge($loggTypeArray, $storageArray, $toStorageArray, $fromStorageArray, $usernameArray, $onUserArray, $productArray,[$fromDateArray, $toDateArray]);
        $this->advSearch->execute($params);
        return $sql;
        //return $this->advSearch->fetchALL(PDO::FETCH_ASSOC);    
        
    }
    
}