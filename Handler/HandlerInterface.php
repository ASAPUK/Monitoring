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
     * @param string $errorText
     * @param null|int $time - error time UNIX
     * @param null|string $type - type of error
     */
    public function addErrorHandle($errorText, $time = null, $type = null);

    /**
     * Errors working
     */
    public function handleErrors();
}