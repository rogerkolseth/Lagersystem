<?php

require_once("Controller.php");

class GroupController extends Controller {

    public function show($page) {
        if ($page == "groupAdm") {
            $this->showGroupPage();
        } else if ($page == "addGroupEngine") {
            $this->addGroup();
        } else if ($page == "getGroupSearchResult") {
            $this->getGroupSearchResult();
        } else if ($page == "getGroupByID") {
            $this->getGroupByID();
        } else if ($page == "deleteGroupEngine") {
            $this->deleteGroupEngine();
        } else if ($page == "editGroupEngine") {
            $this->editGroupEngine();
        } else if ($page == "addGroupRestriction") {
            $this->addGroupRestriction();
        } else if ($page == "addGroupMember") {
            $this->addGroupMember();
        } else if ($page == "getGroupMember") {
            $this->getGroupMember();
        } else if ($page == "getGroupRestriction") {
            $this->getGroupRestriction();
        } else if ($page == "deleteGroupMember") {
            $this->deleteGroupMember();
        } else if ($page == "deleteGroupRestriction") {
            $this->deleteGroupRestriction();
        } 
    }

    private function showGroupPage() {
        return $this->render("groupAdm");
    }

    private function addGroup() {
        $givenGroupName = $_REQUEST["givenGroupName"];

        $groupModel = $GLOBALS["groupModel"];
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
        
        $deleted = $restrictionModel->deleteGroupRestriction($restrictionID);
        if ($deleted) {
            echo json_encode("success");
        } else {
            return false;
        }
    }
}
