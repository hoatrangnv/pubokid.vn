<?php
/**
*  Defautl action
*  @author        : Ong Thế Thành    
*  @date        : 2012/01/23    
*  @version        : 0.0.1
*/
function default_default(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    if($_POST['keyword']) header('Location: /admin.php?mod=cache&act=default&keyword='.$_POST['keyword']);
    #
    /*=============Title & Description Page==================*/
    $title_page = "List - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
    $description_page = '';
    $keyword_page = '';
    /*=============Content Page==================*/
}
?>