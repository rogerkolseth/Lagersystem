<?php

require_once("Controller.php"); //include contoller

class UserController extends Controller {
    //Decide wich function to run based on passed $requset variable
    public function show($request) {
        switch ($request) {
            case "userAdm" :
                return $this->userAdmPage();
            case "addRestriction" :
                return $this->addRestriction();
            case "getUserInfo" :
                return $this->getUserInfo();
            case "addUserEngine" :
                return $this->userCreationEngine();
            case "getUserByID" :
                return $this->getUserByID();
            case "getUserRestriction" :
                return $this->getUserRestriction();
            case "deleteUserEngine" :
                return $this->deleteUserEngine();
            case "editUserEngine" :
                return $this->userEditEngine();
            case "deleteSingleRes" :
                return $this->deleteSingleRes();
            case "editUser" :
                return $this->editUserPage();
            case "employeeTraning" :
                return $this->employeeTraningPage();
            case "editLoggedInUser" :
                return $this->editActiveUserEngine();    
        }
    }

    /**
    * Display user administration page
    */ 
    private function userAdmPage() {
        return $this->view("userAdm");
    }

    /**
    * Display emplyee traning page
    */ 
    private function employeeTraningPage() {
        return $this->view("employeeTraning");
    }

    /**
    * Display edit user page (logged in user)
    */ 
    private function editUserPage() {
        return $this->view("editUser");
    }

    /**
    * Edit existing user information
    */ 
    private function userEditEngine() {
        // get posted values
        $editUserID = $_REQUEST["editUserID"];
        $editName = $_REQUEST["editName"];
        $editUsername = $_REQUEST["editUsername"];
        $editPassword = $_REQUEST["editPassword"];
        $editUserLevel = $_REQUEST["editUserLevel"];
        $editEmail = $_REQUEST["editEmail"];
        $editMediaID = $_REQUEST["editMediaID"];
        
        // set userID as global value in databaes
        $sessionID = $_SESSION["userID"];
        $userEditInfo = $GLOBALS["userModel"];
        $userEditInfo->setSession($sessionID);
        
        // if password passed is shorter than 50 char hash password
        if (strlen($editPassword) < 50) {
            $hash = password_hash($editPassword, PASSWORD_DEFAULT);
            // update user information
            $edited = $userEditInfo->editUser($editName, $editUsername, $hash, $editUserLevel, $editEmail, $editUserID, $editMediaID);
        } else {
            // if not, let hashed password be the same and update user information
            $edited = $userEditInfo->editUser($editName, $editUsername, $editPassword, $editUserLevel, $editEmail, $editUserID, $editMediaID);
        }
        // echo a response to view if edited
        if ($edited) {
            echo json_encode("success");
        } else {
            return false;
        }
    }
    
    /**
    * Edit logged in user
    */ 
    private function editActiveUserEngine() {
        // get posted values
        $userID = $_SESSION["userID"];
        $editName = $_REQUEST["editName"];
        $editPassword = $_REQUEST["editPassword"];
        $editEmail = $_REQUEST["editEmail"];
        $editMediaID = $_REQUEST["editMediaID"];
        
        // set userID as global value in databaes
        $userModel = $GLOBALS["userModel"];
        $userModel->setSession($userID);
        
        // if password passed is shorter than 50 char hash password
        if (strlen($editPassword) < 50) {
            $hash = password_hash($editPassword, PASSWORD_DEFAULT);
            // update user information
            $edited = $userModel->editActiveUser($editName, $hash, $editEmail, $userID, $editMediaID);
        } else {
            // if not, let hashed password be the same and update user information
            $edited = $userModel->editActiveUser($editName, $editPassword, $editEmail, $userID, $editMediaID);
        }
        // echo a response to view if edited
        if ($edited) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    /**
    * Create a new user restriction, or add user to group
    */ 
    private function addRestriction() {
        // check if restrictions should be applied on user 
        if (isset($_POST['userRestrictions']) && isset($_POST['storageRestrictions'])) {
            // get posted values
            $givenUserArray = $_REQUEST['userRestrictions'];
            $givenStorageArray = $_REQUEST['storageRestrictions'];
            $sessionID = $_SESSION["userID"];

            // get involved models
            $setSessionID = $GLOBALS["userModel"];
            $addRestriction = $GLOBALS["restrictionModel"];

            foreach ($givenUserArray as $givenUserID) :
                
                foreach ($givenStorageArray as $givenStorageID) :
                    // check if restriction allready exist
                    $count = $addRestriction->doesRestrictionExist($givenUserID, $givenStorageID);
                    if ($count[0]["COUNT(*)"] < 1) {
                        // if it dont, add new restriction and make userID a global value in database
                        $setSessionID->setSession($sessionID);
                        $added = $addRestriction->addRestriction($givenUserID, $givenStorageID);
                    }
                endforeach;
            endforeach;
            
            // add user to group
        } else if (isset($_POST['userRestrictions']) && isset($_POST['groupRestrictions'])) {
            // get posted values
            $givenUserArray = $_REQUEST['userRestrictions'];
            $givenGroupArray = $_REQUEST['groupRestrictions'];
            $sessionID = $_SESSION["userID"];
            // get involved models
            $setSessionID = $GLOBALS["userModel"];
            $groupModel = $GLOBALS["groupModel"];
            
            foreach ($givenUserArray as $givenUserID) :
                foreach ($givenGroupArray as $givenGroupID) :
                // check if membership allready exist
                    $count = $groupModel->doesMemberExist($givenGroupID, $givenUserID);
                    if ($count[0]["COUNT(*)"] < 1) {
                        // if it dont, add new member and make userID a global value in database
                        $setSessionID->setSession($sessionID);
                        $added = $groupModel->addGroupMember($givenGroupID, $givenUserID);;
                    }
                endforeach;
            endforeach;
        }
        // echo a response to view
        echo json_encode("success");
    }

    
    /**
    * Search for user information, or show all
    */ 
    private function getUserInfo() {
        $userInfo = $GLOBALS["userModel"];   // get user model

        // if search word is posted, get user result from search
        if (isset($_POST['givenUserSearchWord'])) {
            $givenSearchWord = "%{$_REQUEST["givenUserSearchWord"]}%";
            $userModel = $userInfo->getSearchResult($givenSearchWord);
        } else {
            // if not posted, get all users information
            $givenSearchWord = "%%";
            $userModel = $userInfo->getSearchResult($givenSearchWord);
        }

        // echo result as an array to view
        $data = json_encode(array("users" => $userModel));
        echo $data;
    }

    /**
    * Create a new user
    */ 
    private function userCreationEngine() {
        // get posted values
        $givenName = $_REQUEST["givenName"];
        $givenUsername = $_REQUEST["givenUsername"];
        $givenPassword = $_REQUEST["givenPassword"];
        $givenUserLevel = $_REQUEST["givenUserLevel"];
        $givenEmail = $_REQUEST["givenEmail"];
        $givenMediaID = $_REQUEST["givenMediaID"];
        $sessionID = $_SESSION["userID"];

        $userCreationInfo = $GLOBALS["userModel"];  // get user model

        $hash = password_hash($givenPassword, PASSWORD_DEFAULT);    // hash password
        // add new user to database
        $added = $userCreationInfo->addUser($givenName, $givenUsername, $hash, $givenUserLevel, $givenEmail, $givenMediaID, $sessionID);

        // if user is added, echo a response to view
        if ($added) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    /**
    * Get userinformation from userID
    */ 
    private function getUserByID() {
        // get posted userID, or get userID from session
        if (isset($_POST['givenUserID'])) {
            $givenUserID = $_REQUEST["givenUserID"];
        } else {
            $givenUserID = $_SESSION["userID"];
        }
        // get all userinformation from userID
        $userInfo = $GLOBALS["userModel"];
        $userModel = $userInfo->getAllUserInfoFromID($givenUserID);

        // get media information
        $mediaModel = $GLOBALS["mediaModel"];
        $mediaInfo = $mediaModel->getAllMediaInfo();

        // echo result as an array to view
        $data = json_encode(array("user" => $userModel, "media" => $mediaInfo));
        echo $data;
    }

    /**
    * Delete existing user
    */ 
    private function deleteUserEngine() {
        $removeUserID = $_REQUEST["deleteUserID"];

        $removeUserRestriction = $GLOBALS["restrictionModel"];
        $removeUserRestriction->deleteUserRestriction($removeUserID);

        $sessionID = $_SESSION["userID"];

        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);

        $removeUser = $GLOBALS["userModel"];
        $delited = $removeUser->removeUser($removeUserID);

        if ($delited) {
            echo json_encode("success");
        } else {
            return false;
        }
    }

    /**
    * Delete user restriction from storage
    */
    private function deleteSingleRes() {
        // get posted values
        $givenUserID = $_REQUEST["givenUserID"];
        $givenStorageID = $_REQUEST["givenStorageID"];
        $sessionID = $_SESSION["userID"];

        // get involved models
        $setSessionID = $GLOBALS["userModel"];
        $deletedRes = $GLOBALS["restrictionModel"];

        $setSessionID->setSession($sessionID);  // set global value in database
        // remove user restricion
        $deletedRes->deleteSingleRestriction($givenUserID, $givenStorageID);
        // echo a respond to view
        echo json_encode("success");
    }

    /**
    * Get user restrictions from given userID
    */
    private function getUserRestriction() {
        $givenUserID = $_REQUEST['givenUserID'];    // get posted userID
        $restrictionInfo = $GLOBALS["restrictionModel"];    // get restriction model
        // gets all restriction given user have
        $restrictionModel = $restrictionInfo->getAllRestrictionInfoFromUserID($givenUserID);
        // echo result as an array to view
        $data = json_encode(array("restriction" => $restrictionModel));
        echo $data;
    }

}


