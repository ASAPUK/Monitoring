<?php

namespace Monitoring\Handler;

/**
 * Class HandlerAbstract
 * @package Monitoring\Handler
 */
abstract class HandlerAbstract implements HandlerInterface
{
    protected $_errors;
    protected $_params = array();
    protected $_default = array();
    protected $_handlers;

    public function __construct($params = array())
    {
        $this->_params = $params;
    }

    /**
     * @param string $errorText
     * @param null $data
     */
    public function addErrorHandle($errorText, $data = null)
    {
        $hash = sha1($errorText);

        if ( !isset($this->_errors[$hash]) ){
            $this->_errors[$hash] = $errorText;
        }
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
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    abstract function handleErrors();
}