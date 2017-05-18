<?php

require_once("Controller.php");

class mediaController extends Controller {

    public function show($page) {
        $viewMediaAdm = "mediaAdm";
        $viewHome = "home";
        $viewEditUser = "editUser";
        
        switch ($page) {
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

    private function mediaPage() {
        return $this->view("mediaAdm");
    }
    

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
        
        $message = array("errorMessage" => $errorMessage);
        
        if($data == "mediaAdm"){
            return $this->view("mediaAdm", $message);
        } else if ($data == "home"){
            return $this->view("home" , $message);
        } else if ($data == "editUser"){
            return $this->view("editUser", $message);
        }
        
        
    }
    
    private function addMedia($fileName, $givenCaterogy){
        $sessionID = $_SESSION["userID"];
        $setSessionID = $GLOBALS["userModel"];
        $setSessionID->setSession($sessionID);
        
        $mediaModel = $GLOBALS["mediaModel"];
        $added = $mediaModel->addMedia($fileName, $givenCaterogy);
        
        if($added){
            
        }
    }
    
    private function getMediaSearchResult(){
        $mediaModel = $GLOBALS["mediaModel"];
        
        if (isset($_POST['givenMediaSearchWord'])) {
            $givenStorageSearchWord = "%{$_REQUEST["givenMediaSearchWord"]}%";
            $mediaInfo = $mediaModel->getMediaSearchResult($givenStorageSearchWord);
        } else {
            $givenStorageSearchWord = "%%";
            $mediaInfo = $mediaModel->getMediaSearchResult($givenStorageSearchWord);
        }
        
        $data = json_encode(array("mediaInfo" => $mediaInfo));

        echo $data;
    }
    
    private function getMediaByID(){
        $givenMediaID = $_REQUEST["givenMediaID"];
        
        $mediaModel = $GLOBALS["mediaModel"];
        $mediaInfo = $mediaModel->getMediaByID($givenMediaID);
        
        $categoryModel = $GLOBALS["categoryModel"];
        $categoryInfo = $categoryModel->getAllCategoryInfo();
        
        $data = json_encode(array("mediaInfo" => $mediaInfo, "category" => $categoryInfo));
        echo $data;   
    }
    
    private function editMedia(){
        $editMediaID = $_REQUEST["editMediaID"];
        $editMediaName = $_REQUEST["editMediaName"];
        $editCategoryID = $_REQUEST["editCategoryID"];
        
        $mediaModel = $GLOBALS["mediaModel"];
        $result = $mediaModel->getMediaByID($editMediaID);
        
        $oldName = $result[0]["mediaName"];
        $renamed = rename("image/".$oldName."","image/".$editMediaName);
        
        $edited = $mediaModel->editMedia($editMediaID, $editMediaName, $editCategoryID);
        
        if($edited && $renamed){
            echo json_encode("success");
        } 
        else {return false;}
    }
    
    private function deleteMedia(){
        $deleteMediaID = $_REQUEST["deleteMediaID"];
        
        $mediaModel = $GLOBALS["mediaModel"];
        $result = $mediaModel->getMediaByID($deleteMediaID);
        
        $mediaName = $result[0]["mediaName"];
        $deleted = $mediaModel->deletetMediaByID($deleteMediaID);
        
        if($deleted){
            unlink("image/".$mediaName);
            echo json_encode("success");
        } else {return false;}
    }
    
    private function getMediaFromCategory(){
        $givenCategoryID = $_REQUEST["givenCategoryID"]; 
        if($givenCategoryID == 0){
            $this->getMediaSearchResult();
        } else {
        $mediaModel = $GLOBALS["mediaModel"];
        
        $result = $mediaModel->getMediaFromCategory($givenCategoryID);
        
        $data = json_encode(array("mediaInfo" => $result));
        echo $data;
        }
    }

}
