<?php

// Represents a controller of our website
abstract class Controller {

    /**
     * Renders the page - outputs its content
     * @param string $page
     */
    public abstract function show($page);

    /**
     * Takes view part (template) and model part (data) and renders the page content
     *
     * @param string $templateName name of the template to use
     * @param array $data optional data array to be passed to template
     * @return bool true on success
     */
    protected function render($templateName, $data = array()) {
        // Store data in global variables
        foreach ($data as $dataKey => $dataValue) {
            $GLOBALS[$dataKey] = $dataValue;
        }
        // Include template
        $templatePath = "view/{$templateName}.php";
        $success = true;
        if (!file_exists($templatePath)) return false;
        include($templatePath);
        return true;
    }
    

}
//$type, $desc, $storageID, $fromStorageID, $toStorageID, $quantity, $oldQuantity, $newQuantity, $differential, $userID, $onUserID, $productID, $date, $customerNr