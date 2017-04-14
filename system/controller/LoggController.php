<?php

require_once("Controller.php");

class LoggController extends Controller {

    public function show($page) {
        if ($page == "logg") {
            $this->loggPage();
        } else if ($page == "getAllLoggInfo") {
            $this->getAllLoggInfo();
        } else if ($page == "getLatestLoggInfo") {
            $this->getLatestLoggInfo();
        } else if ($page == "loggCheck"){
            $this->loggCheck();
        } else if ($page == "getLoggCheckStatus"){
            $this->getLoggCheckStatus();
        } else if ($page == "getAdvanceSearchData"){
            $this->getAdvanceSearchData();
        } else if($page == "advanceLoggSearch"){
            $this->advanceLoggSearch();
        }
    }

    private function loggPage() {
        return $this->render("logg");
    }

    private function getAllLoggInfo() {
        $loggModel = $GLOBALS["loggModel"];

        if (isset($_POST['givenLogSearchWord'])) {
            $givenLogSearchWord = "%{$_REQUEST["givenLogSearchWord"]}%";
            $LoggInfo = $loggModel->getAllLoggInfo($givenLogSearchWord);
        } else {
            $givenLogSearchWord = "%%";
            $LoggInfo = $loggModel->getAllLoggInfo($givenLogSearchWord);
        }


        $data = json_encode(array("allLoggInfo" => $LoggInfo));
        echo $data;
    }

    private function getLatestLoggInfo() {
        $loggModel = $GLOBALS["loggModel"];
        $loggLateInfo = $loggModel->getLatestLoggInfo();

        $data = json_encode(array("latestLoggInfo" => $loggLateInfo));
        echo $data;
    }

    private function loggCheck() {
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
        
        $loggModel = $GLOBALS["loggModel"];
        
        $typeID = array(1,2,3,4,5,6,7,8,9,10);
        $loggCheck = array($edit, $logIn, $restriction, $creation, $stockdelivery, $sale, $return, $transfer, $deleting, $stocktaking);

        for ($i = 0; $i < sizeof($typeID); $i++) {
            $edited = $loggModel->editLoggCheck($typeID[$i], $loggCheck[$i]);
        }
        if($edited){
            echo json_encode("success");
        } else {return false;}
        
    }
    
    private function getLoggCheckStatus(){
        $loggModel = $GLOBALS["loggModel"];
        $status = $loggModel->getLoggCheckStatus();

        $data = json_encode(array("checkStatus" => $status));
        echo $data;
    }
    
    private function getAdvanceSearchData(){
        $storageModel = $GLOBALS["storageModel"];
        $userModel = $GLOBALS["userModel"];
        $productModel = $GLOBALS["productModel"];
        
        $userInfo = $userModel->getAllUserInfo();
        $storageInfo = $storageModel->getAll();
        $givenProductSearchWord = "%%";
        $productInfo = $productModel->getSearchResult($givenProductSearchWord);
        
        
        
        $data = json_encode(array("userInfo" => $userInfo, "storageInfo" => $storageInfo, "productInfo" => $productInfo));
        echo $data;
        
    }
    
    private function advanceLoggSearch(){
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
        
        $loggModel = $GLOBALS["loggModel"];
        $search = $loggModel->advanceSearch($loggTypeArray, $storageArray, $toStorageArray, $fromStorageArray, $usernameArray, $onUserArray, $productArray);
        
        $data = json_encode(array("allLoggInfo" => $search));
        echo $data;
    }

}
