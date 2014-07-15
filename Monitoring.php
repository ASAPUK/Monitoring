<?php

namespace Monitoring;

class Monitoring
{
    /**
     * @param array $config
     */
    public function __construct( $config = array() )
    {
        $this->_config = $config;
        $this->monitoring();
    }

    /**
     * @throws MonitoringException
     */
    public function monitoring()
    {
        if (!isset($this->_config['handlers'])) {
            throw new MonitoringException('Should set `handler` config');
        }

        if (!isset($this->_config['states'])) {
            throw new MonitoringException('Should set `states` config');
        }

        $handler   = new AlertHandlers( $this->_config['handlers'] );
        $stateList = new AlertStates( $handler, $this->_config['states'] );

        $stateList->verifyError();
    }
}