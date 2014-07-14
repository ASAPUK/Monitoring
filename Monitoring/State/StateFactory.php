<?php

namespace Monitoring\State;

use Monitoring\Handler\HandlerInterface;

class StateFactory
{
    static protected $_instance;

    protected function __construct()
    {}

    public static function getInstance()
    {
        if ( !isset(self::$_instance) ){
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function createStateByConfig( HandlerInterface $handler, $config = array() )
    {
        if ( isset($config['class']) ) {
            return $this->createState( $handler, $config['class'], (isset($config['params']) ? $config['params'] : array()) );
        }

        return false;
    }

    public function createState( HandlerInterface $handler, $name, $params = array() )
    {
        if ( class_exists($name) ) {
            $state = new $name( $handler, $params );
            return $state;
        }

        return false;
    }
}