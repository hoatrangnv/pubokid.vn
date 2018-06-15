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
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list['clsNews'] = $clsClassTable;
    #
    $oneNews = $clsClassTable->getOne(intval($_GET['news_id']));
    $assign_list['oneNews'] = $oneNews;
    #
	/*=============Title & Description Page==================*/
	$title_page = $oneNews['title'];
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
?>