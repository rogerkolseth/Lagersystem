<?php

require_once("Controller.php");

class GroupController extends Controller {

    public function show($page) {
        switch ($page) {
            case "groupAdm" :
                return $this->showGroupPage();
            case "addGroupEngine" :
                return $this->addGroup();
            case "getGroupSearchResult" :
                return $this->getGroupSearchResult();
            case "getGroupByID" :
                return $this->getGroupByID();
            case "deleteGroupEngine" :
                return $this->deleteGroupEngine();
            case "editGroupEngine" :
                return $this->editGroupEngine();
            case "addGroupRestriction" :
                return $this->addGroupRestriction();
            case "addGroupMember" :
                return $this->addGroupMember();
            case "getGroupMember" :
                return $this->getGroupMember();
            case "getGroupRestriction" :
                return $this->getGroupRestriction();
            case "deleteGroupMember" :
                return $this->deleteGroupMember();
            case "deleteGroupRestriction" :
                return $this->deleteGroupRestriction();
            case "getGroupRestrictionFromSto" :
                return $this->getGroupRestrictionFromSto();
            case "getGroupMembershipFromUserID" :
                return $this->getGroupMembershipFromUserID();
        } 
    }

    private function showGroupPage() {
        return $this->view("groupAdm");
    }

    private function addGroup() {
        $givenGroupName = $_REQUEST["givenGroupName"];

        $groupModel = $GLOBALS["groupModel"];
        $sessionID = $_SESSION["userID"];

        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);

        $added = $groupModel->addGroup($givenGroupName);
        if ($added) {
            echo json_encode("success");
        }
    }

    private function getGroupSearchResult() {
        $groupModel = $GLOBALS["groupModel"];

        if (isset($_POST['givenGroupSearchWord'])) {
            $givenGroupSearchWord = "%{$_REQUEST["givenGroupSearchWord"]}%";
            $searchResult = $groupModel->getSearchResult($givenGroupSearchWord);
        } else {
            $givenGroupSearchWord = "%%";
            $searchResult = $groupModel->getSearchResult($givenGroupSearchWord);
        }

        $data = json_encode(array("group" => $searchResult));
        echo $data;
    }

    private function getGroupByID() {
        $givenGroupID = $_REQUEST["givenGroupID"];
        $groupModel = $GLOBALS["groupModel"];

        $result = $groupModel->getGroupByID($givenGroupID);

        $data = json_encode(array("groupByID" => $result));
        echo $data;
    }

    private function deleteGroupEngine() {
        $deleteGroupID = $_REQUEST["deleteGroupID"];

        $sessionID = $_SESSION["userID"];

        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);
        
        $groupModel = $GLOBALS["groupModel"];
        $delited = $groupModel->deleteGroup($deleteGroupID);

        if ($delited) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    private function editGroupEngine() {
        $editGroupID = $_REQUEST["editGroupID"];
        $editGroupName = $_REQUEST["editGroupName"];
        $sessionID = $_SESSION["userID"];

        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);

        $groupModel = $GLOBALS["groupModel"];
        $edited = $groupModel->editGroup($editGroupName, $editGroupID);

        if ($edited) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    private function addGroupRestriction() {
        if (isset($_POST['givenGroupID']) && isset($_POST['storageRestrictions'])) {
            $givenGroupID = $_REQUEST['givenGroupID'];
            $givenStorageArray = $_REQUEST['storageRestrictions'];
            $sessionID = $_SESSION["userID"];

            $setSessionID = $GLOBALS["userModel"];
            $restrictionModel = $GLOBALS["restrictionModel"];

            foreach ($givenStorageArray as $givenStorageID) :
                $count = $restrictionModel->doesRestrictionExist($givenGroupID, $givenStorageID);
                if ($count[0]["COUNT(*)"] < 1) {
                    $setSessionID->setSession($sessionID);
                    $data = $restrictionModel->addGroupRestriction($givenGroupID, $givenStorageID);
                }
            endforeach;
            echo json_encode("success");
        }
    }
    
    private function addGroupMember() {
        if (isset($_POST['givenGroupID']) && isset($_POST['userRestrictions'])) {
            $givenGroupID = $_REQUEST['givenGroupID'];
            $givenUserArray = $_REQUEST['userRestrictions'];
            $sessionID = $_SESSION["userID"];

            $setSessionID = $GLOBALS["userModel"];
            $groupModel = $GLOBALS["groupModel"];

            foreach ($givenUserArray as $givenUserID) :
                $count = $groupModel->doesMemberExist($givenGroupID, $givenUserID);
                if ($count[0]["COUNT(*)"] < 1) {
                    $setSessionID->setSession($sessionID);
                    $groupModel->addGroupMember($givenGroupID, $givenUserID);
                }
            endforeach;
            echo json_encode("success");
        }
    }
    
    private function getGroupMember(){
        $givenGroupID = $_REQUEST['givenGroupID'];
        $groupModel = $GLOBALS["groupModel"];
        $members = $groupModel->getGroupMember($givenGroupID);
        
        $data = json_encode(array("member" => $members));
        echo $data;
    }
    
    private function getGroupRestriction(){
        $givenGroupID = $_REQUEST['givenGroupID'];
        $restrictionModel = $GLOBALS["restrictionModel"];
        
        $result = $restrictionModel->getGroupRestriction($givenGroupID);
        
        $data = json_encode(array("StorageRestriction" => $result));
        echo $data;
    }
    
    private function deleteGroupMember(){
        $memberID = $_REQUEST['memberID'];
        $groupModel = $GLOBALS["groupModel"];
        $sessionID = $_SESSION["userID"];

        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);
        
        $deleted = $groupModel->deleteGroupMember($memberID);
        if ($deleted) {
            echo json_encode("success");
        } else {
            return false;
        }
    }
    
    private function deleteGroupRestriction(){
        $restrictionID = $_REQUEST['restrictionID'];
        $restrictionModel = $GLOBALS["restrictionModel"];
        $sessionID = $_SESSION["userID"];

        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);

        
        $deleted = $restrictionModel->deleteGroupRestriction($restrictionID);
        if ($deleted) {
            echo json_encode("success");
        } else {
            return false;
        }
    }
    
    private function getGroupRestrictionFromSto(){
        $givenStorageID = $_REQUEST['givenStorageID'];
        $restrictionModel = $GLOBALS["restrictionModel"];
        
        $result = $restrictionModel->getGroupRestrictionFromSto($givenStorageID);
        
        $data = json_encode(array("groupRestriction" => $result));
        echo $data;
    }
    
    private function getGroupMembershipFromUserID(){
        $givenUserID = $_REQUEST['givenUserID'];
        $groupModel = $GLOBALS["groupModel"];
        
        $result = $groupModel->getGroupMembershipFromUserID($givenUserID);
        
        $data = json_encode(array("groupMembership" => $result));
        echo $data;
    }
}
