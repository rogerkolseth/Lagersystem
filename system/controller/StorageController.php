<?php

require_once("Controller.php"); //include contoller

class StorageController extends Controller {
    //Decide wich function to run based on passed $requset variable
    public function show($request) {
        switch ($request) {
            case "storageAdm" :
                return $this->storageAdmPage();
            case "addStorageEngine" :
                return $this->storageCreationEngine();
            case "editStorageEngine" :
                return $this->storageEditEngine();
            case "deleteStorageEngine" :
                return $this->deleteStorageEngine();
            case "getAllStorageInfo" :
                return $this->getAllStorageInfo();
            case "getStorageByID" :
                return $this->getStorageByID();
            case "getStorageRestriction" :
                return $this->getStorageRestriction();
            case "getStorageProduct" :
                return $this->getStorageProduct();
            case "chartProduct" :
                return $this->chartProduct();
            case "deleteSingleProd" :
                return $this->deleteSingleProd();
            case "stocktacking" :
                return $this->stocktacking();
            case "setWarningLimit" :
                return $this->setWarningLimit();
            case "getInventoryMac" :
                return $this->getInventoryMac();
        }
    }

     /**
     * Display storage administration page
     */ 
    private function storageAdmPage() {
        return $this->view("storageAdm");     
    }

     /**
     * Add new storage to database
     */ 
    private function storageCreationEngine() {
        // get posted values
        $givenStorageName = $_REQUEST["givenStorageName"];
        if (isset($_POST['givenNegativeSupport'])) {
            $givenNegativeSupport = $_REQUEST["givenNegativeSupport"];
        } else {
            $givenNegativeSupport = 0; // supports negatve inventory status if "1"
        }

        // set userID as global variable in databsae
        $sessionID = $_SESSION["userID"];
        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);

        //add storage information to database
        $storageCreationInfo = $GLOBALS["storageModel"];
        $added = $storageCreationInfo->addStorage($givenStorageName, $givenNegativeSupport);

        // if added, echo a response to view
        if ($added) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

     /**
     * Edit existing storage information
     */ 
    private function storageEditEngine() {
        // get posted values
        $editStorageID = $_REQUEST["editStorageID"];
        $editStorageName = $_REQUEST["editStorageName"];
        if (isset($_POST['editNegativeSupport'])) {
            $editNegativeSupport = $_REQUEST["editNegativeSupport"];
        } else {
            $editNegativeSupport = 0; // supports negatve inventory status if "1"
        }
        
        // set userID as global variable in databsae
        $sessionID = $_SESSION["userID"];
        $sesionLog = $GLOBALS["userModel"];
        $sesionLog->setSession($sessionID);

        //update existing storage information in database
        $storageEditInfo = $GLOBALS["storageModel"];
        $edited = $storageEditInfo->editStorage($editStorageName, $editStorageID, $editNegativeSupport);

        // if added, echo a response to view
        if ($edited) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    /**
     * delete existing storage from database
     */
    private function deleteStorageEngine() {
        $removeStorageID = $_REQUEST["deleteStorageID"]; // get posted storageID

        // if storage is not 1 or 2 (main storage and return storage cant be delited)
        if ($removeStorageID != 1 && $removeStorageID != 2) {
            // set userID as global variable in database
            $sessionID = $_SESSION["userID"];
            $setSessionID = $GLOBALS["userModel"];
            $setSessionID->setSession($sessionID);
            
            //delete inventory containing this storage
            $deleteInventory = $GLOBALS["inventoryModel"];
            $deleteInventory->deleteInventory($removeStorageID);
            
            // delete restriction to this storage
            $restrictionModel = $GLOBALS["restrictionModel"];
            $restrictionModel->deleteResStorageID($removeStorageID);

            // delete storage from database
            $removeStorage = $GLOBALS["storageModel"];
            $removeStorage->removeStorage($removeStorageID);
            
            // echo an response to view
            echo json_encode("success");
        }
    }

    /**
     * get all storages information
     */
    private function getAllStorageInfo() {
        $storageInfo = $GLOBALS["storageModel"];    // get storage model

        // get search result if searchword is posted
        if (isset($_POST['givenStorageSearchWord'])) {
            $givenStorageSearchWord = "%{$_REQUEST["givenStorageSearchWord"]}%";
            $storageModel = $storageInfo->getSearchResult($givenStorageSearchWord);
        } else {
            // if not posted, get all results
            $givenStorageSearchWord = "%%";
            $storageModel = $storageInfo->getSearchResult($givenStorageSearchWord);
        }

        // echo result as an array to view
        $data = json_encode(array("storageInfo" => $storageModel));
        echo $data;
    }

    /**
     * Get storage information from storageID
     */
    private function getStorageByID() {
        $givenStorageID = $_REQUEST["givenStorageID"]; // get posted storageID

        // get all storage information of given storage ID
        $storageInfo = $GLOBALS["storageModel"];
        $storageModel = $storageInfo->getAllStorageInfoFromID($givenStorageID);

        // echo result as an array to view
        $data = json_encode(array("storage" => $storageModel));
        echo $data;
    }

    /**
     * Get restriction to a given storage
     */
    private function getStorageRestriction() {
        $givenStorageID = $_REQUEST['givenStorageID'];  //get storage ID

        // get all restricion to a given storage
        $restrictionInfo = $GLOBALS["restrictionModel"];
        $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromStorageID($givenStorageID);

        // echo result as an array to view
        $data = json_encode(array("storageRestriction" => $restrictionModel));
        echo $data;
    }

    /**
     * Get product within a storage
     */
    private function getStorageProduct() {
        $inventoryInfo = $GLOBALS["inventoryModel"];    // get inventory model

        // if storage ID is posted, get all product within this storage
        if (isset($_POST['givenStorageID'])) {
            $givenStorageID = $_REQUEST['givenStorageID'];
            $inventoryModel = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID);
        } else {
            // else, get storage ID from only user restriction in database
            $givenUserID = $_SESSION["userID"];
            $restrictionInfo = $GLOBALS["restrictionModel"];
            $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromUserID($givenUserID);

            $givenStorageID = $restrictionModel[0]['storageID'];
            // get products from database
            $inventoryModel = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID);
        }
        // echo result as an array
        $data = json_encode(array("storageProduct" => $inventoryModel));
        echo $data;
    }

    /**
     * get product inventory info used for charts
     */
    private function chartProduct() {
        $givenStorageID = $_REQUEST['givenStorageID'];
        // get inventory iformation from storage ID
        $inventoryInfo = $GLOBALS["inventoryModel"];
        $inventoryModel = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID);
        // echo result as array without name to view
        $data = json_encode($inventoryModel);
        echo $data;
    }
    
    /**
     * delete one product from storage
     */
    private function deleteSingleProd() {
        // get posted values and userID from session
        $givenProductID = $_REQUEST["givenProductID"];
        $givenStorageID = $_REQUEST["givenStorageID"];
        $sessionID = $_SESSION["userID"];

        // get models
        $setSessionID = $GLOBALS["userModel"];
        $deletedProd = $GLOBALS["inventoryModel"];

        
        $setSessionID->setSession($sessionID);  // set global variable in database
        // delete prodcut from storage
        $deleted = $deletedProd->deleteSingleProduct($givenProductID, $givenStorageID);

        // if delited, echo a response to view
        if ($deleted) {
            echo json_encode("success");
        }
    }

    /**
     * Register stocktaking
     */
    private function stocktacking() {
        // if get result is posted retrieve posted values
        if (isset($_POST['getResult'])) {
            $givenStorageID = $_REQUEST["givenStorageID"];
            $givenProductIDArray = $_REQUEST["givenProductArray"];
            $oldQuantityArray = $_REQUEST["oldQuantityArray"];
            $givenProductNameArray = $_REQUEST["givenProductNameArray"];
            $givenQuantityArray = $_REQUEST["givenQuantityArray"];

            // calculate difference between old and new quantity
            for ($i = 0; $i < sizeof($givenProductIDArray); $i++) {
                $differance = $givenQuantityArray[$i] - $oldQuantityArray[$i];
                // create an nested array
                $differanceArray[] = (object) array(
                    'productID' => $givenProductIDArray[$i], 
                    'differance' => $differance, 
                    'oldQuantity' => $oldQuantityArray[$i],
                    'newQuantity' => $givenQuantityArray[$i], 
                    'productName' => $givenProductNameArray[$i], 
                    'storageID' => $givenStorageID);
            }
            // echo result as an nested array to view
            $data = json_encode(array("differanceArray" => $differanceArray));
            echo $data;
        } else {
            // if "getResult is not posted, get posted values
            $givenStorageID = $_REQUEST["givenStorageID"];
            $givenProductIDArray = $_REQUEST["givenProductArray"];
            $givenQuantityArray = $_REQUEST["givenQuantityArray"];
            $oldQuantityArray = $_REQUEST["oldQuantityArray"];
            $differanceArray = $_REQUEST["differanceArray"];
            $type = 10;
            $desc = "Oppdatering av antall";
            $sessionID = $_SESSION["userID"];

            // check if incident should be logged
            $loggModel = $GLOBALS["loggModel"];
            $result = $loggModel->loggCheck($type);

            // loop trough product array
            for ($i = 0; $i < sizeof($givenProductIDArray); $i++) {
                // if incident shoul be logged (result = 1), add logg to database
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->stocktaking($type, $desc, $sessionID, $givenStorageID, $givenProductIDArray[$i], $givenQuantityArray[$i], $oldQuantityArray[$i], $differanceArray[$i]);
                }
                // update inventory information from result of stocktaking
                $inventoryInfo = $GLOBALS["inventoryModel"];
                $inventoryInfo->updateInventory($givenStorageID, $givenProductIDArray[$i], $givenQuantityArray[$i]);
            }
            // echo a response to view
            echo json_encode("success");
        }
    }
    
    /**
     * Edit warning limit on email warning and home page warning
     */
    private function setWarningLimit(){
        // get posted values
        $productID = $_REQUEST["productID"];
        $storageIDArray = $_REQUEST["storageID"];
        $emailWarningArray = $_REQUEST["emailWarning"];
        $inventoryWarningArray = $_REQUEST["inventoryWarning"];
        
        $inventoryInfo = $GLOBALS["inventoryModel"];    // get inventory model
        
        // update warning limits
        for ($i = 0; $i < sizeof($storageIDArray); $i++) {
            $inventoryInfo->updateWarningLimit($storageIDArray[$i], $emailWarningArray[$i], $inventoryWarningArray[$i], $productID);
        }
        // echo a response to view
        echo json_encode("success");
    }
    
    /**
     * Get mac adresses of products within a storage
     */
    private function getInventoryMac(){
        // get posted values
        $givenProductID = $_REQUEST["givenProductID"];
        $givenStorageID = $_REQUEST["givenStorageID"];
        $inventoryInfo = $GLOBALS["inventoryModel"];
        // get inventory ID
        $inventoryID = $inventoryInfo->getInventoryID($givenProductID, $givenStorageID);
        // get mac adresses from inventoryID
        $inventoryMac = $inventoryInfo->getInventoryMac($inventoryID[0]["inventoryID"]);
        // echo result as an array to view
        echo json_encode(array("inventoryMac" => $inventoryMac));
    }

}
