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
    if(!class_exists($classTable)) die('System not found mod \''.$classTable.'\'');
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    #
    $cons = "is_trash=0";
    if($_POST['keyword']) {
        $slug = $core->toSlug($_POST['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_POST['keyword'];
    }
    if($_GET['name']) {
        if($_GET['name'] == 'header') $cons .= ' and slug = "header"';        
        if($_GET['name'] == 'trangchu') $cons .= ' and type = 0 and slug not in ("left", "header", "ballon","right","ballon") ';
        if($_GET['name'] == 'chuyenmucc1') $cons .= ' and type = 1 and slug not in ("left", "header", "ballon","right","ballon")  and slug not like "%detail%"';
        if($_GET['name'] == 'chuyenmucc2') $cons .= ' and type = 2';
        if($_GET['name'] == 'detail') $cons .= ' and type = 1 and slug like "detail%"';
        if($_GET['name'] == 'ballon') $cons .= ' and slug = "ballon"';
        if($_GET['name'] == 'left') $cons .= ' and slug = "left"';
        if($_GET['name'] == 'right') $cons .= ' and slug = "right"';     
		if($_GET['name'] == 'data') $cons .= ' and slug like "data%"';  		
    } 
    #
    $listItem = $clsClassTable->getListPage($cons." order by title");
    $paging = $clsClassTable->getNavPageAdmin($cons);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
	/*=============Title & Description Page==================*/
	$title_page = "Danh sách - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_new(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    header('location: ?mod='.$mod.'&mes=lock');
    $classTable = ucfirst(strtolower($mod));
    if(!class_exists($classTable)) die('System not found mod \''.$classTable.'\'');
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    #
    $maxOrderNo = $clsClassTable->getMax('order_no', '');
    $assign_list["maxOrderNo"] = $maxOrderNo+1;
    #
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        $_POST['reg_date'] = time();
        if($_POST['title']) $_POST['slug'] = $core->toSlug($_POST['title']);
        
        if($clsClassTable->insertOne($_POST))
            header('location: ?mod='.$mod.'&mes=insertSuccess');
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
	$title_page = "Thêm mới - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_edit(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = ucfirst(strtolower($mod));
    if(!class_exists($classTable)) die('System not found mod \''.$classTable.'\'');
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    $clsAdsContent = new AdsContent(); $assign_list["clsAdsContent"] = $clsAdsContent;
    #
    $allCategory = $clsCategory->getChild(0); $assign_list["allCategory"] = $allCategory;
    #
    $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
    #
    if($_POST) {
        #
        $arr = explode(' ', $_POST['start_']);
        $date = explode('/', $arr[0]);
        $_POST['start_'] = $date[2].'-'.$date[1].'-'.$date[0].' '.$arr[1].':00';
        #
        $arr1 = explode(' ', $_POST['finish_']);
        $date1 = explode('/', $arr1[0]);
        $_POST['finish_'] = $date1[2].'-'.$date1[1].'-'.$date1[0].' '.$arr1[1].':00';
        #
        $clsClassTable->setShowDate($_POST['finish_'],$_GET['id']);
        #
        if($oneItem['type']>0) {
            if($allCategory) foreach($allCategory as $one) {
                $clsClassTable->deleteCache(MEMCACHE_NAME.'getAllContent_'.$one);
                $clsClassTable->deleteCache(MEMCACHE_NAME.'getContent_'.$oneItem['slug'].'_'.$one);
            }
        }
        #
        $data = $_POST['data']; unset($_POST['data']);
        if(!$clsAdsContent->deleteAll("ads_id=".$_GET['id'])) die('Error!');
        if($data) foreach($data as $key=>$value) if($value) $clsAdsContent->insertOne(array('ads_id'=>$_GET['id'], 'category_id'=>$key, 'content'=>$value));
        #
        
        #
        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            $clsClassTable->deleteCache(MEMCACHE_NAME.'getAllContent_2');
            $clsClassTable->deleteCache(MEMCACHE_NAME.'getContent_'.$oneItem['slug'].'_');
            file_get_contents("http://webthethao.com.vn/?flush=1");
            $clsClassTable->deleteArrKey("KEYARR_ADS");
            $clsAdsContent->deleteArrKey("KEYARR_ADS");
            $clsClassTable->deleteArrKey('CMS');
            header('location: ?mod='.$mod.'&act=edit&id='.$_GET['id'].'&mes=updateSuccess');
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
	$title_page = "Edit - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_in_trash(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = ucfirst(strtolower($mod));
    if(!class_exists($classTable)) die('System not found mod \''.$classTable.'\'');
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $listItem = $clsClassTable->getListPage("is_trash=1 order by order_no desc");
    $paging = $clsClassTable->getNavPage("is_trash=1");
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
	/*=============Title & Description Page==================*/
	$title_page = "Thùng rác - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_movedown(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
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
	$all = $clsClassTable->getAll("order_no<'$current_pos' order by order_no desc");
    if(is_array($all)) {
        $prev_pos = $all[0]['order_no'];
        $prev_id = $all[0][$pkeyTable];
        if((int)$prev_id>0) {
            if(($clsClassTable->updateOne($pvalTable,"order_no='$prev_pos'") and ($clsClassTable->updateOne($prev_id,"order_no='$current_pos'"))))
                header('location: '.$url.'&mes=moveSuccess');
        }
    }
    else header('location: '.$url.'&mes=moveFalse');
}

function default_moveup(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
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
	$all = $clsClassTable->getAll("order_no>'$current_pos' order by order_no asc");
    if(is_array($all)) {
    	$next_pos = $all[0]['order_no'];
    	$next_id = $all[0][$pkeyTable];
        if((int)$next_id>0) {
            if($clsClassTable->updateOne($pvalTable,"order_no='$next_pos'") and $clsClassTable->updateOne($next_id,"order_no='$current_pos'"))
                header('location: '.$url.'&mes=moveSuccess');
        }
    }        
    else header('location: '.$url.'&mes=moveFalse');
}
function default_trash(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    die('Sorry, function locked!');
	$id = $_GET['id'];
    if(!$id) die('Not ID!');
    $classTable = ucfirst(strtolower($mod));
    if(!class_exists($classTable)) die('System not found mod \''.$classTable.'\'');
    $clsClassTable = new $classTable;
    if($clsClassTable->updateOne($id, array('is_trash'=>1))) die('1');
    else die('Update!');
}
function default_restore(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    if(!class_exists($classTable)) die('System not found mod \''.$classTable.'\'');
    $clsClassTable = new $classTable;
    if($clsClassTable->updateOne($id, array('is_trash'=>0))) die('1');
    else die('0');
}
function default_delete(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    if(!class_exists($classTable)) die('System not found mod \''.$classTable.'\'');
    $clsClassTable = new $classTable;
    if($clsClassTable->deleteOne($id)) die('1');
    else die('0');
}
function default_loadCategory(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $temp = $_GET['temp'];
    $clsTemplate = new Template();
    echo $clsTemplate->getSelectCategory('category_id', $category_id, $temp);
    die();
}
?>