<?php

class Mailer
{
    public static function send($details = array()){
        require_once ('includes/email/'.$details['file']);
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Host = gethostbyname('smtp.gmail.com');
        $mail->SMTPSecure = 'tls';
        $mail->Port = Config::get('mailer/port');
        //$mail->SMTPDebug  = 3;
        $mail->Debugoutput = 'html';
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->SMTPAuth   = true;
        $mail->Username   = Config::get('mailer/username');
        $mail->Password   = Config::get('mailer/password');
        $mail->setFrom(Config::get('mailer/username'), 'Zen Body Massage');
        $mail->addAddress($details['email']);
        $mail->Subject  = $details['subject'];
        $mail->msgHTML($msg);
        $mail->AltBody = $msg;
        try{
            $mail->send();
            return true;
        } catch(phpmailerException $e){
            echo $e->getMessage();
            return false;
        }
    }
}