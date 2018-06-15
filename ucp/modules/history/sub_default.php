<?php
/**
*  Defautl action
*  @author		: Ong Thế Thành	
*  @date		: 2012/01/23	
*  @version		: 0.0.1
*/
function default_default(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser;
    $classTable = 'History';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $cons = "1=1";
    if($_GET['news_id']) $cons .= " and news_id='".intval($_GET['news_id'])."'";
    if($_GET['date_from']) $cons .= " and reg_date>='".date('Y-m-d', strtotime($_GET['date_from']))."'";
    if($_GET['date_to']) $cons .= " and reg_date<='".date('Y-m-d', strtotime($_GET['date_to'])+60*60*24)."'";
    if($_GET['user_id']) $cons.=" and user_id='".$_GET['user_id']."'";
    $listItem = $clsClassTable->getListPage($cons." order by history_id desc");
    $paging = $clsClassTable->getNavPage($cons);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
	/*=============Title & Description Page==================*/
	$title_page = "List - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_edit(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsChannel = new Channel(); $assign_list['clsChannel'] = $clsChannel;
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    $oneItem = json_decode($oneItem['data']); if(is_object($oneItem)) $oneItem = get_object_vars($oneItem);
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
    #
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    #
    /*=============Title & Description Page==================*/
    $title_page = "Edit - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
    $description_page = '';
    $keyword_page = '';
    /*=============Content Page==================*/
}
?>