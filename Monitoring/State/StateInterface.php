<?php

namespace Monitoring\State;

use Monitoring\Handler\HandlerInterface;

interface StateInterface
{
    public function __construct( HandlerInterface $handler, $config = array() );
    public function verifyError();
}