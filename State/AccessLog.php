<?php

namespace Monitoring\State;

/**
 * Check apache log on errors
 *
 * Class ApacheLog
 * @package Monitoring\State
 */
class ApacheLog extends StateAbstract
{
    const STATE_TYPE  = 'Apache';
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

        $parser = new \Kassner\LogParser\LogParser();
        $lines = file($apacheLogFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $parser->setFormat('%h %l %u %t "%r" %>s %O "%{Referer}i" \"%{User-Agent}i"');
        foreach ($lines as $line) {
            $entry = $parser->parse($line);
            dd($entry);
        }


        if ( !file_exists($apacheLogFilePath) ) {
            $this->getHandler()->addErrorHandle(
                "error.log was not found in '{$apacheLogFilePath}'",
                time(),
                $this->getStateType()
            );
            return;
        }

        exec("tail -n {$this->getParam(self::COUNT)} {$apacheLogFilePath}", $output);

        if (count($output) > 0) {
            foreach($output as $line) {

                $pattern = '#^\[(.*)\]\s+\[(.*)\]\s+\[(.*)\](.*?)#U';
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
                    $this->getHandler()->addErrorHandle( "{$line['text']}", $line['data'], $this->getStateType() );
                }
            }
        }
    }

    private function getApacheLogPath()
    {
        return $this->getParam(self::PATH, null);
    }
}