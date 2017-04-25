<?php

class InventoryModel {
    
    private $dbConn;


    
    const TABLE = "inventory";
    const FIND_QUERY = "SELECT COUNT(*) FROM " . InventoryModel::TABLE . " WHERE storageID = :givenStorageID AND productID = :givenProductID";
    const ADD_QUERY = "INSERT INTO " . InventoryModel::TABLE . " (storageID, productID, quantity) VALUES (:givenStorageID, :givenProductID, :givenQuantity)";
    const TO_STORAGE = "UPDATE " . InventoryModel::TABLE . " SET quantity = quantity + :givenQuantity WHERE productID = :givenProductID AND storageID = :givenStorageID";
    const FROM_STORAGE = "UPDATE " . InventoryModel::TABLE . " SET quantity = quantity - :givenQuantity WHERE productID = :givenProductID AND storageID = :givenStorageID";
    const SELECT_QUERY_PRODUCTID = "SELECT storage.storageID, inventory.emailWarning, inventory.inventoryWarning, storage.storageName, inventory.productID, inventory.quantity FROM " . InventoryModel::TABLE . " INNER JOIN storage ON storage.storageID = inventory.storageID WHERE productID = :givenProductID";
    const SELECT_QUERY = "SELECT storageID, products.productName, products.productID, quantity FROM " . InventoryModel::TABLE . " INNER JOIN products ON products.productID = inventory.productID";
    const SELECT_QUERY_STORAGEID = "SELECT storageName, inventory.storageID, products.productName, products.productID, quantity FROM " . InventoryModel::TABLE . " INNER JOIN products ON products.productID = inventory.productID INNER JOIN storage ON storage.storageID = inventory.storageID WHERE inventory.storageID = :givenStorageID";
    const SELECT_FROM_stoID_proID = "SELECT products.productID, productName, quantity FROM products INNER JOIN " . InventoryModel::TABLE . " on products.productID LIKE inventory.productID WHERE storageID = :givenStorageID AND products.productID = :givenProductID";
    const DELETE_QUERY = "DELETE FROM " . InventoryModel::TABLE . " WHERE storageID = :givenStorageID";
    const DELETE_SINGLE_QUERY = "DELETE FROM " . InventoryModel::TABLE . " WHERE productID = :givenProductID AND storageID = :givenStorageID";
    const UPDATE_QUERY = "UPDATE " . InventoryModel::TABLE . " SET quantity = :givenQuantity WHERE storageID = :givenStorageID AND productID = :givenProductID"; 
    const LOW_INV_QUERY = "SELECT storage.storageName, products.productName, inventory.quantity FROM  " . InventoryModel::TABLE . " INNER JOIN storage ON inventory.storageID = storage.storageID INNER JOIN products ON inventory.productID = products.productID  WHERE inventory.quantity < inventory.inventoryWarning";
    const SELECT_STOPRO_FROM_CAT = "SELECT storageID, products.productName, products.productID, quantity, products.categoryID FROM " . InventoryModel::TABLE . " INNER JOIN products ON products.productID = inventory.productID WHERE storageID = :givenStorageID AND categoryID = :givenCategoryID";
    const EMAIL_WARNING = "SELECT inventory.inventoryID, storage.storageName, products.productName, inventory.quantity, inventory.emailWarning FROM " . InventoryModel::TABLE . " INNER JOIN storage ON storage.storageID = inventory.storageID INNER JOIN products ON products.productID = inventory.productID WHERE quantity < inventory.emailWarning AND inventory.emailStatus = 0";
    const EMAIL_STATUS = "UPDATE " . InventoryModel::TABLE . " SET emailStatus = 1 WHERE inventoryID = :givenInventoryID";
    const UPDATE_WARNING_LIMIT = "UPDATE " . InventoryModel::TABLE . " SET inventoryWarning = :inventoryWarning, emailWarning = :emailWarning WHERE storageID = :storageID AND productID = :productID";

    public function __construct(PDO $dbConn) {
        $this->dbConn = $dbConn;
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
    }
    public function getLowInventory() {
        $this->lowInvStmt->execute();
        return $this->lowInvStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStorageInventory() {
        $this->selStmt->execute();
        return $this->selStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStorageInventoryByStorageID($givenStorageID){
        $this->selStorageID->execute(array("givenStorageID" => $givenStorageID));
        return $this->selStorageID->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProductLocationByProductID($givenProductID){
        $this->selProductID->execute(array("givenProductID" => $givenProductID));
        return $this->selProductID->fetchAll(PDO::FETCH_ASSOC);        
    }
    
    public function doesProductExistInStorage($givenStorageID, $givenProductID){
        $this->findStm->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID));
        return $this->findStm->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function transferFromStorage($givenStorageID, $givenProductID, $givenQuantity){
        $this->fromStorage->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity));
        return $this->fromStorage->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function transferToStorage($givenStorageID, $givenProductID, $givenQuantity){
        $this->toStorage->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity));
        return $this->toStorage->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    public function addInventory($givenStorageID, $givenProductID, $givenQuantity){
        $this->addStmt->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity));
        return $this->addStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getProdFromStorageIDAndProductID($givenStorageID, $givenProductID){
        $this->stoID_proID->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID));
        return $this->stoID_proID->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteInventory($givenStorageID){
        return $this->delStmt->execute(array("givenStorageID" => $givenStorageID));
    }
    
    public function deleteSingleProduct($givenProductID, $givenStorageID){
        return $this->delSingleStmt->execute(array("givenProductID" => $givenProductID, "givenStorageID" => $givenStorageID));  
    }
    
    public function updateInventory($givenStorageID, $givenProductID, $givenQuantity){
       return $this->editStmt->execute(array("givenStorageID" => $givenStorageID, "givenProductID" => $givenProductID, "givenQuantity" => $givenQuantity)); 
    }
    
    public function getStoProFromCat($givenStorageID, $givenCategoryID){
        $this->stoProFromCat->execute(array("givenStorageID" =>  $givenStorageID, "givenCategoryID" => $givenCategoryID)); 
        return $this->stoProFromCat->fetchAll(PDO::FETCH_ASSOC);  
    }
    
    public function getEmailWarning(){
        $this->emailAlert->execute(); 
        return $this->emailAlert->fetchAll(PDO::FETCH_ASSOC);  
    }
    
    public function updateWarningStatus($givenInventoryID){
        return $this->warningStatus->execute(array("givenInventoryID" => $givenInventoryID));
    }
    
    public function updateWarningLimit($storageIDArray, $emailWarningArray, $inventoryWarningArray, $productID){
        return $this->warningLimit->execute(array("storageID" => $storageIDArray, "emailWarning" => $emailWarningArray, "inventoryWarning" => $inventoryWarningArray, "productID" => $productID));
    }
}   
    

    
