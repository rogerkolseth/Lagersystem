<?php

require_once("Controller.php");

class SaleController extends Controller {

    public function show($page) {
        if ($page == "sale") {
            $this->salePage();
        } else if ($page == "withdrawProduct") {
            $this->withdrawProduct();
        } else if ($page == "saleFromStorageID") {
            $this->saleFromStorageID();
        } else if ($page == "getProdQuantity") {
            $this->getProdQuantity();
        } else if ($page == "mySales") {
            $this->getMySalesPage();
        } else if ($page == "getMySales") {
            $this->getAllMySales();
        } else if ($page == "getSalesFromID") {
            $this->getSalesFromID();
        } else if ($page == "editMySale") {
            $this->editMySale();
        } else if ($page == "getResCount") {
            $this->getResCount();
        } else if ($page == "getLastSaleInfo") {
            $this->getLastSaleInfo();
        } else if ($page == "getAllLastSaleInfo") {
            $this->getAllLastSaleInfo();
        } else if ($page == "getStoProFromCat") {
            $this->getStoProFromCat();
        } else if ($page == "showUserSale") {
            $this->chooseUserSales();
        } else if ($page == "getSalesMacFromID") {
            $this->getSalesMacFromID();
        }
    }

    private function salePage() {
        $restrictionModel = $GLOBALS["restrictionModel"];
        $userID = $_SESSION["userID"];
        $result = $restrictionModel->getUserAndGroupRes($userID);
        
        if(sizeof($result) > "0"){
           $result = "1";
                $saleRestriction = array("saleRestriction" => $result); 
                return $this->render("sale", $saleRestriction);
        };

        return $this->render("sale");
    
    }

    private function getMySalesPage() {
        return $this->render("mySales");
    }

    private function getLastSaleInfo() {
        $userID = $_SESSION["userID"];
        $saleModel = $GLOBALS["saleModel"];
        $saleInfo = $saleModel->getLastSaleInfo($userID);

        $data = json_encode(array("lastSaleInfo" => $saleInfo));

        echo $data;
    }

    private function getAllLastSaleInfo() {
        $saleModel = $GLOBALS["saleModel"];
        $saleInfo = $saleModel->getAllLastSaleInfo();

        $data = json_encode(array("allLastSaleInfo" => $saleInfo));

        echo $data;
    }

    private function saleFromStorageID() {
        $givenStorageID = $_REQUEST["saleStorageID"];
        $saleModel = $GLOBALS["saleModel"];
        $saleInfo = $saleModel->getSaleFromStorageID($givenStorageID);

        $data = json_encode(array("saleFromStorage" => $saleInfo));
        echo $data;
    }

    private function withdrawProduct() {
        $fromStorageID = $_REQUEST["fromStorageID"];
        $withdrawProductIDArray = $_REQUEST["withdrawProductID"];
        $withdrawQuantityArray = $_REQUEST["withdrawQuantity"];
        $customerNumber = $_REQUEST["customerNumber"];
        $userID = $_SESSION["userID"];
        $regMacAdresseArray = $_REQUEST["regMacadresse"];
        if (isset($_POST['withdrawComment'])) {
            $comment = $_REQUEST["withdrawComment"];
        } else {
            $comment = "";
        } if (isset($_POST['withdrawMacadresse'])) {
            $macAdresseArray = $_REQUEST["withdrawMacadresse"];
        }
        $date = $_REQUEST["date"];

        $inventoryInfo = $GLOBALS["inventoryModel"];
        
        if (in_array("1", $regMacAdresseArray)) {
            $macAdresseMissing = array();
            
            $index = 0;
            for ($i = 0; $i < sizeof($withdrawProductIDArray); $i++) {

                if ($regMacAdresseArray[$i] == "1") {
                    for ($x = 0; $x < $withdrawQuantityArray[$i]; $x++) {
                        $fromInventoryID = $inventoryInfo->getInventoryID($withdrawProductIDArray[$i], $fromStorageID);
                        $checkCount = $inventoryInfo->doesMacExist($macAdresseArray[$index], $fromInventoryID[0]["inventoryID"]);
                        if ($checkCount[0]["COUNT(*)"] < 1) {
                            $macAdresseMissing[] = $macAdresseArray[$index];
                        }
                        $index++;
                    }
                }
            }
            
            if (sizeof($macAdresseMissing) > 0) {
                $missingMacString = implode(", ", $macAdresseMissing);
                echo json_encode($missingMacString);
                return false;
            }
        }
        
        
            $saleModel = $GLOBALS["saleModel"];
            
            $index = 0;    
            for ($i = 0; $i < sizeof($withdrawProductIDArray); $i++) {

                $salesID = $saleModel->newSale($fromStorageID, $customerNumber, $withdrawProductIDArray[$i], $withdrawQuantityArray[$i], $userID, $comment, $date);
                $inventoryInfo->transferFromStorage($fromStorageID, $withdrawProductIDArray[$i], $withdrawQuantityArray[$i]);
                
                if ($regMacAdresseArray[$i] == "1") {
                    $newIndex = $this->withdrawMacAdresse($withdrawProductIDArray[$i], $withdrawQuantityArray[$i], $macAdresseArray, $fromStorageID, $index, $salesID);
                    $index = $newIndex;
                }
            }
            echo json_encode("success");
    }
    
    private function withdrawMacAdresse($withdrawProductID, $withdrawQuantity, $macAdresseArray, $fromStorageID, $index, $salesID) {
        $inventoryInfo = $GLOBALS["inventoryModel"];
        $saleModel = $GLOBALS["saleModel"];
        for ($x = 0; $x < $withdrawQuantity; $x++) {
            $fromInventoryID = $inventoryInfo->getInventoryID($withdrawProductID, $fromStorageID);
            $inventoryInfo->removeMacAdresse($fromInventoryID[0]["inventoryID"], $macAdresseArray[$index]);
            $saleModel->addSalesMac($salesID, $macAdresseArray[$index]);
            $index++;
        }
        return $index;
    }

    private function getProdQuantity() {
        $givenProductID = $_REQUEST["givenProductID"];
        $inventoryInfo = $GLOBALS["inventoryModel"];
        $storageModel = $GLOBALS["storageModel"];

        if (isset($_POST['givenStorageID'])) {
            $givenStorageID = $_REQUEST['givenStorageID'];
            $inventoryModel = $inventoryInfo->getProdFromStorageIDAndProductID($givenStorageID, $givenProductID);
            $negativeSupport = $storageModel->getNegativeSupportStatus($givenStorageID);
        } else {
            $givenUserID = $_SESSION["userID"];
            $restrictionInfo = $GLOBALS["restrictionModel"];
            $restrictionModel = $restrictionInfo->getUserAndGroupRes($givenUserID);

            $givenStorageID = $restrictionModel[0]['storageID'];
            $negativeSupport = $storageModel->getNegativeSupportStatus($givenStorageID);
            $inventoryModel = $inventoryInfo->getProdFromStorageIDAndProductID($givenStorageID, $givenProductID);
        }

        $data = json_encode(array("prodInfo" => $inventoryModel, "negativeSupport" => $negativeSupport));
        echo $data;
    }

    private function getAllMySales() {
        $givenUserID = $_SESSION["userID"];

        $saleModel = $GLOBALS["saleModel"];

        if (isset($_POST['givenProductSearchWord'])) {
            $givenProductSearchWord = "%{$_REQUEST["givenProductSearchWord"]}%";
            $mySales = $saleModel->getMySales($givenUserID, $givenProductSearchWord);
        } else {
            $givenProductSearchWord = "%%";
            $mySales = $saleModel->getMySales($givenUserID, $givenProductSearchWord);
        }

        if ($_SESSION["userLevel"] == "Administrator") {
            $userModel = $GLOBALS["userModel"];
            $usernames = $userModel->getUsername();
            $data = json_encode(array("mySales" => $mySales, "usernames" => $usernames));
        } else {
            $data = json_encode(array("mySales" => $mySales));
        }
        echo $data;
    }

    private function getSalesFromID() {
        $givenSalesID = $_REQUEST["givenSalesID"];

        $saleModel = $GLOBALS["saleModel"];

        $saleFromID = $saleModel->getSaleFromID($givenSalesID);

        $data = json_encode(array("sale" => $saleFromID));
        echo $data;
    }

    private function editMySale() {
        $editSaleID = $_REQUEST["editSaleID"];
        $editCustomerNr = $_REQUEST["editCustomerNr"];
        $editComment = $_REQUEST["editComment"];

        $saleModel = $GLOBALS["saleModel"];
        $edited = $saleModel->editMySale($editSaleID, $editCustomerNr, $editComment);

        if ($edited) {
            echo json_encode("success");
        }
    }

    private function getResCount() {
        $givenUserID = $_SESSION["userID"];
        $restrictionModel = $GLOBALS["restrictionModel"];

        $count = $restrictionModel->resCount($givenUserID);

        $resCount = $count[0]["COUNT(*)"];
        echo json_encode($resCount);
    }

    private function getStoProFromCat() {
        $givenCategoryID = $_REQUEST["givenCategoryID"];
        $inventoryInfo = $GLOBALS["inventoryModel"];

        if (isset($_POST['givenStorageID'])) {
            $givenStorageID = $_REQUEST["givenStorageID"];
            if ($givenCategoryID == 0) {
                $result = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID);
                $data = json_encode(array("storageProduct" => $result));
                echo $data;
            } else {
                $result = $inventoryInfo->getStoProFromCat($givenStorageID, $givenCategoryID);
                $data = json_encode(array("storageProduct" => $result));
                echo $data;
            }
        } else {
            $givenUserID = $_SESSION["userID"];
            $restrictionInfo = $GLOBALS["restrictionModel"];
            $restrictionModel = $restrictionInfo->getUserAndGroupRes($givenUserID);
            $givenStorageID = $restrictionModel[0]['storageID'];
            if ($givenCategoryID == 0) {
                $result = $inventoryInfo->getAllStorageInventoryByStorageID($givenStorageID);
                $data = json_encode(array("storageProduct" => $result));
                echo $data;
            } else {
                $result = $inventoryInfo->getStoProFromCat($givenStorageID, $givenCategoryID);
                $data = json_encode(array("storageProduct" => $result));
                echo $data;
            }
        }
    }

    private function chooseUserSales() {
        if (isset($_POST['username'])) {
            $usernameArray = $_REQUEST["username"];
        } else {
            $usernameArray = array();
        }
        $saleModel = $GLOBALS["saleModel"];

        foreach ($usernameArray as $user):
            if ($user == 0) {
                $getAllUserSale = $saleModel->getAllSaleInfo();
                $data = json_encode(array("mySales" => $getAllUserSale));
                echo $data;
                return false;
            }
        endforeach;


        $getUserSale = $saleModel->getSelectedUserSale($usernameArray);

        $data = json_encode(array("mySales" => $getUserSale));
        echo $data;
    }
    
    private function getSalesMacFromID(){
        $givenSalesID = $_REQUEST["givenSalesID"];
        $saleModel = $GLOBALS["saleModel"];
        $macAdresse = $saleModel->getMacFromSaleID($givenSalesID);
        
        $data = json_encode(array("mySalesMac" => $macAdresse));
        echo $data;
    }

}
