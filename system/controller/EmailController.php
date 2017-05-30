<?php

require_once("Controller.php"); //include controller

class EmailController extends Controller {
    
    /**
     * Decide wich function to run based on passed $requset variable
     */
    public function __construct($request) {
        if ($page == "sendInventarWarning") {
            $this->sendEmailWarning();
        } 
    }

       /**
        * @return boolean sends email with storages with lov inventory status 
        */
    private function sendEmailWarning() {
        $inventoryInfo = $GLOBALS["inventoryModel"]; // gets inventory model
        $userModel = $GLOBALS["userModel"]; // gets user model

        // gets information about product with low inventory status from model
        $result = $inventoryInfo->getEmailWarning(); 
        
        // checks if result array contains elements
        if (empty(!$result)) {
            $email = $userModel->getAdminEmail(); // get email adresses from Administrators

            // update warningstatus in database
            foreach ($result as $update):
                $inventoryInfo->updateWarningStatus($update["inventoryID"]);
            endforeach;
            
            // send email
            foreach ($email as $email):
                $this->emailWarning($result, $email["email"]);
            endforeach;

            
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $data
     * @param type $email Configuer setup from PHP mailer : https://github.com/PHPMailer/PHPMailer
     */
    private function emailWarning($data, $email) {
        require 'PHPMailer/PHPMailer-master/PHPMailerAutoload.php';
        foreach ($data as $data):
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
            $mail->Subject = "Varsling om lav varebeholdning";
//Replace the plain text body with one created manually
            $mail->Body = 'Der er kun ' . $data["quantity"] . ' stk ' . $data["productName"] . ' igjen på ' . $data["storageName"];
            $mail->send();
        endforeach;
    }



}
