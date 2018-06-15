<?php
    $classTable = 'News';
    $clsClassTable = new $classTable;
    $clsUser = new User(); $me = $clsUser->getMe();
    $clsCategory = new Category();
    #
    $cons = "1=1";
    $category_path = $me['category_path'];
    if($category_path) {
        $category_path = str_replace('|', ',', trim($category_path,'|'));
        $allCat = $clsCategory->getAll("parent_id in(".$category_path.") OR category_id in (".$category_path.")");
        $cons .= " and category_id in (".implode(',', $allCat).")";
    }
    #
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default','status'=>1,'is_draft'=>'0', 'is_push'=>'','is_unpush'=>''), "Bài chờ biên tập (".$core->toString($clsClassTable->getCount($cons.' and status=1 and is_draft=0 and is_trash=0')).")");
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default','status'=>3,'is_draft'=>'0', 'is_push'=>'0','is_unpush'=>'0'), "Bài chờ xuất bản (".$core->toString($clsClassTable->getCount($cons.' and status=3 and is_draft=0 and is_trash=0 and is_push=0')).")");
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default','status'=>3,'is_draft'=>'0', 'is_push'=>'1', 'is_unpush'=>'0'), "Bài đã xuất bản (".$core->toString($clsClassTable->getCount($cons.' and status=3 and is_draft=0 and is_trash=0 and is_push=1')).")");
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default', 'status'=>3, 'is_draft'=>'0', 'is_push'=>'0', 'is_unpush'=>'1'), "Tin bài gỡ xuống (".$core->toString($clsClassTable->getCount('status=3 and is_draft=0 and is_trash=0 and is_unpush=1 and is_push=0')).")");
    
    $subMenu[] = array(array('keyword'=>'','category_id'=>'','date_from'=>'','date_to'=>'', 'act'=>'default','status'=>2,'is_draft'=>'0', 'is_push'=>'','is_unpush'=>''), "Bài đã trả về (".$core->toString($clsClassTable->getCount($cons.' and status=2 and is_draft=0 and is_trash=0')).")");
    
    $assign_list["subMenu"] = $subMenu;
?>