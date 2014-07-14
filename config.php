<?php


return array(
    'handlers'  => array(
        array(
            'class'  => 'Monitoring\Handler\Log',
            'params' => array(

            )
        ),
        array(
            'class'  => 'Monitoring\Handler\SendEmail',
            'params' => array(

            )
        ),
    ),

    'states'    => array(
        array(
            'class'  => 'Monitoring\State\CPU',
            'params' => array(
                'max_cpu_process_number' => 5
            )
        ),

        array(
            'class'  => 'Monitoring\State\Memory',
            'params' => array(
                'max_memory_usage'  => 95,
                'min_memory_free'   => 10,
                'type'              => 'percent'
            )
        ),

        array(
            'class'  => 'Monitoring\State\DiskSpace',
            'params' => array(
                'min_disk_space' => 95, // 2 Gb 16770000007216
                'type'           => 'percent'
            )
        ),

        array(
            'class'  => 'Monitoring\State\FilePermission',
            'params' => array(
                'files'     => array(
                    array(
                        'path'      => __DIR__ . '/index.php',
                        'perms'     => '664'
                    ),
                    array(
                        'path'      => __DIR__ . '/test.php',
                        'perms'     => '-rw-rw-r--'
                    ),
                    array(
                        'path'      => '/home/sergey/projects/logs/Monitoring/error.log',
                        'perms'     => '-rw-rw-r--'
                    ),
                )
            )

        ),

        array(
            'class'  => 'Monitoring\State\ApacheLog',
            'params'    => array(
                'path'          => '/home/sergey/projects/logs/Monitoring/error.log',
                'count'         => 100,
                'time'          => 300000,
                'type'          => array('error', 'info')
            )
        ),

        array(
            'class'  => 'Monitoring\State\MysqlLog',
            'params' => array(
                'path'  => '/var/log/mysql/error.log',
            )
        )
    )
);