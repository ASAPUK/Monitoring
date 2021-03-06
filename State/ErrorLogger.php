<?php

namespace Monitoring\State;

use Monitoring\ErrorLogger as MErrorLogger;

/**
 * Class ErrorLogger
 * @package Monitoring\State
 */
class ErrorLogger extends StateAbstract
{
    const STATE_TYPE = 'Error';
    const PATH = 'path';

    public function verifyError()
    {
        $logger = MErrorLogger::getInstance();
        $config = $this->getParams();
        $config['path']  = $this->getBasePath() . $config['path'];
        $logger->setConfig($config);

        $errors = $logger->getErrors();

        if ( count($errors) > 0 ) {
            foreach ($errors as $error) {
                $this->getHandler()->addErrorHandle(
                    $logger->parseMessage($error),
                    $logger->parseTrace($error),
                    $this->getStateType()
                );
            }
        }

        $logger->clearFile();
    }
}