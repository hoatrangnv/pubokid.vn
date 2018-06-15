<?php
/**
*  Config DB
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/

    require '../../config.php';
    define("URL_VIKICMS", 	"http://".$_SERVER['HTTP_HOST']."/viki");
    define('USERS_TABLE_NAME', 'default_profile');
    
    $connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
    $database = mysql_select_db(DB_DATABASE) or die(mysql_error());
?>