<?php
    session_start();
    if($_SESSION['LOGIN_JQUERYVIDEO']=='DUC') require('index.htm'); else {
        die('Ban khong co quyen vao modules nay');
    }
//    require('index.htm');
?>
