<?php

require_once("Controller.php");

// Represents home page
class CategoryController extends Controller {

    // Render "Overview" view

    public function show($page) {
        if ($page == "categoryAdm"){
            $this->showHomePage();
        } else if ($page == "addCategoryEngine"){
            $this->addCategory();
        } else if ($page == "getAllCategoryInfo"){
            $this->getAllCategoryInfo();
        } else if ($page == "getCategorySearchResult"){
            $this->getCategorySearchResult();
        } else if ($page == "getCategoryByID"){
            $this->getCategoryByID();
        } else if ($page == "deleteCategoryEngine"){
            $this->deleteCategoryEngine();
        } else if ($page == "editCategoryEngine"){
            $this->editCategoryEngine();
        } 
         
    }
    
    private function showHomePage(){
        return $this->render("categoryAdm");
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

}   