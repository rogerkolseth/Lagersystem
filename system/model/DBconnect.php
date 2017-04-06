<?php


  require_once("UserModel.php");
  require_once("StorageModel.php");
  require_once("RestrictionModel.php");
  require_once("InventoryModel.php");
  require_once("ProductModel.php");
  require_once("SaleModel.php");
  require_once("returnModel.php");
  require_once("MediaModel.php");
  require_once("CategoryModel.php");
  require_once("LoggModel.php");
  
//connection to the database
$dbConn = new PDO('mysql:host=localhost;dbname=tafjord;charset=utf8mb4', 'root', 'Tafjord123');

// Create data models
$userModel = new UserModel($dbConn);
$storageModel = new StorageModel($dbConn);
$restrictionModel = new RestritionModel($dbConn);
$inventoryModel = new InventoryModel($dbConn);
$productModel = new ProductModel($dbConn);
$saleModel = new SaleModel($dbConn);
$returnModel = new ReturnModel($dbConn);
$mediaModel = new MediaModel($dbConn);
$categoryModel = new CategoryModel($dbConn);
$loggModel = new LoggModel($dbConn);

// TODO - create new models here. First create them as a new class
// TODO - once you have more model classes, perhaps some of the functions can be moved to a common parent class?

