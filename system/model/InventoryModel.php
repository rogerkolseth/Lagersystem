<?php

class InventoryModel {
    
    private $dbConn;    //database connection variable

    // tables to access
    const TABLE = "inventory";  
    const MACADRESSE_TABLE = "macadresse";
    
    // query to run, can include binded variables
    const FIND_QUERY = "SELECT COUNT(*) FROM " . InventoryModel::TABLE . " WHERE storageID = :givenStorageID AND productID = :givenProductID";
    const ADD_QUERY = "INSERT INTO " . InventoryModel::TABLE . " (storageID, productID, quantity) VALUES (:givenStorageID, :givenProductID, :givenQuantity)";
    const TO_STORAGE = "UPDATE " . InventoryModel::TABLE . " SET quantity = quantity + :givenQuantity WHERE productID = :givenProductID AND storageID = :givenStorageID";
    const FROM_STORAGE = "UPDATE " . InventoryModel::TABLE . " SET quantity = quantity - :givenQuantity WHERE productID = :givenProductID AND storageID = :givenStorageID";
    const SELECT_QUERY_PRODUCTID = "SELECT storage.storageID, inventory.emailWarning, inventory.inventoryWarning, storage.storageName, inventory.productID, inventory.quantity FROM " . InventoryModel::TABLE . " INNER JOIN storage ON storage.storageID = inventory.storageID WHERE productID = :givenProductID";
    const SELECT_QUERY = "SELECT storageID, products.productName, products.productID, quantity FROM " . InventoryModel::TABLE . " INNER JOIN products ON products.productID = inventory.productID";
    const SELECT_QUERY_STORAGEID = "SELECT storageName, inventory.storageID, products.productName, products.productID, products.macAdresse, quantity FROM " . InventoryModel::TABLE . " INNER JOIN products ON products.productID = inventory.productID INNER JOIN storage ON storage.storageID = inventory.storageID WHERE inventory.storageID = :givenStorageID";
    const SELECT_FROM_stoID_proID = "SELECT products.productID, productName, quantity, products.macAdresse FROM products INNER JOIN " . InventoryModel::TABLE . " on products.productID LIKE inventory.productID WHERE storageID = :givenStorageID AND products.productID = :givenProductID";
    const DELETE_QUERY = "DELETE FROM " . InventoryModel::TABLE . " WHERE storageID = :givenStorageID";
    const DELETE_SINGLE_QUERY = "DELETE FROM " . InventoryModel::TABLE . " WHERE productID = :givenProductID AND storageID = :givenStorageID";
    const UPDATE_QUERY = "UPDATE " . InventoryModel::TABLE . " SET quantity = :givenQuantity WHERE storageID = :givenStorageID AND productID = :givenProductID"; 
    const LOW_INV_QUERY = "SELECT storage.storageName, products.productName, inventory.quantity FROM  " . InventoryModel::TABLE . " INNER JOIN storage ON inventory.storageID = storage.storageID INNER JOIN products ON inventory.productID = products.productID  WHERE inventory.quantity < inventory.inventoryWarning";
    const SELECT_STOPRO_FROM_CAT = "SELECT storageID, products.productName, products.productID, quantity, products.categoryID FROM " . InventoryModel::TABLE . " INNER JOIN products ON products.productID = inventory.productID WHERE storageID = :givenStorageID AND categoryID = :givenCategoryID";
    const EMAIL_WARNING = "SELECT inventory.inventoryID, storage.storageName, products.productName, inventory.quantity, inventory.emailWarning FROM " . InventoryModel::TABLE . " INNER JOIN storage ON storage.storageID = inventory.storageID INNER JOIN products ON products.productID = inventory.productID WHERE quantity < inventory.emailWarning AND inventory.emailStatus = 0";
    const EMAIL_STATUS = "UPDATE " . InventoryModel::TABLE . " SET emailStatus = 1 WHERE inventoryID = :givenInventoryID";
    const UPDATE_WARNING_LIMIT = "UPDATE " . InventoryModel::TABLE . " SET inventoryWarning = :inventoryWarning, emailWarning = :emailWarning WHERE storageID = :storageID AND productID = :productID";
    const GET_InvID = "SELECT inventoryID FROM " . InventoryModel::TABLE . " WHERE storageID = :givenStorgeID AND productID = :givenProductID";
    const INSERT_MAC = "INSERT INTO " . InventoryModel::MACADRESSE_TABLE . " (macAdresse, inventoryID) VALUES (:givenMacAdresse, :givenInventoryID)";
    const DELETE_MAC = "DELETE FROM " . InventoryModel::MACADRESSE_TABLE . " WHERE macAdresse = :givenMacAdresse AND inventoryID = :givenInventoryID";
    const COUNT_MAC = "SELECT COUNT(*) FROM " . InventoryModel::MACADRESSE_TABLE . " WHERE macadresse = :givenMacAdresse AND inventoryID = :givenInventoryID";
    const SELECT_INV_MAC = "SELECT * FROM " . InventoryModel::MACADRESSE_TABLE . " WHERE inventoryID = :givenInventoryID";
    
    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;    // connect to database
        // prepare the statements
        $this->selStmt = $this->dbConn->prepare(InventoryModel::SELECT_QUERY);
        $this->selStorageID = $this->dbConn->prepare(InventoryModel::SELECT_QUERY_STORAGEID);
        $this->selProductID = $this->dbConn->prepare(InventoryModel::SELECT_QUERY_PRODUCTID);
        $this->fromStorage = $this->dbConn->prepare(InventoryModel::FROM_STORAGE);
        $this->toStorage = $this->dbConn->prepare(InventoryModel::TO_STORAGE);
        $this->addStmt = $this->dbConn->prepare(InventoryModel::ADD_QUERY);
        $this->findStm = $this->dbConn->prepare(InventoryModel::FIND_QUERY);
        $this->stoID_proID = $this->dbConn->prepare(InventoryModel::SELECT_FROM_stoID_proID);
        $this->delStmt = $this->dbConn->prepare(InventoryModel::DELETE_QUERY);
        $this->delSingleStmt = $this->dbConn->prepare(InventoryModel::DELETE_SINGLE_QUERY);
        $this->editStmt = $this->dbConn->prepare(InventoryModel::UPDATE_QUERY);
        $this->lowInvStmt = $this->dbConn->prepare(InventoryModel::LOW_INV_QUERY);
        $this->stoProFromCat = $this->dbConn->prepare(InventoryModel::SELECT_STOPRO_FROM_CAT);
        $this->emailAlert = $this->dbConn->prepare(InventoryModel::EMAIL_WARNING);
        $this->warningStatus = $this->dbConn->prepare(InventoryModel::EMAIL_STATUS);
        $this->warningLimit = $this->dbConn->prepare(InventoryModel::UPDATE_WARNING_LIMIT);
        $this->getInvID = $this->dbConn->prepare(InventoryModel::GET_InvID);
        $this->addMac = $this->dbConn->prepare(InventoryModel::INSERT_MAC);
        $this->removeMac = $this->dbConn->prepare(InventoryModel::DELETE_MAC);
        $this->countMac = $this->dbConn->prepare(InventoryModel::COUNT_MAC);
        $this->getInvMac = $this->dbConn->prepare(InventoryModel::SELECT_INV_MAC);
    }
    
    /**
     * Get iventory with lower quantity than warninglimit
     */ 
    public function getLowInventory() {
        $this->lowInvStmt->execute();   // execute SQL statement
        return $this->lowInvStmt->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }

    /**
     * Get all productsinfo within a storage
     */ 
    public function getAllStorageInventory() {
        $this->selStmt->execute();  // execute SQL statement
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }

    /**
     * Get all productinfo within a given storage
     */ 
    public function getAllStorageInventoryByStorageID($givenStorageID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selStorageID->execute(array("givenStorageID" => $givenStorageID));
        return $this->selStorageID->fetchAll(PDO::FETCH_ASSOC);     // return fetched result
    }

    /**
     * Get locations of a given product
     */ 
    public function getAllProductLocationByProductID($givenProductID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->selProductID->execute(array("givenProductID" => $givenProductID));
        return $this->selProductID->fetchAll(PDO::FETCH_ASSOC);     // return fetched result      
    }
    
    /**
     * check if product exist in a given storage
     */ 
    public function doesProductExistInStorage($givenStorageID, $givenProductID){
        //bind variable to the parameter as strings, and execute SQL statement        
        $this->findStm->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID));
        return $this->findStm->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * remove given quantity from a given storage
     */ 
    public function transferFromStorage($givenStorageID, $givenProductID, $givenQuantity){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->fromStorage->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity)); 
    }
    
    /**
     * add a given quantity to a given storage
     */ 
    public function transferToStorage($givenStorageID, $givenProductID, $givenQuantity){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->toStorage->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity));
    }
    
    /**
     * add a product to a storage
     */ 
    public function addInventory($givenStorageID, $givenProductID, $givenQuantity){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addStmt->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity));
    }
    
    /**
     * Get productinformation in a given storage
     */ 
    public function getProdFromStorageIDAndProductID($givenStorageID, $givenProductID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->stoID_proID->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID));
        return $this->stoID_proID->fetchAll(PDO::FETCH_ASSOC);  // return fetched result
    }
    
    /**
     * delete a registered inventory
     */ 
    public function deleteInventory($givenStorageID){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->delStmt->execute(array("givenStorageID" => $givenStorageID));
    }
    
    /**
     * remove a single product from a given storage
     */ 
    public function deleteSingleProduct($givenProductID, $givenStorageID){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->delSingleStmt->execute(array("givenProductID" => $givenProductID, "givenStorageID" => $givenStorageID));  
    }
    
    /**
     * update quantity of a inventory register
     */ 
    public function updateInventory($givenStorageID, $givenProductID, $givenQuantity){
        //bind variable to the parameter as strings, and execute SQL statement
       return $this->editStmt->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity)); 
    }
    
    /**
     * Get products within a storage and a given category
     */ 
    public function getStoProFromCat($givenStorageID, $givenCategoryID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->stoProFromCat->execute(array("givenStorageID" =>  $givenStorageID, "givenCategoryID" => $givenCategoryID)); 
        return $this->stoProFromCat->fetchAll(PDO::FETCH_ASSOC);    // return fetched result
    }
    
    /**
     * Get warninglimit for email sending
     */ 
    public function getEmailWarning(){
        $this->emailAlert->execute();   // execute SQL statement
        return $this->emailAlert->fetchAll(PDO::FETCH_ASSOC);   // return fetched result
    }
    
    /**
     * Update warning satus (if email is sendt, sets it to 1, else sets it to 0)
     */ 
    public function updateWarningStatus($givenInventoryID){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->warningStatus->execute(array("givenInventoryID" => $givenInventoryID));
    }
    
    /**
     * Update registered warning limit for email og home page warning
     */ 
    public function updateWarningLimit($storageIDArray, $emailWarningArray, $inventoryWarningArray, $productID){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->warningLimit->execute(array("storageID" => $storageIDArray, "emailWarning" => $emailWarningArray, "inventoryWarning" => $inventoryWarningArray, "productID" => $productID));
    }
    
    /**
     *  Get inventory ID from productID and storageID
     */ 
    public function getInventoryID($ProductID, $StorageID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->getInvID->execute(array("givenStorgeID" => $StorageID, "givenProductID" => $ProductID)); 
        return $this->getInvID->fetchAll(PDO::FETCH_ASSOC);     // return fetched result
    }
    
    /**
     * add a new mac adresse to inventory
     */ 
    public function addMacAdresse($inventoryID, $macAdresse){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->addMac->execute(array("givenInventoryID" => $inventoryID, "givenMacAdresse" => $macAdresse));  
    }
    
    /**
     * remove a registered mac adresse
     */ 
    public function removeMacAdresse($inventoryID, $macAdresse){
        //bind variable to the parameter as strings, and execute SQL statement
        return $this->removeMac->execute(array("givenInventoryID" => $inventoryID, "givenMacAdresse" => $macAdresse));  
    }
    
    /**
     * check if given mac adresse exist in storage
     */ 
    public function doesMacExist($macAdresse, $inventoryID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->countMac->execute(array("givenMacAdresse" => $macAdresse, "givenInventoryID" => $inventoryID)); 
        return $this->countMac->fetchAll(PDO::FETCH_ASSOC);     // return fetched result
    }
    
    /**
     * Get all macadresse from given inventoryID
     */ 
    public function getInventoryMac($inventoryID){
        //bind variable to the parameter as strings, and execute SQL statement
        $this->getInvMac->execute(array("givenInventoryID" => $inventoryID)); 
        return $this->getInvMac->fetchAll(PDO::FETCH_ASSOC);    // return fetched result
    }
}   
    

    
