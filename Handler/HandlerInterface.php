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
     * @param null $data
     */
    public function addErrorHandle($errorText, $data = null);

    /**
     * Errors working
     */
    public function handleErrors();
}