<?php

require_once("Controller.php"); //include controller

class HomeController extends Controller {

    //Decide wich function to run based on passed $requset variable

    public function show($request) {
        if ($request == "home"){
            $this->showHomePage();
        } 
         
    }
    
    /**
     * display home page
     */
    private function showHomePage(){
        return $this->view("home");
    }

}    
    


