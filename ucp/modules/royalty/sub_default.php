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
    $clsSignature=new Signature(); $assign_list['clsSignature'] = $clsSignature;
    $clsUser = new User(); $assign_list['clsUser'] = $clsUser; $me = $clsUser->getMe(); $assign_list['me'] = $me;
    $classTable = 'News';
    $clsClassTable = new $classTable; $assign_list["clsClassTable"] = $clsClassTable;
    $pkeyTable = $clsClassTable->pkey; $assign_list["pkeyTable"] = $pkeyTable;
    $clsXephang = new Xephang;$assign_list["clsXephang"] = $clsXephang;
    $clsTheloai = new Theloai;$assign_list["clsTheloai"] = $clsTheloai;
    #
    $cons = "is_trash=0 and is_draft=0 and is_push=1"; 
    if($_GET['date_from']) $cons .= " and push_date>='".strtotime($_GET['date_from'])."'";
    if($_GET['date_to']) $cons .= " and push_date<='".(strtotime($_GET['date_to'])+24*60*60)."'";
    if($_GET['keyword']) {
        $slug = $core->toSlug($_GET['keyword']);
        $cons .= " and slug like '%".$slug."%'";
        $assign_list["keyword"] = $_GET['keyword'];
    }
    //die($cons);
    #
    
    if($_GET['user_id']) $cons.=" and user_id='".$_GET['user_id']."'";
    if($_GET['type_id']) $cons.=" and type_id='".$_GET['type_id']."'";
    //if($reg_date) $cons.=" and reg_date like '%".$reg_date."%'";
    if($_GET['nolimit']) {
        $listItem = $clsClassTable->getAll($cons." order by push_date desc");
    } else {
        $listItem = $clsClassTable->getListPage($cons." order by push_date desc");
        $paging = $clsClassTable->getNavPageAdmin($cons);
    }
    
    $assign_list["listItem"] = $listItem;
    $assign_list["paging"] = $paging;
    $assign_list["cursorPage"] = isset($_GET["page"])? $_GET["page"] : 1;
    #
    
    if($_GET['output']=='excel') {
        require_once 'lib/PHPExcel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("info@webthethao.vn")
			 ->setLastModifiedBy("otthanh@gmail.com")
			 ->setTitle("Nhuận bút webthethao.vn")
			 ->setSubject("Nhuận bút webthethao.vn")
			 ->setDescription("Bảng tính nhuận bút của webthethao.vn")
			 ->setKeywords("Nhuận bút")
			 ->setCategory("Salary");
                                     
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'STT')
            ->setCellValue('B1', 'Tiêu đề')
            ->setCellValue('C1', 'Thành viên')
            ->setCellValue('D1', 'Danh mục')
            ->setCellValue('E1', 'Views')
            ->setCellValue('F1', 'Tác giả')
            ->setCellValue('G1', 'Xếp loai')
            ->setCellValue('H1', 'Thể loại')
            ->setCellValue('I1', 'Thưởng')
            ->setCellValue('K1', 'Khác')
            ->setCellValue('L1', 'Tổng')
            ->setCellValue('M1', 'Ngày xuất bản')
            ->setCellValue('N1', 'URL')
            ->setCellValue('O1', 'Danh mục cha');
        ob_clean();   
        if($listItem) foreach($listItem as $key=>$oneItem) {
            $oneItem=$clsClassTable->getOne($oneItem);
            $oneUser = $clsUser->getOne($oneItem['user_id']);
            $i = $key+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $i-1)
                ->setCellValue('B'.$i, $oneItem['title'])
                ->setCellValue('C'.$i, $oneUser['user_name'])
                ->setCellValue('D'.$i, $clsCategory->getTitle($oneItem['category_id']))
                ->setCellValue('E'.$i, $oneItem['views'])
                ->setCellValue('F'.$i, $oneItem['author'])
                ->setCellValue('G'.$i, $clsXephang->getTitle($oneItem['xephang_id']))
                ->setCellValue('H'.$i, $clsTheloai->getTitle($oneItem['theloai_id']))
                ->setCellValue('I'.$i, $oneItem['thuong'])
                ->setCellValue('K'.$i, $oneItem['khac'])
                ->setCellValue('L'.$i, $clsXephang->getSotien($oneItem['xephang_id']) + $clsTheloai->getSotien($oneItem['theloai_id']) + $oneItem['thuong'] + $oneItem['khac'])
                ->setCellValue('M'.$i, date('d/m/Y', $oneItem['push_date']))
                ->setCellValue('N'.$i, $clsClassTable->getLink($oneItem[$pkeyTable]))
                ->setCellValue('O'.$i, $clsCategory->getTitle($clsCategory->getParentID($oneItem['category_id'])));
        }
   
        $objPHPExcel->getActiveSheet()->setTitle('webthethao');
        header('Content-type: application/vnd.ms-excel');
        $file_name = 'Nhuan But';
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

    #
	/*=============Title & Description Page==================*/
	$title_page = "List - ".$classTable; if($_GET['mes']=='updateSuccess') $title_page = "Update Success!";
	$description_page = '';
	$keyword_page = '';
	/*=============Content Page==================*/
}
function default_ajax(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $id = $_GET['id'];
    $data['thuong'] = intval(str_replace('.', '', $_POST['thuong']));
    $data['khac'] = intval(str_replace('.', '', $_POST['khac']));
    $data['xephang_id'] = $_POST['xephang'];
    $data['theloai_id'] = $_POST['theloai'];
    
    
    $clsXephang = new Xephang;
    $clsTheloai = new Theloai;
    $xephang_st = $clsXephang->getSotien($data['xephang_id']);
    $theloai_st = $clsTheloai->getSotien($data['theloai_id']);
    
    
    $total = $data['thuong']+$data['khac']+$xephang_st+$theloai_st;
    if(!$id) die('0');
    $clsNews= new News();
    $clsNews->deleteArrKey('SUM');
    if($clsNews->updateOne($id, $data)) die($core->toString($total));
    else die('0');
}
function default_ajax_noti(){
    $id = intval($_POST['id']); if(!$id) die('0');
    $note = strval($_POST['note']);
    $clsNews= new News();
    if($clsNews->updateOne($id, array('note'=>$note))) die('1');
    else die('0');
}
function default_updateuser(){
    global $assign_list, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $core, $msg;
    #
    $clsRoyalty = new Royalty;
    if($_POST['news_id']) {
        $royalty_text=$_POST['royalty_text'];
        $royalty_video=$_POST['royalty_video'];
        $royalty_photo=$_POST['royalty_photo'];
        
        $list_royalty_text = $clsRoyalty->getAll('news_id = '.$_POST['news_id'].' and status = 1 limit 1',false);
        $list_royalty_video = $clsRoyalty->getAll('news_id = '.$_POST['news_id'].' and status = 2 limit 1',false);
        $list_royalty_photo = $clsRoyalty->getAll('news_id = '.$_POST['news_id'].' and status = 3 limit 1',false);
        
        if($list_royalty_text[0]) {
            $clsRoyalty->updateOne($list_royalty_text[0],array("user_id"=>$royalty_text));
        } else {
            $clsRoyalty->insertOne(array("user_id"=>$royalty_text,"status"=>1,"news_id"=>$_POST['news_id']));
        }
        
        if($list_royalty_video[0]) {
            $clsRoyalty->updateOne($list_royalty_video[0],array("user_id"=>$royalty_video));
        } else {
            $clsRoyalty->insertOne(array("user_id"=>$royalty_video,"status"=>2,"news_id"=>$_POST['news_id']));
        } 
        
        if($list_royalty_photo[0]) {
            $clsRoyalty->updateOne($list_royalty_photo[0],array("user_id"=>$royalty_photo));
        } else {
            $clsRoyalty->insertOne(array("user_id"=>$royalty_photo,"status"=>3,"news_id"=>$_POST['news_id']));
        }  
    }
    #
    die();
}
?>