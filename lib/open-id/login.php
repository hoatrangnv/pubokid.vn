<?php
// importing required files
require 'functions.php';
require 'openid.php';
// callback URL
define('CALLBACK_URL', URL_VIKICMS.'/lib/open-id/return.php');

// calling login functions
if($_SESSION['login-id']!='') {
    $return = isset($_SESSION["login-return"])? $_SESSION["login-return"] : URL_VIKICMS;
    header('Location: '.$return);
} else gooleAuthenticate();

function gooleAuthenticate() {
    // Creating new instance
    $openid = new LightOpenID;
    $openid->identity = 'https://www.google.com/accounts/o8/id';
    //setting call back url
    $openid->returnUrl = CALLBACK_URL;
    //finding open id end point from google
    $endpoint = $openid->discover('https://www.google.com/accounts/o8/id');
    $fields =
            '?openid.ns=' . urlencode('http://specs.openid.net/auth/2.0') .
            '&openid.return_to=' . urlencode($openid->returnUrl) .
            '&openid.claimed_id=' . urlencode('http://specs.openid.net/auth/2.0/identifier_select') .
            '&openid.identity=' . urlencode('http://specs.openid.net/auth/2.0/identifier_select') .
            '&openid.mode=' . urlencode('checkid_setup') .
            '&openid.ns.ax=' . urlencode('http://openid.net/srv/ax/1.0') .
            '&openid.ax.mode=' . urlencode('fetch_request') .
            '&openid.ax.required=' . urlencode('email,firstname,lastname') .
            '&openid.ax.type.firstname=' . urlencode('http://axschema.org/namePerson/first') .
            '&openid.ax.type.lastname=' . urlencode('http://axschema.org/namePerson/last') .
            '&openid.ax.type.email=' . urlencode('http://axschema.org/contact/email');
    header('Location: ' . $endpoint . $fields);
}

?>
