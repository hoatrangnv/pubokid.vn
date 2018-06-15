<?php
    $classTable = 'News';
    $clsClassTable = new $classTable;
    $clsUser = new User(); $me = $clsUser->getMe();
    #
    $subMenu='';
    $subMenu[] = array(0 => 'da_duyet', 1 => "Đã duyệt (".(int)$clsClassTable->getCount("user_id='".$me['user_id']."' and is_draft=0 and status=3 and is_trash=0").")");
    $subMenu[] = array(0 => 'cho_duyet', 1 => "Chờ duyệt (".(int)$clsClassTable->getCount("user_id='".$me['user_id']."' and is_draft=0 and status=1 and is_trash=0").")");
    $subMenu[] = array(0 => 'tra_lai', 1 => "Trả lại (".(int)$clsClassTable->getCount("user_id='".$me['user_id']."' and is_draft=0 and status=2 and is_trash=0").")");
    #
    $assign_list["subMenu"] = $subMenu;
?>