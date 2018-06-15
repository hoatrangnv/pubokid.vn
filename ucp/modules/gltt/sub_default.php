<?php
/**
*  Defautl action
*  @author        : Ong Thế Thành    
*  @date        : 2012/01/23    
*  @version        : 0.0.1
*/
function default_default(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = 'Gltt';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    #
    $cons = "is_trash=0";
    #
    if(isset($_GET['is_push'])) $cons .= " and is_push=".$_GET['is_push'];
    #
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    #
    $order = ' gltt_id desc';
    #
    $listItem = $clsClassTable->getListPage($cons." order by ".$order);
    $paging = $clsClassTable->getNavPageAdmin($cons);
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
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $classTable = 'Gltt';
    $clsClassTable = new $classTable;
    #
    $clsUser = new User(); $me = $clsUser->getMe(); $assign_list["me"] = $me; if(!$me) {header('Location: http://vietq.vn/admin.php?mod=user&act=login&u='.rawurlencode($core->getAddress())); die();}
    $assign_list["clsUser"] = $clsUser;
    #
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    #
    if($_POST) {
        #
        $_POST['slug'] = $core->toSlug($_POST['title']);
        
        if($_POST['ngtraloi_khachmoi'] && is_array($_POST['ngtraloi_khachmoi'])) $_POST['ngtraloi_khachmoi'] = json_encode($_POST['ngtraloi_khachmoi']);
        
        if($_POST['image'] && $_POST['image']!='') {
            $image = $core->ftpUrlUpload($_POST['image'], 'upload', $slug, time());
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'upload', $slug, time());
        } unset($_POST['image']);
        if($image) $_POST['image'] = str_replace("upload/", "", $image);
        
        $_POST['user_id'] = $me['user_id'];
        $_POST['reg_date'] = date("Y-m-d H:i:s");
        
        if($clsClassTable->insertOne($_POST)) {
            $maxId = $clsClassTable->getMaxID();
            $data = $clsClassTable->getOne($maxId);
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
    $title_page = "Giao lưu trực tuyến - ".$classTable;
    $description_page = '';
    $keyword_page = '';
    /*=============Content Page==================*/
}
function default_edit(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $classTable = 'Gltt';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    $clsUser = new User(); $me = $clsUser->getMe(); $assign_list["me"] = $me;
    $assign_list['clsUser'] = $clsUser;
    
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    #
    #
    if($_POST) {
        
        $clsClassTable->deleteArrKey();
        if($_POST['ngtraloi_khachmoi'] && is_array($_POST['ngtraloi_khachmoi'])) $_POST['ngtraloi_khachmoi'] = json_encode($_POST['ngtraloi_khachmoi']);
        
        if($_POST['image'] && $_POST['image']!='') {
            $image= $core->ftpUrlUpload($_POST['image'], 'upload', $oneItem['slug'], strtotime($oneItem['reg_date']));
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'upload', $oneItem['slug'], strtotime($oneItem['reg_date']));
        } unset($_POST['image']);
        if($image) $_POST['image'] = str_replace("upload/", "", $image);
        #
        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            $data = $clsClassTable->getOne(intval($_GET['id']));
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
function default_ajax(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $id = $_GET['id'];
    $field = $_GET['field'];
    $value = $_GET['value'];
    if(!$id) die('0');
    $classTable = 'Gltt';
    $clsClassTable = new $classTable;
    $data_update = array($field=>$value); 
    
    if($clsClassTable->updateOne($id, $data_update)) {
        $clsClassTable->deleteArrKey();
        $clsClassTable->deleteArrKey('CMS');
        $data = $clsClassTable->getOne($id);
        die('1');
    }
    else die('0');
}
function default_in_trash(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $classTable = 'Gltt';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    $listItem = $clsClassTable->getListPage("is_trash=1 order by ".$pkeyTable." desc");
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
    $classTable = 'Gltt';
    $clsClassTable = new $classTable;
    #
    $oneNews = $clsClassTable->getOne($id);
    $clsUser = new User(); $me = $clsUser->getMe();
    #
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
    $classTable = 'Gltt';
    $clsClassTable = new $classTable;
    $clsClassTable->deleteArrKey();
    if($clsClassTable->updateOne($id,array('is_trash'=>'0'))) {
        $data = $clsClassTable->getOne($id);
        die('1');
    }
    else die('0');
}
function default_delete(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $id = $_GET['id'];
    if(!$id) die('0');
    $classTable = 'Gltt';
    $clsClassTable = new $classTable;
    if($clsClassTable->deleteOne($id)) {
        $data = $clsClassTable->getOne($id);
        die('1');
    }
    else die('0');
}
?>