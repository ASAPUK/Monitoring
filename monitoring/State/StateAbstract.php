<?php

namespace Monitoring\State;

use Monitoring\AdapterInterface;


abstract class StateAbstract implements StateInterface
{
    private $_adapter;

    public function __construct( AdapterInterface $adapter )
    {
        $this->_adapter = $adapter;
    }

    public function sendError()
    {

    }

    public function verifyError()
    {

    }
}