<?php

namespace Monitoring\State;

class FeedLog extends StateAbstract
{
    const STATE_TYPE     = 'Feed Log Error';
    const PATH           = 'path';
    const IS_REMOVE_FILE = 'is_remove_file';

    protected $_default = array(
        self::IS_REMOVE_FILE => true
    );

    public function verifyError()
    {
        $path = $this->getParam(self::PATH);

        if ( !file_exists($path) ) {
            return;
        }

        $xml = simplexml_load_file( $path );
        if ( $xml->count() > 0 ) {

            // Remove duplicate errors
            $errors = array();
            foreach( $xml as $line ) {
                $errors[(string)$line->hash]['m'] = (string)$line->message;
                $errors[(string)$line->hash]['d'] = (string)$line->date;
            }

            // Send Message
            foreach ($errors as $error) {
                $this->getHandler()->addErrorHandle( "Feed log error: '{$error['m']}'", $error['d']  );
            }
        }

        if ($this->getParam(self::IS_REMOVE_FILE)) {
            unlink($path);
        }
    }
}