<?php
    $classTable = 'News';
    $clsClassTable = new $classTable;
    $clsUser = new User(); $me = $clsUser->getMe();
    #
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default', 'status'=>'1', 'is_draft'=>'0', 'is_push'=>'', 'is_unpush'=>''), "Bài chờ biên tập (".$core->toString($clsClassTable->getCount('status=1 and is_draft=0 and is_trash=0')).")");
    
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default', 'status'=>'3', 'is_draft'=>'0', 'is_push'=>'0', 'is_unpush'=>'0'), "Bài chờ xuất bản (".$core->toString($clsClassTable->getCount('status=3 and is_draft=0 and is_trash=0 and is_push=0 and is_unpush=0')).")");
    
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default', 'status'=>'3', 'is_draft'=>'0', 'is_push'=>'1', 'is_unpush'=>'0'), "Bài đã xuất bản (".$core->toString($clsClassTable->getCount('status=3 and is_draft=0 and is_trash=0 and is_push=1 and is_unpush=0')).")");
    
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default', 'status'=>'3', 'is_draft'=>'0', 'is_push'=>'0', 'is_unpush'=>'1'), "Tin bài gỡ xuống (".$core->toString($clsClassTable->getCount('status=3 and is_draft=0 and is_trash=0 and is_unpush=1 and is_push=0')).")");
    
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default', 'status'=>'2', 'is_draft'=>'0', 'is_push'=>'', 'is_unpush'=>''), "Tin bài trả về (".$core->toString($clsClassTable->getCount('status=2 and is_draft=0 and is_trash=0')).")");
    #
    $assign_list["subMenu"] = $subMenu;
    //unset($_GET['keyword']);
    //unset($_GET['category_id']);
    //unset($_GET['date_from']);
    //unset($_GET['date_to']);
?>