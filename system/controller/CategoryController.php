<?php

require_once("Controller.php"); //include controller


class CategoryController extends Controller {

    //Decide wich function to run based on passed $requset variable
    public function __construct($request) {
        switch ($request) {
            case "categoryAdm" :
                return $this->showHomePage();
            case "addCategoryEngine" :
                return $this->addCategory();
            case "getAllCategoryInfo" :
                return $this->getAllCategoryInfo();
            case "getCategorySearchResult" :
                return $this->getCategorySearchResult();
            case "getCategoryByID" :
                return $this->getCategoryByID();
            case "deleteCategoryEngine" :
                return $this->deleteCategoryEngine();
            case "editCategoryEngine" :
                return $this->editCategoryEngine();
            case "getCatWithProd" :
                return $this->getCatWithProd();
            case "getCatWithMedia" :
                return $this->getCatWithMedia();
            case "getCatWithProdAndSto" :
                return $this->getCatWithProdAndSto();
        } 
    }
    
    /**
     * display category administration page
     */ 
    private function showHomePage(){
        return $this->view("categoryAdm");
    }
    
    /**
     *  adds a new category
     */ 
    private function addCategory(){
        $givenCategoryName = $_REQUEST["givenCategoryName"]; // get POSTed category name
        
        $addCategory = $GLOBALS["categoryModel"]; // gets category mode
        $added = $addCategory->addCategory($givenCategoryName); // add a new category from Model
       if($added){
          echo json_encode("success");  // if success, echo a respond to view
       }     
    }
    
    /** 
    * gets all categories
    */ 
    private function getAllCategoryInfo(){
        $categoryModel = $GLOBALS["categoryModel"]; // gets category mode
        $categoryInfo = $categoryModel->getAllCategoryInfo(); // gets all categories from model
        
        // echo result as an array to view
        $data = json_encode(array("categoryInfo" => $categoryInfo));
        echo $data;
    }
    
    /**
    * search for categories
    */ 
    private function getCategorySearchResult(){
        $categoryModel = $GLOBALS["categoryModel"]; // gets category model
        
        if (isset($_POST['givenCategorySearchWord'])) {
            // if search word is given, get result from model from given searchword
            $givenCategorySearchWord = "%{$_REQUEST["givenCategorySearchWord"]}%";
            $searchResult = $categoryModel->getSearchResult($givenCategorySearchWord);
        } else {
            // if searchword is not given, retrive all categires
            $givenCategorySearchWord = "%%";
            $searchResult = $categoryModel->getSearchResult($givenCategorySearchWord);
        }
        
        // echo result as an array to view
        $data = json_encode(array("category" => $searchResult));
        echo $data;
    }
    
    /** 
     * gets category info from ID
     */
    private function getCategoryByID(){
        $givenCategoryID = $_REQUEST["givenCategoryID"]; // gets POSTed categoryID
        $categoryModel = $GLOBALS["categoryModel"]; // gets category Model
        
        $result = $categoryModel->getCategoryByID($givenCategoryID); // get category info from model
        
        // echo result as an array to view
        $data = json_encode(array("categoryByID" => $result));
        echo $data;
    }
    
    /**
     * delete a spesific category
     */
    private function deleteCategoryEngine(){
        $deleteCategoryID = $_REQUEST["deleteCategoryID"];  //gets POSTed categoryID to delete
        
        $categoryModel = $GLOBALS["categoryModel"]; // gets ccategory model
        $delited = $categoryModel->deleteCategory($deleteCategoryID); //delete category from database
        
        if($delited){
        echo json_encode("success");}  // if success echo a respond to view
        else {return false;}
    }
    
    /**
     * edit a spesific category
     */
    private function editCategoryEngine(){
        $editCategoryID = $_REQUEST["editCategoryID"]; //gets POSTed categoryID to edit
        $editCategoryName = $_REQUEST["editCategoryName"]; // gets new categoryname
        
        $categoryEditInfo = $GLOBALS["categoryModel"]; // gets category model
        $edited = $categoryEditInfo->editCategory($editCategoryName, $editCategoryID); //edit category info in database
        
        if($edited){
        echo json_encode("success");} else {return false;} //if success, echo a respond to view
    }
    
    /**
     * gets categories containing a product
     */
    private function getCatWithProd(){
        $categoryModel = $GLOBALS["categoryModel"]; // get category model
        $categoryInfo = $categoryModel->getCatWithProd(); // gets all categories containing a product
        
        // echo result from media as an array to view
        $data = json_encode(array("category" => $categoryInfo));
        echo $data;
    }
    
    /**
     * gets categories containing a media
     */
    private function getCatWithMedia(){
        $categoryModel = $GLOBALS["categoryModel"]; // get categorymodel
        $categoryInfo = $categoryModel->getCatWithMedia(); //gets all categories containing a media
        
        // echo result from model  as an array to view
        $data = json_encode(array("category" => $categoryInfo));
        echo $data;
    }

    /**
     * gets categories containing a product inside a spesific storage
     */
    private function getCatWithProdAndSto(){
        $givenStorageID = $_REQUEST["givenStorageID"]; // get POSTed storageID
        
        $categoryModel = $GLOBALS["categoryModel"]; // gets category model
        $categoryInfo = $categoryModel->getCatWithProdAndSto($givenStorageID); // gets categories in use inside a spesific storage
            
        // echo result from model as an array to view
        $data = json_encode(array("category" => $categoryInfo));
        echo $data;
    }
}   