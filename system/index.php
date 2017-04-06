<?php

/////////////////////////////////////////////////
// The main index.php file that glues it all togheter
////////////////////////////////////////////////

//Creates a new Session with the client
session_start();

//Checking if AreLoggedIn Session are set and not false. If the AreLoggedIn is false or not set, user are sent back to login.
if(($_SESSION["AreLoggedIn"]== false)||(!isset($_SESSION["AreLoggedIn"])))
{
  header("Location:../");
}

// View layer - The Same header for all pages
// require("view/header.php");
 
// Controller layer - select page to display (controller will handle it)
// This will select necassary $template and $data
require_once("controller/controllers.php");
require_once("controller/Router.php");

 
 

// View layer - The same footer for all pages
// require("view/footer.html");

// Global configuration
// require_once("config");

// Model layer - Database functions
 require_once("model/DBconnect.php");



$router = new Router();
$controller = $router->getController();
if ($controller instanceof Controller) {
    // Show page content. It might involve selecting data and rendering a taplate
    $controller->show($router->getPage());
    
}
