<?php

// DatabaseCon information
$servername = "localhost";
$username = "root";
$password = "Tafjord123";
$dbname = "tafjord";


//connection to the database
$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);

// Create new data models
$userModel = new UserModel($conn);
$storageModel = new StorageModel($conn);
$restrictionModel = new RestritionModel($conn);
$inventoryModel = new InventoryModel($conn);
$productModel = new ProductModel($conn);
$saleModel = new SaleModel($conn);
$returnModel = new ReturnModel($conn);
$mediaModel = new MediaModel($conn);
$categoryModel = new CategoryModel($conn);
$loggModel = new LoggModel($conn);
$groupModel = new GroupModel($conn);

// TODO - create new models here. First create them as a new class
// TODO - once you have more model classes, perhaps some of the functions can be moved to a common parent class?

$yes;