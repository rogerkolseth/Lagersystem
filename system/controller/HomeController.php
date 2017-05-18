<?php

require_once("Controller.php");

// Represents home page
class HomeController extends Controller {

    // Render "Overview" view

    public function show($request) {
        if ($request == "home"){
            $this->showHomePage();
        } 
         
    }
    
    private function showHomePage(){
        return $this->view("home");
    }

}    
    


