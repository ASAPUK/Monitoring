<?php

namespace Monitoring;

/**
 * Pattern Singleton
 *
 * Class Singleton
 * @package Monitoring
 */
class Singleton
{
    protected final function __construct()
    {}

    public static function getInstance(){
        static $instance;

        if( !isset($instance) ) {
            $instance = new static();
        }

        return $instance;
    }
}