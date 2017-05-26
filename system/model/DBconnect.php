<?php

// DatabaseCon information
$servername = "localhost";
$username = "root";
$password = "Tafjord123";
$dbname = "tafjord";


//connection to the database
$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);

// Create new instance of data models
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

