<?php

// Represents a controller of our website
abstract class Controller {


    protected function view($templateName, $data = array()) {
        // Store data in global variables
        foreach ($data as $dataKey => $dataValue) {
            $GLOBALS[$dataKey] = $dataValue;
        }
        // Include template
        $templatePath = "view/{$templateName}.php";
        $success = true;
        if (!file_exists($templatePath))
            return false;
        include($templatePath);
        return true;
    }

    

}

  