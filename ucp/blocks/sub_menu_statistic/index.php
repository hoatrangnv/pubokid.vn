<?php
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    $clsUser = new User(); $me = $clsUser->getMe();
    #
    $subMenu='';
    $subMenu[] = array(0 => 'default', 1 => "TK Bài viết");
    $subMenu[] = array(0 => 'views', 1 => "TK Lượt xem");
    $subMenu[] = array(0 => 'viewschuyenmuc', 1 => "TK Chuyên mục");
    $subMenu[] = array(0 => 'viewsbvchuyenmuc', 1 => "TK Bài Viết CM");
    #
    $assign_list["subMenu"] = $subMenu;
?>