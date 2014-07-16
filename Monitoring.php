<?php

namespace Monitoring;

class Monitoring
{
    const HANDLERS = 'handlers';
    const STATES   = 'states';

    /**
     * @param array $config
     * @param string $handlerConfig
     * @param string $stateConfig
     */
    public function __construct( $config = array(), $handlerConfig = self::HANDLERS, $stateConfig = self::STATES )
    {
        $this->_config = $config;
        $this->monitoring($handlerConfig, $stateConfig);
    }

    /**
     * @param string $handlerConfig
     * @param string $stateConfig
     * @throws MonitoringException
     */
    public function monitoring( $handlerConfig = self::HANDLERS, $stateConfig = self::STATES )
    {
        if (!isset($this->_config[$handlerConfig])) {
            throw new MonitoringException('Should set `handler` config');
        }

        if (!isset($this->_config[$stateConfig])) {
            throw new MonitoringException('Should set `states` config');
        }

        $handler   = new AlertHandlers( $this->_config[$handlerConfig] );
        $stateList = new AlertStates( $handler, $this->_config[$stateConfig] );

        $stateList->verifyError();
    }
}