<?php session_start();
/**
*  Default Action
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/
    #
    ini_set('max_execution_time', 108000);
    ini_set('memory_limit', '1024M');
    set_time_limit(0);
    header('content-type: text/html; charset: utf-8');
    //sleep(2);
    error_reporting(E_ALL);
    ini_set('safe_mode','On');
    define("DIR_TEMPLATES", "ucp");
    define("DIR_CLASS", "class");
    require ("libs.php");
    
 ?>