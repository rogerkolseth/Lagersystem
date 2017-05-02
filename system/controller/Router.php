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
                case "getuserAndGroupRes" :  
                    return new TransferController();
                    
                case "sale" :
                case "withdrawProduct" :  
                case "getProdQuantity" :   
                case "mySales" :   
                case "getMySales"   : 
                case "getSalesFromID" :   
                case "editMySale" :  
                case "getResCount" :
                case "getLastSaleInfo" :
                case "getAllLastSaleInfo" :
                case "getStoProFromCat" :    
                case "getSalesMacFromID" :  
                    return new SaleController();
                    
                case "return" :
                case "myReturns" :
                case "getMyReturns" :
                case "returnProduct" :
                case "getReturnsFromID" :    
                case "editMyReturn" :
                case "stockDelivery" :  
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
                case "employeeTraning" :    
                    return new UserController();
                    
                case "uploadImageShortcut2" :
                    return new mediaController();
                
                case "sendInventarWarning" :
                case "newPassword" :    
                    return new EmailController(); 
                    
                case "getCatWithProd" :    
                case "getCatWithMedia" : 
                case "getCatWithProdAndSto" :    
                    return new CategoryController();        
            }
            
            if ($_SESSION["userLevel"] == "Administrator") {   
                    switch ($page) {
                case "productAdm" :
                case "addProductEngine" :
                case "editProductEngine" :
                case "deleteProductEngine" :  
                case "getProductFromCategory" :  
                case "getLowInventory" :
                    return new ProductController();
                    
                case "storageAdm":
                case "addStorageEngine":
                case "editStorageEngine" :
                case "deleteStorageEngine" :
                case "deleteSingleProd" :   
                case "emailWarning" :   
                case "setWarningLimit" :   
                case "getInventoryMac" :    
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
                case "getMediaFromCategory" :    
                    return new mediaController();
                    
                case "addCategoryEngine" :
                case "categoryAdm" :  
                case "getAllCategoryInfo" :
                case "getCategorySearchResult" :
                case "getCategoryByID" :
                case "deleteCategoryEngine" :
                case "editCategoryEngine" :
                    return new CategoryController();
                    
                case "showUserSale" :
                    return new SaleController();
                    
                case "showUserReturns" :
                case "getReturnsMacFromID" :    
                    return new ReturnController();
                    
                case "logg" :
                case "getAllLoggInfo" :
                case "getLatestLoggInfo" :
                case "loggCheck" :
                case "getLoggCheckStatus" :   
                case "getAdvanceSearchData" :   
                case "advanceLoggSearch" :    
                    return new LoggController();
                    
                case "groupAdm":
                case "addGroupEngine" :
                case "getGroupSearchResult" :
                case "getGroupByID" :
                case "deleteGroupEngine" :   
                case "editGroupEngine" :    
                case "addGroupRestriction" :    
                    return new GroupController();
                    }
                }
            
            
        } else {
            return new LoginController();
        }
    }

}
