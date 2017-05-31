<?php

require_once("Controller.php"); //include contoller


class transferController extends Controller {

    //Decide wich function to run based on passed $requset variable
    public function __construct($request) {
        switch ($request) {
            case "transfer" :
                return $this->transferPage();
            case "getTransferRestriction" :
                return $this->getTransferRestriction();
            case "transferProduct" :
                return $this->transferProduct();
            case "getuserAndGroupRes" :
                return $this->getUserAndGroupRes(); 
        }
    }

    /**
    * Display transfer page
    */ 
    private function transferPage() {
        // chech restriction tabel, and see if user have restricion to more than one storage
        $restrictionModel = $GLOBALS["restrictionModel"];
        $userID = $_SESSION["userID"];
        $result = $restrictionModel->getUserAndGroupRes($userID);
        
        // if result is larger than one, make global variable 
        if(sizeof($result) > "1"){
           $result = "1";
                $this->data("transferRestriction", $result);
        };
        // display transfer page
        return $this->view("transfer");
    }

     /**
     * Get user restrictions 
     */ 
    private function getTransferRestriction() {
        // get userID from session
        $givenUserID = $_SESSION["userID"];
        // get storage restriction from userID
        $restrictionInfo = $GLOBALS["restrictionModel"];
        $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromUserID($givenUserID);
        // echo result as an array to view
        $data = json_encode(array("transferRestriction" => $restrictionModel));
        echo $data;
    }
    
     /**
     * get bouth user and group storage restrictions
     */ 
    private function getUserAndGroupRes() {
        // get userID from session
        $givenUserID = $_SESSION["userID"];
        // get user and group storage restricitons from userID
        $restrictionInfo = $GLOBALS["restrictionModel"];
        $restrictionModel = $restrictionInfo->getUserAndGroupRes($givenUserID);

        // echo result as an array to view
        $data = json_encode(array("transferRestriction" => $restrictionModel));
        echo $data;
    }

     /**
     * Transfer products from one storage to another storage
     */ 
    private function transferProduct() {
        // get posted values
        $fromStorageID = $_REQUEST["fromStorageID"];
        $transferProductIDArray = $_REQUEST["transferProductID"];
        $transferQuantityArray = $_REQUEST["transferQuantity"];
        $toStorageID = $_REQUEST["toStorageID"];
        $regMacAdresseArray = $_REQUEST["regMacadresse"]; 
        if (isset($_POST['deliveryMacadresse'])) {
            $macAdresseArray = $_REQUEST["deliveryMacadresse"];
        }

        // set values to be logged
        $type = 8;
        $desc = "Overførte produkt";
        $sessionID = $_SESSION["userID"];
        
        // check if this is an incident to be logged. if result = 1 it should
        $loggModel = $GLOBALS["loggModel"];
        $result = $loggModel->loggCheck($type);
        $inventoryInfo = $GLOBALS["inventoryModel"];

        // check if posted regMacAdresse array contains 1 (product with mac)
        if (in_array("1", $regMacAdresseArray)) {
            $macAdresseMissing = array(); // create a new array to contain missing macadresses
            $index = 0;
            for ($i = 0; $i < sizeof($transferProductIDArray); $i++) {
                // if product contains 1 (mac), get inventory ID, and see if mac adresse exist in storage 
                if ($regMacAdresseArray[$i] == "1") {
                    for ($x = 0; $x < $transferQuantityArray[$i]; $x++) {
                        $fromInventoryID = $inventoryInfo->getInventoryID($transferProductIDArray[$i], $fromStorageID);
                        $checkCount = $inventoryInfo->doesMacExist($macAdresseArray[$index], $fromInventoryID[0]["inventoryID"]);
                        if ($checkCount[0]["COUNT(*)"] < 1) {
                            // if cant be found, push mac adresse to macAdresseMissing array
                            $macAdresseMissing[] = $macAdresseArray[$index];
                        }
                        $index++;
                    }
                }
            }
            // if macAdresseMissing array contains value, echo mac adresse to view
            if (sizeof($macAdresseMissing) > 0) {
                $missingMacString = implode(", ", $macAdresseMissing);
                echo json_encode($missingMacString);
                return false; // stop exicuting function
            }
        }

        $index = 0;
        for ($i = 0; $i < sizeof($transferProductIDArray); $i++) {
            // check if product allready exist in storage
            $count = $inventoryInfo->doesProductExistInStorage($toStorageID, $transferProductIDArray[$i]);
            //if it dont, add a new product to storage inventory
            if ($count[0]["COUNT(*)"] < 1) {
                // if incident should be logged, add new transfer logg information
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->transferLogg($type, $desc, $sessionID, $fromStorageID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                }
                // add product to new storage inventory
                $inventoryInfo->addInventory($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                // remove quantity form old storage inventory
                $inventoryInfo->transferFromStorage($fromStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                // if product use mac, add mac to new storage inventory
                if ($regMacAdresseArray[$i] == "1") {
                    $newIndex = $this->transferMacAdresse($transferProductIDArray[$i], $transferQuantityArray[$i], $macAdresseArray, $toStorageID, $fromStorageID, $index);
                    $index = $newIndex;
                }
                // if product exist, add quantity to existing product
            } else {
                // if incident should be logged, add new transfer logg information
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->transferLogg($type, $desc, $sessionID, $fromStorageID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                }
                // remove quantity from storage
                $inventoryInfo->transferFromStorage($fromStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                // add quantity to existing product
                $inventoryInfo->transferToStorage($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
               // if product use mac, add mac to new storage inventory
                if ($regMacAdresseArray[$i] == "1") {
                    $newIndex = $this->transferMacAdresse($transferProductIDArray[$i], $transferQuantityArray[$i], $macAdresseArray, $toStorageID, $fromStorageID, $index);
                    $index = $newIndex;
                }
            }
        }
        // echo a response to view
        $data = json_encode("success");
        echo $data;
    }

    /**
     * Transfer mac adresse from one storage to another
     */ 
    private function transferMacAdresse($transferProductID, $transferQuantity, $macAdresseArray, $toStorageID, $fromStorageID, $index) {
        $inventoryInfo = $GLOBALS["inventoryModel"]; // get inventory model
        for ($x = 0; $x < $transferQuantity; $x++) {
            $toInventoryID = $inventoryInfo->getInventoryID($transferProductID, $toStorageID); // get new inventoryID
            $fromInventoryID = $inventoryInfo->getInventoryID($transferProductID, $fromStorageID);  // get old inventoryID
            $inventoryInfo->addMacAdresse($toInventoryID[0]["inventoryID"], $macAdresseArray[$index]);  // add mac to new inventory
            $inventoryInfo->removeMacAdresse($fromInventoryID[0]["inventoryID"], $macAdresseArray[$index]); // remove mac from old inventory
            $index++;
        }
        return $index;
    }

}
