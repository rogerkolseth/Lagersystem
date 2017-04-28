<?php

require_once("Controller.php");

// Represents home page
class transferController extends Controller {

    // Render "Overview" view

    public function show($page) {
        if ($page == "transfer") {
            $this->transferPage();
        } else if ($page == "getTransferRestriction") {
            $this->getTransferRestriction();
        } else if ($page == "transferProduct") {
            $this->transferProduct();
        } else if ($page == "transferSingle") {
            $this->transferSinglePage();
        }
    }

    private function transferPage() {
        $givenUserID = $_SESSION["userID"];
        $restrictionInfo = $GLOBALS["restrictionModel"];
        $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromUserID($givenUserID);

        $data = array("restrictionInfo" => $restrictionModel);

        return $this->render("transfer", $data);
    }

    private function transferSinglePage() {
        $givenUserID = $_SESSION["userID"];
        $restrictionInfo = $GLOBALS["restrictionModel"];
        $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromUserID($givenUserID);

        $data = array("restrictionInfo" => $restrictionModel);

        return $this->render("transferSingle", $data);
    }

    private function getTransferRestriction() {
        $givenUserID = $_SESSION["userID"];
        $restrictionInfo = $GLOBALS["restrictionModel"];
        $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromUserID($givenUserID);

        $data = json_encode(array("transferRestriction" => $restrictionModel));
        echo $data;
    }

    private function transferProduct() {
        $fromStorageID = $_REQUEST["fromStorageID"];
        $transferProductIDArray = $_REQUEST["transferProductID"];
        $transferQuantityArray = $_REQUEST["transferQuantity"];
        $toStorageID = $_REQUEST["toStorageID"];
        $regMacAdresseArray = $_REQUEST["regMacadresse"];
        if (isset($_POST['deliveryMacadresse'])) {
            $macAdresseArray = $_REQUEST["deliveryMacadresse"];
        }

        //LOGG
        $type = 8;
        $desc = "Overførte produkt";
        $sessionID = $_SESSION["userID"];



        $loggModel = $GLOBALS["loggModel"];
        $result = $loggModel->loggCheck($type);
        $inventoryInfo = $GLOBALS["inventoryModel"];
        
        if (in_array("1", $regMacAdresseArray)) {
                $check = $inventoryInfo->doesMacExist();
                if ($count[0]["COUNT(*)"] < 1) {
                    return false;
                }
            } 
                
        $index = 0;
        for ($i = 0; $i < sizeof($transferProductIDArray); $i++) {
            
                $count = $inventoryInfo->doesProductExistInStorage($toStorageID, $transferProductIDArray[$i]);

                if ($count[0]["COUNT(*)"] < 1) {
                    if ($result[0]["typeCheck"] > 0) {
                        $loggModel->transferLogg($type, $desc, $sessionID, $fromStorageID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                    }
                    $inventoryInfo->addInventory($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                    $inventoryInfo->transferFromStorage($fromStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                } else {
                    if ($result[0]["typeCheck"] > 0) {
                        $loggModel->transferLogg($type, $desc, $sessionID, $fromStorageID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                    }
                    $inventoryInfo->transferFromStorage($fromStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                    $inventoryInfo->transferToStorage($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                }
                if ($regMacAdresseArray[$i] == "1") {
                    for ($x = 0; $x < $transferQuantityArray[$i]; $x++) {
                        $toInventoryID = $inventoryInfo->getInventoryID($transferProductIDArray[$i], $toStorageID);
                        $fromInventoryID = $inventoryInfo->getInventoryID($transferProductIDArray[$i], $fromStorageID);
                        $remove = $inventoryInfo->removeMacAdresse($fromInventoryID[0]["inventoryID"], $macAdresseArray[$index]);
                        if ($remove) {
                            $add = $inventoryInfo->addMacAdresse($toInventoryID[0]["inventoryID"], $macAdresseArray[$index]);
                        }
                        $index++;
                    }
                }
            }
            $data = json_encode("success");
            echo $data;
        
        
    }

}
