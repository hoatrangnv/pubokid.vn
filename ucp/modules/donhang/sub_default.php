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
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    if($_GET['is_trash']) $cons.=" is_trash=".$_GET['is_trash'];
    else $cons.=" is_trash=0";
    if(isset($_GET['is_check'])) $cons .= " and is_check=".$_GET['is_check'];
    
    
    if($_POST['keyword']) {
        $slug = $core->toSlug($_POST['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_POST['keyword'];
    }
    #
    $listItem = $clsClassTable->getListPage($cons."  order by ".$pkeyTable." desc");
    $count = $clsClassTable->getCount($cons); $assign_list["count"] = $count;
    $paging = $clsClassTable->getNavPage($cons);
    
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
    die('Chức năng bị khóa');
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsVote = new Vote; $assign_list['clsVote'] = $clsVote;
    $clsAvote = new AVote;$assign_list['clsAvote'] = $clsAvote;

    #
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        $_POST['is_admin'] = 1;
        $_POST['reg_date'] = time();
        if($clsClassTable->insertOne($_POST)) {
            header('location: ?mod='.$mod.'&act=edit&id='.mysql_insert_id().'&mes=insertSuccess');
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
    $clsVote = new Vote; $assign_list['clsVote'] = $clsVote;
    $clsAvote = new AVote;$assign_list['clsAvote'] = $clsAvote;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) {
        $assign_list[$key] = $val;
    }
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        if($_POST['reg_date']) {
            $arr = explode(' ', $_POST['reg_date']);
            $date = explode('/', $arr[0]);
            $_POST['reg_date'] = strtotime($date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00');
        }
        if($clsClassTable->updateOne($_GET['id'],$_POST))
            header('location: ?mod='.$mod.'&act='.$act.'&id='.$_GET['id'].'&mes=updateSuccess');
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
    $classTable = ucfirst(strtolower($mod));
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
    if($id==6 || $id==77) die("Danh mục này không thể xóa");
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    if($clsClassTable->updateOne($id,array('is_trash'=>1))) die('1');
    else die('Update!');
}
function default_add_avote(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsVote = new Vote; $assign_list['clsVote'] = $clsVote;
    $clsAvote = new AVote;$assign_list['clsAvote'] = $clsAvote;
    $clsDudoan = new Dudoan;
    
    $listAvote = $clsAvote->getAll('vote_id='.$_GET['id']);
    
    $html = '';
    foreach($listAvote as $one) { $one = $clsAvote->getOne($one);
        if($one['avote_id'] == $_GET['avote_id']) $add='selected';
        $html .= '<option value="'.$one['avote_id'].'"  >'.$one['title']."(".$clsDudoan->getCount("avote_id=".$one['avote_id']).')</option>';
    }
    echo $html;
    
    die();
}
?>