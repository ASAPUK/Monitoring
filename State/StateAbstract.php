<?php

namespace Monitoring\State;

use Monitoring\Handler\HandlerInterface;


abstract class StateAbstract implements StateInterface
{
    /**
     * @var HandlerInterface
     */
    protected $_handler;
    /**
     * @var array
     */
    protected $_params;
    /**
     * @var array
     */
    protected $_default = array();

    /**
     * @param HandlerInterface $handler
     * @param array $params
     */
    public function __construct( HandlerInterface $handler, $params = array() )
    {
        $this->_handler = $handler;
        $this->_params  = $params;
    }

    /**
     * Return params: config + default
     *
     * @return array
     */
    public function getParams()
    {
        return array_merge($this->_default, $this->_params);
    }

    /**
     * Get Param by name. Return default if isset
     *
     * @param $name
     * @param null $default
     * @return null
     */
    public function getParam($name, $default = null)
    {
        $params = $this->getParams();
        return isset($params[$name]) ? $params[$name] : $default;
    }

    /**
     * @return HandlerInterface
     */
    public function getHandler()
    {
        return $this->_handler;
    }

    /**
     * Check if const defined
     *
     * @param string $constantName
     * @return bool
     */
    public function defined( $constantName )
    {
        return defined(sprintf('self::%s', $constantName));
    }

    /**
     * @return string
     */
    public function getStateType()
    {
        if ($this->defined('STATE_TYPE')) {
            return $this::STATE_TYPE;
        }

        return get_class($this);
    }

    abstract public function verifyError();
}