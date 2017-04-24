<?php

// Represents a controller of our website
abstract class Controller {

    /**
     * Renders the page - outputs its content
     * @param string $page
     */
    public abstract function show($page);

    /**
     * Takes view part (template) and model part (data) and renders the page content
     *
     * @param string $templateName name of the template to use
     * @param array $data optional data array to be passed to template
     * @return bool true on success
     */
    protected function render($templateName, $data = array()) {
        // Store data in global variables
        foreach ($data as $dataKey => $dataValue) {
            $GLOBALS[$dataKey] = $dataValue;
        }
        // Include template
        $templatePath = "view/{$templateName}.php";
        $success = true;
        if (!file_exists($templatePath))
            return false;
        include($templatePath);
        return true;
    }

    protected function emailWarning($toAdresse, $storageName, $quantity, $productName) {
        require 'PHPMailer/PHPMailer-master/PHPMailerAutoload.php';

//Create a new PHPMailer instance
        $mail = new PHPMailer;
//Tell PHPMailer to use SMTP
        $mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
        $mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
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
        $mail->addAddress($toAdresse, 'John Doe');
//Set the subject line
        $mail->Subject = "Varsling om lav varebeholdning";
//Replace the plain text body with one created manually
        $mail->Body =  'Der er kun ' .$quantity. ' stk ' .$productName. ' igjen på ' .$storageName;
        $mail->send();
    
    }

}

    //    $storageName = 'Hovedlager';
    //    $quantity = '3';
    //    $toAdresse = 'roger.kolseth@gmail.com';
    //    $productName = 'FMG';
    //    $this->emailWarning($toAdresse, $storageName, $quantity, $productName);