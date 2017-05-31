<?php

// main class in controller

// this class is based on teachingcode represented in Datamodellering og databaseapplikasjoner
// class at NTNU Ålesund
abstract class Controller {

    
    /**
     * Inlcudes view page, and make it display for user
     */
    
    protected function view($pageName) {
        // Finds pagename passed to view function
        $pageName = "view/{$pageName}.php";

        // if page excist includes page and display for user
        if (!file_exists($pageName)) {
            return false;
        } else {
            require_once($pageName);
            return true;
        }
    }
    
    
     /**
     * Store data in global variables an make dem accessible from view page
     */
    
    protected function data($variableName, $data){
        // Store data in global variables
            $GLOBALS[$variableName] = $data; 
    }
}
