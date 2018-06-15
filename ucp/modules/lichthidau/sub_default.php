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
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe();
    $clsCategory = new Category;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    $clsLeague = new League; $assign_list["clsLeague"] = $clsLeague;
    $clsTeam = new Team; $assign_list["clsTeam"] = $clsTeam;
    #

    $cons="is_trash=0";
    
    if($_POST['keyword']) {
        $slug = $core->toSlug($_POST['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_POST['keyword'];
    }
    #

    $listItem = $clsClassTable->getListPage($cons." order by reg_date desc");

    $paging = $clsClassTable->getNavPageAdmin($cons);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
	/*=============Title & Description Page==================*/
	$title_page = "List - ".$classTable;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_new(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsLeague = new League; $assign_list["clsLeague"] = $clsLeague;
    $clsTeam = new Team; $assign_list["clsTeam"] = $clsTeam;
    $clsCategory = new Category; $assign_list["clsCategory"] = $clsCategory;
    #
        
    $clsUser = new User();
    $me = $clsUser->getMe(); $assign_list["me"] = $me; if(!$me) {header('Location: '.SITE_PROTOCOL.SITE_DOMAIN.'/admin.php?mod=user&act=login&u='.rawurlencode($core->getAddress())); die();}
    #
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        $_POST['slug'] = $core->toSlug($_POST['title']);
        $_POST['user_id'] = $me['user_id'];
        $_POST['reg_date'] = time();
        
        if($_POST['push_date']) {
            $arr = explode(' ', $_POST['push_date']);
            $date = explode('/', $arr[0]);
            $_POST['push_date'] = strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00');
            
        }
        
        if($clsClassTable->insertOne($_POST)) { 
            $clsClassTable->deleteArrKey();
            
            $maxId = mysql_insert_id();

            header('location: ?mod='.$mod.'&act=edit&id='.$maxId.'&mes=insertSuccess');
        }
        else {
            foreach ($_POST as $key => $val) {
                $assign_list[$key] = $val;
            }
            $msg = "Thêm mới thất bại!";
        }
        unset($_POST);
    }
    #
	/*=============Title & Description Page==================*/
	$title_page = "New - ".$classTable;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_edit(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsCategory = new Category; $assign_list["clsCategory"] = $clsCategory;
    $clsLeague = new League; $assign_list["clsLeague"] = $clsLeague;
    $clsTeam = new Team; $assign_list["clsTeam"] = $clsTeam;
    $clsUser = new User();
    
    $me = $clsUser->getMe(); $assign_list["me"] = $me; if(!$me) {header('Location: '.SITE_PROTOCOL.SITE_DOMAIN.'/admin.php?mod=user&act=login&u='.rawurlencode($core->getAddress())); die();}

    $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) {
        $assign_list[$key] = $val;
    }
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        if($_POST['push_date']) {
            $arr = explode(' ', $_POST['push_date']);
            $date = explode('/', $arr[0]);
            $_POST['push_date'] = strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00');
            
        }
        #
        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            $clsClassTable->deleteArrKey();
            header('location: ?mod='.$mod.'&act='.$act.'&id='.$_GET['id'].'&mes=updateSuccess');

        }
        else {
            foreach ($_POST as $key => $val) {
                $assign_list[$key] = $val;
            }
            $msg = "Chỉnh sửa thất bại!";
        }
        unset($_POST);
    }
	#
	/*=============Title & Description Page==================*/
	$title_page = "Edit - ".$classTable;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_in_trash(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $listItem = $clsClassTable->getListPage("is_trash=1 order by ".$pkeyTable." desc");
    $paging = $clsClassTable->getNavPage("is_trash=1");
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
	/*=============Title & Description Page==================*/
	$title_page = "In trash - ".$classTable;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_trash(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('Not ID!');
    $classTable = 'News';
    $clsClassTable = new $classTable;
    if($clsClassTable->updateOne($id,array('is_trash'=>1))) die('1');
    else die('Update!');
}
function default_restore(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = 'News';
    $clsClassTable = new $classTable;
    if($clsClassTable->updateOne($id,array('is_trash'=>'0'))) die('1');
    else die('0');
}
function default_delete(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = 'News';
    $clsClassTable = new $classTable;
    $clsClassTable->delAllCache($id); 
    if($clsClassTable->deleteOne($id)) die('1');
    else die('0');
}
?>