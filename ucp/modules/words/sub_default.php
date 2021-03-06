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
    
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and title like '%".$_GET['keyword']."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    } 
    if($_GET['type'] == 1) {
        $cons .= " and category_id != ''";
    } 
    if($_GET['type'] == 2) $cons .= " and category_id is NULL";
    #
    $listItem = $clsClassTable->getListPage($cons." order by ".$pkeyTable." desc");
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
    $clsCategory  = new Category; $assign_list["clsCategory"] = $clsCategory;
    #
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        $_POST['title'] = strtolower($_POST['title']);
        $_POST['reg_date'] = time();
        $pos = strpos($_POST['title'], ",");
 
          if ($pos !== false) {
            $array_title = explode(",",$_POST['title']);
        
            foreach($array_title as $arr) {
                $array['title'] = $arr;
                $array['link'] = $_POST['link'];
                $array['uutien'] = $_POST['uutien'];
                $clsClassTable->insertOne($array);
            }
            header('location: ?mod='.$mod.'&act=default');
          } else {
            if($clsClassTable->insertOne($_POST)) {
                $maxId = mysql_insert_id();
                header('location: ?mod='.$mod.'&act=edit&id='.$maxId.'&mes=insertSuccess');
            }
            else {
                foreach ($_POST as $key => $val) {
                    $assign_list[$key] = $val;
                }
                $msg = "Thêm mới thất bại!";
            }
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
    $clsCategory  = new Category; $assign_list["clsCategory"] = $clsCategory;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) {
        $assign_list[$key] = $val;
    }
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        $_POST['title'] = strtolower($_POST['title']);

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
function default_movedown(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    
    #
    $pvalTable = isset($_GET['id'])? $_GET['id'] : "";
    unset($_GET['act']); unset($_GET['id']);    
	$first=true; $url='?'; if($_GET) foreach($_GET as $key => $val) { if($first) $first=false; else $url.='&'; $url.=$key.'='.$val; }
    #    
	$classTable = ucfirst(strtolower($mod));
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	if($pvalTable == "") 
		header('location: '.$url.'&mes=moveFalse');
	$one = $clsClassTable->getOne($pvalTable);
	
	$current_pos = $one['order_no'];
	$all = $clsClassTable->getAll("parent_id='".$_GET['parent_id']."' and order_no<'$current_pos' order by order_no desc");
    if(is_array($all)) {
        $prev_pos = $all[0]['order_no'];
        $prev_id = $all[0][$pkeyTable];
        if((int)$prev_id>0) {
            if(($clsClassTable->updateOne($pvalTable, array('order_no'=>$prev_pos)) and ($clsClassTable->updateOne($prev_id,array('order_no'=>$current_pos)))))
                header('location: '.$url.'&mes=moveSuccess');
        }
    }
    else header('location: '.$url.'&mes=moveFalse');
}

function default_moveup(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    
    #
    $pvalTable = isset($_GET['id'])? $_GET['id'] : "";
    unset($_GET['act']); unset($_GET['id']);    
	$first=true; $url='?'; if($_GET) foreach($_GET as $key => $val) { if($first) $first=false; else $url.='&'; $url.=$key.'='.$val; }
    #    
	$classTable = ucfirst(strtolower($mod));
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	if($pvalTable == "") 
		header('location: '.$url.'&mes=moveFalse');
	$one = $clsClassTable->getOne($pvalTable);
	
	$current_pos = $one['order_no'];
	$all = $clsClassTable->getAll("parent_id='".$_GET['parent_id']."' and order_no>'$current_pos' order by order_no asc");
    if(is_array($all)) {
    	$next_pos = $all[0]['order_no'];
    	$next_id = $all[0][$pkeyTable];
        if((int)$next_id>0) {
            if($clsClassTable->updateOne($pvalTable,array('order_no'=>$next_pos)) and $clsClassTable->updateOne($next_id,array('order_no'=>$current_pos)))
                header('location: '.$url.'&mes=moveSuccess');
        }
    }        
    else header('location: '.$url.'&mes=moveFalse');
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
function default_load_ajax() {
    global $core;
    #
    $classTable = ucfirst(strtolower($_GET['class']));
    $clsNews = new News;
    $html = '<ul>';
    $clsClassTable = new $classTable;
    $pkeyTable = $clsClassTable->pkey;

    if($_GET['title']=='') die();
    if($classTable=='Tags') {
        $slug = $core->toSlug($_GET['title']);
        $all = $clsClassTable->getAll("is_trash = 0 and slug like '%".$slug."%' order by tags_id desc limit 10");
        if($all) foreach($all as $one) { $one=$clsClassTable->getOne($one);
            $res = $one[$pkeyTable];
            $html .= '<li><a href="#" rel="'.$clsNews->getLinkTag($one['title']).'" type="1">'.$one['title'].'</a></li>';
        } else die();
        echo $html.'</ul>'; die();
    }
    else {
        $slug = $core->toSlug($_GET['title']);
        if($classTable=='Keywords') $all = $clsClassTable->getAll("is_trash = 0 and title like '".$_GET['title']."' order by keywords_id desc limit 10");
        else $all = $clsClassTable->getAll("is_trash = 0 and slug like '%".$slug."%' order by reg_date desc limit 10");

        if($all) foreach($all as $one) { $one=$clsClassTable->getOne($one);
            $res = $one[$pkeyTable];
            if($classTable=='Keywords') $res=$one['title'];
            if($classTable=='Category' && $clsClassTable->getParentID($one['category_id']) == 46) $one['title'] = 'video '.$one['title'];
            $html .= '<li><a href="#" rel="'.$res.'">'.$one['title'].'</a></li>';
        } else die();
        echo $html.'</ul>'; die();
    }
}
?>