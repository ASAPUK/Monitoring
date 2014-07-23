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

    protected $_default = array(
        self::STATUS => array('error')
    );

    public function verifyError()
    {
        $path = $this->getParam(self::PATH, null);
        $allowedStatuses = $this->getParam(self::STATUS);

        if (!file_exists($path)) return;
        $log = file_get_contents($path);
        $log = explode('.-==-.', $log);
        $log = array_reverse($log);

        foreach ($log as $l) {
            $status = $this->showStatus($l);

            if (in_array($status, $allowedStatuses)) {
                $error = $this->showError($l);
                $date  = strtotime($this->showDate($l));

                $this->getHandler()->addErrorHandle( $error, $date, $this->getStateType() );
            }
        }
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