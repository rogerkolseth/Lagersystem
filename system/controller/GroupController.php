<?php

require_once("Controller.php");

class GroupController extends Controller {

    public function show($page) {
        if ($page == "groupAdm") {
            $this->showGroupPage();
        } 
    }
    
    private function showGroupPage(){
        return $this->render("groupAdm");
    }
}
