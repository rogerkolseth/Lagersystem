<?php

require_once("Controller.php"); //include controller

class mediaController extends Controller {

    //Decide wich function to run based on passed $requset variable
    public function show($request) {
        $viewMediaAdm = "mediaAdm";
        $viewHome = "home";
        $viewEditUser = "editUser";
        
        switch ($request) {
            case "mediaAdm" :
                return $this->mediaPage();
            case "uploadImage" :
                return $this->uploadImage($viewMediaAdm);
            case "getAllMediaInfo" :
                return $this->getMediaSearchResult();
            case "uploadImageShortcut" :
                return $this->uploadImage($viewHome);
            case "uploadImageShortcut2" :
                return $this->uploadImage($viewEditUser);
            case "getMediaByID" :
                return $this->getMediaByID();
            case "editMedia" :
                return $this->editMedia();
            case "deleteMedia" :
                return $this->deleteMedia();
            case "getMediaFromCategory" :
                return $this->getMediaFromCategory();
        }
    }

    // display media administrator page
    private function mediaPage() {
        return $this->view("mediaAdm");
    }
    
    // function to upload image. Code based example code from w3school.com
    // https://www.w3schools.com/php/php_file_upload.asp
    
    private function uploadImage($data) {
        $givenCaterogyID = $_REQUEST["givenCategoryID"];
        $imageName = "";
        $target_dir = "image/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $errorMessage = "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $errorMessage = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $errorMessage = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
               $errorMessage = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                $imageName = basename($_FILES["fileToUpload"]["name"]);
                $this->addMedia($imageName, $givenCaterogyID);
            } else {
                $errorMessage = "Sorry, there was an error uploading your file.";
            }           
        }
        
        // display required page, with error message from the upload.
        $message = array("errorMessage" => $errorMessage);
        if($data == "mediaAdm"){
            $this->data($message);
            return $this->view("mediaAdm");
        } else if ($data == "home"){
            return $this->view("home");
        } else if ($data == "editUser"){
            $this->data($message);
            return $this->view("editUser");
        }
    }
    
   // add media information to database
    private function addMedia($fileName, $givenCaterogy){
        $sessionID = $_SESSION["userID"];   // get userID from session
        $setSessionID = $GLOBALS["userModel"];  //get user model
        $setSessionID->setSession($sessionID);  //set value as global variable in database
        
        $mediaModel = $GLOBALS["mediaModel"];   // get media model
        $added = $mediaModel->addMedia($fileName, $givenCaterogy);  //add media to database
        
    }
    
    /**
     * Get media serach result
     */
    private function getMediaSearchResult(){
        $mediaModel = $GLOBALS["mediaModel"];   // get media model
        
        if (isset($_POST['givenMediaSearchWord'])) {
            // get media result from model from search word if posted
            $givenStorageSearchWord = "%{$_REQUEST["givenMediaSearchWord"]}%";
            $mediaInfo = $mediaModel->getMediaSearchResult($givenStorageSearchWord);
        } else {
            $givenStorageSearchWord = "%%";
            // get all media result
            $mediaInfo = $mediaModel->getMediaSearchResult($givenStorageSearchWord);
        }
        // echo results as an array to view
        $data = json_encode(array("mediaInfo" => $mediaInfo));
        echo $data;
    }
    
    /**
     * Get media info from ID
     */    
    private function getMediaByID(){
        $givenMediaID = $_REQUEST["givenMediaID"]; // get POSTed mediaID
        
        $mediaModel = $GLOBALS["mediaModel"];   // get media model
        $mediaInfo = $mediaModel->getMediaByID($givenMediaID);  // get media info from model
        
        $categoryModel = $GLOBALS["categoryModel"]; // get category model
        $categoryInfo = $categoryModel->getAllCategoryInfo();   //get all category info
        
        // echo result as an array to view
        $data = json_encode(array("mediaInfo" => $mediaInfo, "category" => $categoryInfo));
        echo $data;   
    }
    
    /**
     * Edit media information
     */    
    private function editMedia(){
        // get posted values
        $editMediaID = $_REQUEST["editMediaID"];
        $editMediaName = $_REQUEST["editMediaName"];
        $editCategoryID = $_REQUEST["editCategoryID"];
        
        // get media model and get media info from ID
        $mediaModel = $GLOBALS["mediaModel"];
        $result = $mediaModel->getMediaByID($editMediaID);
        
        // edits file name on the server
        $oldName = $result[0]["mediaName"];
        $renamed = rename("image/".$oldName."","image/".$editMediaName);
        
        // media info in DB
        $edited = $mediaModel->editMedia($editMediaID, $editMediaName, $editCategoryID);
        
        // echo a result to view if success
        if($edited && $renamed){
            echo json_encode("success");
        } 
        else {return false;}
    }
    
    /**
     * delete media information
     */       
    private function deleteMedia(){
        $deleteMediaID = $_REQUEST["deleteMediaID"]; // get POSTED value
        
        $mediaModel = $GLOBALS["mediaModel"];   // get media model
        $result = $mediaModel->getMediaByID($deleteMediaID);    // get media info from DB
        
        // delete media from DB
        $mediaName = $result[0]["mediaName"];
        $deleted = $mediaModel->deletetMediaByID($deleteMediaID);
        
        // if success, delete file from server and echo respond to view
        if($deleted){
            unlink("image/".$mediaName);
            echo json_encode("success");
        } else {return false;}
    }
    
    /**
     * get meida in a given categrórie 
     */  
    private function getMediaFromCategory(){
        $givenCategoryID = $_REQUEST["givenCategoryID"]; // get POSTed categoryID
        if($givenCategoryID == 0){  //if 0 (show all), run getMediaSerchResult
            $this->getMediaSearchResult();
        } else {
        $mediaModel = $GLOBALS["mediaModel"]; // get media mode
        
        // gets media result from databse
        $result = $mediaModel->getMediaFromCategory($givenCategoryID);
        
        // echo result from model to view
        $data = json_encode(array("mediaInfo" => $result));
        echo $data;
        }
    }

}
