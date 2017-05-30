<?php

// this class is based on teachingcode represented in Datamodellering og databaseapplikasjoner
// class at NTNU Ålesund

class API {

    /**
     * 
     * @return string Returns the requestion passed from view
     */
    public function getRequest() {
        // gets request passed from view, if no request is passed 
        // set request as "home" (display home page)
        if (isset($_REQUEST["request"])) { // get posted request
            $request = $_REQUEST["request"];
        } else {
            $request = "home"; // set default request
        }
        return $request; // returns request result
    }

    /**
     * 
     * @return \LoginController get the login controller class
     */
    public function getLoginController() { 
        return new LoginController();
    }

    /**
     * 
     *  Decide wich controller to handle requset
     */
    public function getController() {
        $request = $this->getRequest();     // gets value of request
        
        if ($_SESSION["verified"] == true) {    // checks if user is logged inn
            
            // checks for request and returns controller to handle spesific request
            switch ($request) {
                case "home":
                    return new HomeController($request);
                         
                case "transfer" :
                case "getTransferRestriction" :
                case "transferProduct" : 
                case "getuserAndGroupRes" :  
                    return new TransferController($request);
                    
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
                    return new SaleController($request);
                    
                case "return" :
                case "myReturns" :
                case "getMyReturns" :
                case "returnProduct" :
                case "getReturnsFromID" :    
                case "editMyReturn" :
                case "stockDelivery" :  
                    return new ReturnController($request);
                    
                case "getAllProductInfo" :
                case "getProductByID" :
                case "getProductLocation" :    
                    return new ProductController($request);    
                    
                case "getAllStorageInfo" :  
                case "getStorageByID" :
                case "getStorageRestriction" :
                case "getStorageProduct" :  
                case "chartProduct" :    
                case "stocktacking" :    
                    return new StorageController($request); 
                  
                case "getUserInfo" :    
                case "getUserByID" :   
                case "getUserRestriction" :  
                case "editUser" :
                case "editUserEngine" :
                case "employeeTraning" :   
                case "editLoggedInUser" :    
                    return new UserController($request);
                    
                case "loginEngine":
                case "logOut" :
                    return new LoginController($request);
                        
                case "uploadImageShortcut2" :
                    return new mediaController($request);
                
                case "sendInventarWarning" :
                case "newPassword" :    
                    return new EmailController($request); 
                    
                case "getCatWithProd" :    
                case "getCatWithMedia" : 
                case "getCatWithProdAndSto" :    
                    return new CategoryController($request);        
            }
            
            // only give access to function if user have userlever "Administrator"
            if ($_SESSION["userLevel"] == "Administrator") {   
                    switch ($request) {
                case "productAdm" :
                case "addProductEngine" :
                case "editProductEngine" :
                case "deleteProductEngine" :  
                case "getProductFromCategory" :  
                case "getLowInventory" :
                    return new ProductController($request);
                    
                case "storageAdm":
                case "addStorageEngine":
                case "editStorageEngine" :
                case "deleteStorageEngine" :
                case "deleteSingleProd" :   
                case "emailWarning" :   
                case "setWarningLimit" :   
                case "getInventoryMac" :    
                    return new StorageController($request);
                    
                case "userAdm"    :
                case "editUserEngine" :           
                case "addRestriction" :    
                case "addUserEngine" : 
                case "deleteUserEngine" : 
                case "deleteSingleRes" :
                    return new UserController($request);    
                    
                case "mediaAdm" :
                case "uploadImage" :  
                case "getAllMediaInfo" :
                case "uploadImageShortcut" :
                case "getMediaByID" :  
                case "editMedia" :    
                case "deleteMedia" :
                case "getMediaFromCategory" :    
                    return new mediaController($request);
                    
                case "addCategoryEngine" :
                case "categoryAdm" :  
                case "getAllCategoryInfo" :
                case "getCategorySearchResult" :
                case "getCategoryByID" :
                case "deleteCategoryEngine" :
                case "editCategoryEngine" :
                    return new CategoryController($request);
                    
                case "showUserSale" :
                    return new SaleController($request);
                    
                case "showUserReturns" :
                case "getReturnsMacFromID" :    
                    return new ReturnController($request);
                    
                case "logg" :
                case "getAllLoggInfo" :
                case "getLatestLoggInfo" :
                case "loggCheck" :
                case "getLoggCheckStatus" :   
                case "getAdvanceSearchData" :   
                case "advanceLoggSearch" :    
                    return new LoggController($request);
                    
                case "groupAdm":
                case "addGroupEngine" :
                case "getGroupSearchResult" :
                case "getGroupByID" :
                case "deleteGroupEngine" :   
                case "editGroupEngine" :    
                case "addGroupRestriction" :
                case "getAllGroupInfo" : 
                case "addGroupMember" :
                case "getGroupMember" :
                case "getGroupRestriction" :
                case "deleteGroupMember" :
                case "deleteGroupRestriction" :
                case "getGroupRestrictionFromSto" : 
                case "getGroupMembershipFromUserID" :    
                    return new GroupController($request);
                    }
                }
            
            
        } else {
            // if user is not verified (not logged in), start login controller to display login page
            return new LoginController();
        }
    }

}
