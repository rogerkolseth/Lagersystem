<?php

require_once("Controller.php");

class ProductController extends Controller {
    //Decide wich function to run based on passed $requset variable
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
    
    /**
     * display product administration page
     */  
    private function productAdmPage() {
        return $this->view("productAdm");
    }

    /**
     * add product information to database
     */      
    private function addProductEngine() {
        // get POSTed values
        $givenProductName = $_REQUEST["givenProductName"];
        $givenPrice = $_REQUEST["givenPrice"];
        $givenCategoryID = $_REQUEST["givenCategoryID"];
        $givenMediaID = $_REQUEST["givenMediaID"];
        if (isset($_POST['givenMacAdresse'])) {
        $givenMacAdresse = $_REQUEST["givenMacAdresse"];
        } else {
        $givenMacAdresse = "0"; // if product dont use mac, givenMacAdresse = 0
        }
        $sessionID = $_SESSION["userID"];   // get userID from session
        $setSessionID = $GLOBALS["userModel"];  // get usermodel
        $setSessionID->setSession($sessionID);  //set global variable in database
        
        $productCreationInfo = $GLOBALS["productModel"];    // get product model
        // add product information to database
        $added = $productCreationInfo->addProduct($givenProductName, $givenPrice, $givenCategoryID, $givenMediaID, $givenMacAdresse);
        // if success, echo response to view
        if($added){
        echo json_encode("success");} 
        else {return false;}
    }

    /**
     * edit product information in database
     */   
    private function editProductEngine() {
        // get POSTed values
        $editProductName = $_REQUEST["editProductName"];
        $editPrice = $_REQUEST["editPrice"];
        $editCategoryID = $_REQUEST["editCategoryID"];
        $editMediaID = $_REQUEST["editMediaID"];
        $editProductID = $_REQUEST["editProductID"];
        $sessionID = $_SESSION["userID"];
        
        $sesionLog = $GLOBALS["userModel"];     // get user model
        $productEditInfo = $GLOBALS["productModel"];    // get product model
        $sesionLog->setSession($sessionID); // set global variable in database
        
        //update product information in database
        $edited = $productEditInfo->editProduct($editProductName, $editProductID, $editPrice, $editCategoryID, $editMediaID);
        
        // if success, echo a response to view
        if($edited){
        echo json_encode("success");} 
        else {return false;}
    }

    /**
     * delete product information in database
     */ 
    private function deleteProductEngine() {
        //get posted variables
        $removeProductID = $_REQUEST["deleteProductID"];
        
        $sessionID = $_SESSION["userID"];

        // set global variable in database
        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);

        //get involved model and run modelfunction
        $removeProduct = $GLOBALS["productModel"];
        $delited = $removeProduct->removeProduct($removeProductID);
        
        //returns "success" if delited
        if($delited){
        echo json_encode("success");} 
        else {return false;}
    }
    
    /**
     * get all product information in database
     */ 
    private function getAllProductInfo() {
        $productInfo = $GLOBALS["productModel"];
        
        // if searchword is posted, get result from model
        if (isset($_POST['givenProductSearchWord'])) {
            $givenProductSearchWord = "%{$_REQUEST["givenProductSearchWord"]}%";
            $productModel = $productInfo->getSearchResult($givenProductSearchWord);
        } else { 
            // else get all product result from model
            $givenProductSearchWord = "%%";
            $productModel = $productInfo->getSearchResult($givenProductSearchWord);
        }
        
        // Echo result as an array to view
        $data = json_encode(array("productInfo" => $productModel));
        echo $data;
    }
    
    /**
     * Get al product info from productID
     */ 
    private function getProductByID(){
        $givenProductID = $_REQUEST["givenProductID"]; // get posted value

        // get all product info from productID
        $productInfo = $GLOBALS["productModel"]; 
        $productModel = $productInfo->getAllProductInfoFromID($givenProductID);

        // get all media information
        $mediaModel = $GLOBALS["mediaModel"];
        $mediaInfo = $mediaModel->getAllMediaInfo();
        
        // get all category information
        $categoryModel = $GLOBALS["categoryModel"];
        $categoryInfo = $categoryModel->getAllCategoryInfo();
        
        // echo result to view as an nested array
        $data = json_encode(array(
            "product" => $productModel, 
            "media" => $mediaInfo, 
            "category" => $categoryInfo));
        echo $data;
    }
    
    /**
     * Get location of product (which storage contains this product)
     */ 
    private function getProductLocation(){
        //get posted variables
        $givenProductID = $_REQUEST['givenProductID'];

        //get productloaction from model, from productID
        $inventoryInfo = $GLOBALS["inventoryModel"];
        $inventoryModel = $inventoryInfo->getAllProductLocationByProductID($givenProductID);
        
        // echo result as an array to view
        $data = json_encode(array("productLocation" => $inventoryModel));
        echo $data; 
    }
    
    /**
     * get storage and productss with low inventory status, 
     */ 
    private function getLowInventory(){
        // get low inventory information from model
        $inventoryModel = $GLOBALS["inventoryModel"];
        $inventoryInfo = $inventoryModel->getLowInventory();
        
        //echo result as an array to view
        $data = json_encode(array("lowInv" => $inventoryInfo));
        echo $data;
    }

    /**
     * Get products within a given category
     */ 
    private function getProductFromCategory(){
        $givenCategoryID = $_REQUEST["givenCategoryID"]; // get POSTed value
        if($givenCategoryID == 0){ // if 0, user have selected "show all"
            $this->getAllProductInfo(); // runs getAllProductInfo function
        } else {
        $productInfo = $GLOBALS["productModel"];
        //get all product within category from model
        $result = $productInfo->getProductFromCategory($givenCategoryID);
        
        // echo result as an array to view
        $data = json_encode(array("productInfo" => $result));
        echo $data;
        }
    }
} 
