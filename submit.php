<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

// define variables and set to empty values
$errors = [];
$name = $email = $number = $creditAmount = $currency = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        array_push($errors,"Name is required");
    } else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["email"])) {
        array_push($errors, "Email is required");
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["number"])) {
        array_push($errors, "number is required");
    } else {
        $number = test_input($_POST["number"]);
    }

    if (empty($_POST["currency"])) {
        array_push($errors, "currency is required");
    } else {
        $currency = test_input($_POST["currency"]);
    }

    if (empty($_POST["creditAmount"])) {
        array_push($errors, "creditAmount is required");
    } else {
        $creditAmount = test_input($_POST["creditAmount"]);
    }


    if (count($errors) == 0) {
        $message = "<ul>
                    <li>Name: {$name}</li>
                    <li>Email: {$email}</li>
                    <li>Number: {$number}</li>
                    <li>Currency: {$currency}</li>
                    <li>Credit Amount: {$creditAmount}</li>
               </ul>";
        sendMail($message);
    }else{
        print_r($errors);
    }

}

function sendMail($message){
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = getenv('EMAIL_USERNAME');                     //SMTP username
        $mail->Password   = getenv('EMAIL_PASSWORD');;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
        //Recipients
        $mail->setFrom(getenv('EMAIL_USERNAME'), 'Mailer');
        $mail->addAddress('lajox84902@bubblybank.com', 'Joe User');     //Add a recipient
        
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = $message;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}