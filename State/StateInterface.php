<?php

namespace Monitoring\State;

use Monitoring\Handler\HandlerInterface;

interface StateInterface
{
    /**
     * @param HandlerInterface $handler
     * @param array $config
     */
    public function __construct( HandlerInterface $handler, $config = array() );

    /**
     * Verify event on errors
     */
    public function verifyError();

    /**
     * Name of state, for identity errors
     *
     * @return string
     */
    public function getStateType();
}