<?php

namespace Monitoring\State;


class FilePermission extends StateAbstract
{
    const FILES = 'files';
    const FILE_PATH  = 'path';
    const FILE_PERMS = 'perms';

    public function verifyError()
    {
        $files = $this->getFiles();
        if ( is_array($files) && count($files) ) {
            foreach( $files as $file ) {
                if ( isset($file[self::FILE_PATH]) && isset($file[self::FILE_PERMS]) ) {
                    if (!file_exists($file[self::FILE_PATH])) {
                        $this->getHandler()->addErrorHandle( "File {$file[self::FILE_PATH]}, was not found" );
                        continue;
                    }

                    $perms = fileperms( $file[self::FILE_PATH] );
                    $permsFull   = $this->convertPerms($perms);
                    $permsDecoct = substr( decoct($perms), 3 );

                    if ($permsFull == $file[self::FILE_PERMS] || $permsDecoct == $file[self::FILE_PERMS]) {
                        continue;
                    } else {
                        $this->getHandler()->addErrorHandle( "Do not match permissions on file '{$file[self::FILE_PATH]}'. Current is '{$permsDecoct}', but should be '{$file[self::FILE_PERMS]}'" );
                    }
                }
            }
        }
    }

    private function convertPerms( $perms )
    {
        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));

        return $info;
    }

    private function getFiles()
    {
        return $this->getParam( self::FILES, array() );
    }
}