<?php

namespace Monitoring\Handler;

use Monitoring\Singleton;

/**
 * Creating Handlers
 *
 * Class HandlerFactory
 * @package Monitoring\Handler
 */
class HandlerFactory extends Singleton
{
    public function createByConfig( $config = array() )
    {
        if ( isset($config['class']) ) {
            return $this->create( $config['class'], (isset($config['params']) ? $config['params'] : array()) );
        }

        return false;
    }

    public function create( $name, $params = array() )
    {
        if ( class_exists($name) ) {
            $object = new $name( $params );
            return $object;
        }

        return false;
    }
}