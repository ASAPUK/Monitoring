<?php

namespace Monitoring;

class CheckDuplicate
{
    private $_xml;
    private $_config;

    public function  __construct( $config )
    {
        // Create XML file if not exist
        $filePath = $config['path'];
        if( !file_exists($filePath) ) {
            if( !file_exists(dirname($filePath)) ) {
                mkdir(dirname($filePath), 0777, true);
            }

            $xml = new DOMDocument();

            $error = $xml->createElement("errors");
            $xml->appendChild($error);

            $xml->formatOutput = true;
            $xml->save($filePath);
        }
        $this->_config = $config;
        $this->_xml = $xml = simplexml_load_file( $filePath );
    }

    public function check($msg, $type = null)
    {dd($type);
        if ($type == null) {
            return $this->checkByMsg($msg);
        } else {
            return$this->checkByType($type);
        }
    }

    public function checkByMsg($msg)
    {
        return true;
    }

    public function checkByType($type)
    {
        return true;
    }
}