<?php

namespace Monitoring\State;


class DiskSpace extends StateAbstract
{
    const DISK_SPACE = 'min_disk_space';

    const TYPE         = 'type';
    const TYPE_NUMBER  = 'number';
    const TYPE_PERCENT = 'percent';

    protected $_default = array(
        self::DISK_SPACE => 5,
        self::TYPE       => self::TYPE_PERCENT
    );

    public function verifyError()
    {
        $path         = $this->getParam(null, '/');
        $diskSpace    = disk_free_space ($path);
        $diskSpaceMin = $this->getMinDiskSpace();

        if ( $this->getParam( self::TYPE ) == self::TYPE_PERCENT ) {
            $totalSpace = disk_total_space($path);
            $diskSpace = round(100 * $diskSpace / $totalSpace , 2);
        }

        if ($diskSpace < $diskSpaceMin ) {
            if ( $this->getParam( self::TYPE ) == self::TYPE_PERCENT ) {
                $this->getHandler()->addErrorHandle( "Current Disk Space is {$diskSpace}%, when min allowed is {$diskSpaceMin}%" );
            } else {
                $this->getHandler()->addErrorHandle( "Current Disk Space is {$this->convertBytes($diskSpace)}, when min allowed is {$this->convertBytes($diskSpaceMin)}" );
            }
        }
    }

    private function getMinDiskSpace()
    {
        return $this->getParam( self::DISK_SPACE );
    }


    private function convertBytes( $bytes )
    {
        $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );

        $base = 1024;
        $class = min((int)log($bytes , $base) , count($si_prefix) - 1);

        return sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
    }
}