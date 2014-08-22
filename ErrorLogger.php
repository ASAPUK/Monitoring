<?php

namespace Monitoring;

class ErrorLogger extends \Monitoring\Singleton
{
    const ENABLED = 'enabled';
    const PATH    = 'path';
    const DATE_FORMAT = 'date_format';

    const ERROR   = 'error';
    const WARNING = 'warning';

    const FORMAT = 'Y-m-d H:i:s';

    private $path;
    private $enabled = false;
    private $dateFormat = self::FORMAT;

    public function setConfig(array $config)
    {
        if ( isset($config[self::ENABLED]) && isset($config[self::PATH]) ) {
            $this->enabled = $config[self::ENABLED];
            $this->path = $config[self::PATH];

            if ( isset($config[self::DATE_FORMAT]) ) {
                $this->dateFormat = $config[self::DATE_FORMAT];
            }
        }
    }

    public function handleError($msg, $type = self::ERROR)
    {
        if ( !$this->getEnabled() ) return;

        $this->createPathIfNotExist();

        $error = array(
            'time'    => date($this->dateFormat),
            'message' => base64_encode($msg),
            'type'    => $type
        );
        $path = $this->getPath();

        $fp = fopen($path, 'a');
        fputcsv($fp, $error);
        fclose($fp);
    }

    private function createPathIfNotExist()
    {
        $path = $this->getPath();
        if( !file_exists(dirname($path)) ) {
            $arr  = explode('/', dirname($path));
            $curr = array();

            foreach($arr as $val){
                $curr[] = $val;
                $path = implode('/', $curr) . '/';
                if ( !file_exists($path) ) {
                    mkdir($path, 0766);
                    @chmod($path, 0766);
                }
            }
        }
    }

    public function clearFile()
    {
        if (!$this->getEnabled()) return;

        $fp = fopen($this->getPath(), 'w');
        fputcsv($fp, array());
        fclose($fp);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function getErrors()
    {
        return $this->getErrorsByType(array(self::ERROR));

    }

    public function getWarnings()
    {
        return $this->getErrorsByType(array(self::WARNING));

    }

    public function getAllErrors()
    {
        return $this->getErrorsByType();
    }

    private function getErrorsByType(array $types = array(self::ERROR, self::WARNING))
    {
        $result = array();
        if (!$this->getEnabled() || !file_exists($this->getPath())) return $result;

        $delimiter = ",";
        $file = new \SplFileObject($this->getPath(), 'r');
        $file->setFlags(\SplFileObject::READ_CSV);
        $file->setCsvControl($delimiter);

        while (!$file->eof()) {
            $current = $file->current();
            $current = array_filter($current);

            if ( !empty($current) && isset($current[2]) && in_array($current[2], $types) ) {
                $result[] = $current;
            }
            $file->next();
        }

        return $result;
    }

    public function parseMessage(array $error)
    {
        return isset($error[1]) ? base64_decode($error[1]) : null;
    }

    public function parseDate(array $error)
    {
        return isset($error[0]) ? $error[0] : null;
    }
}