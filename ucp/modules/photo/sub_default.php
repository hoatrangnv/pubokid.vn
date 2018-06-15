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
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
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
    $listItem = $clsClassTable->getListPage($cons." order by ".$order);
    $paging = $clsClassTable->getNavPage($cons);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
    $listCategory = $clsCategory->getAll("is_trash=0 and parent_id=0 order by order_no");
    $assign_list["listCategory"] = $listCategory;
    #
    /*=============Title & Description Page==================*/
    $title_page = "List - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
    $description_page = '';
    $keyword_page = '';
    /*=============Content Page==================*/
}
function default_new(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $global_options;
    #
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    #
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    $clsUser = new User();
    $me = $clsUser->getMe();
    #
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    $MAX_PHOTOS = $global_options['MAX_PHOTOS']; $assign_list["MAX_PHOTOS"] = $MAX_PHOTOS;
    #
    if($_POST) {
        #
        $slug = $core->toSlug($_POST['title']);
        $_POST['slug'] = $slug;
        
        if($_POST['image'] && $_POST['image']!='') {
            $image = $core->ftpUrlUpload($_POST['image'], 'news', $slug, time());
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'news', $slug, time());
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        #
        $data = array();
        for($i=1; $i<=$MAX_PHOTOS; $i++) {
            $title_name = 'gallery_title_'.$i;
            $image_name = 'gallery_'.$i;
            if($_FILES[$image_name]['name']) {
                $data[$i-1] = array(0=>$_POST[$title_name], 1=>$core->ftpUpload($image_name, 'news', $slug.$i, time()));
                
            }
            unset($_POST[$title_name]);
        }
        if($data) $_POST['content'] = json_encode($data);
        #
        $_POST['user_id'] = $me['user_id'];
        $_POST['reg_date'] = date("Y-m-d H:i:s");
        #print_r($_POST); die();
        if($clsClassTable->insertOne($_POST)) {
            $maxId = $clsClassTable->getMaxID();
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
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg, $global_options;
    #
    $clsChannel = new Channel(); $assign_list['clsChannel'] = $clsChannel;
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    #
    $clsCategory = new Category(); $assign_list["clsCategory"] = $clsCategory;
    $MAX_PHOTOS = $global_options['MAX_PHOTOS']; $assign_list["MAX_PHOTOS"] = $MAX_PHOTOS;
    #
    $data = json_decode($oneItem['content']);
    $assign_list["data"] = $data;
    #
    if($_POST) {
        
        if($_POST['image'] && $_POST['image']!='') {
            $image= $core->ftpUrlUpload($_POST['image'], 'news', $oneItem['slug'], strtotime($oneItem['reg_date']));
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'news', $oneItem['slug'], strtotime($oneItem['reg_date']));
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        #
        for($i=1; $i<=$MAX_PHOTOS; $i++) {
            $title_name = 'gallery_title_'.$i;
            $image_name = 'gallery_'.$i;
            $check = 'gallery_delete_'.$i;
            if($_POST[$check]=='1') {
                unset($data[$i-1]);
            }
            elseif($_FILES[$image_name]['name']!='') {
                $data[$i-1] = array(0=>$_POST[$title_name], 1=>$core->ftpUpload($image_name, 'news', $slug.$i, time()));
            }
            unset($_POST[$title_name]);
            unset($_POST[$check]);
        }
        $_POST['content'] = json_encode($data);
        #
        if($clsClassTable->updateOne($_GET['id'],$_POST))
            header('location: ?mod='.$mod.'&act='.$act.'&id='.$_GET['id'].'&mes=updateSuccess');
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

?>