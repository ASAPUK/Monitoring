<?php

namespace Monitoring\State;

use Monitoring\Handler\HandlerInterface;
use Monitoring\Singleton;

/**
 * Creating States
 *
 * Class StateFactory
 * @package Monitoring\State
 */
class StateFactory extends Singleton
{
    public function createByConfig( HandlerInterface $handler, $config = array() )
    {
        if ( isset($config['class']) ) {
            return $this->create( $handler, $config['class'], (isset($config['params']) ? $config['params'] : array()) );
        }

        return false;
    }

    public function create( HandlerInterface $handler, $name, $params = array() )
    {
        if ( class_exists($name) ) {
            $state = new $name( $handler, $params );
            return $state;
        }

        return false;
    }
}