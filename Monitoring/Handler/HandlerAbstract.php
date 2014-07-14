<?php

namespace Monitoring\Handler;

abstract class HandlerAbstract implements HandlerInterface
{
    protected $_errors;
    protected $_config;

    public function __construct($config = array())
    {
        $this->_config = $config;
    }

    public function addErrorHandle($errorText, $data = null)
    {
        $hash = sha1($errorText);

        if ( !isset($this->_errors[$hash]) ){
            $this->_errors[$hash] = $errorText;
        }
    }

    abstract function handleErrors();
}