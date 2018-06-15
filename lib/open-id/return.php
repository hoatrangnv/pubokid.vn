<?php
session_start();
include 'functions.php';



if (!empty($_GET['openid_ext1_value_firstname']) && !empty($_GET['openid_ext1_value_lastname']) && !empty($_GET['openid_ext1_value_email'])) {    
    $username = $_GET['openid_ext1_value_firstname'] .' '. $_GET['openid_ext1_value_lastname'];
    $email = $_GET['openid_ext1_value_email'];

    $user = new User();
    $userdata = $user->checkUserGoogle($uid, 'Google', $username, $email);
    if(!empty($userdata)) {
        $_SESSION['login-id'] = $userdata['profile_id'];
        $_SESSION['login-username'] = $userdata['username'];
        $_SESSION['login-email'] = $userdata['email'];
        $_SESSION['login-fullname'] = $userdata['fullname'];
        
        $return = isset($_SESSION["login-return"])? $_SESSION["login-return"] : URL_VIKICMS;
        header("Location: ".$return);

    } else {
        header('Location: '.URL_VIKICMS.'/login-false');
    }

}
?>
