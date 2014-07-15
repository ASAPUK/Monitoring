<?php

namespace Monitoring\Handler;

use YiiMailer;
/**
 * Send email with errors
 *
 * Class SendEmail
 * @package Monitoring\Handler
 */
class SendEmailYii extends HandlerAbstract
{
    CONST TO         = 'to';
    CONST SUBJECT    = 'subject';
    CONST FROM_EMAIL = 'from_email';
    CONST FROM_NAME  = 'from_name';

    public function handleErrors()
    {
        $to        = $this->getParam(self::TO);
        $subject   = $this->getParam(self::SUBJECT);
        $message   = $this->generateText();
        $fromEmail = $this->getParam(self::FROM_EMAIL);
        $fromName  = $this->getParam(self::FROM_NAME);

        if ( !empty($to) || !empty($subject) || !empty($message) || !empty($fromEmail) || !empty($fromName) ) return;

        $mail = new YiiMailer();

        $mail->setFrom($fromEmail, $fromName);
        $mail->setTo($to);
        $mail->setSubject($subject);
        $mail->setBody($message);
        $mail->send();
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