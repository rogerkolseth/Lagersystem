<?php

// main class in controller

// this class is based on teachingcode represented in Datamodellering og databaseapplikasjoner
// class at NTNU Ålesund
abstract class Controller {

    /**
     * Hold request variable, for other controller to access
     */
    
//    public abstract function show($request);

    
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
            include($pageName);
            return true;
        }
    }
    
    
     /**
     * Store data in global variables an make dem accessible from view page
     */
    
    protected function data($data = array()){
        // Store data in global variables
        foreach ($data as $dataKey => $dataValue) {
            $GLOBALS[$dataKey] = $dataValue;
        }
    }

}
