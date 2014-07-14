<?php

namespace Monitoring\Handler;

interface HandlerInterface
{
    public function __construct($config = array());
    public function addErrorHandle($errorText, $data = null);
    public function handleErrors();
}