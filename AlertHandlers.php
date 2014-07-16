<?php

namespace Monitoring;

use Monitoring\Handler\HandlerAbstract;
use Monitoring\Handler\HandlerFactory;
use Monitoring\Handler\HandlerInterface;
use SplObjectStorage;

/**
 * Collect Handlers and delegate to each Handler evens
 *
 * Class AlertHandlers
 * @package Monitoring
 */
class AlertHandlers extends HandlerAbstract
{
    /**
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->_config = $config;
        $this->_storage = new SplObjectStorage();

        if ( count($config) > 0 ) {
            foreach ($config as $handlerConfig) {
                $handler = HandlerFactory::getInstance()->createByConfig( $handlerConfig );
                if ( $handler instanceof HandlerInterface ) {
                    echo "Add handler" . get_class($handler). "\n";
                    $this->attach( $handler );
                }
            }
        }
    }

    /**
     * Delegate to SplObjectStorage
     *
     * @param $methodName
     * @param $params
     * @return mixed
     */
    public function __call( $methodName, $params )
    {
        if ( method_exists($this->getStorage() , $methodName) ) {
            return call_user_func_array( array($this->getStorage(), $methodName), $params );
        }
        return null;
    }

    public function __destruct()
    {
        $this->handleErrors();
    }

    /**
     * Delegate to each Handlers
     */
    public function handleErrors()
    {
        if ( $this->count() > 0 ) {
            foreach( $this->getStorage() as $handler) {
                if ($handler instanceof HandlerInterface) {
                    echo "Handle error" . get_class($handler). "\n";
                    $handler->handleErrors();
                }
            }
        }
    }

    /**
     * Delegate to each Handlers
     */
    public function addErrorHandle($errorText, $data = null)
    {
        if ( $this->count() > 0 ) {
            foreach( $this->getStorage() as $handler) {
                if ($handler instanceof HandlerInterface) {
                    $handler->addErrorHandle($errorText, $data);
                }
            }
        }
    }

    /**
     * @return SplObjectStorage
     */
    public function getStorage()
    {
        return $this->_storage;
    }

    /**
     * Delegate to SplObjectStorage->attach()
     *
     * @param HandlerInterface $handler
     * @param null $data
     */
    public function attach( HandlerInterface $handler, $data = null )
    {
        $this->getStorage()->attach($handler, $data);
    }

    /**
     * Delegate to SplObjectStorage->detach()
     *
     * @param HandlerInterface $handler
     */
    public function detach( HandlerInterface $handler )
    {
        $this->getStorage()->detach($handler);
    }
}