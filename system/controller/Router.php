<?php

// Controller layer - the router selects controller to use depending on URL and request parameters

class Router {

    // Returns the requested page name

    public function getPage() {
        // Get page from request, or use default
        if (isset($_REQUEST["page"])) {
            $page = $_REQUEST["page"];
        } else {
            // $page = GLOBALS["DEFAULT_PAGE"];
            $page = "home";
        }
        return $page;
    }

    public function getLoginController() {
        return new LoginController();
    }

    // Decide wich page to show

    public function getController() {
        $page = $this->getPage();

        if ((isset($_SESSION["AreLoggedIn"])) && ($_SESSION["AreLoggedIn"] == "true")) {


            switch ($page) {
                case "home":
                    return new HomeController();
                    
                case "loginEngine":
                    return new LoginController();
                    
                case "transfer" :
                case "getTransferRestriction" :
                case "transferProduct" : 
                case "transferSingle" :    
                    return new TransferController();
                    
                case "sale" :
                case "withdrawProduct" :  
                case "getProdQuantity" :   
                case "mySales" :   
                case "getMySales"   : 
                case "getSalesFromID" :   
                case "editMySale" :  
                case "getResCount" :
                case "saleSingle" :  
                case "getLastSaleInfo" :
                case "getAllLastSaleInfo" :
                    return new SaleController();
                    
                case "return" :
                case "myReturns" :
                case "getMyReturns" :
                case "returnProduct" :
                case "getReturnsFromID" :    
                case "editMyReturn" :
                case "stockDelivery" :
                case "returnSingle" :    
                    return new ReturnController();
                    
                case "getAllProductInfo" :
                case "getProductByID" :
                case "getProductLocation" :    
                    return new ProductController();    
                    
                case "getAllStorageInfo" :  
                case "getStorageByID" :
                case "getStorageRestriction" :
                case "getStorageProduct" :  
                case "chartProduct" :    
                case "stocktacking" :    
                    return new StorageController(); 
                  
                case "getUserInfo" :    
                case "getUserByID" :   
                case "getUserRestriction" :  
                case "editUser" :
                case "editUserEngine" :
                    return new UserController();
                    
                case "uploadImageShortcut2" :
                    return new mediaController();
                        
            }
            
            if ($_SESSION["userLevel"] == "Administrator") {   
                    switch ($page) {
                case "productAdm" :
                case "addProductEngine" :
                case "editProductEngine" :
                case "deleteProductEngine" :  
                case "getAllCategoryInfo" :
                case "getLowInventory" :
                    return new ProductController();
                    
                case "storageAdm":
                case "addStorageEngine":
                case "editStorageEngine" :
                case "deleteStorageEngine" :
                case "deleteSingleProd" :    
                    return new StorageController();
                    
                case "userAdm"    :
                case "editUserEngine" :           
                case "addRestriction" :    
                case "addUserEngine" : 
                case "deleteUserEngine" : 
                case "deleteSingleRes" :
                    return new UserController();    
                    
                case "mediaAdm" :
                case "uploadImage" :  
                case "getAllMediaInfo" :
                case "uploadImageShortcut" :
                case "getMediaByID" :  
                case "editMedia" :    
                case "deleteMedia" :
                    return new mediaController();
                    
                case "addCategoryEngine" :
                    return new HomeController();
                    
                case "logg" :
                case "getAllLoggInfo" :
                case "getLatestLoggInfo" :
                    return new LoggController();
                    }
                }
            
            
        } else {
            return new LoginController();
        }
    }

}
