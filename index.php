<?php

//Index files are based based on teachingcode represented in Datamodellering og databaseapplikasjoner
// class at NTNU Ålesund

// Creae a new session with the client
session_start();
//Check if the user are logged in, if true the user will be redirected to the main index file.

// creates session variables. Verified is default as false
$_SESSION["verified"] = false;
$_SESSION["nameOfUser"] = "";
$_SESSION["userID"] = "";
$_SESSION["userLevel"] = "";

 
// includes file with all controllers to include, and include Interface file
require_once("system/controller/IncludedControllers.php");
require_once("system/controller/Interface.php");
 

//Include file with all models to include. And include DB connection file
require_once("system/model/IncludedModels.php");
require_once("system/model/DBconnect.php");


//Creates a new API 
$interface = new API();

//Gets logincontroller
$controller = $interface->getLoginController();





    
    

