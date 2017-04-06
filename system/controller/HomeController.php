<?php

require_once("Controller.php");

// Represents home page
class HomeController extends Controller {

    // Render "Overview" view

    public function show($page) {
        if ($page == "home"){
            $this->showInventory();
        } else if ($page == "addCategoryEngine"){
            $this->addCategory();
        }
         
    }
    
    private function showInventory(){
        return $this->render("home");
    }
    
    private function addCategory(){
        $givenCategoryName = $_REQUEST["givenCategoryName"];
        
        $addCategory = $GLOBALS["categoryModel"];
        $added = $addCategory->addCategory($givenCategoryName);
       if($added){
          echo json_encode("success"); 
       } 
        
        
    }

}    
    


