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
    CONST TO        = 'to';
    CONST SUBJECT   = 'subject';
    CONST FROM      = 'from';
    CONST REPLY_TO  = 'reply-to';

    public function handleErrors()
    {
        $to      = $this->getParam(self::TO);
        $subject = $this->getParam(self::SUBJECT);
        $message = $this->generateText();
        $from    = $this->getParam(self::FROM);
        $reply   = $this->getParam(self::REPLY_TO);

        if ( !$to || !$subject || !$message || !$from ) d('oi');


        $headers = "From: {$from} \r\n" ;
        if ($reply) {
            $headers .= "Reply-To: {$reply} \r\n";
        }
        $headers .= "X-Mailer: PHP/" .phpversion();


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