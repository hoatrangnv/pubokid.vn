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
        $cons .= " and name like '%".$_GET['keyword']."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    if($_GET['type']) {
        if($_GET['type']==1) $cons .= " and is_unsubscribe = '0'";
        if($_GET['type']==2) $cons .= " and is_unsubscribe = '1'";
        if($_GET['type']==3) $cons .= " and is_confirm = 1"; 
        if($_GET['type']==4) $cons .= " and is_confirm = 0"; 
    }
    #
    $order = $pkeyTable.' desc';
    $listItem = $clsClassTable->getListPage($cons." order by ".$order);
    $paging = $clsClassTable->getNavPageAdmin($cons);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
    $listCategory = $clsCategory->getAll("is_trash=0 and parent_id=0 order by order_no");
    $assign_list["listCategory"] = $listCategory;
    #
    $assign_list["views_week"] = $clsClassTable->getSum('views_week', "reg_date > '".date('Y-m-d', strtotime('this week', time())).' 00:00:00'."'");
    $assign_list["views_month"] = $clsClassTable->getSum('views_month', "reg_date > '".date('Y-m')."-1 00:00:00'");
    #
    #
    ob_clean();
    if($_GET['output']=='excel') {
        require_once 'lib/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("info@thethao24.tv")
			 ->setLastModifiedBy("nguyenvanduc.ptit@gmail.com")
			 ->setTitle("Profiles Thethao24")
			 ->setSubject("Profiles Thethao24")
			 ->setDescription("Bảng Profiles Bài của tôi Thethao24");
                                     
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'STT')
            ->setCellValue('B1', 'Họ và tên')
            ->setCellValue('D1', 'Email')
            ->setCellValue('E1', 'Giới tính')
            ->setCellValue('G1', 'Ngày đăng ký')
            ->setCellValue('J1', 'Trạng thái');
        
        if($_GET['nolimit']==1) {
            $listItem = $clsClassTable->getAll($cons." order by profile_id desc",false);
        }
        
        if($listItem) foreach($listItem as $key=>$oneItem) {
            $oneItem=$clsClassTable->getOne($oneItem);
            if($oneItem['is_confirm'] == 1) $type = 'Đã xác nhận'; else $type = 'Chưa xác nhận';
            $i = $key+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $i-1)
                ->setCellValue('B'.$i, $oneItem['name'])
                ->setCellValue('D'.$i, $oneItem['email'])
                ->setCellValue('E'.$i, $oneItem['gender'])
                ->setCellValue('G'.$i, date("H:i d/m/Y",$oneItem['reg_date']))
                ->setCellValue('J'.$i, $type);
        }
                    
        $objPHPExcel->getActiveSheet()->setTitle('THETHAOT24');
        header('Content-type: application/vnd.ms-excel');
        $file_name = 'Profile - Trang '.$assign_list["cursorPage"];
        header('Content-Disposition: attachment; filename="'.$file_name.'.xls"');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        die();
    }
    
    
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
    #
    if($_POST) {
        $_POST['reg_date'] = time();
        #print_r($_POST); die();
        if($clsClassTable->insertOne($_POST)) {
            header('location: ?mod='.$mod.'&act=edit&id='.mysql_insert_id().'&mes=insertSuccess');
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
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $oneItem = $clsClassTable->getOne($_GET['id']);
    if($oneItem) foreach($oneItem as $key => $val) $assign_list[$key] = $val;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    #

    #
    if($_POST) {
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
function default_sendmail(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
	$id = $_POST['news_id'];
    if(!$id) die('0');
    $classTable = ucfirst(strtolower($mod));
    $clsClassTable = new $classTable;
    $list_email = $clsClassTable->getAll("is_trash = 0 and is_confirm = 1");
    ob_start();
        include_once('email-templates/send_artice.html');
    $content = ob_get_contents();ob_end_clean();
    
    $count = 0;
    foreach($list_email as $email) { $email = $clsClassTable->getOne($email);
        $count++;
        $content = str_replace(array("{name}","{link}"),array($email['name'],$link),$content);
        //$send = $core->mailtoHtml($email['email'],'Xác nhận đăng ký bản tin',$content,'Thethao24.tv');
    }
        
    die($count);
}
?>