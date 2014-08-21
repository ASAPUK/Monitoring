<?php

namespace Monitoring\Handler;

/**
 * Log errors to file
 *
 * Class Log
 * @package Monitoring\Handler
 */
class Log extends HandlerAbstract
{
    const PATH = 'path';
    protected $_default = array(
        self::PATH => 'monitoring.error.log'
    );

    public function handleErrors()
    {
        $path = $this->getParam(self::PATH);
        $this->createDirIfNotExist( $path );

        $text = $this->generateText();

        if ($text) {
            $fp = fopen($path, "a");
            fwrite($fp, $text);
            fclose($fp);
        }
    }

    private function createDirIfNotExist($file)
    {
        if( !file_exists(dirname($file)) ) {
            $arr  = explode('/', dirname($file));
            $curr = array();
            foreach($arr as $val){
                $arr  = explode('/', dirname($file));
                $curr = array();
                foreach($arr as $val){
                    $curr[] = $val;
                    $path = implode('/', $curr) . '/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777);
                        @chmod($path, 0777);
                    }
                }
            }
        }
    }

    private function generateText()
    {
        if (count($this->getErrors()) == 0) return null;

        $text = '[ ' . date('m/d/Y H:i:s', time()) . ' ]' . "\n";
        foreach( $this->getErrors() as $error) {
            $text .= (isset($error['t']) ? $error['t'].': ' : '') .  $error['m'] . "\n";
        }
        $text .= "\n";

        return $text;
    }
}