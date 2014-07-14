<?php

namespace Monitoring\State;


class ApacheLog extends StateAbstract
{
    const PATH  = 'path';
    const COUNT = 'count';
    const TIME  = 'time';
    const TYPE  = 'type';

    protected $_default = array(
        self::COUNT => 100,
        self::TIME  => 3600,
        self::TYPE  => array('error')
    );

    public function verifyError()
    {
        $apacheLogFilePath = $this->getApacheLogPath();
        if ( !file_exists($apacheLogFilePath) ) {
            $this->getHandler()->addErrorHandle( "Apache error.log was not found in '{$apacheLogFilePath}'" );
            return;
        }

        exec("tail -n {$this->getParam(self::COUNT)} {$apacheLogFilePath}", $output);

        if (count($output) > 0) {
            foreach($output as $line) {

                $pattern = '#^\[(.*)\]\s+\[(.*)\]\s+\[client (.*)\](.*?)#U';
                preg_match($pattern, $line, $match);
                if(count($match) != 5) continue;

                $line = array(
                    'data' => strtotime($match[1]),
                    'type' => $match[2],
                    'ip'   => $match[3],
                    'text' => $match[4]
                );

                $type = $this->getParam(self::TYPE);
                if (is_array($type) && in_array($line['type'], $type) && time() - $line['data'] < $this->getParam(self::TIME)) {
                    $this->getHandler()->addErrorHandle( "Apache log {$line['type']}: '{$line['text']}'", $line['data'] );
                }
            }
        }
    }

    private function getApacheLogPath()
    {
        return $this->getParam(self::PATH, null);
    }
}