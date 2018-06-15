<?php
    $classTable = ucfirst(strtolower($mod));
    if($mod=='category_keywords') $classTable = 'CategoryKeywords';
    $clsClassTable = new $classTable;
    $clsUser = new User(); $me = $clsUser->getMe();
    #
    $subMenu='';
    $subMenu[] = array(0 => 'default', 1 => "Tất cả (".$clsClassTable->getCount('is_trash=0').")");
    $subMenu[] = array(0 => 'in_trash', 1 => "Thùng rác (".$clsClassTable->getCount('is_trash=1').")");
    #
    if($me['user_level_id']<3) {
        $subMenu='';
        $subMenu[] = array(0 => 'default', 1 => "Tất cả");
        $subMenu[] = array(0 => 'in_trash', 1 => "Thùng rác");
    }
    #
    $assign_list["subMenu"] = $subMenu;
?>