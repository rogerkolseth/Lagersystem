<?php

require_once("Controller.php");

// Represents home page
class ProductController extends Controller {

    // Render "Overview" view

    public function show($page) {
        if ($page == "productAdm") {
            $this->productAdmPage();
        } else if ($page == "addProductEngine") {
            $this->addProductEngine();
        } else if ($page == "editProductEngine") {
            $this->editProductEngine();
        } else if ($page == "deleteProductEngine") {
            $this->deleteProductEngine();
        } else if ($page == "getAllProductInfo"){
            $this->getAllProductInfo();
        } else if ($page == "getProductByID"){
            $this->getProductByID();
        } else if ($page == "getProductLocation") {
            $this->getProductLocation();
        } else if ($page == "getAllCategoryInfo"){
            $this->getAllCategoryInfo();
        }
          else if ($page == "getLowInventory")
        {
            $this->getLowInventory();
        }
    }
    

    private function productAdmPage() {
        return $this->render("productAdm");
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
        $givenMacAdresse = "FALSE";    
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
    
    private function getAllCategoryInfo(){
        $categoryModel = $GLOBALS["categoryModel"];
        $categoryInfo = $categoryModel->getAllCategoryInfo();
        
        $data = json_encode(array("categoryInfo" => $categoryInfo));
        echo $data;
    }
    
    private function getLowInventory()
    {
        $inventoryModel = $GLOBALS["inventoryModel"];
        
        $inventoryInfo = $inventoryModel->getLowInventory();
        
        $data = json_encode(array("lowInv" => $inventoryInfo));
        
        echo $data;
    }

} 
