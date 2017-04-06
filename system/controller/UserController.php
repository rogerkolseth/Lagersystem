<?php

require_once("Controller.php");

class UserController extends Controller {

    public function show($page) {
        if ($page == "userAdm") {
            $this->userAdmPage();
        }    else if ($page == "addRestriction") {
            $this->addRestriction();
        } else if ($page == "getUserInfo") {
            $this->getUserInfo(); 
        } else if ($page == "addUserEngine") {
            $this->userCreationEngine();
        } else if ($page == "getUserByID"){
            $this->getUserByID();
        } else if ($page == "getUserRestriction"){
            $this->getUserRestriction();
        } else if ($page == "deleteUserEngine") {
            $this->deleteUserEngine();
        } else if ($page == "editUserEngine") {
            $this->userEditEngine();
        } else if ($page == "deleteSingleRes"){
            $this->deleteSingleRes();
        } else if ($page == "editUser"){
            $this->editUserPage();
        } 
            
    }

    private function userAdmPage() {
        return $this->render("userAdm");
    }
    
    private function editUserPage() {
        return $this->render("editUser");
    }

    private function userEditEngine() {
        $editUserID = $_REQUEST["editUserID"];
        $editName = $_REQUEST["editName"];
        $editUsername = $_REQUEST["editUsername"];
        $editPassword = $_REQUEST["editPassword"];
        $editUserLevel = $_REQUEST["editUserLevel"];
        $editEmail = $_REQUEST["editEmail"];
        $editMediaID = $_REQUEST["editMediaID"];
        
        $sessionID = $_SESSION["userID"];
        $userEditInfo = $GLOBALS["userModel"];
        $userEditInfo->setSession($sessionID);
        if(strlen($editPassword) < 50)
        {
        $hash = password_hash($editPassword, PASSWORD_DEFAULT);
        $edited = $userEditInfo->editUser($editName, $editUsername, $hash, $editUserLevel, $editEmail, $editUserID, $editMediaID);
        }
        else
        {    
        $edited = $userEditInfo->editUser($editName, $editUsername, $editPassword, $editUserLevel, $editEmail, $editUserID, $editMediaID);
        }
        if($edited){
        echo json_encode("success");
        } else {return false;}
    }

    
    
    private function addRestriction() {
        if (isset($_POST['userRestrictions']) && isset($_POST['storageRestrictions'])) {
            $givenUserArray = $_REQUEST['userRestrictions'];
            $givenStorageArray = $_REQUEST['storageRestrictions'];
            $sessionID = $_SESSION["userID"];
            
            $setSessionID = $GLOBALS["userModel"];
            $addRestriction = $GLOBALS["restrictionModel"];
            
            foreach ($givenUserArray as $givenUserID) :
                
                foreach ($givenStorageArray as $givenStorageID) :
                $count = $addRestriction->doesRestrictionExist($givenUserID, $givenStorageID);
                    if ($count[0]["COUNT(*)"] < 1) {
                        $setSessionID->setSession($sessionID);
                        $data = $addRestriction->addRestriction($givenUserID, $givenStorageID);
                    }
                endforeach;
            endforeach;  
        echo json_encode("success");
            
        } 
        
    }
    
    
    private function getUserInfo() {
        $userInfo = $GLOBALS["userModel"];
          
        if (isset($_POST['givenUserSearchWord'])) {
        $givenSearchWord = "%{$_REQUEST["givenUserSearchWord"]}%";
        $userModel = $userInfo->getSearchResult($givenSearchWord);
        } else {
        $givenSearchWord = "%%";
        $userModel = $userInfo->getSearchResult($givenSearchWord);
        }
        
        $data = json_encode(array("users" => $userModel));
        echo $data;
    }

    
    private function userCreationEngine() {
        $givenName = $_REQUEST["givenName"];
        $givenUsername = $_REQUEST["givenUsername"];
        $givenPassword = $_REQUEST["givenPassword"];
        $givenUserLevel = $_REQUEST["givenUserLevel"];
        $givenEmail = $_REQUEST["givenEmail"];
        $givenMediaID = $_REQUEST["givenMediaID"];
        $sessionID = $_SESSION["userID"];
        
        $userCreationInfo = $GLOBALS["userModel"];
        
        $hash = password_hash($givenPassword, PASSWORD_DEFAULT);
        $added = $userCreationInfo->addUser($givenName, $givenUsername, $hash, $givenUserLevel, $givenEmail, $givenMediaID, $sessionID);
        
        if($added){
        $data = json_encode("success");
        
        echo $data;
        } else {
        return false;    
        }
    }
    
    private function getUserByID(){
        if (isset($_POST['givenUserID']) ) {
        $givenUserID = $_REQUEST["givenUserID"];
        } else {
            $givenUserID = $_SESSION["userID"];
        }
        $userInfo = $GLOBALS["userModel"];
        $userModel = $userInfo->getAllUserInfoFromID($givenUserID);
        
        $mediaModel = $GLOBALS["mediaModel"];
        $mediaInfo = $mediaModel->getAllMediaInfo();
        
        
        $data = json_encode(array("user" => $userModel, "media" => $mediaInfo));
        echo $data;
        
    }
    
    private function deleteUserEngine() {
        $removeUserID = $_REQUEST["deleteUserID"]; 
        
        $removeUserRestriction = $GLOBALS["restrictionModel"];
        $removeUserRestriction->deleteUserRestriction($removeUserID);
        
        $removeUser = $GLOBALS["userModel"];
        $delited = $removeUser->removeUser($removeUserID);
        
        if($delited){
        echo json_encode("success");} 
        else {return false;}
    }
    
    private function deleteSingleRes(){
        $givenUserID = $_REQUEST["givenUserID"];
        $givenStorageID = $_REQUEST["givenStorageID"];
        $sessionID = $_SESSION["userID"];
        
        $setSessionID = $GLOBALS["userModel"];
        $deletedRes = $GLOBALS["restrictionModel"];
        
        $setSessionID->setSession($sessionID);
        $deletedRes->deleteSingleRestriction($givenUserID, $givenStorageID);
        
        echo json_encode("success");
    }
    
    private function getUserRestriction(){
        $givenUserID = $_REQUEST['givenUserID'];
        $restrictionInfo = $GLOBALS["restrictionModel"];
        $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromUserID($givenUserID);
        
        $data = json_encode(array("restriction" => $restrictionModel));
        echo $data;
    }
    
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

