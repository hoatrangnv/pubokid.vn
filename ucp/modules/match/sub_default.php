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
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsCategory = new Category;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe();
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    $clsLeague = new League; $assign_list["clsLeague"] = $clsLeague;
    $clsTeam = new Team; $assign_list["clsTeam"] = $clsTeam;
    #
    $cons = 'is_live = 1';
    if($_GET['is_trash']) $cons.=" and is_trash=".$_GET['is_trash'];
    else $cons.=" and is_trash=0";
    
    if($_POST['keyword']) {
        $slug = $core->toSlug($_POST['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_POST['keyword'];
    }
    #
    $listItem = $clsClassTable->getListPage($cons."  order by full_time,".$pkeyTable." desc");
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
    $classTable = 'News';
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
        $_POST['last_edit'] = time();
        $_POST['slug'] = $core->toSlug($_POST['title']);
        $_POST['is_live'] = 1;
        $_POST['category_id'] = 15;
        $_POST['status'] = 3;
        $_POST['user_id'] = $me['user_id'];
        $_POST['reg_date'] = time();
        $_POST['is_unpush'] = 0;
        #
        if($_POST['image'] && $_POST['image']!='') {
            $image = $core->ftpUrlUpload($_POST['image'], 'upload', $_POST['slug'].rand(1,9), time());
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'upload', $_POST['slug'].rand(1,9), time());
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        #
        if($_POST['live'] && is_array($_POST['live'])) $_POST['live'] = json_encode($_POST['live']);
        if($_POST['embed_live'] && is_array($_POST['embed_live'])) $_POST['embed_live'] = json_encode($_POST['embed_live']);
        #
        if($_POST['is_hot']==1) $_POST['hot_date']=time();
        if($_POST['is_pick']==1) $_POST['pick_date']=time();
        if($_POST['is_featured']==1) $_POST['featured_date']=time();
        if($_POST['is_top']==1) $_POST['top_date']=time();
        if($_POST['is_push']==1) {
            $_POST['push_date']=time();
            $_POST['user_push'] = $me['user_id'];
        }
        
        if($_POST['tags']) {
            $arrTags = explode(",",$_POST['tags']);   
            $clsTags = new Tags;
            foreach($arrTags as $oneTag) {
                $tags_id = $clsTags->getAll('slug = "'.$core->toSlug(trim($oneTag)).'" limit 1');
                if(!$tags_id[0]) {
                    $clsTags->insertOne(array("title"=>trim($oneTag),"slug"=>$core->toSlug(trim($oneTag)),"category_id"=>"|".$_POST['category_id']));
                }
            }
        }
        #
        if($clsClassTable->insertOne($_POST)) {
            $clsClassTable->deleteArrKey('IS_TOP');
            $clsClassTable->deleteArrKey('IS_HOT');
            $clsClassTable->deleteArrKey('IS_NEW');
            $clsClassTable->deleteArrKey('IS_PICK');
            $clsClassTable->deleteArrKey('IS_FEATURED');
            $clsClassTable->deleteArrKey('KEYARR_'.$_POST['category_id']);
            $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($_POST['category_id']));

            $clsClassTable->deleteArrKey('CMS');
            $maxId = mysql_insert_id();
            $data = $clsClassTable->getOne($maxId);
            $clsHistory = new History();
            $clsHistory->add($data, "Viết bài mới");
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
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsCategory = new Category; $assign_list["clsCategory"] = $clsCategory;
    $clsLeague = new League; $assign_list["clsLeague"] = $clsLeague;
    $clsTeam = new Team; $assign_list["clsTeam"] = $clsTeam;
    $clsUser = new User();
    
    $clsLive = new Live; $assign_list["clsLive"] = $clsLive;
    $live1 = $clsLive->getAll("is_trash = 0 and news_id = ".$_GET['id']." order by order_by asc, match_id asc");
    $assign_list["live1"] = $live1;
    $me = $clsUser->getMe(); $assign_list["me"] = $me; if(!$me) {header('Location: '.SITE_PROTOCOL.SITE_DOMAIN.'/admin.php?mod=user&act=login&u='.rawurlencode($core->getAddress())); die();}

    $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) {
        $assign_list[$key] = $val;
    }
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        $_POST['last_edit'] = time();
        $_POST['status'] = 3;
        $_POST['is_live'] = 1;
        $_POST['category_id'] = 15;
        $_POST['channel_path'] = '|'.trim($_POST['channel_path'], '|').'|';
        $_POST['news_related'] = '|'.trim($_POST['news_related'], '|').'|';
        $_POST['is_unpush'] = 0;
        #
        if($oneItem['is_push']=='1' && $_POST['is_push']=='0') $_POST['is_unpush'] = '1';
        if($oneItem['is_push']=='0' && $_POST['is_push']=='1') $_POST['is_unpush'] = '0';
        #
        if($_POST['is_hot']==1 && !$oneItem['is_hot']) $_POST['hot_date']=time();
        if($_POST['is_pick']==1 && !$oneItem['is_pick']) $_POST['pick_date']=time();
        if($_POST['is_featured']==1 && !$oneItem['is_featured']) $_POST['featured_date']=time();
        if($_POST['is_top']==1 && !$oneItem['is_top']) $_POST['top_date']=time();
        if($_POST['is_push']==1 && !$oneItem['is_push']) {
            $_POST['push_date'] = time();
            $_POST['user_push'] = $me['user_id'];
        } 
        #
        if($_POST['image'] && $_POST['image']!='') {
            $image= $core->ftpUrlUpload($_POST['image'], 'upload', $oneItem['slug'].rand(1,9), $oneItem['reg_date']);
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'files', $oneItem['slug'].rand(1,9), $oneItem['reg_date'],true);
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        #
        if($_POST['live'] && is_array($_POST['live'])) $_POST['live'] = json_encode($_POST['live']);
        if($_POST['embed_live'] && is_array($_POST['embed_live'])) $_POST['embed_live'] = json_encode($_POST['embed_live']);
        #
        if($_POST['tags']) {
            $arrTags = explode(",",$_POST['tags']);   
            $clsTags = new Tags;
            foreach($arrTags as $oneTag) {
                $tags_id = $clsTags->getAll('slug = "'.$core->toSlug(trim($oneTag)).'" limit 1');
                if(!$tags_id[0]) { 
                    $clsTags->insertOne(array("title"=>trim($oneTag),"slug"=>$core->toSlug(trim($oneTag)),"category_id"=>"|".$_POST['category_id']."|"));
                }
            }
        }
        #
        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            $clsClassTable->deleteArrKey('IS_TOP');
            $clsClassTable->deleteArrKey('IS_HOT');
            $clsClassTable->deleteArrKey('IS_NEW');
            $clsClassTable->deleteArrKey('IS_PICK');
            $clsClassTable->deleteArrKey('IS_FEATURED');
            $clsClassTable->deleteArrKey('KEYARR_'.$_POST['category_id']);
            $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($_POST['category_id']));    
            $clsClassTable->deleteArrKey('KEYARR_'.$oneItem['category_id']);
            $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($oneItem['category_id']));
            $clsClassTable->deleteArrKey();
            $clsClassTable->deleteArrKey('CMS');
            $data = $clsClassTable->getOne(intval($_GET['id']));
            $clsHistory = new History();
            $note = 'Sửa bài';
            $clsHistory->add($data, $note);
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
    $listItem = $clsClassTable->getListPage("is_trash=1 and is_live=1 order by ".$pkeyTable." desc");
    $paging = $clsClassTable->getNavPageAdmin("is_trash=1 and is_live=1");
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
    $classTable = 'News';
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
function default_ajaxinsert(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsLive = new Live;
	$time = $_POST['time'];
    $content = $_POST['content'];
    $news_id = $_POST['news_id'];
    $_POST['reg_date'] = time();

    $clsLive->insertOne($_POST);
    $clsLive->deleteArrKey();
    echo mysql_insert_id();
    die();
}
function default_ajaxupdate(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsLive = new Live;
	$time = $_POST['time'];
    $content = $_POST['content'];
    $match_id = $_POST['match_id'];
    $_POST['last_edit'] = time();
    unset($_POST['match_id']);
    if($clsLive->updateOne($match_id,$_POST)) {
        echo "1";
    } else {
        echo 0;
    }
    die();
}
function default_ajaxdelete(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsLive = new Live;
    $match_id = $_POST['match_id'];
    if($clsLive->updateOne($match_id,array("is_trash"=>1))) {
        $clsLive->deleteArrKey();
        echo 1;
    } else {
        echo 0;
    }
    die();
}
function default_ajaxreload(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsLive = new Live;
    $match_id = $_POST['match_id'];
    $clsLive->deleteArrKey();
    $keyCache = $clsLive->getKey($match_id);
    $clsLive->deleteCache($keyCache);
    $one = $clsLive->getOne($match_id);
    
    echo json_encode($one);
    
    die();
}
?>