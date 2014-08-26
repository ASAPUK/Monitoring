<?php

namespace Monitoring\Handler;

interface HandlerInterface
{
    /**
     * @param array $params
     */
    public function __construct($params = array());

    /**
     * Add error to storage
     *
     * @param $errorText
     * @param string $trace
     * @param null|string $type - type of error
     */
    public function addErrorHandle($errorText, $trace = null, $type = null);

    /**
     * Errors working
     */
    public function handleErrors();
}