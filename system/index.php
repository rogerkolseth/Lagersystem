<?php

/////////////////////////////////////////////////
// The main index.php file that glues it all togheter
////////////////////////////////////////////////
//Creates a new Session with the client
session_start();

//Checking if AreLoggedIn Session are set and not false. If the AreLoggedIn is false or not set, user are sent back to login.
if (($_SESSION["AreLoggedIn"] == false) || (!isset($_SESSION["AreLoggedIn"]))) {
    header("Location:../");
} else if ($_SESSION["AreLoggedIn"] == true) {



// Controller layer - select page to display (controller will handle it)
// This will select necassary $template and $data
    require_once("controller/includedControllers.php");
    require_once("controller/Router.php");
    

// Model layer - Database functions
    require_once("model/includedModels.php");
    require_once("model/DBconnect.php");
    

    $router = new Router();
    
    $controller = $router->getController();

    $controller->show($router->getRequest());
    
}