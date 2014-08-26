<?php

namespace Monitoring\State;

/**
 * Check Yii Application log on errors
 *
 * Class YiiApplicationLog
 * @package Monitoring\State
 */
class YiiApplicationLog extends StateAbstract
{
    const STATE_TYPE = 'YiiApplication';
    const PATH       = 'path';
    const STATUS     = 'status';
    const COUNT      = 'count';
    const TIME       = 'time';

    protected $_default = array(
        self::STATUS => array('error'),
        self::COUNT  => 200,
        self::TIME   => 3600
    );

    public function verifyError()
    {
        $path = $this->getParam(self::PATH, null);
        $allowedStatuses = $this->getParam(self::STATUS);

        if (!file_exists($path)) return;
        exec("tail -n {$this->getParam(self::COUNT)} {$path}", $tail);

        if ( count($tail) == 0 ) return;

        $log = $buffer = array();
        foreach (array_reverse($tail) as $str) {
            $buffer[] = $str;
            if ( preg_match('#\d{4}/\d{2}/\d{2}#', $str) ) {
                $log[] = implode("\r\n", array_reverse($buffer));
                $buffer = array();
            }
        }

        $allowedTime = $this->getParam(self::TIME);
        foreach ($log as $l) {
            $status = $this->showStatus($l);
            $date   = strtotime($this->showDate($l));

            if ( in_array($status, $allowedStatuses) && (time() - $date < $allowedTime) ) {
                $error  = $this->showError($l);

                $this->getHandler()->addErrorHandle( $error, $this->showStack($l), $this->getStateType() );
            }
        }
    }

    private function showStack($text)
    {
        $text = explode('Stack trace:', $text);
        return @$text[1];
    }

    private function showDate($text)
    {
        return date('H:i d.m.Y', strtotime(mb_substr($text, 0, 20,'utf8')));
    }

    private function showError($text)
    {
        $text = mb_substr($text, 20, mb_strlen($text,'utf8'),'utf8');

        $text = explode('Stack trace:', $text);
        $text = $text[0];

        return $text;
    }

    private function showStatus($text)
    {
        if (preg_match('%\[error\]%',$text)) {
            return 'error';
        } elseif (preg_match('%\[warning\]%',$text)) {
            return 'warning';
        } elseif (preg_match('%\[info\]%',$text)) {
            return 'info';
        }else {
            return 'undefined';
        }
    }
}