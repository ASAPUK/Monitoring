<?php

namespace Monitoring\State;

/**
 *
 * Check memory working
 *
 * Class Memory
 * @package Monitoring\State
 */
class Memory extends StateAbstract
{
    const STATE_TYPE   = 'Memory';
    const MEMORY_USAGE = 'max_memory_usage';
    const MEMORY_FREE  = 'min_memory_free';

    const TYPE         = 'type';
    const TYPE_NUMBER  = 'number';
    const TYPE_PERCENT = 'percent';

    protected $_default = array(
        self::MEMORY_USAGE => 100,
        self::MEMORY_FREE  => 0,
        self::TYPE         => self::TYPE_PERCENT
    );

    public function verifyError()
    {
        exec("free", $output);

        $titles = array_filter( explode(' ', $output[0]), 'strlen' );
        $values = array_filter( explode(' ', $output[1]), 'strlen' );

        unset($values[0]);

        $result = array_combine ( $titles , $values );

        $type = '';
        $used = $result['used'] - $result['buffers'] - $result['cached'];
        $free = $result['total'] - $used;
        if ( $this->getParam( self::TYPE ) == self::TYPE_PERCENT ) {
            $type = '%';
            $used = round(100 * $used / $result['total'], 2);
            $free = round(100 * $free / $result['total'], 2);
        }

        $memoryUsageMax = $this->getMaxMemoryUsage();
        $memoryFreeMin  = $this->getMinFreeMemory();

        if ( $used > $memoryUsageMax ) {
            $this->getHandler()->addErrorHandle(
                "Current Memory Usage is {$used}{$type}, when allowed is {$memoryUsageMax}{$type}",
                '',
                $this->getStateType()
            );
        }
        if ( $free < $memoryFreeMin ) {
            $this->getHandler()->addErrorHandle(
                "Current Memory Free is {$free}{$type}, when allowed is {$memoryFreeMin}{$type}",
                '',
                $this->getStateType()
            );
        }
    }

    private function getMaxMemoryUsage()
    {
        return $this->getParam(self::MEMORY_USAGE);
    }

    private function getMinFreeMemory()
    {
        return $this->getParam(self::MEMORY_FREE);
    }
}