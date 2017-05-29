<?php

require_once("Controller.php"); //include controller

class LoginController extends Controller {
    
    //Decide wich function to run based on passed $requset variable
    public function show($request) {
        if ($request == "newPassword") {
            $this->generateNewPassword();
        } else if ($request == "loginEngine") {
            $this->loginEngine();   
        }else if ($request == "logOut"){ 
            $this->logOutEngine();
        } else {
            $this->displayLoginPage();
        } 
    }

    // display login page
    public function displayLoginPage() {
        return $this->view("LoginPage");
    }

    // verifie user
    public function loginEngine() {

        if (isset($_POST['givenUsername']) && ($_POST['givenPassword'])) {
            $givenUsername = $_REQUEST["givenUsername"];    // get POSTed username
            $givenPassword = $_REQUEST["givenPassword"];    // get POSTed password

            $type = 2;
            $desc = "Bruker logget inn";

            $loggModel = $GLOBALS["loggModel"];     //get logg model
            $userModel = $GLOBALS["userModel"];     // get user model
            
            $result = $loggModel->loggCheck($type);
            $Users = $userModel->getAllUserInfo();

            foreach ($Users as $User) {
                if ($User["username"] == $givenUsername) {
                    if (password_verify($givenPassword, $User["password"])) {
                        $_SESSION["verified"] = "true";
                        $_SESSION["nameOfUser"] = $User["name"];
                        $_SESSION["userID"] = $User["userID"];
                        $_SESSION["userLevel"] = $User["userLevel"];
                        if ($result[0]["typeCheck"] > 0) {
                            $loggModel->loginLog($type, $desc, $User["userID"]);
                        }
                    }
                }
            }
            $userModel->updateLastLogin($givenUsername);    // update last login
            
             if (($_SESSION["verified"] == true) && (!isset($_POST['API'])) ) {
                // check if login request comes from the system or an external page
                header("Location:system/index.php");
            } else if (isset($_POST['API']) && ($_SESSION["verified"] == true)){
                // when login from an external page, API must also be posted
                $_SESSION["API"] = true;
                echo json_encode("success");    // echo result to view on success
            } else {
                
            // if failed to verifie, give user errormessage
            $errorMessage = "Feil brukernavn eller passord";
            $message = array("errorMessage" => $errorMessage);
            $this->data($message);
            //display loginpage
            return $this->view("LoginPage");
            }
        }
    }
    
    private function logOutEngine(){
        // destroy session on logout
        session_destroy();
    }
    
    // generate new random password 
    private function generateNewPassword() {
        $givenUsername = $_REQUEST["givenUsername"]; // get POSTed username
        $givenEmail = $_REQUEST["givenEmail"];  // get POSTed email
        

        $userModel = $GLOBALS["userModel"]; // get user model
        // check if user exist from given information
        $exist = $userModel->forgottenPassword($givenUsername, $givenEmail);

        if (empty(!$exist)) {
            // if user exist get userID from result
            $userID = $exist[0]["userID"];

            $newPassword = $this->generateRandomPassword(); // get new password
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);  // hash password

            $userModel->newPassword($hash, $userID);    // update new password in DB

            // send email with new password
            $this->emailNewPassword($newPassword, $givenEmail);
            echo json_encode("sucess"); // echo a response to view on success
        } else {
            return false;
        }
    }

        // generate new password, gotten from stackoverflow: https://stackoverflow.com/questions/4356289/php-random-string-generator
    private function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // sends new email, configure file taken from PHP maile git page: https://github.com/PHPMailer/PHPMailer
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
        $mail->Host = 'pop.mimer.no';
        $mail->Port = 465;
//Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'ssl';
//Whether to use SMTP authentication
        $mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "lager@mimer.no";
//Password to use for SMTP authentication
        $mail->Password = "WR*3Z@s8";
//Set who the message is to be sent from
        $mail->setFrom('lager@mimer.no', 'Lagersystem');
//Set who the message is to be sent to
        $mail->addAddress($email);
//Set the subject line
        $mail->Subject = "Nytt passord";
//Replace the plain text body with one created manually
        $mail->Body = 'Ditt nye passord er: ' . $newPassword;
        $mail->send();
    }

}
