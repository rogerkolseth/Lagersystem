<?php

require_once("Controller.php");     //include controller

class LoggController extends Controller {

    /**
     * Decide wich function to run based on passed $requset variable
     */
    public function __construct($request) {
        switch ($request) {
            case "logg" :
                return $this->loggPage();
            case "getAllLoggInfo" :
                return $this->getAllLoggInfo();
            case "getLatestLoggInfo" :
                return $this->getLatestLoggInfo();
            case "loggCheck" :
                return $this->loggCheck();
            case "getLoggCheckStatus" :
                return $this->getLoggCheckStatus();
            case "getAdvanceSearchData" :
                return $this->getAdvanceSearchData();
            case "advanceLoggSearch" :
                return $this->advanceLoggSearch();
        }
    }

    /**
     *  dispaly logg page
     */
    private function loggPage() {
        return $this->view("logg");
    }

    /**
     *  gets all log result from a search word, or get all logs
     */
    private function getAllLoggInfo() {
        $loggModel = $GLOBALS["loggModel"]; // get logg model

        if (isset($_POST['givenLogSearchWord'])) {
            // if search word is POSTed, get result containing this word
            $givenLogSearchWord = "%{$_REQUEST["givenLogSearchWord"]}%";
            $LoggInfo = $loggModel->getAllLoggInfo($givenLogSearchWord);
        } else {
            // else get all logg result
            $givenLogSearchWord = "%%";
            $LoggInfo = $loggModel->getAllLoggInfo($givenLogSearchWord);
        }
        // echo result as an array to view
        $data = json_encode(array("allLoggInfo" => $LoggInfo));
        echo $data;
    }

    /**
     *  get 10 latest loggs
     */
    private function getLatestLoggInfo() {
        $loggModel = $GLOBALS["loggModel"]; // get logg model
        $loggLateInfo = $loggModel->getLatestLoggInfo();    // get 10 latest loggs from model

        // echo result as an array to view
        $data = json_encode(array("latestLoggInfo" => $loggLateInfo));
        echo $data;
    }

    /**
     *  edit what type of loggs to logg
     */
    private function loggCheck() {
        // gets POSTed values
        $edit = $_REQUEST["Redigering"];
        $logIn = $_REQUEST["Innlogging"];
        $restriction = $_REQUEST["Tilgang"];
        $creation = $_REQUEST["Opprettelse"];
        $stockdelivery = $_REQUEST["Varelevering"];
        $sale = $_REQUEST["Uttak"];
        $return = $_REQUEST["Retur"];
        $transfer = $_REQUEST["Overføring"];
        $deleting = $_REQUEST["Sletting"];
        $stocktaking = $_REQUEST["Varetelling"];
        
        $loggModel = $GLOBALS["loggModel"]; // get logg model
        
        
        $typeID = array(1,2,3,4,5,6,7,8,9,10);  // create an array containing type values
        // create array containing variables of POSTed values
        $loggCheck = array($edit, $logIn, $restriction, $creation, $stockdelivery, $sale, $return, $transfer, $deleting, $stocktaking);

        // loop trough array and update database 
        for ($i = 0; $i < sizeof($typeID); $i++) {
            $edited = $loggModel->editLoggCheck($typeID[$i], $loggCheck[$i]);
        }
        // if success, echo an response to view
        if($edited){
            echo json_encode("success");
        } else {return false;}
        
    }
    
    /**
     *  get result of what types is marked to be logged 
     */
    private function getLoggCheckStatus(){
        $loggModel = $GLOBALS["loggModel"]; // get log model
        $status = $loggModel->getLoggCheckStatus(); // get log status (what to log)

        // echo result from model as a array to view
        $data = json_encode(array("checkStatus" => $status));
        echo $data;
    }
    
    /**
     *  get information to be used in advance searcho
     */
    private function getAdvanceSearchData(){
        // get involved models
        $storageModel = $GLOBALS["storageModel"];
        $userModel = $GLOBALS["userModel"];
        $productModel = $GLOBALS["productModel"];
        $groupModel = $GLOBALS["groupModel"];
        
        $userInfo = $userModel->getAllUserInfo();   // get userinformation from model
        $storageInfo = $storageModel->getAll();     // get storageinformation from model
        $givenSearchWord = "%%";
        $productInfo = $productModel->getSearchResult($givenSearchWord);    // get product information
        $groupInfo = $groupModel->getSearchResult($givenSearchWord);        // get group information
        
        // echo result as an nested array to view
        $data = json_encode(array(
            "userInfo" => $userInfo, 
            "storageInfo" => $storageInfo, 
            "productInfo" => $productInfo, 
            "groupInfo" => $groupInfo));
        echo $data;
        
    }
    
    /**
     *  get result from an advance search
     */
    private function advanceLoggSearch(){
        // check if value is posted, else create empty array
        if (isset($_POST['loggType'])) {
            $loggTypeArray = $_REQUEST["loggType"];
        } else { $loggTypeArray = array();}
        if (isset($_POST['storage'])) {
            $storageArray = $_REQUEST["storage"];
        } else { $storageArray = array();}
        if (isset($_POST['toStorage'])) {
            $toStorageArray = $_REQUEST["toStorage"];
        } else { $toStorageArray = array();}
        if (isset($_POST['fromStorage'])) {
            $fromStorageArray = $_REQUEST["fromStorage"];
        } else { $fromStorageArray = array();}
        if (isset($_POST['username'])) {
            $usernameArray = $_REQUEST["username"];
        } else { $usernameArray = array();}
        if (isset($_POST['onUser'])) {
            $onUserArray = $_REQUEST["onUser"];
        } else { $onUserArray = array();}
        if (isset($_POST['product'])) {
            $productArray = $_REQUEST["product"];
        } else { $productArray = array();}
        if (isset($_POST['group'])) {
            $groupArray = $_REQUEST["group"];
        } else { $groupArray = array();}
        if (empty(!$_POST['date'])) {
            $dateArray = $_REQUEST["date"];
            $date = explode("/", $dateArray);
            $fromDate = $date[0];
            $toDate = $date[1];
        } else { $fromDate=""; $toDate="";}
        
        
        $loggModel = $GLOBALS["loggModel"]; // get logg model
        // get advance search result from model
        $search = $loggModel->advanceSearch($loggTypeArray, $storageArray, $toStorageArray, $fromStorageArray, $usernameArray, $onUserArray, $productArray, $groupArray, $fromDate, $toDate);
        
        // echo result as an array to view
        $data = json_encode(array("allLoggInfo" => $search));
        echo $data;
    }

}
