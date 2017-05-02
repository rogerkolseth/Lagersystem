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
        if (isset($_POST['userGroupRestrictions']) && isset($_POST['storageRestrictions'])) {
            $givenGroup = $_REQUEST['userRestrictions'];
            $givenStorageArray = $_REQUEST['storageRestrictions'];
            $sessionID = $_SESSION["userID"];

            $setSessionID = $GLOBALS["userModel"];
            $restrictionModel = $GLOBALS["restrictionModel"];

            foreach ($givenStorageArray as $givenStorageID) :
                $count = $restrictionModel->doesRestrictionExist($givenGroup, $givenStorageID);
                if ($count[0]["COUNT(*)"] < 1) {
                    $setSessionID->setSession($sessionID);
                    $data = $restrictionModel->addGroupRestriction($givenGroup, $givenStorageID);
                }
            endforeach;
            echo json_encode("success");
        }
    }
}
