<?php
/**
*  Defautl action
*  @author		: Ong Thế Thành	
*  @date		: 2012/01/23	
*  @version		: 0.0.1
*/
function default_default(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page;
    #
    header('location: admin.php?mod=setting&act=edit');
	/*=============Title & Description Page==================*/
	$title_page = "Danh sách Category - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_edit(){
	global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core;
    $letter = "Setting"; $assign_list["letter"] = $letter;
	#
    $classTable = $mod;
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $_GET['id'] = $clsClassTable->getMax('setting_id', '');
    $oneItem = $clsClassTable->getOne($_GET['id']);
    $assign_list["oneItem"] = $oneItem;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
    $assign_list['options'] = get_object_vars(json_decode($oneItem['options']));
    #
    if($_POST) {
        
        if($_POST['image'] && $_POST['image']!='') {
            $image = str_replace("http://thethao24.tv/","",$_POST['image']);
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'files', 'logo'.rand(1,9), time(),true);
        } unset($_POST['image']);
        if($image) $_POST['logo'] = $image;
        
        $_POST['options'] = json_encode($_POST['options']);
        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            header('location: ?mod='.$mod.'&act='.$act.'&id='.$_GET['id'].'&mes=updateSuccess');
        }
        else {
            foreach ($_POST as $key => $val) {
                $assign_list[$key] = $val;
            }
            $assign_list["msg"] = "Error Insert Database!";
        }
        unset($_POST);
    }
	#
	/*=============Title & Description Page==================*/
	$title_page = "Danh sách Category - ".PAGE_NAME;
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_set_ads(){
    $clsSetting = new Setting();
    $clsSetting->set_min_ads($_POST['min']);
    $clsSetting->set_max_ads($_POST['max']);
    die('1');
}
function default_flush() {
    $clsNews = new News();
    $clsNews->deleteArrKey();
    die('OK');
}
?>