<?php

require_once("Controller.php");

// Represents home page
class ProductController extends Controller {

    // Render "Overview" view

    public function show($request) {
        switch ($request) {
            case "productAdm" :
                return $this->productAdmPage();
            case "addProductEngine" :
                return $this->addProductEngine();
            case "editProductEngine" :
                return $this->editProductEngine();
            case "deleteProductEngine" :
                return $this->deleteProductEngine();
            case "getAllProductInfo" :
                return $this->getAllProductInfo();
            case "getProductByID" :
                return $this->getProductByID();
            case "getProductLocation" :
                return $this->getProductLocation();
            case "getLowInventory" :
                return $this->getLowInventory();
            case "getProductFromCategory" :
                return $this->getProductFromCategory();
        }
    }
    

    private function productAdmPage() {
        return $this->view("productAdm");
    }

    private function addProductEngine() {
        $givenProductName = $_REQUEST["givenProductName"];
        $givenPrice = $_REQUEST["givenPrice"];
        $givenCategoryID = $_REQUEST["givenCategoryID"];
        $givenMediaID = $_REQUEST["givenMediaID"];
        $givenProductDate = $_REQUEST["date"];
        if (isset($_POST['givenMacAdresse'])) {
        $givenMacAdresse = $_REQUEST["givenMacAdresse"];
        } else {
        $givenMacAdresse = "0";    
        }
        $sessionID = $_SESSION["userID"];
        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);
        
        $productCreationInfo = $GLOBALS["productModel"];
        $added = $productCreationInfo->addProduct($givenProductName, $givenPrice, $givenCategoryID, $givenMediaID, $givenProductDate, $givenMacAdresse);
        
        if($added){
        echo json_encode("success");} 
        else {return false;}
    }

    private function editProductEngine() {
        $editProductName = $_REQUEST["editProductName"];
        $editPrice = $_REQUEST["editPrice"];
        $editCategoryID = $_REQUEST["editCategoryID"];
        $editMediaID = $_REQUEST["editMediaID"];
        $editProductID = $_REQUEST["editProductID"];
        $sessionID = $_SESSION["userID"];
        
        $sesionLog = $GLOBALS["userModel"];
        $productEditInfo = $GLOBALS["productModel"];
        $sesionLog->setSession($sessionID);
        
        $edited = $productEditInfo->editProduct($editProductName, $editProductID, $editPrice, $editCategoryID, $editMediaID);
        
        if($edited){
        echo json_encode("success");} 
        else {return false;}
    }

    private function deleteProductEngine() {
        $removeProductID = $_REQUEST["deleteProductID"];
        
        $sessionID = $_SESSION["userID"];

        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);

        $removeProduct = $GLOBALS["productModel"];
        $delited = $removeProduct->removeProduct($removeProductID);
        
        if($delited){
        echo json_encode("success");} 
        else {return false;}
    }
    
    private function getAllProductInfo() {
        $productInfo = $GLOBALS["productModel"];

        if (isset($_POST['givenProductSearchWord'])) {
            $givenProductSearchWord = "%{$_REQUEST["givenProductSearchWord"]}%";
            $productModel = $productInfo->getSearchResult($givenProductSearchWord);
        } else {
            $givenProductSearchWord = "%%";
            $productModel = $productInfo->getSearchResult($givenProductSearchWord);
        }
        
        $data = json_encode(array("productInfo" => $productModel));

        echo $data;
    }
    
    private function getProductByID(){
        $givenProductID = $_REQUEST["givenProductID"];

        $productInfo = $GLOBALS["productModel"];
        $productModel = $productInfo->getAllProductInfoFromID($givenProductID);

        $mediaModel = $GLOBALS["mediaModel"];
        $mediaInfo = $mediaModel->getAllMediaInfo();
        
        $categoryModel = $GLOBALS["categoryModel"];
        $categoryInfo = $categoryModel->getAllCategoryInfo();
        
        $data = json_encode(array("product" => $productModel, "media" => $mediaInfo, "category" => $categoryInfo));
        echo $data;
    }
    
    private function getProductLocation(){
        $givenProductID = $_REQUEST['givenProductID'];

        $inventoryInfo = $GLOBALS["inventoryModel"];
        $inventoryModel = $inventoryInfo->getAllProductLocationByProductID($givenProductID);

        $data = json_encode(array("productLocation" => $inventoryModel));
        echo $data; 
    }
    
    private function getLowInventory()
    {
        $inventoryModel = $GLOBALS["inventoryModel"];
        
        $inventoryInfo = $inventoryModel->getLowInventory();
        
        $data = json_encode(array("lowInv" => $inventoryInfo));
        
        echo $data;
    }

    private function getProductFromCategory(){
        $givenCategoryID = $_REQUEST["givenCategoryID"]; 
        if($givenCategoryID == 0){
            $this->getAllProductInfo();
        } else {
        $productInfo = $GLOBALS["productModel"];
        
        $result = $productInfo->getProductFromCategory($givenCategoryID);
        
        $data = json_encode(array("productInfo" => $result));

        echo $data;
        }
    }
} 
