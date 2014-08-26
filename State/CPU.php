<?php

namespace Monitoring\State;

/**
 * Check CPU working
 *
 * Class CPU
 * @package Monitoring\State
 */
class CPU extends StateAbstract
{
    const STATE_TYPE         = 'CPU';
    const CPU_PROCESS_NUMBER = 'max_cpu_process_number';
    const TIME               = 'time';
    protected $_default = array(
        self::CPU_PROCESS_NUMBER => 80
    );

    public function verifyError()
    {
        $load = sys_getloadavg();
        $CPUProcessNumber = $this->getMaxCPUProcessNumbers();

        if ($load[$this->getTime()] > $CPUProcessNumber ) {
            $this->getHandler()->addErrorHandle(
                "Current CPU process number is {$load[$this->getTime()]}, when allowed is {$CPUProcessNumber}",
                '',
                $this->getStateType()
            );
        }
    }

    private function getMaxCPUProcessNumbers()
    {
        return $this->getParam( self::CPU_PROCESS_NUMBER );
    }

    private function getTime()
    {
        switch ($this->getParam( self::TIME )) {
            case 1:
                return 0;
                break;
            case 5:
                return 1;
                break;
            case 15:
                return 2;
                break;
            default:
                return 0;
        }
    }
}