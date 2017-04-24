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
        } else if ($page == "showUserReturns"){
            $this->chooseUserReturns();
        }
    }

    private function returnPage() {
        return $this->render("return");
    }

    private function myReturnsPage() {
        return $this->render("myReturns");
    }

    private function returnProduct() {
        $toStorageID = $_REQUEST["toStorageID"];
        $returnProductIDArray = $_REQUEST["returnProductID"];
        $returnQuantityArray = $_REQUEST["returnQuantity"];
        $customerNumber = $_REQUEST["customerNumber"];
        $userID = $_SESSION["userID"];
        $comment = $_REQUEST["returnComment"];
        $date = $_REQUEST["date"];


        if ($toStorageID == 0) {
            return false;
        } else {

            for ($i = 0; $i < sizeof($returnProductIDArray); $i++) {


                $returnModel = $GLOBALS["returnModel"];
                $inventoryInfo = $GLOBALS["inventoryModel"];

                $returnModel->newReturn($toStorageID, $customerNumber, $returnProductIDArray[$i], $returnQuantityArray[$i], $userID, $comment, $date);
                $inventoryInfo->transferToStorage($toStorageID, $returnProductIDArray[$i], $returnQuantityArray[$i]);
            }
            echo json_encode("success");
        }
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
        $toStorageID = "1";

        $type = 5;
        $desc = "Inn på lager";
        $sessionID = $_SESSION["userID"];

        $loggModel = $GLOBALS["loggModel"];
        $inventoryInfo = $GLOBALS["inventoryModel"];

        $result = $loggModel->loggCheck($type);


        for ($i = 0; $i < sizeof($transferProductIDArray); $i++) {
            $count = $inventoryInfo->doesProductExistInStorage($toStorageID, $transferProductIDArray[$i]);

            if ($count[0]["COUNT(*)"] < 1) {
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->stockdelivery($type, $desc, $sessionID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                }
                $inventoryInfo->addInventory($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
            } else {
                if ($result[0]["typeCheck"] > 0) {
                    $loggModel->stockdelivery($type, $desc, $sessionID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                }
                $inventoryInfo->transferToStorage($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
            }
        }

        $data = json_encode("success");
        echo $data;
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
