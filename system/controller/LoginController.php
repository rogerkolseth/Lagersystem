<?php

require_once("Controller.php");

class LoginController extends Controller {

    public function show($page) {
        if ($page == "newPassword") {
            $this->generateNewPassword();
        } else if ($page == "loginEngine") {
            $this->loginEngine();       
        } else {
            $this->displayLoginPage();
        } 
    }

    public function displayLoginPage() {
        return $this->render("LoginPage");
    }

    public function loginEngine() {

        if (isset($_POST['givenUsername']) && ($_POST['givenPassword']) && ($_POST['givenLastLogin'])) {
            $givenUsername = $_REQUEST["givenUsername"];
            $givenPassword = $_REQUEST["givenPassword"];
            $givenLastLogin = $_REQUEST["givenLastLogin"];

            $type = 2;
            $desc = "Bruker logget inn";

            $loggModel = $GLOBALS["loggModel"];
            $userModel = $GLOBALS["userModel"];
            $userModel->updateLastLogin($givenLastLogin, $givenUsername);
            $result = $loggModel->loggCheck($type);
            $Users = $userModel->getAllUserInfo();

            foreach ($Users as $User) {
                if ($User["username"] == $givenUsername) {
                    if (password_verify($givenPassword, $User["password"])) {
                        $_SESSION["AreLoggedIn"] = "true";
                        $_SESSION["nameOfUser"] = $User["name"];
                        $_SESSION["userID"] = $User["userID"];
                        $_SESSION["userLevel"] = $User["userLevel"];
                        if ($result[0]["typeCheck"] > 0) {
                            $loggModel->loginLog($type, $desc, $User["userID"]);
                        }
                    }
                }
            }
            if ($_SESSION["AreLoggedIn"] == true) {
                header("Location:system/index.php");
            }


            $errorMessage = "Feil brukernavn eller passord";
            $message = array("errorMessage" => $errorMessage);
            return $this->render("LoginPage", $message);
        }
    }
    
    private function generateNewPassword() {
        $givenUsername = $_REQUEST["givenUsername"];
        $givenEmail = $_REQUEST["givenEmail"]; 
        

        $userModel = $GLOBALS["userModel"];
        $exist = $userModel->forgottenPassword($givenUsername, $givenEmail);

        if (empty(!$exist)) {
            $userID = $exist[0]["userID"];

            $newPassword = $this->generateRandomPassword();
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);

            $userModel->newPassword($hash, $userID);

            $this->emailNewPassword($newPassword, $givenEmail);
            echo json_encode("sucess");
        } else {
            return false;
        }
    }

    private function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function emailNewPassword($newPassword, $email) {
        require 'system/PHPMailer/PHPMailer-master/PHPMailerAutoload.php';
//Create a new PHPMailer instance
        $mail = new PHPMailer;
//Tell PHPMailer to use SMTP
        $mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
        $mail->SMTPDebug = 0;
//Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
        $mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "roger.kolseth@gmail.com";
//Password to use for SMTP authentication
        $mail->Password = "******";
//Set who the message is to be sent from
        $mail->setFrom('roger.kolseth@gmail.com', 'Lagersystem');
//Set who the message is to be sent to
        $mail->addAddress($email, 'John Doe');
//Set the subject line
        $mail->Subject = "Nytt passord";
//Replace the plain text body with one created manually
        $mail->Body = 'Ditt nye passord er: ' . $newPassword;
        $mail->send();
    }

}
