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

}
