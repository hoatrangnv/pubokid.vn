<?php
    $classTable = 'News_crawler';
    $clsClassTable = new $classTable;
    #
    $subMenu[] = array(array('is_trash'=>'0','act'=>'default', 'source'=>'dantri.com.vn'), "Dân trí");
    $subMenu[] = array(array('is_trash'=>'0','act'=>'default', 'source'=>'thethao.vnexpress.net'), "VnExpress");
    $subMenu[] = array(array('is_trash'=>'1'),'act'=>'in_trash', "Thùng rác");
    #
    $assign_list["subMenu"] = $subMenu;
?>