<?php
    $classTable = 'News';
    $clsClassTable = new $classTable;
    $clsUser = new User(); $me = $clsUser->getMe();
    #
    $subMenu='';
    $subMenu[] = array(array('act'=>'new', 'is_draft'=>'','is_push'=>'', 'status'=>'', 'user_id'=>''), "Viết bài mới");
    $subMenu[] = array(array('act'=>'default', 'is_draft'=>1,'is_push'=>'', 'status'=>'', 'user_id'=>''), "Bài đang viết (".$clsClassTable->getCount("is_trash=0 and is_draft=1 and user_id='".$me['user_id']."'").")");
    $subMenu[] = array(array('act'=>'in_trash', 'is_draft'=>'', 'is_push'=>'','status'=>'', 'user_id'=>''), "Thùng rác (".$clsClassTable->getCount("is_trash=1 and user_id='".$me['user_id']."'").")");
    #
    $assign_list["subMenu"] = $subMenu;
?>