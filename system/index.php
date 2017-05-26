<?php

/////////////////////////////////////////////////
// The main index.php file that glues it all togheter
////////////////////////////////////////////////
//Creates a new Session with the client
// This index-file is based on code from Datamodellering og databaseapplikasjoner classes

session_start();

//Checking if AreLoggedIn Session are set and not false. If the AreLoggedIn is false or not set, user are sent back to login.

if ($_SESSION["verified"] == true) {


// Controller layer - select page to display (controller will handle it)
// This will select necassary $template and $data
    require_once("controller/IncludedControllers.php");
    require_once("controller/Interface.php");


// Model layer - Database functions
    require_once("model/IncludedModels.php");
    require_once("model/DBconnect.php");


    $interface = new API();

    $controller = $interface->getController();

    if ($controller instanceof Controller) {
        $controller->show($interface->getRequest());
    } 
} else {
  header("Location:../");
    
}