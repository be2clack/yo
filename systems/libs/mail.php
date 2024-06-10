<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'PHPMailer/PHPMailer.php';
include 'PHPMailer/Exception.php';
include 'PHPMailer/SMTP.php';

function mailer($to, $subject, $html_mail, $attach=false) {
    global $settings;

    if( !$to ) return false;

    $mail = new PHPMailer(true);

    try {
        
        if($settings["variant_send_mail"] == 2){

            $mail->SMTPDebug =  $settings["SMTPDebug"];
            $mail->isSMTP();
            $mail->Host       = $settings["smtp_host"];
            $mail->SMTPAuth   = $settings["smtp_auth"];
            $mail->Username   = $settings["smtp_username"];
            $mail->Password   = decrypt($settings["smtp_password"]);
            $mail->SMTPSecure = $settings["smtp_secure"];
            $mail->Port       = $settings["smtp_port"];
           
            if( $settings["smtp_secure"] == "tsl" ){
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }

        }
        
        $mail->CharSet    = "utf-8";
        $mail->setFrom($settings["email_noreply"], $settings["name_responder"]);
        $mail->addReplyTo($settings["email_noreply"], $settings["name_responder"]);

        $recipients = explode(",", $to);
        foreach ($recipients as $value) {
          $mail->addAddress($value);
        }

        if($attach) $mail->addAttachment($attach);


        $mail->isHTML(true);                                
        $mail->Subject = $subject;
        $mail->Body    = $html_mail;

        $mail->send();
        
        return true;

    } catch (Exception $e) {
        return false;
    }
  
}

?>