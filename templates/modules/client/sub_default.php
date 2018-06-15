<?php
/**
*  Defautl action
*  @author		: Nguyễn Văn Đức
*  @date		: 2015/01/13	
*  @version		: 0.0.1
*/
function default_register(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track, $image;
	#
    /*=============Content Page==================*/
    #
    $clsClient = new Client();
    if($_POST) {
        unset($_POST['user_pass_mask']);
        $userpass_current = $_POST['user_pass'];
        $_POST['birthday'] = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
        unset($_POST['year']);unset($_POST['month']);unset($_POST['day']);
        $_POST['token_key'] = md5($_POST['email']);
        $_POST['user_pass'] = md5($_POST['user_pass']);
        $_POST['reg_date'] = time();
        
        
        $err= array();
        $exist_email = $clsClient->is_exits_email(trim($_POST["email"]));
        if($exist_email) $err[] = $_POST["email"].' đã được đăng ký';
        
        $exist_mobile = $clsClient->is_exits_mobile(trim($_POST["mobile"]));
        if($exist_mobile) $err[] = $_POST["mobile"].' đã được đăng ký';        
        $assign_list['err'] = $err;
        
        if(!$err) {
            if($clsClient->insertOne($_POST)) {
                $clsClient->login($_POST['email'],$userpass_current);
                header('Location: '.PCMS_URL);
            } else {
                $mess = 'Có lỗi trong quá trình đăng ký';$assign_list['mess'] = $mess;
            }
        }
       
    }
    #   
}
function default_login(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track, $image;
	#
    /*=============Content Page==================*/
    #
    $clsClient = new Client();
    if($_POST) {
        if($clsClient->login($_POST['email'],$_POST['user_pass'])) {
            header('Location:/');
        }
        else {
            $mess = 'Thông tin đăng nhập không đúng';
            $assign_list['mess'] = $mess;
        }
    }
    #   
}
function default_login_facebook(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track, $image;
	#
    /*=============Content Page==================*/
    $clsClient = new Client;
    $clsClient->deleteArrKey();
    $profileFb = $clsClient->getUserFacebook();

    if($profileFb) {
        $email = $profileFb['profile']['email'];
        $access_token = $profileFb['accessToken'];
        
        if($client_id = $clsClient->checkEmail($email)) {
            if(!$clsClient->checkToken_fb($access_token)) {
                $clsClient->updateOne($client_id,array("token_fb"=>$access_token));
                $clsClient->login3($email);
                //$_SESSION['redirect_mypage'] = '1'; 
                //header("Location: ".PCMS_URL."/mypage.html");
            } else {
                $clsClient->login3($email);
                //header("Location: ".PCMS_URL."/mypage.html");
                //$_SESSION['redirect_mypage'] = '1';
            }
        } else {
            $array['fb_id'] = $profileFb['profile']['id'];
            $array['email'] = $profileFb['profile']['email'];
            $array['email_mask'] = $profileFb['profile']['email'];

            if($profileFb['profile']['gender'] == 'male') $array['gender'] = 1; else $array['gender'] = 2;
            $array["token_fb"] = $access_token;
            if($profileFb['profile']['birthday']) {
                $array["birthday"] = date("Y-m-d",strtotime($profileFb['profile']['birthday']));
            }
            
            //$_SESSION['dangkyfacebook'] = $array;

            //header("Location: ".PCMS_URL."/complete_register.html");
        }
    }  
}
function default_logout() {
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track;
    #
    unset($_SESSION['EMAIL_CLIENT']);
    die('');
}
function default_forget(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $ads, $canonical, $google_track, $image;
	#
    /*=============Content Page==================*/
    #
    $clsClient = new Client();
    #   
    if($_POST['user_name_r'] && !$_POST['user_pass_r']) {
            $user_name = $_POST['user_name_r'];
            $clsClient = new Client;
            $all = $clsClient->getAll('is_trash = 0 and email="'.$user_name.'" limit 1');
            if($all[0]) {        
                $one = $clsClient->getOne($all[0]);    
                
                $header  .= 'MIME-Version: 1.0' . "\r\n";
                $header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                
                
                $message = $one['full_name'].' thân mến,<br><br>';
                $message .= 'Nhấp vào link dưới đây để hoàn thành quá trình thay đổi mật khẩu<br>';
                $message .= '<a href="'.PCMS_URL.'reset_pass_'.$one['token_key'].'">'.PCMS_URL.'reset_pass_'.$one['token_key'].'.html</a><br>';
                
                $to = $one["email"];  //khai báo địa chỉ mail người nhận
                $subject = 'Quên mật khẩu chotoc.vn '; // chủ đề của mail
                
                $mail_sent = mail($to,$subject,$message,$header);
                $message = 'Email xác nhận thay đổi mật khẩu đã được gửi tới bạn, vui lòng xác nhận thông tin để thay đổi mật khẩu';
                $assign_list['mess'] = $mess;
            } else {
                $mess = 'Không tồn tại Email này';
                $assign_list['mess'] = $mess;
        }
    } else if($_POST['user_name_r'] && $_POST['user_pass_r'] && $_GET['token_key']) {
            $user_name = $_POST['user_name_r'];
            $clsClient = new Client;
            $all = $clsClient->getAll('is_trash = 0 and email="'.$user_name.'" and token_key="'.$_GET['token_key'].'" limit 1');    
            if($all) {
                $one = $clsClient->getOne($all[0]);    
                $pass = $_POST['user_pass_r'];
                $array['user_pass'] = md5($pass);
                
                $clsClient->updateOne($one['client_id'],$array);
                $clsClient->deleteArrKey();
                $clsClient->login($_POST["user_name_r"],$pass);
                header('location: '.PCMS_URL);
            } else {
                $mess = 'Không tồn tại Email này';
                $assign_list['mess'] = $mess;
            }
    }
    
}
?>