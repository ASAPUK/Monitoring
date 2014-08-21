<?php

namespace Monitoring\Handler;

/**
 * Log errors to file
 *
 * Class Log
 * @package Monitoring\Handler
 */
class ConsoleLog extends HandlerAbstract
{

    public function handleErrors()
    {
        if (count($this->getErrors()) == 0) {
            echo 'No new errors' . "\n";
            return;
        }

        echo '[ ' . date('m/d/Y H:i:s', time()) . ' ]' . "\n";
        echo 'Errors List: ' . "\n";
        foreach( $this->getErrors() as $error) {
            echo (isset($error['t']) ? $error['t'].': ' : '') .  $error['m'] . "\n";
        }
        echo "\n";
    }
}