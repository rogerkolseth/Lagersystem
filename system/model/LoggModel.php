<?php

class LoggModel {
    
    private $dbConn;    //database connection variable
    
    // tables to access
    const TABLE = "logg";
    const CHECK_TABLE = "loggtype";
    
    // query to run, can include binded variables
    const SELECT_QUERY = 
         "SELECT lt.typeName, l.desc, s1.storageName, s2.storageName AS fromStorage, s3.storageName AS toStorage, l.quantity, l.oldQuantity, l.newQuantity, l.differential, g.groupName, u1.username, u2.username AS onUsername, p.productName, l.customerNr, l.deletedUser, l.deletedStorage, l.deletedProduct, l.deletedGroup, DATE_FORMAT(l.date,'%d %b %Y %T') AS date FROM " . LoggModel::TABLE . " AS l "
        ."LEFT JOIN storage as s1 ON l.storageID = s1.storageID "
        ."LEFT JOIN storage as s2 ON l.fromStorageID = s2.storageID "
        ."LEFT JOIN storage as s3 ON l.toStorageID = s3.storageID "
        ."LEFT JOIN users as u1 ON l.userID = u1.userID "
        ."LEFT JOIN users as u2 ON l.onUserID = u2.userID "
        ."LEFT JOIN loggtype as lt ON l.typeID = lt.typeID " 
        ."LEFT JOIN user_group as g ON l.groupID = g.groupID "   
        ."LEFT JOIN products as p ON l.productID = p.productID WHERE lt.typeName LIKE :givenSearchWord OR l.desc LIKE :givenSearchWord OR s1.storageName LIKE :givenSearchWord OR s2.storageName LIKE :givenSearchWord OR s3.storageName LIKE :givenSearchWord OR l.quantity LIKE :givenSearchWord "
            . "OR l.oldQuantity LIKE :givenSearchWord OR l.newQuantity LIKE :givenSearchWord OR l.differential LIKE :givenSearchWord OR u1.username LIKE :givenSearchWord OR u2.username OR p.productName LIKE :givenSearchWord OR customerNr LIKE :givenSearchWord OR g.groupName LIKE :givenSearchWord ORDER BY date DESC LIMIT 100";
    
    const SELECT_LATEST_QUERY = 
         "SELECT lt.typeName, l.desc, s1.storageName, s2.storageName AS fromStorage, s3.storageName AS toStorage, l.quantity, l.oldQuantity, l.newQuantity, l.differential, g.groupName, u1.username, u2.username AS onUsername, p.productName, l.customerNr, l.deletedUser, l.deletedStorage, l.deletedProduct, l.deletedGroup, DATE_FORMAT(l.date,'%d %b %Y %T') AS date FROM " . LoggModel::TABLE . " AS l "
        ."LEFT JOIN storage as s1 ON l.storageID = s1.storageID "
        ."LEFT JOIN storage as s2 ON l.fromStorageID = s2.storageID "
        ."LEFT JOIN storage as s3 ON l.toStorageID = s3.storageID "
        ."LEFT JOIN users as u1 ON l.userID = u1.userID "
        ."LEFT JOIN users as u2 ON l.onUserID = u2.userID "
        ."LEFT JOIN loggtype as lt ON l.typeID = lt.typeID "  
        ."LEFT JOIN user_group as g ON l.groupID = g.groupID "    
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
      $this->dbConn = $dbConn;  // connect to database
      // prepare the statements
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
    
    /**
     * Get all logg info
     */ 
    public function getAllLoggInfo($givenLogSearchWord){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selStmt->execute(array("givenSearchWord" => $givenLogSearchWord));
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * add new register about transfering a product
     */ 
    public function transferLogg($type, $descript, $sessionID, $fromStorageID, $toStorageID, $transferProductID, $transferQuantity) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addTransLogg->execute(array("givenType" => $type, "givenDesc" => $descript, "givenSessionID" => $sessionID, "givenFromStorageID" => $fromStorageID, "givenToStorageID" => $toStorageID, "givenProductID" => $transferProductID, "givenQuantity" => $transferQuantity));
    }
    
    /**
     * add new register about stockdelivery
     */ 
    public function stockdelivery($type, $descript, $sessionID, $toStorageID, $transferProductID, $transferQuantity) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addDeliveryLogg->execute(array("givenType" => $type, "givenDesc" => $descript, "givenSessionID" => $sessionID, "givenToStorageID" => $toStorageID, "givenProductID" => $transferProductID, "givenQuantity" => $transferQuantity));
    }
    
    /**
     * add new register about stocktaking
     */ 
    public function stocktaking($type, $descript, $sessionID, $givenStorageID, $givenProductID, $givenQuantity, $oldQuantity, $differance){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->stocktakeLogg->execute(array("givenType" => $type, "givenDesc" => $descript, "givenSessionID" => $sessionID, "givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity, "givenOldQuantity" => $oldQuantity, "givenDifferanse" => $differance));
    }
    
    /**
     * get 10 lates loggs
     */ 
    public function getLatestLoggInfo() {
        $this->selLateStmt->execute();  // execute SQL statement
        return $this->selLateStmt->fetchALL(PDO::FETCH_ASSOC);      // return fetched result
    }
    
    /**
     * add new register about user logging in 
     */ 
    public function loginLog($type, $desc, $givenUserID){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->loginLogg->execute(array("givenType" => $type, "givenDesc" => $desc, "givenUserID" => $givenUserID));
    }
    
    /**
     * check if incident should be logged, returns 1 if yes
     */ 
    public function loggCheck($givenTypeID) {
        //bind variable to the parameter as strings, and execute SQL statement
        $this->checkStmt->execute(array("givenTypeID" => $givenTypeID));
        return $this->checkStmt->fetchAll(PDO::FETCH_ASSOC);    // return fetched result
    }
    
    /**
     * edit what incidents to be logged
     */ 
    public function editLoggCheck($typeID, $loggCheck) {
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->editLoggCheck->execute(array("givenTypeID" => $typeID, "givenLoggCheck" => $loggCheck));
    }
    
    /**
     * get status of what to logg
     */ 
    public function getLoggCheckStatus() {
        $this->chechStatus->execute();  // execute SQL statement
        return $this->chechStatus->fetchALL(PDO::FETCH_ASSOC);      // return fetched result  
    }
    
    /**
     * pass variables containing advance search criterias
     */ 
    public function advanceSearch($loggTypeArray, $storageArray, $toStorageArray, $fromStorageArray, $usernameArray, $onUserArray, $productArray, $groupArray, $fromDateArray, $toDateArray){
        // check if array contains value
        if(empty(!$loggTypeArray)){ 
        $type = implode(',', array_fill(0, count($loggTypeArray), '?'));    // create a '?' for each value
        $typeQuery = "l.typeID IN ($type)";     // create part of query for binded value
        } else {$typeQuery = "l.typeID IN (SELECT l.typeID)";}  // adding this query if array is empty
        
        // check if array contains value
        if(empty(!$storageArray)){
        $storage = implode(',', array_fill(0, count($storageArray), '?'));  // create a '?' for each value
        $storageQuery = "AND l.storageID IN ($storage)";    // create part of query for binded value
        } else {$storageQuery = "";}    // adding this query if array is empty
        
        // check if array contains value
        if(empty(!$toStorageArray)){
        $toStorage = implode(',', array_fill(0, count($toStorageArray), '?'));  // create a '?' for each value
        $toStorageQuery = "AND l.toStorageID IN ($toStorage)";  // create part of query for binded value
        } else {$toStorageQuery = "";}  // adding this query if array is empty
        
        // check if array contains value
        if(empty(!$fromStorageArray)){
        $fromStorage = implode(',', array_fill(0, count($fromStorageArray), '?'));  // create a '?' for each value
        $fromStorageQuery = "AND l.fromStorageID IN ($fromStorage)";    // create part of query for binded value
        } else {$fromStorageQuery = "";}    // adding this query if array is empty
        
        // check if array contains value
        if(empty(!$usernameArray)){
        $username = implode(',', array_fill(0, count($usernameArray), '?'));    // create a '?' for each value
        $usernameQuery = "AND l.userID IN ($username)"; // create part of query for binded value
        } else {$usernameQuery = "";}   // adding this query if array is empty
        
        // check if array contains value
        if(empty(!$onUserArray)){
        $onUser = implode(',', array_fill(0, count($onUserArray), '?'));    // create a '?' for each value
        $onUserQuery = "AND l.onUserID IN ($onUser)";   // create part of query for binded value
        } else {$onUserQuery = "";} // adding this query if array is empty
        
        // check if array contains value
        if(empty(!$productArray)){
        $product = implode(',', array_fill(0, count($productArray), '?'));  // create a '?' for each value
        $productQuery = "AND l.productID IN ($product)";    // create part of query for binded value
        } else {$productQuery = "";}    // adding this query if array is empty
        
        // check if array contains value
        if(empty(!$groupArray)){
        $group = implode(',', array_fill(0, count($groupArray), '?'));  // create a '?' for each value
        $goupQuery = "AND g.groupID IN ($group)";   // create part of query for binded value
        } else {$goupQuery = "";}   // adding this query if array is empty
        
        // check if array contains value
        if(empty(!$fromDateArray) && empty(!$toDateArray)){
        $fromDate = implode(',', array_fill(0, count($fromDateArray), '?'));    // create a '?' for each value
        $toDate = implode(',', array_fill(0, count($toDateArray), '?'));    // create a '?' for each value
        $fromDateQuery = "AND (l.date BETWEEN $fromDate";   // create part of query for binded value
        $toDateQuery = "AND $toDate)";  // create part of query for binded value
        $params = array_merge($loggTypeArray, $storageArray, $toStorageArray, $fromStorageArray, $usernameArray, $onUserArray, $groupArray, $productArray,[$fromDateArray], [$toDateArray]);
        } else {$fromDateQuery = ""; $toDateQuery= "";  // adding this query if array is empty
        $params = array_merge($loggTypeArray, $storageArray, $toStorageArray, $fromStorageArray, $usernameArray, $onUserArray, $groupArray, $productArray);
        }
        
        // query to run
        $sql = "SELECT lt.typeName, l.desc, s1.storageName, s2.storageName AS fromStorage, s3.storageName AS toStorage, l.quantity, l.oldQuantity, l.newQuantity, l.differential, g.groupName, u1.username, u2.username AS onUsername, p.productName, l.customerNr, l.deletedUser, l.deletedStorage, l.deletedProduct, l.deletedGroup, DATE_FORMAT(l.date,'%d %b %Y %T') AS date FROM " . LoggModel::TABLE . " AS l "
        ."LEFT JOIN storage as s1 ON l.storageID = s1.storageID "
        ."LEFT JOIN storage as s2 ON l.fromStorageID = s2.storageID "
        ."LEFT JOIN storage as s3 ON l.toStorageID = s3.storageID "
        ."LEFT JOIN users as u1 ON l.userID = u1.userID "
        ."LEFT JOIN users as u2 ON l.onUserID = u2.userID "
        ."LEFT JOIN loggtype as lt ON l.typeID = lt.typeID "    
        ."LEFT JOIN user_group as g ON l.groupID = g.groupID "
        ."LEFT JOIN products as p ON l.productID = p.productID WHERE $typeQuery $storageQuery $toStorageQuery $fromStorageQuery $usernameQuery $onUserQuery $goupQuery $productQuery $fromDateQuery $toDateQuery ORDER BY date DESC";
        
        $this->advSearch = $this->dbConn->prepare($sql);    // prepare the statement
        
        $this->advSearch->execute($params); // execute SQL statement

        return $this->advSearch->fetchALL(PDO::FETCH_ASSOC);    // return fetched result   
        
    }
    
}