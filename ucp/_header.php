<?php 
    $clsUser = new User(); $assign_list["clsUser"] = $clsUser;
    #
    $me = $clsUser->getMe(); $assign_list["me"] = $me; 
    $user = $_SESSION['USER']; if(!$user) {$user = $_COOKIE['USER']; $_SESSION['USER'] = $user;}
    $pass = $_SESSION['PASS']; if(!$pass) {$pass = $_COOKIE['PASS']; $_SESSION['PASS'] = $pass;}
    
    if((!$clsUser->checkUser($user, $pass)) && !($mod=="user" && $act=="login")) {
        header('Location: '.PCMS_URL.'/admin.php?mod=user&act=login');
        exit();
    }
    $_SESSION['LOGIN_JQUERYVIDEO'] = 'DUC';
    if($mod=='news' && $act=='edit' && isset($_GET['id'])) {
        if($me['user_level_id']==1) {
            header('Location: '.PCMS_URL.'/admin.php?mod=news_ctv&act=edit&id='.$_GET['id']);
            die();
        }
        elseif($me['user_level_id']==2) {
            header('Location: '.PCMS_URL.'/admin.php?mod=news_btv&act=edit&id='.$_GET['id']);
            die();
        }
        elseif(!$me['user_level_id']) die('Bạn không được sửa bài viết này');
    }
    
    $listModule = $clsUser->getModule();
    $listAction = $clsUser->getAction();
    $listMenu = json_decode($me['permission']);
    if($listMenu) foreach($listMenu as $key=>$val) {
        $menu_top[] = array('mod'=>$key, 'title'=>$listModule[$key]);
    } else {
        foreach($listModule as $key=>$val) {
            $menu_top[] = array('mod'=>$key, 'title'=>$val);
        }
    }
    //if($_COOKIE['key']=='admin') {print_r($listMenu->{$mod}->{$act}); die();}
    if($listMenu && $listModule[$mod]!='' && $listAction[$act]!='') {
        if($listMenu->{$mod}->{$act} != 1) {
            if($act=='trash' || $act=='delete' || $act=='restore') die('Bạn chưa được phân quyền cho chức năng này :)');
            else {
                if($mod=='home' || $mod=='news') header('location: '.PCMS_URL.'/admin.php?mod='.$menu_top[0]['mod'].'&mes=welcome');
                else header('location: '.PCMS_URL.'/admin.php?mod='.$menu_top[0]['mod'].'&mes=lock');
            }
        }
    }
    
    $assign_list['menu_top'] = $menu_top;
    
    
    
?>