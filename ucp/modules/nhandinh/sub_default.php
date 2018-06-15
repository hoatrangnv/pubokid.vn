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
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe();
    $clsCategory = new Category;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    $clsLeague = new League; $assign_list["clsLeague"] = $clsLeague;
    $clsTeam = new Team; $assign_list["clsTeam"] = $clsTeam;
    #

    if($_GET['is_trash']) $cons.="is_trash=".$_GET['is_trash']." and is_nhandinh=1";
    else $cons.="is_trash=0 and is_nhandinh=1";
    
    if($_POST['keyword']) {
        $slug = $core->toSlug($_POST['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_POST['keyword'];
    }
    #
    $listItem = $clsClassTable->getListPage($cons."  order by news_id desc");
    $paging = $clsClassTable->getNavPageAdmin($cons);
    ob_clean();
    if($_GET['output']=='excel') {
        require_once 'lib/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("info@thethao24.vn")
			 ->setLastModifiedBy("nguyenvanduc.ptit@gmail.com")
			 ->setTitle("Nhuận bút thethao24.vn")
			 ->setSubject("Nhuận bút thethao24.vn")
			 ->setDescription("Bảng tính nhuận bút của thethao24.vn")
			 ->setKeywords("Nhuận bút")
			 ->setCategory("Salary");
                                     
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'STT')
            ->setCellValue('B1', 'Tiêu đề')
            ->setCellValue('C1', 'Người tạo')
            ->setCellValue('D1', 'Chuyên mục')
            ->setCellValue('E1', 'Views')
            ->setCellValue('J1', 'Ngày xuất bản')
            ->setCellValue('K1', 'URL');
        
        if($_GET['nolimit']) {
            $listItem = $clsClassTable->getAll($cons." order by push_date desc");
        } 

        if($listItem) foreach($listItem as $key=>$oneItem) {
            $oneItem = $clsClassTable->getOne($oneItem,false);            
            $oneUser = $clsUser->getOne($oneItem['user_id']);
            $channel_title = '';
            $i = $key+2;

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $i-1)
                ->setCellValue('B'.$i, $oneItem['title'])
                ->setCellValue('C'.$i, $oneUser['user_name'])
                ->setCellValue('D'.$i, 'Nhận Định')
                ->setCellValue('E'.$i, $oneItem['views'])
                ->setCellValue('J'.$i, date('d/m/Y', $oneItem['push_date']))
                ->setCellValue('K'.$i, $clsClassTable->getLink($oneItem[$pkeyTable]));
        }
                    
        $objPHPExcel->getActiveSheet()->setTitle('THETHAO24');
        header('Content-type: application/vnd.ms-excel');
        $file_name = 'Nhận định';
        if($_GET['user_id'] || $_GET['date_from'] || $_GET['date_to']) {
            $file_name = '';
            if($_GET['user_id']) {
                $oneUser = $clsUser->getOne($_GET['user_id']);
                $file_name .= $oneUser['user_name'];
            }
            if(!$_GET['date_from']) $date_from = '01-01-2008'; else $date_from = date('d-m-Y', strtotime($_GET['date_from']));
            if(!$_GET['date_to']) $date_to = date('d-m-Y'); else $date_to = date('d-m-Y', strtotime($_GET['date_to']));
            $file_name .= ' - ['.$date_from.']';
            $file_name .= ' - ['.$date_to.']';
        }
        
        $file_name .= '- Trang '.$assign_list["cursorPage"];
        header('Content-Disposition: attachment; filename="'.$file_name.'.xls"');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        die();
    }
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
        $_POST['is_nhandinh'] = 1;
        $_POST['status'] = 3;
        $_POST['is_unpush'] = 0;
        $_POST['last_edit'] = time();
        $_POST['slug'] = $core->toSlug($_POST['title']);
        $_POST['user_id'] = $me['user_id'];
        
        
        $_POST['reg_date'] = time();
        if($_POST['is_hot']==1) $_POST['hot_date']=time();
        if($_POST['is_pick']==1) $_POST['pick_date']=time();
        if($_POST['is_featured']==1) $_POST['featured_date']=time();
        if($_POST['is_top']==1) $_POST['top_date']=time();
        if($_POST['is_push']==1) { 
            $_POST['push_date']=time();
            $_POST['user_push'] = $me['user_id'];
        }
        #
        if($_POST['recent_home'] && is_array($_POST['recent_home'])) {
            foreach($_POST['recent_home'] as $a) {
                $recent_home .= $a.',';
            }
        }
        unset($_POST['recent_home']);$_POST['recent_home']=$recent_home;
        if($_POST['recent_away'] && is_array($_POST['recent_away'])) {
            foreach($_POST['recent_away'] as $a) {
                $recent_away .= $a.',';
            }
        }
        unset($_POST['recent_away']);$_POST['recent_away']=$recent_away;
        if($_POST['head_to_head'] && is_array($_POST['head_to_head'])) $_POST['head_to_head'] = json_encode($_POST['head_to_head']);
        #
        if($_POST['image'] && $_POST['image']!='') {
            $image = $core->ftpUrlUpload($_POST['image'], 'upload', $_POST['slug'].rand(1,9), time());
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'upload', $_POST['slug'].rand(1,9), time());
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        #
        #
        if($clsClassTable->insertOne($_POST)) { 
            $clsClassTable->deleteArrKey();
            $clsClassTable->deleteArrKey('IS_TOP');
            $clsClassTable->deleteArrKey('IS_HOT');
            $clsClassTable->deleteArrKey('IS_NEW');
            $clsClassTable->deleteArrKey('IS_PICK');
            $clsClassTable->deleteArrKey('IS_FEATURED');
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
        $_POST['is_nhandinh'] = 1;
        $_POST['is_unpush'] = 0;
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
        if($_POST['recent_home'] && is_array($_POST['recent_home'])) {
            foreach($_POST['recent_home'] as $a) {
                $recent_home .= $a.',';
            }
        }
        unset($_POST['recent_home']);$_POST['recent_home']=$recent_home;
        if($_POST['recent_away'] && is_array($_POST['recent_away'])) {
            foreach($_POST['recent_away'] as $a) {
                $recent_away .= $a.',';
            }
        }
        unset($_POST['recent_away']);$_POST['recent_away']=$recent_away;
        if($_POST['head_to_head'] && is_array($_POST['head_to_head'])) $_POST['head_to_head'] = json_encode($_POST['head_to_head']);
        if($_POST['is_push']==1 && !$oneItem['is_push']) {
            $_POST['push_date'] = time();
        } 
        #
        if($_POST['image'] && $_POST['image']!='') {
            $image = $core->ftpUrlUpload($_POST['image'], 'upload', $oneItem['slug'].rand(1,9), time());
        } else if($_FILES['image']['name']) {
            $image = $core->ftpUpload('image', 'upload', $oneItem['slug'].rand(1,9), time());
        } unset($_POST['image']);
        if($image) $_POST['image'] = $image;
        #
        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            $clsClassTable->deleteArrKey();
            $clsClassTable->deleteArrKey('IS_TOP');
            $clsClassTable->deleteArrKey('IS_HOT');
            $clsClassTable->deleteArrKey('IS_NEW');
            $clsClassTable->deleteArrKey('IS_PICK');
            $clsClassTable->deleteArrKey('IS_FEATURED');
            $clsClassTable->deleteArrKey('KEYARR_'.$_POST['category_id']);
            $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($_POST['category_id']));    
            $clsClassTable->deleteArrKey('KEYARR_'.$oneItem['category_id']);
            $clsClassTable->deleteArrKey('KEYARR_'.$clsCategory->getParentID($oneItem['category_id']));
            
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
    $classTable = 'News';
    $clsClassTable = new $classTable;
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
?>