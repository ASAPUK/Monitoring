<?php

namespace Monitoring;

class CheckDuplicate
{
    private $_xml;
    private $_config;
    private $_def_time = 3600;

    public function  __construct( $config )
    {
        // Create XML file if not exist
        $filePath = $config['path'];
        if( !file_exists($filePath) ) {

            $arr  = explode('/', dirname($filePath));
            $curr = array();
            foreach($arr as $val){
                $curr[] = $val;
                $path = implode('/', $curr) . '/';
                if (!file_exists($path)) {
                    mkdir($path, 0777);
                    @chmod($path, 0777);
                }
            }

            $xml = new \DOMDocument();

            $error = $xml->createElement("errors");
            $xml->appendChild($error);

            $xml->formatOutput = true;
            $xml->save($filePath);

            @chmod($filePath, 0777);
        }

        $this->_config = $config;
        $this->_xml = simplexml_load_file( $filePath );
    }

    public function saveFile()
    {
        $this->_xml->asXML($this->_config['path']);
    }

    public function check($msg, $type = null)
    {
        if ($type == null || !in_array($type, array_keys($this->_config['type']))) {
            return $this->checkByMsg($msg);
        } else {
            return $this->checkByType($type);
        }
    }

    public function checkByMsg($msg)
    {
        $xml    = (array)$this->_xml;
        $hash   = sha1($msg);

        if (isset($xml['error']) && !in_array($hash, $xml['error'])) {
            $this->_xml->error[]= $hash;
            return true;
        }

        return false;
    }

    public function checkByType($type)
    {
        if ( (!isset($this->_xml->{$type})) || (time() - $this->_xml->{$type} > $this->getTime($type)) ) {
            $this->_xml->{$type} = time();
            return true;
        }

        return false;
    }

    private function getTime($type)
    {
        return isset($this->_config['type'][$type]['time']) ? $this->_config['type'][$type]['time'] : $this->_def_time;
    }
}