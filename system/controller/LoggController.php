<?php

require_once("Controller.php");

class LoggController extends Controller {
    
    public function show($page){
        if ($page == "logg"){
            $this->loggPage();
        } else if ($page == "getAllLoggInfo"){
            $this->getAllLoggInfo();
        }
        elseif ($page == "getLatestLoggInfo") {
            $this->getLatestLoggInfo();
    }
    }
    
    private function loggPage() {
        return $this->render("logg");
    }
    
    private function getAllLoggInfo(){
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
    
    private function getLatestLoggInfo()
    {
        $loggModel = $GLOBALS["loggModel"];
        $loggLateInfo = $loggModel->getLatestLoggInfo();
        
        $data = json_encode(array("latestLoggInfo" => $loggLateInfo));
        echo $data;
    }
    
}
