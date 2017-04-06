<?php

require_once("Controller.php");

class ReturnController extends Controller {
    
    public function show($page){
        if ($page == "return"){
            $this->returnPage();
        } else if ($page == "myReturns"){
            $this->myReturnsPage();
        } else if ($page == "getMyReturns"){
            $this->getAllMyReturns();
        } else if ($page == "returnProduct"){
            $this->returnProduct();
        } else if ($page == "getReturnsFromID"){
            $this->getReturnsFromID();
        } else if ($page == "editMyReturn"){
            $this->editMyReturn();
        } else if ($page == "stockDelivery"){
            $this->stockDelivery();
        } else if ($page == "returnSingle"){
            $this->returnSinlePage();
        }
    }
    
    private function returnPage() {
        return $this->render("return");
    }
    
    private function returnSinlePage() {
        return $this->render("returnSingle");
    }
    
    private function myReturnsPage(){
        return $this->render("myReturns");
    }
    
    private function returnProduct(){
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
    
    private function getAllMyReturns(){
        $givenUserID = $_SESSION["userID"];
        
        $returnModel = $GLOBALS["returnModel"];
        
        if (isset($_POST['givenProductSearchWord'])) {
            $givenProductSearchWord = "%{$_REQUEST["givenProductSearchWord"]}%";
            $myReturns = $returnModel->getAllReturnInfo($givenUserID, $givenProductSearchWord);
        } else {
            $givenProductSearchWord = "%%";
            $myReturns = $returnModel->getAllReturnInfo($givenUserID, $givenProductSearchWord);
        }
        
        $data = json_encode(array("myReturns" => $myReturns));
        echo $data;
    }
    
        private function getReturnsFromID(){
        $givenReturnsID = $_REQUEST["givenReturnsID"];
        
        $returnModel = $GLOBALS["returnModel"];
        
        $returnFromID = $returnModel->getReturnFromID($givenReturnsID);
        
        $data = json_encode(array("returns" => $returnFromID));
        echo $data;
    }
    
    private function editMyReturn(){
        $editReturnID = $_REQUEST["editReturnID"];
        $editCustomerNr = $_REQUEST["editCustomerNr"];
        $editComment = $_REQUEST["editComment"];
        
        $returnModel = $GLOBALS["returnModel"];
        $edited = $returnModel->editMyReturn($editReturnID, $editCustomerNr, $editComment);        
           
        if($edited){
            echo json_encode("success");
        }
    }
    
    private function stockDelivery(){
        $transferProductIDArray = $_REQUEST["deliveryProductID"];
        $transferQuantityArray = $_REQUEST["deliveryQuantity"];
        $toStorageID = "1";
        
        $type = "Varelevering";
        $desc= "Inn på lager";
        $sessionID = $_SESSION["userID"];

            $loggModel = $GLOBALS["loggModel"];
            $inventoryInfo = $GLOBALS["inventoryModel"];

            for ($i = 0; $i < sizeof($transferProductIDArray); $i++) {
                $count = $inventoryInfo->doesProductExistInStorage($toStorageID, $transferProductIDArray[$i]);

                    if ($count[0]["COUNT(*)"] < 1) {
                        $loggModel->stockdelivery($type, $desc, $sessionID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                        $inventoryInfo->addInventory($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                    } else {
                        $loggModel->stockdelivery($type, $desc, $sessionID, $toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                        $inventoryInfo->transferToStorage($toStorageID, $transferProductIDArray[$i], $transferQuantityArray[$i]);
                    }     
            }

        $data = json_encode("success");
        echo $data;
    }          
}