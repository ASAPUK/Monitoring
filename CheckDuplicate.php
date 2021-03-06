<?php

namespace Monitoring;

class CheckDuplicate
{
    private $_xml;
    private $_config;
    private $_def_time = 3600;

    public function  __construct( $config )
    {
        $this->_config = $config;

        // Create XML file if not exist
        $filePath = $this->getPath();
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

        $this->_xml = simplexml_load_file( $filePath );
    }

    public function saveFile()
    {
        $filePath = $this->getPath();
        $this->_xml->asXML($filePath);
        @chmod($filePath, 0777);

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

        if (isset($xml['error']) && is_array($xml['error']) && in_array($hash, $xml['error'])) {
            return false;
        }

        $this->_xml->error[]= $hash;
        return true;
    }

    public function checkByType($type)
    {
        if ( (!isset($this->_xml->{$type})) || (time() - $this->_xml->{$type} > $this->getTime($type)) ) {
            $this->_xml->{$type} = time();
            return true;
        }

        return false;
    }

    public function getPath()
    {
        return $this->_config['base_path'] . $this->_config['path'];
    }

    private function getTime($type)
    {
        return isset($this->_config['type'][$type]['time']) ? $this->_config['type'][$type]['time'] : $this->_def_time;
    }
}