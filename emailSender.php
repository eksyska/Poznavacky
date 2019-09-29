<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function sendEmail($to, $subject, $message, $fromAddress = 'poznavacky@email.com', $fromName = 'Poznávačky')
{
    require 'phpMailer/src/Exception.php';
    require 'phpMailer/src/PHPMailer.php';
    require 'phpMailer/src/SMTP.php';
    
    $mail = new PHPMailer();
    
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'free.mboxhosting.com';
    $mail->Port = '587';
    $mail->isHTML();
    $mail->Username = 'noreply@poznavacky.dx.am';
    $mail->Password = 'SECRET';
    $mail->SetFrom($fromAddress, $fromName, true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AddAddress($to);
    
    $result = $mail->Send();
    if(!$result)
    {
        return "E-mail nemohl být odeslán! Chyba: ".$mail->ErrorInfo." Prosíme, kontaktujte správce.";
    }
    else
    {
        //return "E-mail byl úspěšně odeslán.";
        return NULL;
    }
}

/*-----------------------------------------------------------------*/

require 'CONSTANTS.php';

//Kód pro odeslání e-mailu, pokud je tento skript použit v AJAX
$emailCode = @$_POST['acc'];

$to = @$_POST['to'];
$subject = @$_POST['sub'];
$message = nl2br(@$_POST['msg']);

if (!($to === 'null' || $subject === 'null' || $message === 'null' || empty($to) || empty($subject) || empty($message)))
{
    if ($emailCode !== EMAIL_CODE){die("alert('Neplatný bezpečnostní kód.');");}   //Kontrola, zda není tento skript vyvolán neoprávněne na straně klienta modifikací JS
    
    $result = sendEmail($to, $subject, $message) === NULL;
    if($result)
    {
        echo "alert('Email byl úspěšně odeslán');";
    }
    else
    {
        echo "alert($result);";
    }
}
