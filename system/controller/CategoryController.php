<?php

require_once("Controller.php");

// Represents home page
class CategoryController extends Controller {

    // view "Overview" view

    public function show($page) {
        switch ($page) {
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
    
    private function showHomePage(){
        return $this->view("categoryAdm");
    }
    
    private function addCategory(){
        $givenCategoryName = $_REQUEST["givenCategoryName"];
        
        $addCategory = $GLOBALS["categoryModel"];
        $added = $addCategory->addCategory($givenCategoryName);
       if($added){
          echo json_encode("success"); 
       }     
    }
    
    private function getAllCategoryInfo(){
        $categoryModel = $GLOBALS["categoryModel"];
        $categoryInfo = $categoryModel->getAllCategoryInfo();
        
        $data = json_encode(array("categoryInfo" => $categoryInfo));
        echo $data;
    }
    
    private function getCategorySearchResult(){
        $categoryModel = $GLOBALS["categoryModel"];
        
        if (isset($_POST['givenCategorySearchWord'])) {
            $givenCategorySearchWord = "%{$_REQUEST["givenCategorySearchWord"]}%";
            $searchResult = $categoryModel->getSearchResult($givenCategorySearchWord);
        } else {
            $givenCategorySearchWord = "%%";
            $searchResult = $categoryModel->getSearchResult($givenCategorySearchWord);
        }
        
        $data = json_encode(array("category" => $searchResult));
        echo $data;
    }
    
    private function getCategoryByID(){
        $givenCategoryID = $_REQUEST["givenCategoryID"];
        $categoryModel = $GLOBALS["categoryModel"];
        
        $result = $categoryModel->getCategoryByID($givenCategoryID);
        
        $data = json_encode(array("categoryByID" => $result));
        echo $data;
    }
    
    private function deleteCategoryEngine(){
        $deleteCategoryID = $_REQUEST["deleteCategoryID"]; 
        
        $categoryModel = $GLOBALS["categoryModel"];
        $delited = $categoryModel->deleteCategory($deleteCategoryID);
        
        if($delited){
        echo json_encode("success");} 
        else {return false;}
    }
    
    private function editCategoryEngine(){
        $editCategoryID = $_REQUEST["editCategoryID"];
        $editCategoryName = $_REQUEST["editCategoryName"];
        $sessionID = $_SESSION["userID"];
        
        $categoryEditInfo = $GLOBALS["categoryModel"];
        $edited = $categoryEditInfo->editCategory($editCategoryName, $editCategoryID);
        
        if($edited){
        echo json_encode("success");} else {return false;}
    }
    
    private function getCatWithProd(){
        $categoryModel = $GLOBALS["categoryModel"];
        $categoryInfo = $categoryModel->getCatWithProd();
        
        $data = json_encode(array("category" => $categoryInfo));
        echo $data;
    }
    
    private function getCatWithMedia(){
        $categoryModel = $GLOBALS["categoryModel"];
        $categoryInfo = $categoryModel->getCatWithMedia();
        
        $data = json_encode(array("category" => $categoryInfo));
        echo $data;
    }

    private function getCatWithProdAndSto(){
        $givenStorageID = $_REQUEST["givenStorageID"];
        
        $categoryModel = $GLOBALS["categoryModel"];
        $categoryInfo = $categoryModel->getCatWithProdAndSto($givenStorageID);
        
        $data = json_encode(array("category" => $categoryInfo));
        echo $data;
    }
}   