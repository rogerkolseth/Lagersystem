<?php

require_once("Controller.php");

class StorageController extends Controller {

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

    private function storageAdmPage() {
        return $this->view("storageAdm");     
    }

    private function storageCreationEngine() {
        $givenStorageName = $_REQUEST["givenStorageName"];
        if (isset($_POST['givenNegativeSupport'])) {
            $givenNegativeSupport = $_REQUEST["givenNegativeSupport"];
        } else {
            $givenNegativeSupport = 0;
        }

        $sessionID = $_SESSION["userID"];

        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);

        $storageCreationInfo = $GLOBALS["storageModel"];
        $added = $storageCreationInfo->addStorage($givenStorageName, $givenNegativeSupport);

        if ($added) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    private function storageEditEngine() {
        $editStorageID = $_REQUEST["editStorageID"];
        $editStorageName = $_REQUEST["editStorageName"];
        if (isset($_POST['editNegativeSupport'])) {
            $editNegativeSupport = $_REQUEST["editNegativeSupport"];
        } else {
            $editNegativeSupport = 0;
        }

        $sessionID = $_SESSION["userID"];

        $sesionLog = $GLOBALS["userModel"];
        $sesionLog->setSession($sessionID);

        $storageEditInfo = $GLOBALS["storageModel"];
        $edited = $storageEditInfo->editStorage($editStorageName, $editStorageID, $editNegativeSupport);

        if ($edited) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    private function deleteStorageEngine() {
        $removeStorageID = $_REQUEST["deleteStorageID"];

        if ($removeStorageID != 1 && $removeStorageID != 2) {
            $sessionID = $_SESSION["userID"];

            $setSessionID = $GLOBALS["userModel"];
            $setSessionID->setSession($sessionID);
            
            $deleteInventory = $GLOBALS["inventoryModel"];
            $deleteInventory->deleteInventory($removeStorageID);
            
            $restrictionModel = $GLOBALS["restrictionModel"];
            $restrictionModel->deleteResStorageID($removeStorageID);

            $removeStorage = $GLOBALS["storageModel"];
            $removeStorage->removeStorage($removeStorageID);

            echo json_encode("success");
        }
    }

    private function getAllStorageInfo() {
        $storageInfo = $GLOBALS["storageModel"];

        if (isset($_POST['givenStorageSearchWord'])) {
            $givenStorageSearchWord = "%{$_REQUEST["givenStorageSearchWord"]}%";
            $storageModel = $storageInfo->getSearchResult($givenStorageSearchWord);
        } else {
            $givenStorageSearchWord = "%%";
            $storageModel = $storageInfo->getSearchResult($givenStorageSearchWord);
        }

        $data = json_encode(array("storageInfo" => $storageModel));

        echo $data;
    }

    private function getStorageByID() {
        $givenStorageID = $_REQUEST["givenStorageID"];

        $storageInfo = $GLOBALS["storageModel"];
        $storageModel = $storageInfo->getAllStorageInfoFromID($givenStorageID);

        $data = json_encode(array("storage" => $storageModel));
        echo $data;
    }

    private function getStorageRestriction() {
        $givenStorageID = $_REQUEST['givenStorageID'];

        $restrictionInfo = $GLOBALS["restrictionModel"];
        $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromStorageID($givenStorageID);

        $data = json_encode(array("storageRestriction" => $restrictionModel));
        echo $data;
    }

    private function getStorageProduct() {
        $inventoryInfo = $GLOBALS["inventoryModel"];

        if (isset($_POST['givenStorageID'])) {
            $givenStorageID = $_REQUEST['givenStorageID'];
            $inventoryModel = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID);
        } else {
            $givenUserID = $_SESSION["userID"];
            $restrictionInfo = $GLOBALS["restrictionModel"];
            $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromUserID($givenUserID);

            $givenStorageID = $restrictionModel[0]['storageID'];

            $inventoryModel = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID);
        }



        $data = json_encode(array("storageProduct" => $inventoryModel));
        echo $data;
    }

    private function chartProduct() {
        $givenStorageID = $_REQUEST['givenStorageID'];

        $inventoryInfo = $GLOBALS["inventoryModel"];
        $inventoryModel = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID);

        $data = json_encode($inventoryModel);
        echo $data;
    }

    private function deleteSingleProd() {
        $givenProductID = $_REQUEST["givenProductID"];
        $givenStorageID = $_REQUEST["givenStorageID"];
        $sessionID = $_SESSION["userID"];

        $setSessionID = $GLOBALS["userModel"];
        $deletedProd = $GLOBALS["inventoryModel"];

        $setSessionID->setSession($sessionID);
        $deleted = $deletedProd->deleteSingleProduct($givenProductID, $givenStorageID);

        if ($deleted) {
            echo json_encode("success");
        }
    }

    private function stocktacking() {

        if (isset($_POST['getResult'])) {
            $givenStorageID = $_REQUEST["givenStorageID"];
            $givenProductIDArray = $_REQUEST["givenProductArray"];
            $oldQuantityArray = $_REQUEST["oldQuantityArray"];
            $givenProductNameArray = $_REQUEST["givenProductNameArray"];
            $givenQuantityArray = $_REQUEST["givenQuantityArray"];

            for ($i = 0; $i < sizeof($givenProductIDArray); $i++) {
                $differance = $givenQuantityArray[$i] - $oldQuantityArray[$i];

                $differanceArray[] = (object) array('productID' => $givenProductIDArray[$i], 'differance' => $differance, 'oldQuantity' => $oldQuantityArray[$i],
                            'newQuantity' => $givenQuantityArray[$i], 'productName' => $givenProductNameArray[$i], 'storageID' => $givenStorageID);
            }

            $data = json_encode(array("differanceArray" => $differanceArray));
            echo $data;
        } else {

            $givenStorageID = $_REQUEST["givenStorageID"];
            $givenProductIDArray = $_REQUEST["givenProductArray"];
            $givenQuantityArray = $_REQUEST["givenQuantityArray"];
            $oldQuantityArray = $_REQUEST["oldQuantityArray"];
            $differanceArray = $_REQUEST["differanceArray"];
            $type = 10;
            $desc = "Oppdatering av antall";
            $sessionID = $_SESSION["userID"];

            $loggModel = $GLOBALS["loggModel"];
            $result = $loggModel->loggCheck($type);

            for ($i = 0; $i < sizeof($givenProductIDArray); $i++) {
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->stocktaking($type, $desc, $sessionID, $givenStorageID, $givenProductIDArray[$i], $givenQuantityArray[$i], $oldQuantityArray[$i], $differanceArray[$i]);
                }
                $inventoryInfo = $GLOBALS["inventoryModel"];
                $inventoryInfo->updateInventory($givenStorageID, $givenProductIDArray[$i], $givenQuantityArray[$i]);
            }

            echo json_encode("success");
        }
    }
    
    private function setWarningLimit(){
        $productID = $_REQUEST["productID"];
        $storageIDArray = $_REQUEST["storageID"];
        $emailWarningArray = $_REQUEST["emailWarning"];
        $inventoryWarningArray = $_REQUEST["inventoryWarning"];
        
        $inventoryInfo = $GLOBALS["inventoryModel"];
        
        for ($i = 0; $i < sizeof($storageIDArray); $i++) {
            $inventoryInfo->updateWarningLimit($storageIDArray[$i], $emailWarningArray[$i], $inventoryWarningArray[$i], $productID);
        }
        
        echo json_encode("success");
    }
    
    private function getInventoryMac(){
        $givenProductID = $_REQUEST["givenProductID"];
        $givenStorageID = $_REQUEST["givenStorageID"];
        $inventoryInfo = $GLOBALS["inventoryModel"];
        $inventoryID = $inventoryInfo->getInventoryID($givenProductID, $givenStorageID);
        $inventoryMac = $inventoryInfo->getInventoryMac($inventoryID[0]["inventoryID"]);
        echo json_encode(array("inventoryMac" => $inventoryMac));
        //echo "prod: " .$givenProductID . " , Storage: " .$givenStorageID. " , invID: " .$inventoryID[0]["inventoryID"];
    }

}
