<?php
    $classTable = 'Hoidap';
    $clsClassTable = new $classTable;
    $clsUser = new User(); $me = $clsUser->getMe();
    #
    if($me['user_level_id'] == 3) {
        $subMenu[] = array(array('keyword'=>'','gltt_id'=>$_GET['gltt_id'],'date_from'=>'','date_to'=>'', 'act'=>'default', 'is_show'=>'1', 'is_draft'=>'', 'is_push'=>'', 'is_unpush'=>''), "Đã xuất bản (".$core->toString($clsClassTable->getCount('is_show=1 and gltt_id = '.$_GET['gltt_id'].' and is_trash=0')).")");
        $subMenu[] = array(array('keyword'=>'','gltt_id'=>$_GET['gltt_id'],'date_from'=>'','date_to'=>'', 'act'=>'default', 'is_show'=>'0', 'is_draft'=>'', 'is_push'=>'', 'is_unpush'=>''), "Chưa xuất bản (".$core->toString($clsClassTable->getCount('is_show=0 and gltt_id = '.$_GET['gltt_id'].' and is_trash=0')).")");
    } else if($me['user_level_id'] == 2) {
        $subMenu[] = array(array('keyword'=>'','gltt_id'=>$_GET['gltt_id'],'date_from'=>'','date_to'=>'', 'act'=>'default', 'is_show'=>'', 'is_draft'=>'', 'is_push'=>'', 'is_unpush'=>''), "Câu hỏi (".$core->toString($clsClassTable->getCount('gltt_id = '.$_GET['gltt_id'].' and is_trash=0 and user_id ='.$me['user_id'])).")");
    }
    #
    $subMenu[] = array(array('act'=>'in_trash', 'is_draft'=>'', 'status'=>'', 'user_id'=>''), "Thùng rác (".$clsClassTable->getCount("is_trash=1").")");
    
    $assign_list["subMenu"] = $subMenu;
    //unset($_GET['keyword']);
    //unset($_GET['category_id']);
    //unset($_GET['date_from']);
    //unset($_GET['date_to']);
?>