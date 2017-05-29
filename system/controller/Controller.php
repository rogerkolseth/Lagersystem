<?php

// main class in controller

// this class is based on teachingcode represented in Datamodellering og databaseapplikasjoner
// class at NTNU Ålesund
abstract class Controller {

    /**
     * Renders the page - outputs its content
     * @param string $request
     */
    
    public abstract function show($request);

    
    /**
     * Inlcudes view page, and make it display for user
     * @param string of pagename to include (view page name)
     * @return bool true on success
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
     * @param array $data, containing data to pass to template
     */
    
    protected function data($data = array()){
        // Store data in global variables
        foreach ($data as $dataKey => $dataValue) {
            $GLOBALS[$dataKey] = $dataValue;
        }
    }

}
