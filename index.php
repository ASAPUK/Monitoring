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
function include_all_php($folder)
{
    foreach (glob("{$folder}/*.php") as $filename) {
        if ( file_exists($filename) ){
            dd($filename);
            include_once $filename;
        }
    }
}
dd(1);
include_all_php('monitoring');
dd(2);
include_all_php('monitoring/State');

include 'monitoring/State/StateAbstract.php';
/******************************************************/


$config = include 'config.php';
//
//$adapter   = new Monitoring\AlertAdapter();
//$stateList = new Monitoring\AlertStateList( $adapter, $config );
//
//$stateList->verifyError();