<?php

import_lib('external/PHPMailer/class.phpmailer');

class Mailer {
    private $from;
    private $to;
    private $subject;
    private $body;

    private $copySubject;
    private $copyBody;

    private $error;

    function __construct() {
        $this->copySubject = 'Copy of: ';
        $this->copyBody = 'Thank you for filling in our form. We have received the following e-mail.';
    }

    function setCopyText($subject, $body) {
        $this->copySubject = $subject;
        $this->copyBody = $body;
    }

    function setFrom($from) {
        $this->from = $from;
    }

    function setTo($to) {
        $this->to = $to;
    }

    function setSubject($subject) {
        $this->subject = $subject;
    }

    function setBody($body) {
        $this->body = $body;
    }

    private function sendOne($from, $to, $subject, $message) {
        global $s;
        $mail = new PHPMailer();
        $mail->SetFrom($from);
        $mail->AddReplyTo($from);
        $mail->AddAddress($to);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $result = $mail->Send();
        if (!$result)
            $this->error = $mail->ErrorInfo;
        return $result;
    }

    function send($confirmation = true) {
        $this->error = null;
        if (!$this->sendOne($this->from, $this->to, $this->subject, $this->body))
            return false;
        if ($confirmation && !$this->sendOne($this->to, $this->from, $this->copySubject . $this->subject, '<p>' . $this->copyBody . '</p>' . $this->body))
            return false;
        return true;
    }

    function getError() {
        return $this->error;
    }
}

?>
