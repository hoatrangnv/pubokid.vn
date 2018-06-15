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
    $clsGltt = new Gltt();
    $clsCategory = new Category(); $assign_list['clsCategory'] = $clsCategory;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = 'Hoidap';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    #
    if($_GET['gltt_id']) $gltt_id = $_GET['gltt_id'];
    else header('Location: /404.html');
    $oneItem = $clsGltt->getOne($gltt_id);
    $assign_list['oneItem'] = $oneItem;
    #
    $is_user_create = ($oneItem['user_id']==$me['user_id'])?1:0;
    $assign_list['is_user_create'] = $is_user_create;
    #
    $cons = "is_trash=0";
    if(isset($_GET['is_show']) && $_GET['is_show'] == 1) $cons .= " and is_show = 1";
    if(isset($_GET['is_show']) && $_GET['is_show'] == 0) $cons .= " and is_show = 0";
    if(!$is_user_create && $me['user_level_id'] != 3) $cons .= " and (user_related like '%,".$me['user_id'].",%')";
    #
    $order = ' hoidap_id desc';
    #
    $listItem = $clsClassTable->getListPage($cons." and gltt_id=".$gltt_id." order by ".$order);
    $paging = $clsClassTable->getNavPage($cons." and gltt_id=".$gltt_id." order by ".$order);
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    ob_clean();
    if($_GET['output']=='excel') {
        require_once 'lib/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("info@thethao24.vn")
			 ->setLastModifiedBy("nguyenvanduc.ptit@gmail.com")
			 ->setTitle("Hỏi đáp thethao24.vn")
			 ->setSubject("Hỏi đáp thethao24.vn")
			 ->setDescription("Hỏi đáp của thethao24.vn")
			 ->setKeywords("Hỏi đáp")
			 ->setCategory("Salary");
                                     
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'STT')
            ->setCellValue('C1', 'Người hỏi')
            ->setCellValue('D1', 'Email')
            ->setCellValue('E1', 'Số điện thoại')
            ->setCellValue('J1', 'Địa chỉ')
            ->setCellValue('K1', 'Nội dung');
        if($_GET['nolimit']) {
            $listItem = $clsClassTable->getAll($cons." order by push_date desc");
        } 
        
        if($listItem) foreach($listItem as $key=>$oneItem) {
            $oneItem = $clsClassTable->getOne($oneItem,false);            
            $oneUser = $clsUser->getOne($oneItem['user_id']);
            $channel_title = '';
            $clsChannel = new Channel; 
            if(trim($oneItem['channel_path'], '|') != '') { 
                $arr = array();
                $arr = explode('|', trim($oneItem['channel_path'],'|')); 
                $channel_id=$arr[0]; 
                $channel_title = $clsChannel->getTitle($channel_id); 
            }
            $i = $key+2;

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $i-1)
                ->setCellValue('C'.$i, $oneItem['ask_name'])
                ->setCellValue('D'.$i, $oneItem['ask_email'])
                ->setCellValue('E'.$i, $oneItem['ask_sdt'])
                ->setCellValue('J'.$i, $oneItem['ask_address'])
                ->setCellValue('K'.$i, $oneItem['ask_content']);
        }
                    
        $objPHPExcel->getActiveSheet()->setTitle('Thethao24');
        header('Content-type: application/vnd.ms-excel');
        $file_name = 'Hỏi đáp - ';
        $file_name .= $clsGltt->getTitle($_GET['gltt_id']);
        $file_name .= '- Trang '.$assign_list["cursorPage"];
        header('Content-Disposition: attachment; filename="'.$file_name.'.xls"');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        die();
    }
    #
    #
    /*=============Title & Description Page==================*/
    $title_page = "List - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
    $description_page = '';
    $keyword_page = '';
    /*=============Content Page==================*/
}
function default_setUser() {
    $user_related = ",".implode(",",$_POST['user_related']).",";
    if(!$_POST['user_related'][0]) {
        $user_related = '';
    } 
    
    
    $hoidap_id = intval($_POST['hoidap_id']);
    

    $gltt_id = intval($_POST['gltt_id']);
    $clsHoidap = new Hoidap();
    //$khachmoi = $clsHoidap->getKhachMoi($user_id,$gltt_id);
    $res = $clsHoidap->updateOne($hoidap_id, array('user_related'=>$user_related));
    if($res) {
        $clsHoidap->deleteArrKey();
        die('1');
    } else die('0');
    
}
function default_new(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $classTable = 'Hoidap';
    $clsClassTable = new $classTable;$assign_list["clsClassTable"] = $clsClassTable;
    #
    $clsUser = new User(); $me = $clsUser->getMe(); $assign_list["me"] = $me; if(!$me) {header('Location: http://vietq.vn/admin.php?mod=user&act=login&u='.rawurlencode($core->getAddress())); die();}
    $assign_list["clsUser"] = $clsUser;
    #
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    #
    if($_POST) {
        
        $_POST['reg_date'] = date("Y-m-d H:i:s");
        $_POST['created_asker'] = $me['user_id'];
        $_POST['gltt_id'] = $_GET['gltt_id'];
        $_POST['khachmoi'] = $clsClassTable->getKhachMoi($_POST['user_id'],$_GET['gltt_id']);
        if($clsClassTable->insertOne($_POST)) {
            $maxId = $clsClassTable->getMaxID();
            $data = $clsClassTable->getOne($maxId);
            header('location: ?mod='.$mod.'&act=edit&gltt_id='.$_GET['gltt_id'].'&id='.$maxId.'&mes=insertSuccess');
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
    $classTable = 'Hoidap';
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
        if($_POST['answer_content']!='' && !$oneItem['answer_content']) $_POST['answer_date'] = date('Y-m-d H:i:s');
        if($_POST['is_show']==1 && !$oneItem['is_show']) $_POST['show_date'] = date("Y-m-d H:i:s");
        $_POST['created_asker'] = $me['user_id'];
        if(!$oneItem['khachmoi']) { 
            if($clsClassTable->getKhachMoi($_POST['user_id'],$_POST['gltt_id'])) {
            $_POST['khachmoi'] = $clsClassTable->getKhachMoi($_POST['user_id'],$_POST['gltt_id']);
        }}
        #
        $clsClassTable->deleteArrKey();
        #
        if($clsClassTable->updateOne($_GET['id'],$_POST)) {
            $data = $clsClassTable->getOne(intval($_GET['id']));
            header('location: ?mod='.$mod.'&act='.$act.'&gltt_id='.$_GET['gltt_id'].'&id='.$_GET['id'].'&mes=updateSuccess');
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
    $classTable = 'Hoidap';
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
    $classTable = 'Hoidap';
    $clsClassTable = new $classTable;
    #
    $oneNews = $clsClassTable->getOne($id);
    $clsUser = new User(); $me = $clsUser->getMe();
    if($me['user_id']!=$oneNews['user_id'] && $me['user_level_id'] != 3) die('Bạn không thể xóa bài của user khác!');
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
    $classTable = 'Hoidap';
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
    $classTable = 'Hoidap';
    $clsClassTable = new $classTable;
    if($clsClassTable->deleteOne($id)) {
        $data = $clsClassTable->getOne($id);
        die('1');
    }
    else die('0');
}
?>