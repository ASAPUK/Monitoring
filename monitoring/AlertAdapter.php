<?php

namespace Monitoring;


class AlertAdapter implements AdapterInterface
{
    private $_alert;

    public function __construct()
    {
        $this->_alert = new Alerts();
    }





}