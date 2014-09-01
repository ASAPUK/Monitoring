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
     * @param CheckDuplicate $duplicate
     */
    public function __construct($config = array(), CheckDuplicate $duplicate = null)
    {
        $this->_config = $config;
        $this->_storage = new SplObjectStorage();
        $this->_duplicat = $duplicate;

        if ( count($config) > 0 ) {
            foreach ($config as $handlerConfig) {
                $handler = HandlerFactory::getInstance()->createByConfig( $handlerConfig );
                if ( $handler instanceof HandlerInterface ) {

                    if (isset($handlerConfig['params'])) {
                        $handlerConfig['params']['base_path'] = $config['base_path'];
                    } else {
                        $handlerConfig['params'] = array('base_path' => $config['base_path']);
                    }

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
                    $handler->handleErrors();
                }
            }
        }
    }

    /**
     * Delegate to each Handlers
     */
    public function addErrorHandle($errorText, $trace = null, $type = null)
    {
        if ( $this->count() > 0 && $this->isUniqueMessage($errorText, $type)) {
            foreach( $this->getStorage() as $handler) {
                if ($handler instanceof HandlerInterface) {
                    $handler->addErrorHandle($errorText, $trace, $type);
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

    public function isUniqueMessage($msg, $type)
    {
        if ($this->_duplicat != null) {
            return $this->_duplicat->check($msg, $type);
        }

        return true;
    }
}