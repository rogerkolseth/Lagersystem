<?php

// Represents a controller of our website
abstract class Controller {

    /**
     * Renders the page - outputs its content
     * @param string $page
     */
    public abstract function show($request);

    /**
     * Takes view part (template) and model part (data) and renders the page content
     *
     * @param string $templateName name of the template to use
     * @param array $data optional data array to be passed to template
     * @return bool true on success
     */
    protected function view($pageName) {
        // Include view page
        $pageName = "view/{$pageName}.php";

        if (!file_exists($pageName)) {
            return false;
        } else {
            include($pageName);
            return true;
        }
    }
    
    protected function data($data = array()){
        // Store data in global variables 
        foreach ($data as $dataKey => $dataValue) {
            $GLOBALS[$dataKey] = $dataValue;
        }
    }

}
