<?php

require_once("Controller.php");   //include controller

class GroupController extends Controller {

    //Decide wich function to run based on passed $requset variable
    public function __construct($request) {
        switch ($request) {
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

    /**
     * display group administration page
     */
    private function showGroupPage() {
        return $this->view("groupAdm");
    }

    /**
     *  creates a new group
     */
    private function addGroup() {
        $givenGroupName = $_REQUEST["givenGroupName"]; // gets POSTed group name

        $groupModel = $GLOBALS["groupModel"];   // gets group model
        $sessionID = $_SESSION["userID"];   // gets userID from session

        $setSessionID = $GLOBALS["userModel"]; // gets user model
        $setSessionID->setSession($sessionID);  // create a session within the database to use with triggers

        $added = $groupModel->addGroup($givenGroupName); // creates new group in database
        if ($added) {
            echo json_encode("success");    // echo a respond to view on success
        }
    }

    /**
     *  search for groups or get all groups result
     */
    private function getGroupSearchResult() {
        $groupModel = $GLOBALS["groupModel"]; // get group model

        if (isset($_POST['givenGroupSearchWord'])) { // gets POSTed search word if given
            $givenGroupSearchWord = "%{$_REQUEST["givenGroupSearchWord"]}%";
            $searchResult = $groupModel->getSearchResult($givenGroupSearchWord); // get result from molde
        } else {
            // if searchword is not given, get all group information
            $givenGroupSearchWord = "%%";
            $searchResult = $groupModel->getSearchResult($givenGroupSearchWord);
        }

        // echo result from model as an array to view
        $data = json_encode(array("group" => $searchResult));
        echo $data;
    }

    /**
     *  gets group information from group ID
     */
    private function getGroupByID() {
        $givenGroupID = $_REQUEST["givenGroupID"];  //gets POSTed group ID
        $groupModel = $GLOBALS["groupModel"];   // get group model

        $result = $groupModel->getGroupByID($givenGroupID); //get group information from model

        // echo result from model as an array to view
        $data = json_encode(array("groupByID" => $result));
        echo $data;
    }

    /**
     * delete an exicting group
     */
    private function deleteGroupEngine() {
        $deleteGroupID = $_REQUEST["deleteGroupID"]; // gets POSTed group ID

        $sessionID = $_SESSION["userID"];   // get userID from session

        $setSessionID = $GLOBALS["userModel"];  // gets user model
        $setSessionID->setSession($sessionID);  // sets sessionID as global variable in DB, to access from trigger
        
        $groupModel = $GLOBALS["groupModel"];   // get group model
        $delited = $groupModel->deleteGroup($deleteGroupID);    // deletes group from database

        // if deleted, echo a response to view
        if ($delited) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    /**
     * edit an exicting group
     */
    private function editGroupEngine() {
        $editGroupID = $_REQUEST["editGroupID"];    // get POSTed group ID
        $editGroupName = $_REQUEST["editGroupName"];    // get POSTed group name
        $sessionID = $_SESSION["userID"];   //get userID from session

        $setSessionID = $GLOBALS["userModel"];  //get user model
        $setSessionID->setSession($sessionID);  // sets sessionID as global variable in DB, to access from trigger

        $groupModel = $GLOBALS["groupModel"];   // get group model
        $edited = $groupModel->editGroup($editGroupName, $editGroupID); // edits group in database

        // if success, echo a response to view
        if ($edited) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    /**
     *  Give group restrictions to a storage
     */
    private function addGroupRestriction() {
        if (isset($_POST['givenGroupID']) && isset($_POST['storageRestrictions'])) {
            $givenGroupID = $_REQUEST['givenGroupID'];  // get POSTed group ID
            $givenStorageArray = $_REQUEST['storageRestrictions'];  // get array containing storageID 
            $sessionID = $_SESSION["userID"];   // get userID from session

            $setSessionID = $GLOBALS["userModel"];  // get user model
            $restrictionModel = $GLOBALS["restrictionModel"];   //get restriction model

            foreach ($givenStorageArray as $givenStorageID) : // loop trough storageArray
                // checks if group already have this restriction
                $count = $restrictionModel->doesRestrictionExist($givenGroupID, $givenStorageID);
                // if not, add restriction to database
                if ($count[0]["COUNT(*)"] < 1) {
                    $setSessionID->setSession($sessionID); // set global variable in database
                    $data = $restrictionModel->addGroupRestriction($givenGroupID, $givenStorageID);
                }
            endforeach;
            // Echo a response to view
            echo json_encode("success");
        }
    }
    
    /**
     *  add new group member
     */
    private function addGroupMember() {
        if (isset($_POST['givenGroupID']) && isset($_POST['userRestrictions'])) {
            $givenGroupID = $_REQUEST['givenGroupID'];  // gets POSTed groupID
            $givenUserArray = $_REQUEST['userRestrictions'];    // get array containing userID
            $sessionID = $_SESSION["userID"];   // get userID from session

            $setSessionID = $GLOBALS["userModel"];  // get user model
            $groupModel = $GLOBALS["groupModel"];   // get group model

            foreach ($givenUserArray as $givenUserID) : // loop trough user array
                //checks if membership allready exist
                $count = $groupModel->doesMemberExist($givenGroupID, $givenUserID);
                // if not, add member to group
                if ($count[0]["COUNT(*)"] < 1) {
                    $setSessionID->setSession($sessionID);  // set global variable in database
                    $groupModel->addGroupMember($givenGroupID, $givenUserID);
                }
            endforeach;
            //Echo a response to view
            echo json_encode("success");
        }
    }
    
    /**
     *  get members in a spesific group
     */
    private function getGroupMember(){
        $givenGroupID = $_REQUEST['givenGroupID'];  // gets POSTed group ID
        $groupModel = $GLOBALS["groupModel"]; // get group model
        $members = $groupModel->getGroupMember($givenGroupID); // get group members from model
        
        // echo result as an array to view
        $data = json_encode(array("member" => $members));
        echo $data;
    }
    
    /**
     *  get storage restrictions to a spesific group
     */
    private function getGroupRestriction(){
        $givenGroupID = $_REQUEST['givenGroupID'];  // get group ID
        $restrictionModel = $GLOBALS["restrictionModel"];   // get restriction model
        
        $result = $restrictionModel->getGroupRestriction($givenGroupID);    // get group restrictions from modle
        
        // echo result as an array to view
        $data = json_encode(array("StorageRestriction" => $result));
        echo $data;
    }
    
    /**
     * delete a spesific groupmember
     */
    private function deleteGroupMember(){
        $memberID = $_REQUEST['memberID'];  // get POSTed memberID
        $groupModel = $GLOBALS["groupModel"];   // get group model
        $sessionID = $_SESSION["userID"];   // get userID from session

        $setSessionID = $GLOBALS["userModel"];  // get user model
        $setSessionID->setSession($sessionID);  // set global variable in DB, to use in trigger
            
        $deleted = $groupModel->deleteGroupMember($memberID); // delete given member
        if ($deleted) {
            echo json_encode("success"); // on success, echo a response to view
        } else {
            return false;
        }
    }
    
    /**
     * delete a sepsific groups restriction to a spesific storage
     */
    private function deleteGroupRestriction(){
        $restrictionID = $_REQUEST['restrictionID'];    // get POSTed restriction ID
        $restrictionModel = $GLOBALS["restrictionModel"];   // get restriction model
        $sessionID = $_SESSION["userID"];   // get userID from session

        $setSessionID = $GLOBALS["userModel"];  // get user model
        $setSessionID->setSession($sessionID);  // set global variable in database

        // delete given restriction from database
        $deleted = $restrictionModel->deleteGroupRestriction($restrictionID);
        // if success, echo a response to view
        if ($deleted) {
            echo json_encode("success");
        } else {
            return false;
        }
    }
    
    /**
     *  get all group restrictions from a storage 
     */
    private function getGroupRestrictionFromSto(){
        $givenStorageID = $_REQUEST['givenStorageID'];  // gets POSTed storageID
        $restrictionModel = $GLOBALS["restrictionModel"];   // get restriction model
        
        //Get all groups with restrictions to given storage
        $result = $restrictionModel->getGroupRestrictionFromSto($givenStorageID);
        
        // echo result as an array to view
        $data = json_encode(array("groupRestriction" => $result));
        echo $data;
    }
    
    /**
     *  get all group memberships from a given userID
     */
    private function getGroupMembershipFromUserID(){
        $givenUserID = $_REQUEST['givenUserID']; // get POSTed userID
        $groupModel = $GLOBALS["groupModel"];   // get group Model
        
        // gets all memberships given user have
        $result = $groupModel->getGroupMembershipFromUserID($givenUserID);
        
        // echo result as an array to view
        $data = json_encode(array("groupMembership" => $result));
        echo $data;
    }
}
