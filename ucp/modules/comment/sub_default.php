<?php
/**
*  Defautl action
*  @author        : Ong Thế Thành    
*  @date        : 2012/01/23    
*  @version        : 0.0.1
* MAX_PHOTOS
*/
function default_default(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $cons = "is_trash=0";
    #
    if($_GET['category_id']) $cons.=" and (category_id=".$_GET['category_id']." or category_id in(SELECT category_id FROM default_category WHERE parent_id=".$_GET['category_id']."))";
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    #
    $order = $pkeyTable.' desc';
    $listItem = $clsClassTable->getListPage($cons." order by ".$order,30);
    $paging = $clsClassTable->getNavPageAdmin($cons,30);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
    //$listCategory = $clsCategory->getAll("is_trash=0 and parent_id=0 order by order_no");
    //$assign_list["listCategory"] = $listCategory;
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
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsNews = new News;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) {
        $assign_list[$key] = $val;
    }
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        $oneNews = $clsNews->getOne($oneItem['news_id']);
        if($_POST['is_push'] == 1 && $oneItem['is_push'] != $_POST['is_push']) {
            $count_comment = $oneNews['count_comment'] + 1;
            $clsNews->updateOne($oneNews['news_id'],array("count_comment"=>$count_comment));
        }
        if($_POST['is_push'] == 0 && $oneItem['is_push'] != $_POST['is_push']) {
            $count_comment = $oneNews['count_comment'] - 1;
            $clsNews->updateOne($oneNews['news_id'],array("count_comment"=>$count_comment));
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
    $clsNews = new News(); $assign_list['clsNews'] = $clsNews;
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
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
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    $clsClassTable->deleteArrKey();
    if($clsClassTable->updateOne($id,array('is_trash'=>1))) die('1');
    else die('Update!');
}
function default_restore(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    $clsClassTable->deleteArrKey();
    if($clsClassTable->updateOne($id,array('is_trash'=>'0'))) die('1');
    else die('0');
}
function default_delete(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    if($clsClassTable->deleteOne($id)) die('1');
    else die('0');
}
function default_ajax(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $id = $_GET['id'];
    $field = $_GET['field'];
    $value = $_GET['value'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    $clsNews = new News;
    $clsClassTable = new $classTable;
    $oneComment = $clsClassTable->getOne($id);
    
    
    $data_update = array($field=>$value);
    $data_update['push_date'] = date("Y-m-d H:i:s");
    
    $oneNews = $clsNews->getOne($oneComment['news_id']);
    if($field == 'is_push' && $value == 1 && $value != $oneComment['is_push']) {
        $count_comment = $oneNews['count_comment'] + 1;
        $clsNews->updateOne($oneNews['news_id'],array("count_comment"=>$count_comment));
    }    
    if($field == 'is_push' && $value == 0 && $value != $oneComment['is_push']) {
        $count_comment = $oneNews['count_comment'] - 1;
        $clsNews->updateOne($oneNews['news_id'],array("count_comment"=>$count_comment));
    }  
    if($clsClassTable->updateOne($id, $data_update)) {
        $clsClassTable->deleteArrKey();
        $clsClassTable->deleteArrKey('CMS');
        $data = $clsClassTable->getOne($id);
        die('1');
    }
    else die('0');
}

?>