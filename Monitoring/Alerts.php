<?php

namespace Monitoring;

use Monitoring\AlertHandler;
use Monitoring\AlertStateList;

class Alerts
{
    public function __construct( $config = array() )
    {
        $this->_config = $config;
        $this->monitoring();
    }

    public function monitoring()
    {
        $handler   = new AlertHandler( $this->_config['handlers'] );
        $stateList = new AlertStateList( $handler, $this->_config['states'] );
        $stateList->verifyError();



    }
}