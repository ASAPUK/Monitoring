<?php

namespace Monitoring\Handler;

/**
 * Send email with errors
 *
 * Class SendEmail
 * @package Monitoring\Handler
 */
class SendEmail extends HandlerAbstract
{
    CONST TO         = 'to';
    CONST SUBJECT    = 'subject';
    CONST FROM_EMAIL = 'from_email';
    CONST FROM_NAME  = 'from_name';
    CONST REPLY      = 'reply_to';

    public function handleErrors()
    {
        $to        = $this->getParam(self::TO);
        $subject   = $this->getParam(self::SUBJECT);
        $message   = $this->generateText();
        $fromEmail = $this->getParam(self::FROM_EMAIL);
        $fromName  = $this->getParam(self::FROM_NAME);
        $reply     = $this->getParam(self::REPLY);

        if ( empty($reply) || empty($to) || empty($subject) || empty($message) || empty($fromEmail) || empty($fromName) ) return;

        $headers = "From: {$fromEmail} \r\n" ;
        if ($reply) {
            $headers .= "Reply-To: {$reply} \r\n";
        }
        $headers .= "Content-type: text/html";

        mail($to, $subject, $message, $headers);
    }

    private function generateText()
    {
        if (count($this->getErrors()) == 0) return null;

        $text = '';
        foreach( $this->getErrors() as $error) {
            $text .= date('m/d/Y H:i:s', time()) . ' - ' . $error . "\n";
        }

        return $text;
    }
}