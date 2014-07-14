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
//require_once 'sdfsdf.ds';
$config = include 'config.php';

new Monitoring\Alerts($config);