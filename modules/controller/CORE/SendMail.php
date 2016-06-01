
<?php

header('Content-type: text/html; charset=utf-8');
include 'PHPMailer/PHPMailerAutoload.php';

class SendMail {

    var $from;
    var $to;
    var $subject;
    var $message;
    var $attachments;
    var $host;
    var $userName;
    var $password;
    var $SMTPSecure;
    var $port;
    var $debug = false;
    var $SMTPAuth;
    var $idMail;

    public function setDebug($debug) {
        $this->debug = $debug;
    }

    public function __construct($host, $userName, $password, $port, $SMTPSecure) {
        $this->host = $host;
        $this->userName = $userName;
        $this->password = $password;
        $this->SMTPSecure = $SMTPSecure;

        $this->port = $port;
    }

    public function sendMail($from, $to, $subject, $message, $attachments = "", $idMail = "") {
        @session_start();
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->attachments = $attachments;
        $this->MailId = $idMail;

        return $this->execute();
    }

    public function execute() {

        $mail = new PHPMailer;

        if ($this->debug == true) {
            $mail->SMTPDebug = 3;                               // Enable verbose debug output
        }
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $this->host;  // Specify main and backup SMTP servers

        if ($this->userName != "" && $this->password != "" && $this->port != "") {
            $mail->SMTPAuth = TRUE;                               // Enable SMTP authentication
            $mail->Username = $this->userName;                 // SMTP username
            $mail->Password = $this->password;                           // SMTP password
            $mail->SMTPSecure = $this->SMTPSecure;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $this->port;                                    // TCP port to connect to        
        }
        $mail->setFrom($this->from);
        $mail->addAddress($this->to);     // Add a recipient

        foreach ($this->attachments as $attach) {
            $mail->addAttachment($attach);         // Add attachments
            // echo $attach;
        }
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $this->subject;
        $mail->Body = $this->message;
        if (!$mail->send()) {
            //  echo 'Message could not be sent.';
            //   echo 'Mailer Error: ' . $mail->ErrorInfo;
            // return false;
        } else {
            //  echo 'Message  sent';
            // return "true";
        }
    }

}
