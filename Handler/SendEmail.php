<?php

namespace Monitoring\Handler;

use PHPMailer;
/**
 * Send email with errors
 *
 * Class SendEmail
 * @package Monitoring\Handler
 */
class SendEmail extends HandlerAbstract
{
    CONST HOST        = 'host';
    CONST USERNAME    = 'username';
    CONST PASSWORD    = 'password';
    CONST SMTP_SECURE = 'smtp_secure';

    CONST FROM_EMAIL  = 'from_email';
    CONST FROM_NAME   = 'from_name';

    CONST TO_EMAIL    = 'to_email';
    CONST TO_NAME     = 'to_name';

    CONST CC          = 'cc';
    CONST BCC         = 'bcc';
    CONST REPLY_TO    = 'reply_to';
    CONST REPLY_TITLE = 'reply_title';

    CONST WORLD_WRAP  = 'world_wrap';
    CONST IS_HTML     = 'is_html';

    CONST SUBJECT     = 'subject';

    protected $_default = array(
        self::HOST          => 'smtp1.example.com',
        self::USERNAME      => 'user@example.com',
        self::PASSWORD      => 'secret',
        self::SMTP_SECURE   => 'tls',
        self::TO_EMAIL      => 'joe@example.net',
        self::TO_NAME       => 'Joe User',
        self::FROM_EMAIL    => 'from@example.com',
        self::FROM_NAME     => 'Mailer',
        self::CC            => 'cc@example.com',
        self::BCC           => 'bcc@example.com',
        self::REPLY_TO      => 'info@example.com',
        self::REPLY_TITLE   => 'Information',
        self::WORLD_WRAP    => 50,
        self::IS_HTML       => true,
        self::SUBJECT       => 'tls'
    );

    public function handleErrors()
    {
        $debug = false;
        $message = $this->generateText();
        if (empty($message)) {
            if ($debug) echo 'Nothing to send';
            return;
        }

        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = $this->getParam(self::HOST);

        $mail->SMTPAuth = true;
        $mail->Username = $this->getParam(self::USERNAME);
        $mail->Password = $this->getParam(self::PASSWORD);
        $mail->SMTPSecure = $this->getParam(self::SMTP_SECURE);

        $mail->From = $this->getParam(self::FROM_EMAIL);
        $mail->FromName = $this->getParam(self::FROM_NAME);

        $mail->addAddress($this->getParam(self::TO_EMAIL), $this->getParam(self::TO_NAME));

        $mail->addReplyTo( $this->getParam(self::REPLY_TO), $this->getParam(self::REPLY_TITLE) );
        $mail->addCC( $this->getParam(self::CC) );
        $mail->addBCC( $this->getParam(self::BCC) );

        $mail->WordWrap = $this->getParam(self::WORLD_WRAP);
        $mail->isHTML( $this->getParam(self::IS_HTML) );

        $mail->Subject = $this->getParam(self::SUBJECT);
        $mail->Body    = $message;

        if ($debug) {
            if(!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent to mail: "' . $this->getParam(self::TO_EMAIL) .'"';
            }
            echo "\n";
        } else {
            $mail->send();
        }
    }

    private function generateText()
    {
        if (count($this->getErrors()) == 0) return null;

        $text = '[ ' . date('m/d/Y H:i:s', time()) . ' ]' . "\n";
        foreach( $this->getErrors() as $error) {
            $text .= (isset($error['t']) ? $error['t'].': ' : '') .  $error['m'] . "\n";
        }

        return $text;
    }
}