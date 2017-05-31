<?php

require_once("Controller.php"); //include contoller

class ReturnController extends Controller {
    //Decide wich function to run based on passed $requset variable
    public function __construct($request) {
        switch ($request) {
            case "return" :
                return $this->returnPage();
            case "myReturns" :
                return $this->myReturnsPage();
            case "getMyReturns" :
                return $this->getAllMyReturns();
            case "returnProduct" :
                return $this->returnProduct();
            case "getReturnsFromID" :
                return $this->getReturnsFromID();
            case "editMyReturn" :
                return $this->editMyReturn();
            case "stockDelivery" :
                return $this->stockDelivery();
            case "showUserReturns" :
                return $this->chooseUserReturns();
            case "getReturnsMacFromID" :
                return $this->getReturnsMacFromID();
        }
    }

    /**
     * Display return page
     */ 
    private function returnPage() {
        $restrictionModel = $GLOBALS["restrictionModel"]; //get restricion model
        // get storage restrictions of logged in user
        $userID = $_SESSION["userID"];  
        $result = $restrictionModel->getUserAndGroupRes($userID);
        // checks if logged in user have access to storageID "2" (return storage)
        foreach ($result as $result):
            if ($result["storageID"] == "2") {
                $result = "1";
                // make returnRestriction an global variable to access from view
                $this->data("returnRestriction", $result);
            };
        endforeach;
        // display return page
        return $this->view("return");
    }

    /**
     * Display my returns page
     */ 
    private function myReturnsPage() {
        return $this->view("myReturns");
    }

    /**
     * Register a return to return storage
     */ 
    private function returnProduct() {
        $toStorageID = "2"; // set storage as return storage (ID 2)
        // get posted values
        $returnProductIDArray = $_REQUEST["returnProductID"];
        $returnQuantityArray = $_REQUEST["returnQuantity"];
        $customerNumber = $_REQUEST["customerNumber"];
        $userID = $_SESSION["userID"];
        $regMacAdresseArray = $_REQUEST["regMacadresse"];
        $comment = $_REQUEST["returnComment"];
        if (isset($_POST['returnMacadresse'])) {
            $macAdresseArray = $_REQUEST["returnMacadresse"];
        }
        $returnModel = $GLOBALS["returnModel"];
        $inventoryInfo = $GLOBALS["inventoryModel"];

        $index = 0; 
        // loop through productID array
        for ($i = 0; $i < sizeof($returnProductIDArray); $i++) {
            // checks if product exist in storage
            $count = $inventoryInfo->doesProductExistInStorage($toStorageID, $returnProductIDArray[$i]);
            // if it dont, add product to storage. 
            if ($count[0]["COUNT(*)"] < 1) {
                $returnID = $returnModel->newReturn($toStorageID, $customerNumber, $returnProductIDArray[$i], $returnQuantityArray[$i], $userID, $comment);
                $inventoryInfo->addInventory($toStorageID, $returnProductIDArray[$i], $returnQuantityArray[$i]);
                // if product use mac adresse, run addReturnMacAdresse function
                if ($regMacAdresseArray[$i] == "1") {
                    $newIndex = $this->addReturnMacAdresse($returnProductIDArray[$i], $returnQuantityArray[$i], $macAdresseArray, $toStorageID, $index, $returnID);
                    $index = $newIndex;
                }
            } else {
                //If product allready exsist, add quantity to the existing product
                $returnID = $returnModel->newReturn($toStorageID, $customerNumber, $returnProductIDArray[$i], $returnQuantityArray[$i], $userID, $comment);
                $inventoryInfo->transferToStorage($toStorageID, $returnProductIDArray[$i], $returnQuantityArray[$i]);
                if ($regMacAdresseArray[$i] == "1") {
                    $newIndex = $this->addReturnMacAdresse($returnProductIDArray[$i], $returnQuantityArray[$i], $macAdresseArray, $toStorageID, $index, $returnID);
                    $index = $newIndex;
                }
            }
        }
        // echo a response to view
        echo json_encode("success");
    }

    /**
     * add mac adresse to database
     */ 
    private function addReturnMacAdresse($transferProductID, $transferQuantity, $macAdresseArray, $toStorageID, $index, $returnID) {
       // get involved models
        $inventoryInfo = $GLOBALS["inventoryModel"];
        $returnModel = $GLOBALS["returnModel"];
        for ($x = 0; $x < $transferQuantity; $x++) {
            $inventoryID = $inventoryInfo->getInventoryID($transferProductID, $toStorageID); // gets inventory ID 
            $added = $inventoryInfo->addMacAdresse($inventoryID[0]["inventoryID"], $macAdresseArray[$index]);   // add macadresse
            $returnModel->addReturnMac($returnID, $macAdresseArray[$index]);    // add mac adresse to return table
            $index++;
        }
        return $index;
    }

    /**
     * Get logged in users returns
     */ 
    private function getAllMyReturns() {
        $givenUserID = $_SESSION["userID"]; // get userID from session

        $returnModel = $GLOBALS["returnModel"]; //get return model

        // get returns information from search word
        if (isset($_POST['givenProductSearchWord'])) {
            $givenProductSearchWord = "%{$_REQUEST["givenProductSearchWord"]}%";
            $myReturns = $returnModel->getMyReturns($givenUserID, $givenProductSearchWord);
        } else {
            // get all returns information
            $givenProductSearchWord = "%%";
            $myReturns = $returnModel->getMyReturns($givenUserID, $givenProductSearchWord);
        }
        // if logged inn user is Administrator, return all usernames to advance search
        if ($_SESSION["userLevel"] == "Administrator") {
            $userModel = $GLOBALS["userModel"];
            $usernames = $userModel->getUsername(); // get usernames from model
            // create array to echo
            $data = json_encode(array("myReturns" => $myReturns, "usernames" => $usernames));
        } else {
            $data = json_encode(array("myReturns" => $myReturns));
        }
        // echo data to view
        echo $data;
    }

    /**
     * get return info from returnID
     */ 
    private function getReturnsFromID() {
        $givenReturnsID = $_REQUEST["givenReturnsID"];  // get posted value
        $returnModel = $GLOBALS["returnModel"]; //get return model

        //gets return info from ID
        $returnFromID = $returnModel->getReturnFromID($givenReturnsID);
        
        //echo result as an array to view
        $data = json_encode(array("returns" => $returnFromID));
        echo $data;
    }

    /**
     * edit registered return
     */ 
    private function editMyReturn() {
        //get posted values
        $editReturnID = $_REQUEST["editReturnID"];
        $editCustomerNr = $_REQUEST["editCustomerNr"];
        $editComment = $_REQUEST["editComment"];

        //get model, and update information about given return ID
        $returnModel = $GLOBALS["returnModel"];
        $edited = $returnModel->editMyReturn($editReturnID, $editCustomerNr, $editComment);
        // on success, echo an response to view
        if ($edited) {
            echo json_encode("success");
        }
    }

    /**
     * Register a delivery to Mainstorage (ID = 1)
     */ 
    private function stockDelivery() {
        // gets posted values
        $transferProductIDArray = $_REQUEST["deliveryProductID"];
        $transferQuantityArray = $_REQUEST["deliveryQuantity"];
        $regMacAdresseArray = $_REQUEST["regMacadresse"];
        if (isset($_POST['deliveryMacadresse'])) {
            $macAdresseArray = $_REQUEST["deliveryMacadresse"];
        }
        $toStorageID = "1"; // to main storage (ID = 1)
        // set info to add to logg 
        $type = 5;  
        $desc = "Inn på lager";
        $sessionID = $_SESSION["userID"];

        // get involved models
        $loggModel = $GLOBALS["loggModel"];
        $inventoryInfo = $GLOBALS["inventoryModel"];

        $result = $loggModel->loggCheck($type); // check if this type of incident should be logged

        $index = 0;
        for ($i = 0; $i < sizeof($transferProductIDArray); $i++) {
            // check if product exist in storage
            $count = $inventoryInfo->doesProductExistInStorage($toStorageID, $transferProductIDArray[$i]);
            // if product dont exist, add product to inventory
            if ($count[0]["COUNT(*)"] < 1) {
                // if loggCheck is true (1), logg incident
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->stockdelivery($type, $desc, $sessionID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                }
                $delivery = $inventoryInfo->addInventory($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                if ($regMacAdresseArray[$i] == "1") {
                    // if product use mac, add mac adresse to database
                    $newIndex = $this->addMacAdresse($transferProductIDArray[$i], $transferQuantityArray[$i], $macAdresseArray, $toStorageID, $index);
                    $index = $newIndex;
                }
            // if product exist, add quantity to existing product
            } else {
                // if loggCechk is true, logg incident
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->stockdelivery($type, $desc, $sessionID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                }
                $delivery = $inventoryInfo->transferToStorage($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                // if product use mac, add mac adresse to database
                if ($regMacAdresseArray[$i] == "1") {
                    $newIndex = $this->addMacAdresse($transferProductIDArray[$i], $transferQuantityArray[$i], $macAdresseArray, $toStorageID, $index);
                    $index = $newIndex;
                }
            }
        }
        // on success, echo a response to view
        if ($delivery) {
            $data = json_encode("success");
            echo $data;
        } else {
            return false;
        }
    }

    /**
     * adds mac adresse to database
     */ 
    private function addMacAdresse($transferProductID, $transferQuantity, $macAdresseArray, $toStorageID, $index) {
        $inventoryInfo = $GLOBALS["inventoryModel"]; // get inventory model
        for ($x = 0; $x < $transferQuantity; $x++) { // loop trough mac quantity array
            $inventoryID = $inventoryInfo->getInventoryID($transferProductID, $toStorageID); // gets inventory ID
            $added = $inventoryInfo->addMacAdresse($inventoryID[0]["inventoryID"], $macAdresseArray[$index]);   // add mac to DB
            $index++;
        }
        return $index;
    }

    /**
     * get returns from other user
     */ 
    private function chooseUserReturns() {
        // get posed usernames, if none posted, crate empty array
        if (isset($_POST['username'])) {
            $usernameArray = $_REQUEST["username"];
        } else {
            $usernameArray = array();
        }
        $returnModel = $GLOBALS["returnModel"];
        
        // if username == 0, show returns from all users
        foreach ($usernameArray as $user):
            if ($user == 0) {
                $getAllUserReturns = $returnModel->getAllReturnInfo();
                //echo result as an array to view
                $data = json_encode(array("myReturns" => $getAllUserReturns)); 
                echo $data;
                return false;
            }
        endforeach;

        // get selected users returns
        $getUserReturns = $returnModel->getSelectedUserReturns($usernameArray);

        // echo result as an array to view
        $data = json_encode(array("myReturns" => $getUserReturns));
        echo $data;
    }

    /**
     * Get mac adresse from a spesific return
     */ 
    private function getReturnsMacFromID() {
        $givenReturnsID = $_REQUEST["givenReturnsID"];  // get posted value

        // get mac adresse result from model
        $returnModel = $GLOBALS["returnModel"];
        $macAdresse = $returnModel->getMacFromReturnID($givenReturnsID);

        // echo result as an array to view
        $data = json_encode(array("myReturnsMac" => $macAdresse));
        echo $data;
    }

}
