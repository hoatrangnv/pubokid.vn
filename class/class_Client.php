<?php
/**
*  Created by   :
*  @author		: Ong The Thanh
*  @date		: 2012/01/23
*  @version		: 0.0.1
*/ 
class Client extends dbBasic{
	function Client(){
		$this->pkey = "client_id";
		$this->tbl = DB_PREFIX."client";
	}
    function getMe(){
        $email = $_SESSION['EMAILCLIENT']; if(!$email) {$email = $_COOKIE['EMAILCLIENT']; $_SESSION['EMAILCLIENT'] = $user;}
        $all = $this->getAll("is_trash=0 and is_blacklist = 0 and email='".addslashes($email)."'");
        return $this->getOne($all[0]);
	}
	function checkUser($email, $user_pass){
        $all = $this->getAll("is_trash=0 and is_blacklist = 0 and email='".addslashes($email)."'");
        $one = $all[0]; $one=$this->getOne($one);
        if($one['password']==$user_pass && $one['password']!='') return true;
        else return false;
	}
    function checkToLogin() {
        setcookie("EMAILCLIENT", $_SESSION['EMAILCLIENT'], time()+31536000);
        setcookie("PASSCLIENT", $_SESSION['PASSCLIENT'], time()+31536000);
        //if(!$this->checkUser($_SESSION['EMAILCLIENT'], $_SESSION['PASSCLIENT']))
        //header('location: admin.php?mod=user&act=login&u='.rawurlencode($core->selfURL()));
    }
    function login($email, $user_pass){
        $all = $this->getAll("is_trash=0 and is_blacklist = 0 and email='".addslashes($email)."'");
        $one = $all[0]; $one=$this->getOne($one);
        
        if($one['password']==md5($user_pass) && $one['password']!='') {
            $_SESSION['EMAILCLIENT'] = ($email);
            $_SESSION['PASSCLIENT'] = md5($user_pass);
            setcookie("EMAILCLIENT", $_SESSION['EMAILCLIENT'], time()+31536000);
            setcookie("PASSCLIENT", $_SESSION['PASSCLIENT'], time()+31536000);
            return true;
        }
        else return false;
	}
    function is_exits_user($email) {
        $all = $this->getAll("is_trash=0 and email='".addslashes($email)."'");
        if($all[0]) return true;
        else return false;
    }
    function getEmail($user_id) {
        $one = $this->getOne($user_id);
        return $one['email'];
    }
    function getFullName($user_id) {
        $one = $this->getOne($user_id);
        return $one['last_name'].' '.$one['first_name'];
    }
    function getUserFacebook() {
        require_once('lib/facebook/facebook.php');
        $facebook = new Facebook(array(
            'appId' => '1494625070860392',
            'secret' => '9849091b0399c7a4c3d290027ba1eba1',
        ));

        $user = $facebook->getUser();
        
        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile['profile'] = $facebook->api('/me');
                $user_profile['accessToken'] = $facebook->getAccessToken();
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }

            if (!empty($user_profile)) {
                # User info ok? Let's print it (Here we will be adding the login and registering routines)
                return $user_profile;
            } else {
                # For testing purposes, if there was an error, let's kill the script
                return false;
            }
        } else {
            # There's no active session, let's generate one
            $login_url = $facebook->getLoginUrl(array('scope' => 'public_profile,email,user_friends', 'redirect_uri' => PCMS_URL.'/?mod=user&act=login_facebook', 'display' => 'popup'));
            header("Location: " . $login_url);
        }

    }
}
?>