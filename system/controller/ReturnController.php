<?php

require_once("Controller.php");

class ReturnController extends Controller {

    public function show($page) {
        if ($page == "return") {
            $this->returnPage();
        } else if ($page == "myReturns") {
            $this->myReturnsPage();
        } else if ($page == "getMyReturns") {
            $this->getAllMyReturns();
        } else if ($page == "returnProduct") {
            $this->returnProduct();
        } else if ($page == "getReturnsFromID") {
            $this->getReturnsFromID();
        } else if ($page == "editMyReturn") {
            $this->editMyReturn();
        } else if ($page == "stockDelivery") {
            $this->stockDelivery();
        } else if ($page == "showUserReturns") {
            $this->chooseUserReturns();
        }
    }

    private function returnPage() {
        $restrictionModel = $GLOBALS["restrictionModel"];
        $result = $restrictionModel->getAllStorageRestrictionInfo();
        foreach ($result as $result):
            if ($result["storageID"] == "2") {
                $result = "1";
                $returnRestriction = array("returnRestriction" => $result);
                return $this->render("return", $returnRestriction);
            };
        endforeach;
        return $this->render("return");
    }

    private function myReturnsPage() {
        return $this->render("myReturns");
    }

    private function returnProduct() {
        $toStorageID = "2";
        $returnProductIDArray = $_REQUEST["returnProductID"];
        $returnQuantityArray = $_REQUEST["returnQuantity"];
        $customerNumber = $_REQUEST["customerNumber"];
        $userID = $_SESSION["userID"];
        $comment = $_REQUEST["returnComment"];
        $date = $_REQUEST["date"];

        $returnModel = $GLOBALS["returnModel"];
        $inventoryInfo = $GLOBALS["inventoryModel"];

        for ($i = 0; $i < sizeof($returnProductIDArray); $i++) {
            $count = $inventoryInfo->doesProductExistInStorage($toStorageID, $returnProductIDArray[$i]);

            if ($count[0]["COUNT(*)"] < 1) {
                $returnModel->newReturn($toStorageID, $customerNumber, $returnProductIDArray[$i], $returnQuantityArray[$i], $userID, $comment, $date);
                $inventoryInfo->addInventory($toStorageID, $returnProductIDArray[$i], $returnQuantityArray[$i]);
            } else {
                $returnModel->newReturn($toStorageID, $customerNumber, $returnProductIDArray[$i], $returnQuantityArray[$i], $userID, $comment, $date);
                $inventoryInfo->transferToStorage($toStorageID, $returnProductIDArray[$i], $returnQuantityArray[$i]);
            }
        }
        echo json_encode("success");
    }

    private function getAllMyReturns() {
        $givenUserID = $_SESSION["userID"];

        $returnModel = $GLOBALS["returnModel"];

        if (isset($_POST['givenProductSearchWord'])) {
            $givenProductSearchWord = "%{$_REQUEST["givenProductSearchWord"]}%";
            $myReturns = $returnModel->getMyReturns($givenUserID, $givenProductSearchWord);
        } else {
            $givenProductSearchWord = "%%";
            $myReturns = $returnModel->getMyReturns($givenUserID, $givenProductSearchWord);
        }

        if ($_SESSION["userLevel"] == "Administrator") {
            $userModel = $GLOBALS["userModel"];
            $usernames = $userModel->getUsername();
            $data = json_encode(array("myReturns" => $myReturns, "usernames" => $usernames));
        } else {
            $data = json_encode(array("myReturns" => $myReturns));
        }

        echo $data;
    }

    private function getReturnsFromID() {
        $givenReturnsID = $_REQUEST["givenReturnsID"];

        $returnModel = $GLOBALS["returnModel"];

        $returnFromID = $returnModel->getReturnFromID($givenReturnsID);

        $data = json_encode(array("returns" => $returnFromID));
        echo $data;
    }

    private function editMyReturn() {
        $editReturnID = $_REQUEST["editReturnID"];
        $editCustomerNr = $_REQUEST["editCustomerNr"];
        $editComment = $_REQUEST["editComment"];

        $returnModel = $GLOBALS["returnModel"];
        $edited = $returnModel->editMyReturn($editReturnID, $editCustomerNr, $editComment);

        if ($edited) {
            echo json_encode("success");
        }
    }

    private function stockDelivery() {
        $transferProductIDArray = $_REQUEST["deliveryProductID"];
        $transferQuantityArray = $_REQUEST["deliveryQuantity"];
        $regMacAdresseArray = $_REQUEST["regMacadresse"];
        if (isset($_POST['deliveryMacadresse'])) {
            $macAdresseArray = $_REQUEST["deliveryMacadresse"];
        }
        $toStorageID = "1";

        $type = 5;
        $desc = "Inn på lager";
        $sessionID = $_SESSION["userID"];

        $loggModel = $GLOBALS["loggModel"];
        $inventoryInfo = $GLOBALS["inventoryModel"];

        $result = $loggModel->loggCheck($type);

        $index = 0;
        for ($i = 0; $i < sizeof($transferProductIDArray); $i++) {

            $count = $inventoryInfo->doesProductExistInStorage($toStorageID, $transferProductIDArray[$i]);
            if ($count[0]["COUNT(*)"] < 1) {
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->stockdelivery($type, $desc, $sessionID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                }
                $delivery = $inventoryInfo->addInventory($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                if ($regMacAdresseArray[$i] == "1") {
                    $this->addMacAdresse($transferProductIDArray[$i], $transferQuantityArray[$i], $macAdresseArray[$index], $toStorageID);
                    $index++;
                }
            } else {
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->stockdelivery($type, $desc, $sessionID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                }
                $delivery = $inventoryInfo->transferToStorage($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                if ($regMacAdresseArray[$i] == "1") {
                    $this->addMacAdresse($transferProductIDArray[$i], $transferQuantityArray[$i], $macAdresseArray[$index], $toStorageID);
                    $index++;
                }
            }
        }

        if ($delivery) {
            $data = json_encode("success");
            echo $data;
        } else {
            return false;
        }
    }

    private function addMacAdresse($transferProductID, $transferQuantity, $macAdresse, $toStorageID) {
        $inventoryInfo = $GLOBALS["inventoryModel"];
                for ($x = 0; $x < $transferQuantity; $x++) {
                    $inventoryID = $inventoryInfo->getInventoryID($transferProductID, $toStorageID);
                    echo $macAdresse . "," . $inventoryID[0]["inventoryID"];
                    $added = $inventoryInfo->addMacAdresse($inventoryID[0]["inventoryID"], $macAdresse);
                }
    }

    private function chooseUserReturns() {
        if (isset($_POST['username'])) {
            $usernameArray = $_REQUEST["username"];
        } else {
            $usernameArray = array();
        }
        $returnModel = $GLOBALS["returnModel"];

        foreach ($usernameArray as $user):
            if ($user == 0) {
                $getAllUserReturns = $returnModel->getAllReturnInfo();
                $data = json_encode(array("myReturns" => $getAllUserReturns));
                echo $data;
                return false;
            }
        endforeach;


        $getUserReturns = $returnModel->getSelectedUserReturns($usernameArray);

        $data = json_encode(array("myReturns" => $getUserReturns));
        echo $data;
    }

}
