<?php

/******************************************************/
function d($a = null){
    dd($a);
    die;
}
function dd($a = null){
    echo '<pre>';
    var_dump($a);
    echo '</pre>';
}
function __autoload($class)
{
    $parts = explode('\\', $class);
    require implode('/', $parts) . '.php';
}
/******************************************************/
$config = include 'config.php';

try{
    new Monitoring\Monitoring($config);
} catch (\Monitoring\MonitoringException $e) {
    print_r( $e->getMessage() );
    die;
}