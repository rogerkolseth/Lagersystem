<?php

require_once("Controller.php"); //include contoller 

class SaleController extends Controller {
    //Decide wich function to run based on passed $requset variable
    public function __construct($request) {

        switch ($request) {
            case "getMySales" :
                return $this->getAllMySales();
            case "sale" :
                return $this->salePage();
            case "withdrawProduct" :
                return $this->withdrawProduct();
            case "saleFromStorageID" :
                return $this->saleFromStorageID();
            case "getProdQuantity" :
                return $this->getProdQuantity();
            case "mySales" :
                return $this->getMySalesPage();
            case "getSalesFromID" :
                return $this->getSalesFromID();
            case "editMySale" :
                return $this->editMySale();
            case "getResCount" :
                return $this->getResCount();
            case "getLastSaleInfo" :
                return $this->getLastSaleInfo();
            case "getAllLastSaleInfo" :
                return $this->getAllLastSaleInfo();
            case "getStoProFromCat" :
                return $this->getStoProFromCat();
            case "showUserSale" :
                return $this->chooseUserSales();
            case "getSalesMacFromID" :
                return $this->getSalesMacFromID();
        }
    }

    /**
     * Display sale page
     */ 
    private function salePage() {
        // get restriction model
        $restrictionModel = $GLOBALS["restrictionModel"];
        $userID = $_SESSION["userID"];  // get userID of logged in user
        $result = $restrictionModel->getUserAndGroupRes($userID);   //gets restrictions from userID

        //If user have more than one storage restriction POST saleRestriction array 
        if (sizeof($result) > "0") {
            $result = "1";
            $this->data("saleRestriction", $result);
        };
        
        //View sale page
        return $this->view("sale");
    }

    private function getMySalesPage() {
        //View my Sales page
        return $this->view("mySales");
    }

    //gets latest sales info
    private function getLastSaleInfo() {
        $userID = $_SESSION["userID"];      //get userID of legged in user
        $saleModel = $GLOBALS["saleModel"]; //get saleModel
        $saleInfo = $saleModel->getLastSaleInfo($userID); //gets latest sales from userID

        //Echo result to view as an array 
        $data = json_encode(array("lastSaleInfo" => $saleInfo));
        echo $data;
    }

    //gets all latest sales info
    private function getAllLastSaleInfo() {
        $saleModel = $GLOBALS["saleModel"];    //gets saleModel
        $saleInfo = $saleModel->getAllLastSaleInfo();   //gets all latest sale

        //Echo result to view as an array
        $data = json_encode(array("allLastSaleInfo" => $saleInfo));
        echo $data;
    }

    // gets sales from a given storage
    private function saleFromStorageID() {
        $givenStorageID = $_REQUEST["saleStorageID"];   //gets POSTed staorageID
        $saleModel = $GLOBALS["saleModel"];     //gets saleModel
        $saleInfo = $saleModel->getSaleFromStorageID($givenStorageID);  //gets sales from given storageID

        //Echo result to view as an array
        $data = json_encode(array("saleFromStorage" => $saleInfo));
        echo $data;
    }

    //withdraws product form inventory, also checks if given macadresses exist in given storage
    private function withdrawProduct() {
        $fromStorageID = $_REQUEST["fromStorageID"];    //gets POSTed storageID
        $withdrawProductIDArray = $_REQUEST["withdrawProductID"];   //gets POSTed productID
        $withdrawQuantityArray = $_REQUEST["withdrawQuantity"];     //gets POSTed quantoty
        $customerNumber = $_REQUEST["customerNumber"];  //get POSTed customer number
        $userID = $_SESSION["userID"];      //gets userID from session
        $regMacAdresseArray = $_REQUEST["regMacadresse"];   //get POSTed regMacadresse. if 1, mac adresse is used.
        if (isset($_POST['withdrawComment'])) {
            $comment = $_REQUEST["withdrawComment"];    //gets POSTed comment, not required
        } else {
            $comment = ""; 
        } if (isset($_POST['withdrawMacadresse'])) { //gets POSTed macadresse, not required
            $macAdresseArray = $_REQUEST["withdrawMacadresse"];
        }

        $inventoryInfo = $GLOBALS["inventoryModel"];    //gets inventory model

        if (in_array("1", $regMacAdresseArray)) { //checks if macadresse is used. if 1, it is. 
            $macAdresseMissing = array(); //creates array to contain missing macadresse

            $index = 0; 
            for ($i = 0; $i < sizeof($withdrawProductIDArray); $i++) {  //loop trough POSTed productID array
                
                if ($regMacAdresseArray[$i] == "1") { //checks if product is using macadresse
                    for ($x = 0; $x < $withdrawQuantityArray[$i]; $x++) {
                        $fromInventoryID = $inventoryInfo->getInventoryID($withdrawProductIDArray[$i], $fromStorageID); //gets inventoryID to withdraw from
                        $checkCount = $inventoryInfo->doesMacExist($macAdresseArray[$index], $fromInventoryID[0]["inventoryID"]); //checks if given macadresse exist
                        if ($checkCount[0]["COUNT(*)"] < 1) {
                            $macAdresseMissing[] = $macAdresseArray[$index]; //if macadresse does not exist, push it to macAdresseMissing array
                        }
                        $index++; //increment index value
                    }
                }
            }

            if (sizeof($macAdresseMissing) > 0) { // if array contains missing macadresse do:
                $missingMacString = implode(", ", $macAdresseMissing);
                echo json_encode($missingMacString); // Echo missing macadresse to view
                return false; // stops running function
            }
        }


        $saleModel = $GLOBALS["saleModel"]; //gets saleModel

        $index = 0;
        for ($i = 0; $i < sizeof($withdrawProductIDArray); $i++) {//loop trough POSTed productID array

            // register new sale
            $salesID = $saleModel->newSale($fromStorageID, $customerNumber, $withdrawProductIDArray[$i], $withdrawQuantityArray[$i], $userID, $comment);
            //withdraw product form selected storage
            $inventoryInfo->transferFromStorage($fromStorageID, $withdrawProductIDArray[$i], $withdrawQuantityArray[$i]);

            if ($regMacAdresseArray[$i] == "1") { //checks if product is registered using mac
                //if yws, rund withdrawMacAdresse function
                $newIndex = $this->withdrawMacAdresse($withdrawProductIDArray[$i], $withdrawQuantityArray[$i], $macAdresseArray, $fromStorageID, $index, $salesID);
                $index = $newIndex; 
            }
        }
        echo json_encode("success");
    }

    //withdrawing mac adresse from inventory and add macadresse to registered sales
    private function withdrawMacAdresse($withdrawProductID, $withdrawQuantity, $macAdresseArray, $fromStorageID, $index, $salesID) {
        // gets involved models
        $inventoryInfo = $GLOBALS["inventoryModel"]; 
        $saleModel = $GLOBALS["saleModel"]; 
        
        // loop through products to withdraw
        for ($x = 0; $x < $withdrawQuantity; $x++) {
            $fromInventoryID = $inventoryInfo->getInventoryID($withdrawProductID, $fromStorageID); //gets inventory to withdraw from
            $inventoryInfo->removeMacAdresse($fromInventoryID[0]["inventoryID"], $macAdresseArray[$index]); //removes given macadresse
            $saleModel->addSalesMac($salesID, $macAdresseArray[$index]); //adds mac in salesMac table
            $index++;
        }
        return $index; //returns index value
    }

    //gets product information from a storage
    private function getProdQuantity() {
        $givenProductID = $_REQUEST["givenProductID"]; //gets products quantity
        //gets involved models
        $inventoryInfo = $GLOBALS["inventoryModel"];
        $storageModel = $GLOBALS["storageModel"];

        if (isset($_POST['givenStorageID'])) {
            $givenStorageID = $_REQUEST['givenStorageID']; //gets storageID if POSTed
            //gets information about a product in a spesific storage
            $inventoryModel = $inventoryInfo->getProdFromStorageIDAndProductID($givenStorageID, $givenProductID);
            $negativeSupport = $storageModel->getNegativeSupportStatus($givenStorageID); // checks if storage supports negative inventory
        } else {
            //if storageID is not posted, get product information from the only storage user have access to
            $givenUserID = $_SESSION["userID"];
            $restrictionInfo = $GLOBALS["restrictionModel"];
            $restrictionModel = $restrictionInfo->getUserAndGroupRes($givenUserID);

            $givenStorageID = $restrictionModel[0]['storageID'];
            $negativeSupport = $storageModel->getNegativeSupportStatus($givenStorageID);
            $inventoryModel = $inventoryInfo->getProdFromStorageIDAndProductID($givenStorageID, $givenProductID);
        }

        //Echo result to view
        $data = json_encode(array("prodInfo" => $inventoryModel, "negativeSupport" => $negativeSupport));
        echo $data;
    }

    // gets all sales, or sales result from a search
    private function getAllMySales() {
        $givenUserID = $_SESSION["userID"]; //gets userID from session

        $saleModel = $GLOBALS["saleModel"]; // gets sale Model

        if (isset($_POST['givenProductSearchWord'])) {
            // if user is searching, pass given search word and retrive result
            $givenProductSearchWord = "%{$_REQUEST["givenProductSearchWord"]}%";
            $mySales = $saleModel->getMySales($givenUserID, $givenProductSearchWord);
        } else {
            // if not, retrive all sales info
            $givenProductSearchWord = "%%";
            $mySales = $saleModel->getMySales($givenUserID, $givenProductSearchWord);
        }

        //if user is administrator, pass all usernames to view (to make advance search)
        if ($_SESSION["userLevel"] == "Administrator") {
            $userModel = $GLOBALS["userModel"];
            $usernames = $userModel->getUsername();
            //echo result to view
            $data = json_encode(array("mySales" => $mySales, "usernames" => $usernames));
        } else {
            $data = json_encode(array("mySales" => $mySales)); 
        }
        echo $data;
    }

    //gets information about a spesific sale
    private function getSalesFromID() {
        $givenSalesID = $_REQUEST["givenSalesID"]; //get POSTed salesID

        $saleModel = $GLOBALS["saleModel"]; //gets sales Model

        $saleFromID = $saleModel->getSaleFromID($givenSalesID); // gets salesinformation from salesID

        //echo result from model to view
        $data = json_encode(array("sale" => $saleFromID));
        echo $data;
    }

    // edit sales information
    private function editMySale() {
        $editSaleID = $_REQUEST["editSaleID"]; //get ID of sale to edit
        $editCustomerNr = $_REQUEST["editCustomerNr"];  // gets new customer number
        $editComment = $_REQUEST["editComment"];    //gets new comment

        // pass new value to model and update existing register
        $saleModel = $GLOBALS["saleModel"];
        $edited = $saleModel->editMySale($editSaleID, $editCustomerNr, $editComment);

        if ($edited) {
            echo json_encode("success"); //if success, echo a respond to view
        }
    }

    // gets wich restrictions logged user have
    private function getResCount() {
        // gets userID from session and get models
        $givenUserID = $_SESSION["userID"];
        $restrictionModel = $GLOBALS["restrictionModel"];
        
        //run a count query from model
        $count = $restrictionModel->resCount($givenUserID);

        // echo result to view
        $resCount = $count[0]["COUNT(*)"];
        echo json_encode($resCount);
    }

    //gets products from a spesific category
    private function getStoProFromCat() {
        $givenCategoryID = $_REQUEST["givenCategoryID"]; //gets category ID
        $inventoryInfo = $GLOBALS["inventoryModel"]; //gets model

        if (isset($_POST['givenStorageID'])) { 
            // if storageID is given, get products from storage
            $givenStorageID = $_REQUEST["givenStorageID"];
            if ($givenCategoryID == 0) { //if category equals 0 ("show all" from view), get all products within storage
                $result = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID);
                $data = json_encode(array("storageProduct" => $result)); 
                echo $data; // Echo result to view
            } else {
                // else get all products from this category
                $result = $inventoryInfo->getStoProFromCat($givenStorageID, $givenCategoryID);
                $data = json_encode(array("storageProduct" => $result)); 
                echo $data; // Echo result to view
            }
        } else {
            //if storageID is not posted, get product information from the only storage user have access to
            $givenUserID = $_SESSION["userID"];
            $restrictionInfo = $GLOBALS["restrictionModel"];
            $restrictionModel = $restrictionInfo->getUserAndGroupRes($givenUserID); //gets user restriction
            $givenStorageID = $restrictionModel[0]['storageID'];
            if ($givenCategoryID == 0) {
                $result = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID); //get all product within storage
                $data = json_encode(array("storageProduct" => $result));
                echo $data; // echo result to view
            } else {
                $result = $inventoryInfo->getStoProFromCat($givenStorageID, $givenCategoryID); //get products from category
                
                //echo result as an array to view
                $data = json_encode(array("storageProduct" => $result));
                echo $data; 
            }
        }
    }

    // get sales from other users 
    private function chooseUserSales() {
        if (isset($_POST['username'])) {
            $usernameArray = $_REQUEST["username"]; //gets given usernames
        } else {
            $usernameArray = array();
        }
        $saleModel = $GLOBALS["saleModel"]; // get sale model

        foreach ($usernameArray as $user): //lopp trough array of usernames
            if ($user == 0) { // if username with value 0 is posted (show all in view)
                $getAllUserSale = $saleModel->getAllSaleInfo(); //gets all salesinfo from model (sales from all users
                $data = json_encode(array("mySales" => $getAllUserSale));   
                echo $data; // echo result to view
                return false;   //stops running function
            }
        endforeach;


        $getUserSale = $saleModel->getSelectedUserSale($usernameArray); //gets result from sale model

        // echo result as an array to view
        $data = json_encode(array("mySales" => $getUserSale)); 
        echo $data;
    }

    // gets macadresses from a spesific sale
    private function getSalesMacFromID() {  
        $givenSalesID = $_REQUEST["givenSalesID"]; //gets ID from sale
        $saleModel = $GLOBALS["saleModel"]; // gets sale model
        $macAdresse = $saleModel->getMacFromSaleID($givenSalesID); //gets mac result from model

        // echo result as an array to view
        $data = json_encode(array("mySalesMac" => $macAdresse)); 
        echo $data; 
    }

}
