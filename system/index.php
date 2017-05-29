<?php
// This index-file is based on code from Datamodellering og databaseapplikasjoner classes

//Creates a new Session with the client

session_start();
//Checking if verified session are true. If the verified variable is false or not set, user are sent back to login page

if ($_SESSION["verified"] == true) {

// includes file with all controllers to include, and include Interface file
    require_once("controller/IncludedControllers.php");
    require_once("controller/Interface.php");


//Include file with all models to include. And include DB connection file
    require_once("model/IncludedModels.php");
    require_once("model/DBconnect.php");

//Creates a new API 
    $interface = new API();

    //Gets getController 
    $controller = $interface->getController();

    //Calls the show function of the logincontroller, if request is registerd in controller
    if ($controller instanceof Controller) {
        $controller->show($interface->getRequest());
    } 
} else {
    // if "verified" is not true, redirect user to first index page (log in page will be viewed)
  header("Location:../");
    
}