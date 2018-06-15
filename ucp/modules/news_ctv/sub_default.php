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
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    if(!$_GET['is_draft']) header('Location: /admin.php?mod=news_ctv&act=default&is_draft=1');
    #
    $cons = "is_trash=0";
    $cons .= " and user_id=".$me['user_id'];
    #
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and (slug like '%".$slug."%' or title like '%".$slug."%')";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    #
    $listItem = $clsClassTable->getListPage($cons." order by show_date desc",RECORD_PER_PAGE,"",false);
    $paging = $clsClassTable->getNavPageAdmin($cons);
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
function default_new(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $classTable = 'News';
    $clsClassTable = new $classTable;
    #
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    $clsUser = new User();
    $me = $clsUser->getMe(); $assign_list["me"] = $me; if(!$me) {header('Location: '.SITE_PROTOCOL.SITE_DOMAIN.'/admin.php?mod=user&act=login&u='.rawurlencode($core->getAddress())); die();}
    #
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    #
    if($_POST) {
        $_POST['last_edit'] = time();
        $_POST['slug'] = $core->toSlug($_POST['title']);
        if($_POST['image'] && $_POST['image']!='') {
            $image = str_replace("http://webthethao.com.vn/","",$_POST['image']);
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'files', $_POST['slug'], time(),true);
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        
        $_POST['user_id'] = $me['user_id'];
        $_POST['reg_date'] = time();
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
        $_POST['content'] = str_replace("<p>&nbsp;</p>","",$_POST['content']);
        $_POST['content'] = str_replace("<br />","</p><p>",$_POST['content']);
        
        $_POST['seo_note'] = $clsClassTable->checkSeo($_POST);
        
        if($clsClassTable->insertOne($_POST)) {
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
    $clsChannel = new Channel(); $assign_list['clsChannel'] = $clsChannel;
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    #
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    $clsUser = new User();
    $me = $clsUser->getMe(); $assign_list["me"] = $me;
    #
    if($oneItem['is_push']) die('Ban khong duoc sua bai da xuat ban');
    if($me['user_id']!=$oneItem['user_id']) die('Ban khong duoc sua bai cua nguoi khac');
    #
    if($_POST) {
        if(!$_POST['slug']) $_POST['slug'] = $core->toSlug($_POST['title']);
        $_POST['last_edit'] = time();
        if($_POST['image'] && $_POST['image']!='') {
            $image = str_replace("http://webthethao.com.vn/","",$_POST['image']);
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'files', $oneItem['slug'].rand(0,9), strtotime($oneItem['reg_date']),true);
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        #
        $note = $_POST['note']; unset($_POST['note']);
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
        $_POST['content'] = str_replace("<p>&nbsp;</p>","",$_POST['content']);
        $_POST['content'] = str_replace("<br />","</p><p>",$_POST['content']);

        $_POST['seo_note'] = $clsClassTable->checkSeo($_POST);

        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            $data = $clsClassTable->getOne(intval($_GET['id']));
            $clsHistory = new History();
            $note = 'Sửa bài';
            $clsHistory->add($data, $note);
            header('location: ?mod='.$mod.'&act='.$act.'&id='.$_GET['id'].'&mes=updateSuccess');
        }
        else {
            print_r($_POST); die();
            foreach ($_POST as $key => $val) {
                $assign_list[$key] = $val;
            }
            $msg = "Chỉnh sửa thất bại!";
        }
        unset($_POST);
    }
	#
	/*=============Title & Description Page==================*/
	$title_page = "Edit - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_in_trash(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsUser = new User(); $me = $clsUser->getMe();
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $listItem = $clsClassTable->getListPage("is_trash=1 and user_id='".$me['user_id']."' order by ".$pkeyTable." desc");
    $paging = $clsClassTable->getNavPageAdmin("is_trash=1");
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
    $classTable = 'News';
    $clsClassTable = new $classTable;
    $clsClassTable->deleteArrKey();
    if($clsClassTable->updateOne($id,array('is_trash'=>1))) {
        $data = $clsClassTable->getOne($id);
        $clsHistory = new History();
        $clsHistory->add($data, "Xóa tạm");
        die('1');
    }
    else die('Update!');
}
function default_restore(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = 'News';
    $clsClassTable = new $classTable;
    $clsClassTable->deleteArrKey();
    if($clsClassTable->updateOne($id,array('is_trash'=>'0'))) {
        $data = $clsClassTable->getOne($id);
        $clsHistory = new History();
        $clsHistory->add($data, "Phục hồi");
        die('1');
    }
    else die('0');
}
function default_delete(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_GET['id'];
    if(!$id) die('0');
    $classTable = 'News';
    $clsClassTable = new $classTable;
    if($clsClassTable->deleteOne($id)) {
        $data = $clsClassTable->getOne($id);
        $clsHistory = new History();
        $clsHistory->add($data, "Xóa vĩnh viễn");
        die('1');
    }
    else die('0');
}
function default_cho_duyet(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    
    if($_GET['up_picker']=='1') {
        $clsClassTable->updateOne($_GET['id'], "picker_date='".date('Y-m-d H:i:s', time())."'");
        header('location: ?mod='.$mod.'&is_picker=1&category_id='.$_GET['category_id'].'&nocache=1');
    }
    #
    $cons = "is_trash=0 and status=1";
    $cons .= " and user_id=".$me['user_id'];
    #
    if($_GET['category_id']) $cons.=" and (category_id=".$_GET['category_id']." or category_id in(SELECT category_id FROM default_category WHERE parent_id=".$_GET['category_id']."))";
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    #
    $listItem = $clsClassTable->getListPage($cons." order by news_id desc",RECORD_PER_PAGE,"",false);
    $paging = $clsClassTable->getNavPageAdmin($cons);
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
function default_da_duyet(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    
    if($_GET['up_picker']=='1') {
        $clsClassTable->updateOne($_GET['id'], "picker_date='".date('Y-m-d H:i:s', time())."'");
        header('location: ?mod='.$mod.'&is_picker=1&category_id='.$_GET['category_id'].'&nocache=1');
    }
    #
    $cons = "is_trash=0 and status=3";
    $cons .= " and user_id=".$me['user_id'];
    #
    if($_GET['category_id']) $cons.=" and (category_id=".$_GET['category_id']." or category_id in(SELECT category_id FROM default_category WHERE parent_id=".$_GET['category_id']."))";
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    #
    $listItem = $clsClassTable->getListPage($cons." order by push_date desc, last_edit desc");
    $paging = $clsClassTable->getNavPageAdmin($cons);
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
function default_tra_lai(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
	#
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    
    if($_GET['up_picker']=='1') {
        $clsClassTable->updateOne($_GET['id'], "picker_date='".date('Y-m-d H:i:s', time())."'");
        header('location: ?mod='.$mod.'&is_picker=1&category_id='.$_GET['category_id'].'&nocache=1');
    }
    #
    $cons = "is_trash=0 and status=2 and is_draft=0";
    $cons .= " and user_id=".$me['user_id'];
    #
    if($_GET['category_id']) $cons.=" and (category_id=".$_GET['category_id']." or category_id in(SELECT category_id FROM default_category WHERE parent_id=".$_GET['category_id']."))";
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    #
    $listItem = $clsClassTable->getListPage($cons." order by news_id desc",RECORD_PER_PAGE,"",false);
    $paging = $clsClassTable->getNavPageAdmin($cons);
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
function default_gui_duyet() {
    $clsNews = new News();
    $id = (int)$_GET['id'];
    $clsNews->updateOne($id, array('status'=>1, 'is_draft'=>0, 'last_edit'=>time()));
    $clsNews->deleteArrKey();
    $data = $clsNews->getOne($id);
    $clsHistory = new History();
    $clsHistory->add($data, "Gửi duyệt");
    header('Location: /admin.php?mod=news_ctv&act=cho_duyet');
    die();
}
?>