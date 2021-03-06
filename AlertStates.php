<?php

namespace Monitoring;

use Monitoring\State\StateInterface;
use Monitoring\State\StateAbstract;
use Monitoring\State\StateFactory;
use Monitoring\Handler\HandlerInterface;

/**
 * Collect States and delegate to each State evens
 *
 * Class AlertStateList
 * @package Monitoring
 */
class AlertStates extends StateAbstract
{
    /**
     * @var HandlerInterface
     */
    protected $_handler;
    /**
     * @var array of StateInterface
     */
    protected $_states = array();

    /**
     * @param HandlerInterface $handler
     * @param array $states
     * @param string $base_path
     */
    public function __construct( HandlerInterface $handler, $states = array(), $base_path = __DIR__ )
    {
        $this->_handler = $handler;

        if ( count($states) > 0 ) {
            foreach ($states as $stateConfig) {

                if (isset($stateConfig['params'])) {
                    $stateConfig['params']['base_path'] = $base_path;
                } else {
                    $stateConfig['params'] = array('base_path' => $base_path);
                }

                $state = StateFactory::getInstance()->createByConfig( $this->_handler, $stateConfig );
                if ( $state instanceof StateInterface ) {
                    $this->addState( $state );
                }
            }
        }
    }

    /**
     * Doing errors verify of each items
     */
    public function verifyError()
    {
        if ( $this->count() > 0 ) {
            foreach($this->getStates() as $state) {
                $state->verifyError();
            }
        }
    }

    /**
     * Calculate states count
     *
     * @return int
     */
    public function count()
    {
        return count($this->getStates());
    }

    /**
     * Return all states
     *
     * @return array
     */
    public function getStates()
    {
        return $this->_states;
    }


    /**
     * Add State
     *
     * @param StateInterface $state
     */
    public function addState( StateInterface $state )
    {
        if ( !$this->isState($state) ) {
            $this->_states[ get_class($state) ] = $state;
        }
    }

    /**
     * Remove state
     *
     * @param StateInterface $state
     */
    public function removeState( StateInterface $state )
    {
        if ( $this->isState($state) ) {
            unset( $this->_states[ get_class($state) ] );
        }
    }

    /**
     * Check if state set
     *
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