<?php

require_once("Controller.php");

class LoginController extends Controller {

    public function show($page) {
        if ($page == "loginEngine") {
            $this->loginEngine();
        } else {
            $this->displayLoginPage();
        }
    }

    public function displayLoginPage() {
        return $this->render("loginPage");
    }

    public function loginEngine() {
        if (isset($_POST['givenUsername']) && ($_POST['givenPassword']) && ($_POST['givenLastLogin'])) {
            $givenUsername = $_REQUEST["givenUsername"];
            $givenPassword = $_REQUEST["givenPassword"];
            $givenLastLogin = $_REQUEST["givenLastLogin"];

            $type = "Innlogging";
            $desc = "Bruker logget inn";

            $loggModel = $GLOBALS["loggModel"];
            $userModel = $GLOBALS["userModel"];
            $userModel->updateLastLogin($givenLastLogin, $givenUsername);

            $Users = $userModel->getAllUserInfo();

            foreach ($Users as $User) {
                if ($User["username"] == $givenUsername) {
                    if (password_verify($givenPassword, $User["password"])) {
                        $_SESSION["AreLoggedIn"] = "true";
                        $_SESSION["nameOfUser"] = $User["name"];
                        $_SESSION["userID"] = $User["userID"];
                        $_SESSION["userLevel"] = $User["userLevel"];
                        $loggModel->loginLog($type, $desc, $User["userID"]);
                    }
                }
            }
            $errorMessage = "Feil brukernavn eller passord";
            $message = array("errorMessage" => $errorMessage);
            return $this->render("loginPage", $message);
        } else {
            header("Location:system/");
        }
    }

}
