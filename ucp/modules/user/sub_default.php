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
    $classTable = $mod;
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    #
	$pkeyTable = $clsClassTable->pkey;
    $assign_list["pkeyTable"] = $pkeyTable;
    #
    $clsUser = new User();
    $me = $clsUser->getMe();
    #
    $cons = 'is_trash = 0';
    if($_POST['keyword']) {
        $cons .= " and user_name like '%".$_POST['keyword']."%'";
        $assign_list["keyword"] = $_POST['keyword'];
    }

    $listItem = $clsClassTable->getListPage($cons." order by user_id desc");
    $paging = $clsClassTable->getNavPage($cons);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
	#
	/*=============Title & Description Page==================*/
	$title_page = "Quản trị  - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_login(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
	$clsUser = new User();
    if($_POST['user_name'] && $_POST['user_pass']) {
        if($clsUser->login($_POST['user_name'], $_POST['user_pass'])) {
            header('location: '.PCMS_URL.'/admin.php?mod=news');
        }
        else {
            $msg = 'Đăng nhập không thành công';
        }
        
    }
	#
	/*=============Title & Description Page==================*/
	$title_page = 'Login - '.PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_logout(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $_SESSION['USER'] = '';
    $_SESSION['PASS'] = '';
	unset($_SESSION);
    setcookie ("USER", "", time() - 3600);
    setcookie ("PASS", "", time() - 3600);
    header('location: '.PCMS_URL.'/admin.php?mod=home');
	#
	/*=============Title & Description Page==================*/
	$title_page = "Quản trị  - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}

function default_new(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = $mod;
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    #
    $clsUser = new User();
    $me = $clsUser->getMe();
    if((int)$me['user_level_id']<=1) header('location: ?mod='.$mod.'&mes=lock');
    #
    $maxOrderNo = $clsClassTable->getMax('order_no', '');
    $assign_list["maxOrderNo"] = $maxOrderNo+1;
    #
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
    #
    if($_POST) {
        if($_POST['user_level_id']==3) $_POST['category_path'] = '0';
        $_POST['user_pass']=md5($_POST['user_pass']);
        $_POST['reg_date'] = time();
        $_POST['parent_id'] = $me['user_id'];
        #
        $_POST['permission'] = json_encode($_POST['permission']);
        $_POST['category_path'] = '|'.implode('|', $_POST['category_path']).'|';
        
        if(!$clsClassTable->is_exits_user($_POST['user_name'])) {
            if($clsClassTable->insertOne($_POST)){
                $clsClassTable->deleteArrKey();
                
                $pathUser = '/www/tinnuocmy.com/public_html/files/'.$_POST['user_name'].'/';
                if (!is_dir($pathUser)) {
                    mkdir($pathUser,0777);         
                }
                
                header('location: ?mod='.$mod.'&mes=insertSuccess');}
            else {
                foreach ($_POST as $key => $val) {
                    $assign_list[$key] = $val;
                }
                $msg = "Error Insert Database!";
            }
            unset($_POST);
        } else $msg = "User name is exits";
    }
    #
	/*=============Title & Description Page==================*/
	$title_page = "Quản trị  - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_edit(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    #
    $clsUser = new User();
    $me = $clsUser->getMe();
    
    #
    $classTable = $mod;
    $clsClassTable = new $classTable; $assign_list['clsClassTable'] = $clsClassTable;
    $oneItem = $me;
    if($_GET['id']) $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        if($_POST['user_level_id']==3) $_POST['category_path'] = '0';
        $_POST['permission'] = json_encode($_POST['permission']);
        if($_POST['user_pass']=='') unset($_POST['user_pass']);
        else $_POST['user_pass']=md5($_POST['user_pass']);
        $_POST['category_path'] = '|'.implode('|', $_POST['category_path']).'|';
        
        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            header('location: ?mod='.$mod.'&act=edit&id='.$_GET['id'].'&mes=updateSuccess');
        }
        else {
            foreach ($_POST as $key => $val) {
                $assign_list[$key] = $val;
            }
            $msg = "Error Insert Database!";
        }
        unset($_POST);
    }
	#
	/*=============Title & Description Page==================*/
	$title_page = "Quản trị  - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_profile(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    #
    $clsUser = new User();
    $me = $clsUser->getMe();
    
    #
    $classTable = $mod;
    $clsClassTable = new $classTable; $assign_list['clsClassTable'] = $clsClassTable;
    $oneItem = $me;
    if($_GET['id']) $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        if($_POST['user_pass']=='') unset($_POST['user_pass']);
        else $_POST['user_pass']=md5($_POST['user_pass']);
        
        if($_FILES['avatar']['name']) {
            $image = $core->ftpUpload('avatar', 'upload', $me['user_name'], time());
        } unset($_POST['avatar']);
        if($image) $_POST['image'] = '/'.$image;
        
        if($clsClassTable->updateOne($me['user_id'],$_POST)) {
            $_SESSION['PASS'] = $_POST['user_pass'];
            header('location: ?mod='.$mod.'&mes=updateSuccess');
        }
        else {
            foreach ($_POST as $key => $val) {
                $assign_list[$key] = $val;
            }
            $msg = "Error Insert Database!";
        }
        unset($_POST);
    }
	#
	/*=============Title & Description Page==================*/
	$title_page = "Quản trị  - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_in_trash(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = $mod;
    $clsClassTable = new $classTable; $assign_list['clsClassTable'] = $clsClassTable;
    $assign_list['pkeyTable'] = $clsClassTable->pkey;
    #
    $clsUser = new User();
    $me = $clsUser->getMe();
    #
    $listItem = $clsClassTable->getListPage("is_trash=1 order by user_id desc");
    $paging = $clsClassTable->getNavPage("is_trash=1");
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
	#
	/*=============Title & Description Page==================*/
	$title_page = "Quản trị  - ".PAGE_NAME;
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
	$all = $clsClassTable->getAll("order_no<'$current_pos' order by order_no desc");
    if(is_array($all)) {
        $prev_pos = $all[0]['order_no'];
        $prev_id = $all[0][$pkeyTable];
        if((int)$prev_id>0) {
            $clsClassTable->deleteArrKey();
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
	$all = $clsClassTable->getAll("order_no>'$current_pos' order by order_no asc");
    if(is_array($all)) {
    	$next_pos = $all[0]['order_no'];
    	$next_id = $all[0][$pkeyTable];
        if((int)$next_id>0) {
            $clsClassTable->deleteArrKey();
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
    $clsClassTable->deleteArrKey();
    if($clsClassTable->deleteOne($id)) die('1');
    else die('0');
}
?>