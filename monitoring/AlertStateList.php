<?php

namespace Monitoring;

use Monitoring\State\StateInterface;
use Monitoring\State\StateAbstract;


class AlertStateList extends StateAbstract
{
    private $_adapter;
    private $_states;

    public function __construct( StateAbstract $adapter, $config = array() )
    {
        $this->_adapter = $adapter;

        if ( isset($config['states']) && count($config['states']) > 0 ) {
            foreach ($config['states'] as $stateConfig) {
                $state = StateFactory::getInstance()->getStateByConfig( $stateConfig );
                if ( $state instanceof StateInterface ) {
                    $this->addState( $state );
                }
            }
        }
    }

    public function verifyError()
    {

    }

    public function sendError()
    {

    }

    public function addState( StateInterface $state )
    {
        if ( !$this->isState($state) ) {
            $this->_states[ get_class($state) ] = $state;
        }
    }

    public function removeState( StateInterface $state )
    {
        if ( $this->isState($state) ) {
            unset( $this->_states[ get_class($state) ] );
        }
    }

    /**
     * @param {StateInterface|string} $state
     * @return bool
     */
    public function isState( $state )
    {
        if ($state instanceof StateInterface) {
            $state = get_class($state);
        }

        return isset( $this->_states[ $state ] );
    }
}