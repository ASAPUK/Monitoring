<?php

namespace Monitoring;

use Monitoring\Handler\HandlerAbstract;

class AlertHandler extends HandlerAbstract
{
    protected $_handlers;

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function __destruct()
    {
        $this->handleErrors();
    }

    public function handleErrors()
    {
        d($this->_errors);
    }
}